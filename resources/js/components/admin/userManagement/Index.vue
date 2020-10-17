<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
            <form class="my-4">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="ユーザー検索">
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
        </div>

    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'

export default {
    data(){
        return{
            users:[]
        }
    },
    methods:{
        getUser(){
            axios.get('/api/users')
            .then(res => {
                this.users = res.data;
            });
        },
        deleteUser(id){
            axios.delete('/api/users/'+ id)
            .then(() => {
                this.getUser();
            })
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
