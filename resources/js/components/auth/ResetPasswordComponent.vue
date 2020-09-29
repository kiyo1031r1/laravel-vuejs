<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">パスワード再設定</div>

                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>
                                <template v-if="errors.password">
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control is-invalid" name="password" v-model="password">
                                        <div class="invalid-feedback">{{ errors.password[0]}}</div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" v-model="password">
                                    </div>
                                </template>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">パスワードの確認</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" v-model="password_confirmation">
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
export default {
    data(){
        return {
            password: '',
            password_confirmation: '',
            errors: []
        }
    },
    methods: {
        submit(){

        }
    }
}
</script>