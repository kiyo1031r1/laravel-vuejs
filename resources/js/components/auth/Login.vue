<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row">
                <!-- ログインフォーム -->
                <div class="col-md-8 my-3">
                    <div class="card">
                        <div class="card-header">ログイン</div>
                        <div class="card-body">
                            <form @submit.prevent="login">
                                <!-- SNS認証エラー -->
                                <div v-if="errors.not_found" class="form-group row">
                                    <div class="col-md-12">
                                        <p class="text-danger text-center mb-0">{{ errors.not_found[0] }}</p>
                                    </div>
                                </div>

                                <!-- メールアドレス -->
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>
                                    <div class="col-md-6">
                                        <input :class="errors.email ? 'form-control is-invalid' : 'form-control'" id="email" v-model="user.email">
                                        <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0]}}</div>
                                    </div>
                                </div>

                                <!-- パスワード -->
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>
                                    <div class="col-md-6">
                                        <input :type="is_password_hidden ? 'password' : 'text'"
                                        :class="errors.password ? 'form-control is-invalid' : 'form-control'" id="password" v-model="user.password">
                                        <div v-if="errors.password" class="invalid-feedback">{{ errors.password[0]}}</div>
                                    </div>
                                    <div @click="passwordHiddenToggle()" class="password-icon col-md-1">
                                        <span v-if="is_password_hidden" ><v-icon name="eye-slash" scale="1.5"/></span>
                                        <span v-else><v-icon name="eye" scale="1.5"/></span>
                                    </div>
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

                <!-- ソーシャルログインフォーム -->
                <div class="col-md-4 my-3">
                    <div class="card">
                        <div class="card-header">ソーシャルアカウントでログイン</div>
                        <div class="card-body">
                            <div class="col-md-11 mx-auto text-center">
                                <button onclick="location.href='/login/google'" class="btn btn-danger btn-block mb-2 py-3">Googleアカウントでログイン</button>
                                <button onclick="location.href='/login/facebook'" class="btn btn-primary btn-block mb-2 py-3" style="background-color: blue; border-color: blue;">Facebookアカウントでログイン</button>
                                <button onclick="location.href='/login/twitter'" class="btn btn-primary btn-block mb-2 py-3">Twitterアカウントでログイン</button>
                                <p class="mb-0">※本番環境では使用できません</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             <!-- アプリ確認用 -->
            <div class="row border-top">
                <div class="col-md-8 py-3">
                    <div class="card">
                        <div class="card-header">アプリ確認用(※通常アカウントは[ユーザー新規作成]からも作成可能です)</div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered table-hover text-center ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">内容</th>
                                        <th scope="col">メールアドレス</th>
                                        <th scope="col">パスワード</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>テストユーザー(管理者アカウント)</td>
                                        <td>test1@test.com</td>
                                        <td>11111111</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>テストユーザー(管理者アカウント)</td>
                                        <td>test2@test.com</td>
                                        <td>22222222</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td>テストユーザー(管理者アカウント)</td>
                                        <td>test3@test.com</td>
                                        <td>33333333</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">4</th>
                                        <td>テストユーザー(管理者アカウント)</td>
                                        <td>test4@test.com</td>
                                        <td>44444444</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">5</th>
                                        <td>テストユーザー(通常アカウント)</td>
                                        <td>test5@test.com</td>
                                        <td>55555555</td>
                                    <tr>
                                        <th scope="row">6</th>
                                        <td>テストユーザー(通常アカウント)</td>
                                        <td>test6@test.com</td>
                                        <td>66666666</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">7</th>
                                        <td>テストユーザー(通常アカウント)</td>
                                        <td>test7@test.com</td>
                                        <td>77777777</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">8</th>
                                        <td>テストユーザー(通常アカウント)</td>
                                        <td>test8@test.com</td>
                                        <td>88888888</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 py-3">
                    <div class="card">
                        <div class="card-header">アプリ確認用</div>
                        <div class="card-body text-center">
                            <router-link :to="{name:'admin_login'}">
                                <button class="btn btn-primary">管理者ログインページへ遷移</button>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import VueCookies from 'vue-cookies'
import Header from '@/components/users/Header'

export default {
    data(){
        return{
            user:{},
            errors:{},
            is_password_hidden: true,
        }
    },
    components:{
        Header
    },
    methods:{
        passwordHiddenToggle(){
            this.is_password_hidden = !this.is_password_hidden;
        },
        login(){
            axios.get('sanctum/csrf-cookie')
            .then(() => {
                axios.post('/api/login', this.user)
                .then((res) => {
                    //遷移前のpath情報があれば、そのページに遷移
                    this.$router.push(
                        this.$route.query.redirect ? this.$route.query.redirect : {name: 'video'}
                    );
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

<style scoped>
.password-icon {
    display: flex;
    align-items: center;
    margin-top: 5px;
    cursor: pointer;
}

.fa-icon{
    height: 20px;
}
</style>