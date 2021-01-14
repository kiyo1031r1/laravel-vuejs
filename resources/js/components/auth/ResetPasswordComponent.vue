<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center mt-3">
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

                                    <!-- 新パスワード -->
                                    <label for="password" class="col-md-4 col-form-label text-md-right">新パスワード</label>
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

                                <!-- パスワードの確認 -->
                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">新パスワードの確認</label>
                                    <div class="col-md-6">
                                        <input :type="is_password_hidden ? 'password' : 'text'"
                                        class="form-control" id="password_confirmation" v-model="user.password_confirmation">
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <div class="col-md-3 mt-4 mx-auto">
                                        <button type="submit" class="btn btn-primary btn-block">
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
    </div>
</template>

<script>
import VueCookies from 'vue-cookies'
import Header from '../users/Header'

export default {
    data(){
        return {
            user:{
                password: '',
                password_confirmation: '',
                token: ''
            },
            errors: [],
            is_password_hidden: true
        }
    },
    components:{
        Header
    },
    methods: {
        passwordHiddenToggle(){
            this.is_password_hidden = !this.is_password_hidden;
        },
        submit(){
            axios.post('/api/reset', this.user)
            .then(res => {
                //changed_passwordページのヘッダー切り替え用
                const user = res.data;
                this.$store.dispatch('setUser', user);

                this.$router.push({name: 'changed_password'});
            })
            .catch(error => {
                this.errors = error.response.data.errors;
                this.user.password_confirmation = ''
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