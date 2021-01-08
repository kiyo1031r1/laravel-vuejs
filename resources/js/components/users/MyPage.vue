<template>
    <div>
        <Header></Header>
        <div class="col-md-6 mx-auto mt-4">
            <table class="table table-bordered bg-white">
                <tbody>
                    <tr class="table-borderless" style="background-color:#00000008;">
                        <td scope="row">ユーザー情報</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td scope="row">ID</td>
                        <td>{{user.id}}</td>
                    </tr>
                    <tr>
                        <td scope="row">ユーザー名</td>
                        <td>{{user.name}}</td>
                    </tr>
                    <tr>
                        <td scope="row">email</td>
                        <td>{{user.email}}</td>
                    </tr>
                    <tr>
                        <td scope="row">作成日</td>
                        <td>{{user.created_at | moment_details}}</td>
                    </tr>
                    <tr>
                        <td scope="row">ステータス</td>
                        <td>{{user.status}}</td>
                    </tr>
                    <tr>
                        <td scope="row">次回更新日</td>
                        <td v-if="user.next_update != null">{{user.next_update | moment_details}}</td>
                        <td v-else></td>
                    </tr>
                </tbody>
            </table>

            <div class="col-md-4 mx-auto mt-5">
                <button @click="edit()" class="btn btn-primary btn-block" type="submit">更新</button>
            </div>

        </div>
    </div>
</template>

<script>
import Header from './UsersHeaderComoponent'
import moment from 'moment'

export default {
    data(){
        return {
            user:{},
        }
    },
    components:{
        Header
    },
    methods:{
        getUser(){
            axios.get('/api/user/')
            .then(res => {
                this.user = res.data;
            });
        },
        edit(){
            const result = confirm('ユーザー情報を変更します。よろしいですか？');
            if(result){
                axios.put('/api/users/' + this.user.id, this.user)
                .then(() => {
                    this.$router.push({name: 'video'})
                });
            }
        }
    },
    created(){
        this.getUser();
    }
}
</script>