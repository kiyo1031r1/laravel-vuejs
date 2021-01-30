<template>
    <div>
        <Header></Header>
        <div class="col-md-6 mx-auto mt-4">
            <div class="card">
                <div class="card-header">ユーザー情報</div>
                <div class="card-body">
                    <!-- ユーザー名 -->
                    <div class="form-group row">
                        <label class="col-form-label col-md-3">ユーザー名</label>
                        <div class="col-md-7">
                            <input class="form-control" :value="before_name" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-3" for="name">ユーザー名を変更</label>
                        <div class="col-md-7">
                            <input :class="errors.name ? 'form-control is-invalid' : 'form-control'" id="name" v-model="user.name">
                            <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0]}}</div>
                        </div>
                    </div>

                    <!-- メールアドレス -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-md-3">メールアドレス</label>
                        <div class="col-md-7">
                            <input class="form-control" :value="before_email" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-3" for="email">メールアドレスを変更</label>
                        <div class="col-md-7">
                            <input :class="errors.email ? 'form-control is-invalid' : 'form-control'" id="email" v-model="user.email">
                            <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0]}}</div>
                        </div>
                    </div>

                    <!-- パスワード -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-md-3" for="password">パスワードを変更</label>
                        <div class="col-md-7">
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
                        <label class="col-form-label col-md-3" for="password_confirmation">パスワード再確認</label>
                        <div class="col-md-7">
                            <input :type="is_password_hidden ? 'password' : 'text'"
                            class="form-control" id="password_confirmation" v-model="user.password_confirmation">
                        </div>
                    </div>

                    <!-- ステータス -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-md-3">ステータス</label>
                        <div class="col-md-7">
                            <input class="form-control" :value="user.status" disabled>
                        </div>
                    </div>

                    <!-- 次回更新日 -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-md-3">次回更新日</label>
                        <div class="col-md-7">
                            <input class="form-control" disabled>
                        </div>
                    </div>

                </div>
            </div>


            <div class="col-md-4 mx-auto mt-5">
                <button @click="edit()" class="btn btn-primary btn-block" type="submit">更新</button>
            </div>

        </div>
    </div>
</template>

<script>
import Header from './Header'

export default {
    data(){
        return {
            user:{},
            before_name:'',
            before_email:'',
            errors:{},
            is_password_hidden: true,
        }
    },
    components:{
        Header
    },
    methods:{
        getUser(){
            this.user = this.$store.getters.user;
            this.before_name = this.user.name;
            this.before_email = this.user.email;
        },
        passwordHiddenToggle(){
            this.is_password_hidden = !this.is_password_hidden;
        },
        edit(){
            const result = confirm('ユーザー情報を変更します。よろしいですか？');
            if(result){
                axios.put('/api/users/update_from_user/' + this.user.id, this.user)
                .then(() => {
                    this.$router.push({name: 'video'})
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
            }
        }
    },
    created(){
        this.getUser();
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