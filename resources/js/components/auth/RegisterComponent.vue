<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">ユーザー作成</div>

                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">ユーザー名</label>
                                <template v-if="errors.name">
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control is-invalid" name="text" v-model="user.name">
                                        <div class="invalid-feedback">{{ errors.name[0]}}</div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="text" v-model="user.name">
                                    </div>
                                </template>
                            </div>
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
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">パスワードの確認</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" v-model="user.password_confirmation">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary px-4 mt-4">
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
</template>

<script>
export default {
    data(){
        return {
           user:{} ,
           errors: []
        }
    },
    methods:{
        submit() {
            axios.get('sanctum/csrf-cookie')
            .then(() => {
                axios.post('/api/register', this.user)
                .then((res) => {
                    this.$router.push({name: 'task.list'});
                })
                .catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            }); 
        }
    }
}
</script>