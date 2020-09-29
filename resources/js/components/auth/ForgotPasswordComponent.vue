<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">パスワード再設定用確認メール</div>

                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>
                                <template v-if="errors.email">
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control is-invalid" name="email" v-model="email">
                                        <div class="invalid-feedback">{{ errors.email[0]}}</div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" v-model="email">
                                    </div>
                                </template>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary mr-4">
                                        送信
                                    </button>
                                </div>
                            </div>
                        </form>

                        <router-link :to="{ name: 'reset_password'}">
                            <p>仮の再設定用ページリンク</p>
                        </router-link>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            email: '',
            errors: []
        }
    },
    methods: {
        submit(){
            this.$router.push({ name: 'send_mail' });
        }
    }
}
</script>