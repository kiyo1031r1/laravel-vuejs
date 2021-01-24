<template>
    <div>
        <!-- ヘッダー(ページ専用) -->
        <div class="container-fluif bg-dark mb-3">
            <div class="container">
                <nav class="navbar">
                <router-link :to="{ name: 'home'}">
                    <span class="nabvar-brand mb-0 h1 text-light">Laravel-vuejs</span>
                </router-link>
                </nav>
            </div>
        </div>

        <div class="container">
            <!-- ログインフォーム -->
            <div class="row justify-content-center my-3">
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- アプリ確認用 -->
            <div class="row mx-auto border-top">
                <div class="col-md-8 py-3">
                    <div class="card">
                        <div class="card-header">アプリ確認用</div>
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
                            <router-link :to="{name:'login'}">
                                <button class="btn btn-primary">通常ログインページへ遷移</button>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data(){
        return{
            user:{},
            errors:{}
        }
    },
    methods:{
        login(){
            axios.get('sanctum/csrf-cookie')
            .then(() => {
                axios.post('/api/admin/login', this.user)
                .then((res) => {
                    //遷移前のpath情報があれば、そのページに遷移
                    //this.$router.push(
                    //    this.$route.query.redirect ? this.$route.query.redirect : {name: 'video_management'}
                    //);
                })
                .catch((error) =>{
                    //this.errors = error.response.data.errors;
                })
            });
        }
    }
}
</script>