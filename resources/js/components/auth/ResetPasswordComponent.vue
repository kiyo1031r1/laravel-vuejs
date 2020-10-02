<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">パスワード再設定</div>

                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="form-group row">
                                <div v-if="errors.user" class="col-md-12">
                                    <p class="text-danger text-center">{{ errors.user[0] }}</p>
                                </div>
                                <div v-if="errors.token" class="col-md-12">
                                    <p class="text-danger text-center">{{ errors.token[0] }}</p>
                                </div>
                                <label for="password" class="col-md-4 col-form-label text-md-right">新パスワード</label>
                                <template v-if="errors.password">
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control is-invalid" name="password" v-model="user.password">
                                        <div class="invalid-feedback">{{ errors.password[0]}}</div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" v-model="user.password">
                                    </div>
                                </template>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">新パスワードの確認</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" v-model="user.password_confirmation">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary px-4 mt-4">
                                        再設定
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueCookies from 'vue-cookies'

export default {
    data(){
        return {
            user:{
                password: '',
                password_confirmation: '',
                token: ''
            },
            errors: [],
        }
    },
    methods: {
        submit(){
            axios.post('/api/reset', this.user)
            .then(() => {
                localStorage.setItem('auth', 'true');
                this.$store.dispatch('updateAuth', 'true');
                this.$router.push({name: 'changed_password'});
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        }
    },
    created(){
       this.user.token = VueCookies.get('RESET_TOKEN');

       if(this.user.token == null) {
           this.$router.push({name: 'login'});
       }
       else{
           VueCookies.remove('RESET_TOKEN');
       }
   }
}
</script>