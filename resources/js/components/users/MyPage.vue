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
                        <td>{{status}}</td>
                    </tr>
                    <tr>
                        <td scope="row">次回更新日</td>
                        <td v-if="user.next_update != null">{{user.next_update | moment_details}}</td>
                        <td v-else></td>
                    </tr>
                    <tr>
                        <td scope="row">権限</td>
                        <td>{{role_id | role}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    
        <div class="col-md-6 mx-auto my-4">
            <div class="card">
                <div class="card-header">ステータス変更</div>
                <div class="card-body">
                    <form @submit.prevent="edit">
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-form-label col-md-4 pt-0">ステータス</legend>
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="status_normal">ノーマル</label>
                                        <input class="form-check-input" type="radio" id="status_normal" value="normal" v-model="user.status">
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="status_premium">プレミアム</label>
                                        <input class="form-check-input" type="radio" id="status_premium" value="premium" v-model="user.status">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
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
import Header from './UsersHeaderComoponent'
import moment from 'moment'

export default {
    data(){
        return {
            user:{},
            status:'',
            role_id: '',
        }
    },
    components:{
        Header
    },
    methods:{
        getUser(){
            axios.get('/api/users/' + this.id)
            .then(res => {
                this.user = res.data;
                this.status = this.user.status;
                this.role_id = this.user.role_id;
            });
        },
        edit(){
            const result = confirm('ユーザー情報を変更します。よろしいですか？');
            if(result){
                if(this.status == 'normal' && this.user.status == 'premium'){
                    this.user.next_update = moment().add(1, 'M').format('YYYY-MM-DD');
                }
                else if(this.status == 'premium' && this.user.status == 'normal'){
                    this.user.next_update = moment(this.user.next_update).subtract(1, 'M').subtract(1, 'd').format('YYYY-MM-DD');
                }

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