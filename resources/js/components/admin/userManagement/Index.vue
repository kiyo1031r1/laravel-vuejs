<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
            <h4>ユーザー検索</h4>
            <form @submit.prevent="searchUser" class="my-4">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="ユーザー名" v-model="search.name">
                </div>
                <button class="btn btn-primary mt-4" type="submit">検索</button>
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
                <tbody v-for="user in displayUsers" :key="user.id">
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

            <div class="text-center">
                <v-container>
                <v-row justify="center">
                    <v-col cols="8">
                    <v-container class="max-width">
                        <v-pagination
                        v-model="page"
                        class="my-4"
                        :length="length"
                        @input = "changePage"
                        ></v-pagination>
                    </v-container>
                    </v-col>
                </v-row>
                </v-container>
            </div>

        </div>

    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'

export default {
    data(){
        return{
            users:[],
            displayUsers:[],
            pageSize: 10,
            page: 1,
            length: 0,
            search:{
                name: null
            }
        }
    },
    methods:{
        getUser(){
            axios.get('/api/users')
            .then(res => {
                this.users = res.data;
                this.length = Math.ceil(this.users.length / this.pageSize);
                this.changePage(this.page);
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
        changePage(pageNumber){
            this.displayUsers = this.users.slice(
                this.pageSize * (pageNumber - 1) , this.pageSize * pageNumber
            );
        },
        searchUser(){
            axios.post('/api/users/search', this.search)
            .then(res => {
                this.users = res.data;
                this.length = Math.ceil(this.users.length / this.pageSize);
                this.changePage(this.page);
            });
        }
    },
    components:{
        AdminHeader
    },
    created(){
        this.getUser();
    }
}
</script>
