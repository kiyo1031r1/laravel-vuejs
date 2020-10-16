<template>
    <div>
        <AdminHeader></AdminHeader>
            <div class="col-md-6 mx-auto">
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
                            <td>{{user.created_at | moment}}</td>
                        </tr>
                        <tr>
                            <td scope="row">ステータス</td>
                            <td>{{status}}</td>
                        </tr>
                        <tr>
                            <td scope="row">次回更新日</td>
                            <td>{{user.next_update}}</td>
                        </tr>
                        <tr>
                            <td scope="row">権限</td>
                            <td>{{user.role_id | role}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        
            <div class="col-md-6 mx-auto">
                <div class="card my-4">
                    <div class="card-header">ステータス変更</div>
                    <div class="card-body">
                        <form>
                            <fieldset class="form-group">
                                <div class="row">
                                    <legend class="col-form-label col-md-4 pt-0">ステータス</legend>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label" for="status_normal">ノーマル</label>
                                            <input class="form-check-input" type="radio" id="status_normal" value="normal" v-model="user.status" >
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label" for="status_premium">プレミアム</label>
                                            <input class="form-check-input" type="radio" id="status_premium" value="premium" v-model="user.status">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="time">期間</label>
                                <div class="col-md-6">
                                    <select :value="time" @change="time = $event.target.value" class="form-control" name="time" id="time">
                                        <option value="1">1</option>
                                        <option value="7">7</option>
                                        <option value="14">14</option>
                                        <option value="28">28</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="role">権限</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="role" id="role">
                                        <option value="subscriber">一般ユーザー</option>
                                        <option value="admin">管理者</option>
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
    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'

export default {
    data(){
        return {
            user:{},
            status:'',
            time: '1'
        }
    },
    props: ['id']  
    ,
    components:{
        AdminHeader
    },
    methods:{
        getUser(){
            axios.get('/api/users/' + this.id)
            .then(res => {
                this.user = res.data;
                this.status = this.user.status;
            });
        }
    },
    created(){
        this.getUser();
    }
}
</script>