<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">ログイン</div>
                        <div class="card-body">
                            <form @submit.prevent="login">
                                <div class="form-group row">
                                    <div v-if="errors.not_found" class="col-md-12">
                                        <p class="text-danger text-center">{{ errors.not_found[0] }}</p>
                                    </div>
                                    <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>
                                    <template v-if="errors.email">
                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control is-invalid" name="email" v-model="user.email">
                                            <div class="invalid-feedback">{{ errors.email[0]}}</div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email" v-model="user.email">
                                        </div>
                                    </template>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>
                                    <template v-if="errors.password">
                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control is-invalid" name="password" v-model="user.password">
                                            <div class="invalid-feedback">{{ errors.password[0] }}</div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password" v-model="user.password">
                                        </div>
                                    </template>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary mr-4">
                                            ログイン
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <router-link :to="{ name: 'forgot_password'}">
                                <p class="text-right">パスワードを忘れてしまった場合</p>
                            </router-link>

                            <div class='text-right'>
                                <router-link :to="{ name: 'register'}">
                                    <button type="submit" class="btn btn-success">
                                        ユーザー新規作成
                                    </button>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">ソーシャルアカウントでログイン</div>
                        <div class="card-body">
                            <div class="text-center">
                                <button onclick="location.href='/login/google'" class="btn btn-danger">Googleアカウントでログイン</button>
                            </div>
                            <div class="text-center">
                                <button onclick="location.href='/login/facebook'" class="btn btn-primary mt-3" style="background-color: blue; border-color: blue;">Facebookアカウントでログイン</button>
                            </div>
                            <div class="text-center">
                                <button onclick="location.href='/login/twitter'" class="btn btn-primary mt-3 ">Twitterアカウントでログイン</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import VueCookies from 'vue-cookies'
import Header from '../users/UsersHeaderComoponent'

export default {
    data(){
        return{
            user:{},
            errors:{}
        }
    },
    components:{
        Header
    },
    methods:{
        login(){
            axios.get('sanctum/csrf-cookie')
            .then(() => {
                axios.post('/api/login', this.user)
                .then((res) => {
                    axios.get('/api/user')
                    .then(res => {
                        let user = res.data;
                        if(user.role_id === 2){
                            localStorage.setItem(process.env.MIX_APP_NAME + '-admin', res.data.token);
                        }
                        localStorage.setItem(process.env.MIX_APP_NAME, res.data.token);

                        //遷移前のpath情報があれば、そのページに遷移
                        this.$router.push(
                            this.$route.query.redirect ? this.$route.query.redirect : {name: 'video'}
                        );
                    });
                })
                .catch((error) =>{
                this.errors = error.response.data.errors;
                })
            });
        }
    },
    created(){
        if(VueCookies.get('SOCIAL_LOGIN_FAILED')) {
            this.errors.not_found = ['ユーザー登録に失敗しました'];
            VueCookies.remove('SOCIAL_LOGIN_FAILED');
       }
   }
}
</script>