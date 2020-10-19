<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
            <h4>ユーザー検索</h4>
            <form @submit.prevent="searchUser" class="my-4">
                <div class="form-inline">
                    <input class="form-control" type="text" placeholder="ユーザー名" v-model="search.name">
                    <input class="form-control" type="text" placeholder="email" v-model="search.email">
                    
                    <select class="form-control" v-model="search.role">
                        <option value="default" selected>権限</option>
                        <option value="一般ユーザー">一般ユーザー</option>
                        <option value="管理者">管理者</option>
                    </select>
                    <select class="form-control" v-model="search.status">
                        <option value="default" selected>ステータス</option>
                        <option value="normal">normal</option>
                        <option value="premium">premium</option>
                    </select>

                    <button class="btn btn-primary mt-4" type="submit">検索</button>
                </div>
            </form>

            <table class="table table-sm table-bordered table-hover text-center">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">ユーザー名</th>
                        <th scope="col">email</th>
                        <th scope="col">登録日</th>
                        <th scope="col">権限</th>
                        <th scope="col">ステータス</th>
                        <th scope="col">次回更新日</th>
                        <th scope="col">編集</th>
                        <th scope="col">削除</th>
                    </tr>
                </thead>
                <tbody v-for="user in users" :key="user.id">
                    <tr>
                        <th scope="row">{{user.id}}</th>
                        <td>{{user.name}}</td>
                        <td>{{user.email}}</td>
                        <td>{{moment(user.created_at).format('YYYY-MM-DD HH:MM:SS')}}
                        <td>{{user.role_id | role}}</td>
                        <td>{{user.status}}</td>
                        <td v-if="user.next_update != null">{{moment(user.next_update).format('YYYY-MM-DD')}}</td>
                        <td v-else></td>
                        <td>
                            <router-link :to="{name: 'user_management_edit', params: { id: user.id}}">
                                <button class="btn btn-primary px-2 py-0">編集</button>
                            </router-link>
                        </td>
                        <td><button @click="deleteUser(user.id)" class="btn btn-danger px-2 py-0">削除</button></td>
                    </tr>
                </tbody>
            </table>

            <nav>
            <ul class="pagination">
                <li @click="changePage(1)" class="page-item mx-2"><a class="page-link" href="#">先頭</a></li>
                <li @click="changePreviousPage()" class="page-item"><a class="page-link" href="#">前</a></li>
                <span v-if="leftMorePage" class="mx-2">...</span>
                <li v-for="(page, index) in createPageColumn" :key="page.index" @click="changePage(page)" :class="isCurrent(page, index) ? 'page-item active' : 'page-item inactive'">
                    <a  ref="focus_page" class="page-link" href="#">{{page}}</a>
                </li>
                <span v-if="rightMorePage" class="mx-2">...</span>
                <li @click="changeNextPage()" class="page-item"><a class="page-link" href="#">次</a></li>
                <li @click="changePage(last_page)" class="page-item mx-2"><a class="page-link" href="#">最終</a></li>
            </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'

export default {
    data(){
        return{
            users:[],
            current_page: 1,
            last_page: null,
            page_length: 9,
            focus_page_index: null,
            leftMorePage: false,
            rightMorePage: false,
            search:{
                name: null,
                email: null,
                role: 'default',
                status: 'default'
            }
        }
    },
    computed:{
        createPageColumn() {
            const column = [];
            let start;
            let last;
            //指定ページ数以上
            if(this.last_page > this.page_length){
                //ページ冒頭処理
                if(this.current_page < Math.floor(this.page_length / 2) + 1){
                    start = 1;
                    last = this.page_length;
                    this.leftMorePage = false;
                    this.rightMorePage = true;
                }
                //ページ末尾処理
                else if(this.current_page > this.last_page - Math.floor(this.page_length / 2)){
                    start = this.last_page - this.page_length + 1;
                    last = this.last_page;
                    this.leftMorePage = true;
                    this.rightMorePage = false;
                }
                //通常処理
                else{
                    start = this.current_page - Math.floor(this.page_length / 2);
                    last = this.current_page + Math.floor(this.page_length / 2);
                    this.leftMorePage = true;
                    this.rightMorePage = true;
                }
            }
            //指定ページ数未満
            else{
                start = 1;
                last = this.last_page;
                this.leftMorePage = false;
                this.rightMorePage = false;
            }
            
            for(let i = start; i <= last; i++){
                column.push(i);
            }
            return column;
        },
    },
    methods:{
        getUser(){
            axios.get('/api/users?page=' + this.current_page)
            .then(res => {
                const resData = res.data;
                this.users = resData.data;
                this.current_page = resData.current_page;
                this.last_page = resData.last_page;
            });
        },
        deleteUser(id){
            const result = confirm('ユーザーを削除します。よろしいですか？');
            if(result){
                axios.delete('/api/users/'+ id)
                .then(() => {
                    if(this.search){
                        this.searchUser();
                    }
                    else{
                        this.getUser();
                    }
                });
            }
        },
        changePage(page){
            this.current_page = page;
            this.getUser();
        },
        changePreviousPage(){
            if(this.current_page > 1) {
                this.changePage(this.current_page - 1);
            }
        },
        changeNextPage(){
            if(this.current_page < this.last_page) {
                this.changePage(this.current_page + 1);
            }
        },
        isCurrent(page, index){
            if(page === this.current_page){
                this.focus_page_index = index;
            }
            return page === this.current_page;
        },
        searchUser(){
            axios.post('/api/users/search', this.search)
            .then(res => {
                this.users = res.data.data;
            });
        }
    },
    components:{
        AdminHeader
    },
    created(){
        this.getUser();
    },
    updated(){
        this.$refs.focus_page[this.focus_page_index].focus();
    }
}
</script>

