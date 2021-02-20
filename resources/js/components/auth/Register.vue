<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">ユーザー作成</div>

                        <div class="card-body">
                            <form @submit.prevent="submit">
                                <!-- ユーザー名 -->
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">ユーザー名</label>
                                    <div class="col-md-6">
                                        <input :class="errors.name ? 'form-control is-invalid' : 'form-control'" id="name" v-model="user.name">
                                        <div v-if="errors.name" class="invalid-feedback" 
                                        data-testid="error_name">{{ errors.name[0]}}</div>
                                    </div>
                                </div>

                                <!-- メールアドレス -->
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>
                                    <div class="col-md-6">
                                        <input :class="errors.email ? 'form-control is-invalid' : 'form-control'" id="email" v-model="user.email">
                                        <div v-if="errors.email" class="invalid-feedback" 
                                        data-testid="error_email">{{ errors.email[0]}}</div>
                                    </div>
                                </div>

                                <!-- パスワード -->
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>
                                    <div class="col-md-6">
                                        <input :type="is_password_hidden ? 'password' : 'text'"
                                        :class="errors.password ? 'form-control is-invalid' : 'form-control'" id="password" v-model="user.password">
                                        <div v-if="errors.password" class="invalid-feedback" 
                                        data-testid="error_password">{{ errors.password[0]}}</div>
                                    </div>
                                    <div @click="passwordHiddenToggle()" class="password-icon col-md-1" 
                                    data-testid="password_icon">
                                        <span v-if="is_password_hidden" data-testid="eye_slash"><v-icon name="eye-slash" scale="1.5"/></span>
                                        <span v-else data-testid="eye"><v-icon name="eye" scale="1.5"/></span>
                                    </div>
                                </div>

                                <!-- パスワード確認 -->
                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">パスワードの確認</label>
                                    <div class="col-md-6">
                                        <input :type="is_password_hidden ? 'password' : 'text'"
                                        class="form-control" id="password_confirmation" v-model="user.password_confirmation">
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <div class="col-md-3 mt-4 mx-auto">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            作成
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
import Header from '@/components/users/Header'
import axios from 'axios'

export default {
    data(){
        return {
           user:{} ,
           errors: [],
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
        submit() {
            axios.get('sanctum/csrf-cookie')
            .then(() => {
                axios.post('/api/register', this.user)
                .then(() => {
                    this.$router.push({name: 'video'});
                })
                .catch((error) =>{
                    this.errors = error.response.data.errors;
                    this.user.password_confirmation = ''
                });
            }); 
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