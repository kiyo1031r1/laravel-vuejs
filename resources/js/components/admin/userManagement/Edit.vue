<template>
    <div>
        <AdminHeader></AdminHeader>
        <!-- ユーザー情報 -->
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
                        <td></td>
                    </tr>
                    <tr>
                        <td scope="row">権限</td>
                        <td>{{role_id | role}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    
        <!-- ステータス切り替え -->
        <div class="col-md-6 mx-auto my-4">
            <div class="card">
                <div class="card-header">ステータス変更</div>
                <div class="card-body">
                    <form @submit.prevent="edit">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="role">権限</label>
                            <div class="col-md-6">
                                <select v-model="user.role_id" class="form-control" name="role" id="role">
                                    <option value="1">一般ユーザー</option>
                                    <option value="2">管理者</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mx-auto mt-5">
                            <button class="btn btn-primary btn-block" type="submit">更新</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminHeader from '@/components/admin/AdminHeader'

export default {
    data(){
        return {
            user:{},
            status:'',
            role_id: '',
        }
    },
    props: ['id']  
    ,
    components:{
        AdminHeader
    },
    beforeRouteEnter(to,from,next){
        axios.post('/api/users/exist', {
            id: to.params.id
        })
        .then(() => {
            next();
        })
        .catch(() => {
            next({name: 'user_management'});
        })
    },
    methods:{
        getUser(){
            axios.get('/api/users/' + this.id)
            .then(res => {
                this.user = res.data;
                this.role_id = this.user.role_id;
            });
        },
        edit(){
            const result = confirm('ユーザー情報を変更します。よろしいですか？');
            if(result){
                axios.put('/api/users/' + this.id, this.user)
                .then(() => {
                    this.$router.push({name: 'user_management'})
                });
            }
        }
    },
    created(){
        this.getUser();
    }
}
</script>