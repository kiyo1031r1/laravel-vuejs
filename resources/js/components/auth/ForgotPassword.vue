<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="col-md-8 mt-3 mx-auto">
                <div class="card">
                    <div class="card-header">パスワード再設定用確認メール(開発環境のみ可)</div>
                    <div class="card-body">
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

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button @click="submit()" class="btn btn-primary mr-4">送信</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '@/components/users/Header'
import VueCookies from 'vue-cookies'

export default {
    data(){
        return {
            user: {},
            errors: []
        }
    },
    components:{
        Header
    },
    methods: {
        submit(){
            axios.post('/api/forgot', this.user)
            .then(() => {
                VueCookies.set('SEND_MAIL');
                this.$router.push({ name: 'send_mail' });
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>