<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
            <div class="col-md-6 mx-auto">
                <ul class="list-group">
                    <li class="list-group-item" style="background-color:#00000008;">ユーザー情報</li>
                    <li class="list-group-item">ID: *****</li>
                    <li class="list-group-item">ユーザー名: *****</li>
                    <li class="list-group-item">email: *****</li>
                    <li class="list-group-item">作成日: *****</li>
                    <li class="list-group-item">ステータス: *****</li>
                    <li class="list-group-item">次回更新日: *****</li>
                </ul>
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
                                            <label class="form-check-label" for="status_nomal">ノーマル</label>
                                            <input class="form-check-input" type="radio" name="status" id="status_nomal" value="nomal">
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label" for="status_premium">プレミアム</label>
                                            <input class="form-check-input" type="radio" name="status" id="status_premium" value="premium">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="time">期間</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="time" id="time">
                                        <option value="1">1</option>
                                        <option value="7">7</option>
                                        <option value="14">14</option>
                                        <option value="28">28</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="role">権限</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="role" id="role">
                                        <option value="subscriber">ユーザー</option>
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
            props: ['id']
        }
    },
    components:{
        AdminHeader
    },
    methods:{
        getUser(){
            axios.get('/api/users/'+ this.id)
            .then(res => {
                this.user = res.data;
            });
        }
    },
    created(){
        this.getUser();
    }
}
</script>