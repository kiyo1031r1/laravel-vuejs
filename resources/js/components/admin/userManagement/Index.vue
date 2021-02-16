<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container-fluid">
            <!-- 表示件数 -->
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div class="form-inline justify-content-end px-3 my-3">
                        <label class="col-form-label p-2" for="per_page">表示件数</label>
                        <select @change="changeFirstPage()" v-model="sort.per_page" class="form-control" id="per_page">
                            <option value="10">10件</option>
                            <option value="20">20件</option>
                            <option value="50">50件</option>
                            <option value="100">100件</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- サイドバー -->
                <div class="col-md-2">
                    <!-- ビデオ検索 -->
                    <p class="sidebar-head">ユーザー検索</p>
                    <div class="sidebar px-4 mb-3">
                        <form @submit.prevent="changeFirstPage()">
                            <!-- ユーザー名 -->
                            <div class="form-group">
                                <label class="col-form-label" for="name">ユーザー名</label>
                                <input v-model="search.name" class="form-control" type="text" id="name">
                            </div>

                            <!-- メールアドレス -->
                            <div class="form-group">
                                <label class="col-form-label" for="email">メールアドレス</label>
                                <input  v-model="search.email" class="form-control" type="text" id="email">
                            </div>

                            <!-- 登録日 -->
                            <div class="form-group">
                                <label class="col-form-label">登録日</label>
                                <Datepicker
                                    v-model="search.created_at_start"
                                    :language="datepicker.language"
                                    :format="datepicker.format"
                                    :input-class="datepicker.input_class"
                                    :bootstrap-styling="true"
                                    :clear-button="true"
                                    :placeholder="'〜から(未指定可)'"
                                    :disabled-dates="{from: search.created_at_end}">
                                </Datepicker>
                                <Datepicker
                                    v-model="search.created_at_end"
                                    :language="datepicker.language"
                                    :format="datepicker.format"
                                    :input-class="datepicker.input_class"
                                    :bootstrap-styling="true"
                                    :clear-button="true"
                                    :placeholder="'〜まで(未指定可)'"
                                    :disabled-dates="{to: search.created_at_start}">
                                </Datepicker>
                            </div>

                            <!-- ステータス -->
                            <div class="form-group">
                                <label class="col-form-label">ステータス</label>
                                <select v-model="search.status" class="form-control">
                                    <option selected></option>
                                    <option value="normal">normal</option>
                                    <option value="premium">premium</option>
                                </select>
                            </div>

                            <!-- 次回更新日 -->
                            <div class="form-group">
                                <label class="col-form-label">次回更新日(※現在不可)</label>
                                <Datepicker
                                    v-model="search.next_update_start"
                                    :language="datepicker.language"
                                    :format="datepicker.format"
                                    :input-class="datepicker.input_class"
                                    :bootstrap-styling="true"
                                    :clear-button="true"
                                    :placeholder="'〜から(未指定可)'"
                                    :disabled-dates="{from: search.next_update_end}">
                                </Datepicker>
                                <Datepicker
                                    v-model="search.next_update_end"
                                    :language="datepicker.language"
                                    :format="datepicker.format"
                                    :input-class="datepicker.input_class"
                                    :bootstrap-styling="true"
                                    :clear-button="true"
                                    :placeholder="'〜まで(未指定可)'"
                                    :disabled-dates="{to: search.next_update_start}">
                                </Datepicker>
                            </div>

                            <!-- 権限 -->
                            <div class="form-group">
                                <label class="col-form-label">権限</label>
                                <select v-model="search.role" class="form-control">
                                    <option selected></option>
                                    <option value="1">一般ユーザー</option>
                                    <option value="2">管理者</option>
                                </select>
                            </div>
                            
                            <!-- 検索ボタン -->
                            <div class="col-md-8 mx-auto my-4">
                                <button class="btn btn-primary btn-block" type="submit">検索</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- メイン -->
                <div class="main col-md-9">
                    <!-- ユーザー存在時(読み込み時に見出しとページネーション非表示) -->
                    <div v-show="isUsers">
                        <!-- ユーザーリスト -->
                        <table class="table table-sm table-bordered table-hover text-center ">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">
                                        <span class="mr-1">ID</span>
                                        <v-icon @click="sortList('id', 'asc');" name="caret-square-up"/>
                                        <v-icon @click="sortList('id', 'desc')" name="caret-square-down"/>
                                    </th>
                                    <th scope="col">ユーザー名</th>
                                    <th scope="col">email</th>
                                    <th scope="col">
                                        <span class="mr-1">登録日</span>
                                        <v-icon @click="sortList('created_at', 'asc');" name="caret-square-up"/>
                                        <v-icon @click="sortList('created_at', 'desc')" name="caret-square-down"/>
                                    </th>
                                    <th scope="col">
                                        <span class="mr-1">ステータス</span>
                                        <v-icon @click="sortList('status', 'asc');" name="caret-square-up"/>
                                        <v-icon @click="sortList('status', 'desc')" name="caret-square-down"/>
                                    </th>
                                    <th scope="col">
                                        <span class="mr-1">次回更新日</span>
                                    </th>
                                    <th scope="col">
                                        <span class="mr-1">権限</span>
                                        <v-icon @click="sortList('role', 'asc');" name="caret-square-up"/>
                                        <v-icon @click="sortList('role', 'desc')" name="caret-square-down"/>
                                    </th>
                                    <th scope="col">編集</th>
                                    <th scope="col">削除</th>
                                </tr>
                            </thead>
                            <tbody v-for="user in users" :key="user.id">
                                <tr>
                                    <th scope="row">{{user.id}}</th>
                                    <td>{{user.name}}</td>
                                    <td>{{user.email}}</td>
                                    <td>{{user.created_at | moment}}</td>
                                    <td>{{user.status}}</td>
                                    <td></td>
                                    <td>{{user.role_id | role}}</td>
                                    <td>
                                        <router-link :to="{name: 'user_management_edit', params: { id: user.id}}">
                                            <button class="btn btn-primary px-2 py-0">編集</button>
                                        </router-link>
                                    </td>
                                    <td><button @click="deleteUser(user.id)" class="btn btn-danger px-2 py-0">削除</button></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- ページネーション -->
                        <nav>
                            <ul class="pagination justify-content-center">
                                <li @click="changePage(1)" class="page-item mx-2"><a class="page-link" href="#">先頭</a></li>
                                <li @click="changePreviousPage()" class="page-item"><a class="page-link" href="#">前</a></li>
                                <li v-show="leftMorePage" class="mx-2">...</li>
                                <li v-for="(page, index) in createPageColumn" :key="page.index" @click="changePage(page)" :class="isCurrent(page, index) ? 'page-item active' : 'page-item inactive'">
                                    <a  ref="focus_page" class="page-link" href="#">{{page}}</a>
                                </li>
                                <li v-show="rightMorePage" class="mx-2">...</li>
                                <li @click="changeNextPage()" class="page-item"><a class="page-link" href="#">次</a></li>
                                <li @click="changePage(last_page)" class="page-item mx-2"><a class="page-link" href="#">最終</a></li>
                            </ul>
                        </nav>
                    </div>

                    <!-- ユーザー非存在時(検索結果が0件) -->
                    <div v-show="!isUsers" class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">ユーザー検索結果</div>
                                <div class="card-body">
                                    <p class="text-center text-danger mb-0 my-4">一致するユーザーがいませんでした</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminHeader from '@/components/admin/AdminHeader'
import Datepicker from 'vuejs-datepicker'
import {ja} from 'vuejs-datepicker/dist/locale'

export default {
    data(){
        return{
            users:[],
            isUsers: true,

            //ページネーション
            current_page: 1,
            last_page: null,
            page_length: 9,
            focus_page_index: 0,
            leftMorePage: false,
            rightMorePage: false,
            
            search:{
                name: null,
                email: null,
                role: '',
                status: '',
                created_at_start: null,
                created_at_end: null,
                next_update_start: null,
                next_update_end: null
            },

            datepicker:{
                language: ja,
                format: 'yyyy-MM-dd (D)',
                input_class: 'bg-white mb-2',
            },

            sort:{
                id: null,
                created_at: null,
                status: null,
                role: null,
                per_page: '10',
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
                if(this.current_page <= Math.floor(this.page_length / 2) + 1){
                    start = 1;
                    last = this.page_length;
                    this.leftMorePage = false;
                    this.rightMorePage = true;
                }
                //ページ末尾処理
                else if(this.current_page >= this.last_page - Math.floor(this.page_length / 2)){
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

            //ページ番号へフォーカス
            if(this.focus_page_index > 0) {
                this.$refs.focus_page[this.focus_page_index].focus();
            }

            return column;
        },
    },
    methods:{
        getUser(){
            axios.post('/api/users/search?page=' + this.current_page,{
                search: this.search, 
                sort: this.sort
            })
            .then(res => {
                const resData = res.data;

                this.users = resData.data;
                if(this.users.length > 0){
                    this.isUsers = true;
                }
                else{
                    this.isUsers = false;
                }

                this.last_page = resData.last_page;
            });
        },
        deleteUser(id){
            const result = confirm('ユーザーを削除します。よろしいですか？');
            if(result){
                axios.delete('/api/users/'+ id)
                .then(() => {
                    this.getUser();
                });
            }
        },
        changeFirstPage(){
            //最初のユーザーから表示する仕様
            this.current_page = 1;
            this.focus_page_index = 0;
            this.getUser();
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
        sortList(column, order){
            this.sort.id = null;
            this.sort.created_at = null;
            this.sort.status = null;
            this.sort.role = null;
            if(column === 'id'){
                this.sort.id = order;
            }
            if(column === 'created_at'){
                this.sort.created_at = order;
            }
            if(column === 'status'){
                this.sort.status = order;
            }
            if(column === 'role'){
                this.sort.role = order;
            }
            this.getUser();
        }
    },
    components:{
        AdminHeader,
        Datepicker
    },
    created(){
        this.getUser();
    }
}
</script>

<style scoped>
.sidebar{
    border: 2px solid #dee2e6;
}

.sidebar-head{
    font-weight: bold;
    color: #f8f9fa;
    background-color: #343a40;
    text-align: center;
    padding: 4px 0px;
    margin-bottom: 0px;
}

.vdp-datepicker >>> .vdp-datepicker__calendar{
    width: 110%;
}
.vdp-datepicker >>> .vdp-datepicker__clear-button{
    height: calc(1.6em + 0.75rem + 2px);
    margin-bottom: 0.5rem;
}

</style>