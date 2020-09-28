<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">ログイン</div>

                    <div class="card-body">
                        <form @submit.prevent="login">
                            <div class="form-group row">
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
                                <template v-if="errors.passowrd">
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

                        <div class='float-right'>
                            <router-link :to="{ name: 'register'}">
                                <button type="submit" class="btn btn-success">
                                    ユーザー新規作成
                                </button>
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
            errors:[]
        }
    },
    methods:{
        login(){
            this.$store.dispatch('login', this.user)
            .then(() => {
                this.$router.push({ name: 'task.list'});
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        }
    }

}
</script>