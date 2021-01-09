<template>
    <div>
        <Header></Header>
        <div class="col-md-6 mx-auto mt-4">
            <div class="card">
                <div class="card-header">ユーザー情報</div>
                <div class="card-body">
                    <!-- ユーザー名 -->
                    <div class="form-group row">
                        <label class="col-form-label col-md-3">ユーザー名</label>
                        <div class="col-md-8">
                            <input class="form-control" :value="user.name" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-3" for="name">ユーザー名を変更</label>
                        <div class="col-md-8">
                            <input :class="errors.name ? 'form-control is-invalid' : 'form-control'" id="name" v-model="user.name">
                            <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0]}}</div>
                        </div>
                    </div>

                    <!-- メールアドレス -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-md-3">メールアドレス</label>
                        <div class="col-md-8">
                            <input class="form-control" :value="user.email" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-3" for="email">メールアドレスを変更</label>
                        <div class="col-md-8">
                            <input :class="errors.email ? 'form-control is-invalid' : 'form-control'" id="email" v-model="user.email">
                            <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0]}}</div>
                        </div>
                    </div>

                    <!-- パスワード -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-md-3" for="password">パスワードを変更</label>
                        <div class="col-md-8">
                            <input type="password" :class="errors.email ? 'form-control is-invalid' : 'form-control'" id="password" v-model="user.password">
                            <div v-if="errors.password" class="invalid-feedback">{{ errors.password[0]}}</div>
                        </div>
                        <div class="password-icon col-md-1"><v-icon name="eye-slash" scale="1.5"/>
                        </div>
                    </div>

                    <!-- パスワードの確認 -->
                    <div class="form-group row">
                        <label class="col-form-label col-md-3" for="password_confirmation">パスワード再確認</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" id="password_confirmation" v-model="user.password_confirmation">
                        </div>
                    </div>

                    <!-- ステータス -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-md-3">ステータス</label>
                        <div class="col-md-8">
                            <input class="form-control" :value="user.status" disabled>
                        </div>
                    </div>

                    <!-- 次回更新日 -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-md-3">次回更新日</label>
                        <div class="col-md-8">
                            <input class="form-control" :value="user.next_update === null ? '-' : user.next_update" disabled>
                        </div>
                    </div>

                </div>
            </div>


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
            errors:{},
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

<style scoped>
.password-icon {
    margin: auto;
    padding: 0;
    cursor: pointer;
}
</style>