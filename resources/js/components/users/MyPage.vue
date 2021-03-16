<template>
    <div>
        <Header></Header>
        <div class="col-lg-6 mx-auto mt-4">
            <div class="card">
                <div class="card-header">ユーザー情報</div>
                <div class="card-body">
                    <!-- ユーザー名 -->
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">ユーザー名</label>
                        <div class="col-lg-7">
                            <input class="form-control" :value="isAuthenticated.name" disabled 
                            data-testid="input_name_disabled">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="name">ユーザー名を変更</label>
                        <div class="col-lg-7">
                            <input :class="errors.name ? 'form-control is-invalid' : 'form-control'" id="name" v-model="user.name" 
                            data-testid="input_name">
                            <div v-if="errors.name" class="invalid-feedback" data-testid ="error_name">
                                {{ errors.name[0]}} 
                            </div>
                        </div>
                    </div>

                    <!-- メールアドレス -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-lg-3">メールアドレス</label>
                        <div class="col-lg-7">
                            <input class="form-control" :value="isAuthenticated.email" disabled 
                            data-testid="input_email_disabled">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="email">メールアドレスを変更</label>
                        <div class="col-lg-7">
                            <input :class="errors.email ? 'form-control is-invalid' : 'form-control'" id="email" v-model="user.email" 
                            data-testid="input_email">
                            <div v-if="errors.email" class="invalid-feedback" data-testid ="error_email">
                                {{ errors.email[0]}}
                            </div>
                        </div>
                    </div>

                    <!-- パスワード -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-lg-3" for="password">パスワードを変更</label>
                        <div class="col-lg-7">
                            <input :type="is_password_hidden ? 'password' : 'text'"
                            :class="errors.password ? 'form-control is-invalid' : 'form-control'" id="password" v-model="user.password"
                            data-testid="input_password">
                            <div v-if="errors.password" class="invalid-feedback" data-testid ="error_password">
                                {{ errors.password[0]}}
                            </div>
                        </div>
                        <div @click="passwordHiddenToggle()" class="password-icon col-lg-1"
                        data-testid="password_icon">
                            <span v-if="is_password_hidden" data-testid="eye_slash"><v-icon name="eye-slash" scale="1.5"/></span>
                            <span v-else data-testid="eye"><v-icon name="eye" scale="1.5"/></span>
                        </div>
                    </div>

                    <!-- パスワードの確認 -->
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="password_confirmation">パスワード再確認</label>
                        <div class="col-lg-7">
                            <input :type="is_password_hidden ? 'password' : 'text'"
                            class="form-control" id="password_confirmation" v-model="user.password_confirmation">
                        </div>
                    </div>

                    <!-- ステータス -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-lg-3">ステータス</label>
                        <div class="col-lg-7">
                            <input class="form-control" :value="user.status" disabled 
                            data-testid="input_status_disabled">
                        </div>
                    </div>

                    <!-- 次回更新日 -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-lg-3">次回更新日</label>
                        <div class="col-lg-7">
                            <input class="form-control" v-model="next_update" disabled>
                        </div>
                    </div>

                    <!-- クレジットカード -->
                    <div class="form-group row pt-5">
                        <label class="col-form-label col-lg-3">クレジットカード</label>
                        <div v-if="user.stripe_id" class="col-lg-6 pt-3 m-3 border rounded">
                            <div class="form-group">
                                <label class="col-form-label">名前</label>
                                <input class="form-control" :value="card_information.name" disabled>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">ブランド</label>
                                <input class="form-control" :value="card_information.brand" disabled>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">番号</label>
                                <input class="form-control" :value="'************' + card_information.last_four" disabled>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">有効期限</label>
                                <input class="form-control" :value="card_information.exp_month + '月 / ' + card_information.exp_year + '年'" disabled>
                            </div>
                        </div>
                        <div v-else class="col-lg-7">
                            <input class="form-control" value="未登録" disabled>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-4 mx-auto my-5">
                <button @click="edit()" class="btn btn-primary btn-block" type="submit" 
                data-testid="button_edit">更新</button>
            </div>

        </div>
    </div>
</template>

<script>
import axios from 'axios'
import Header from '@/components/users/Header'

export default {
    data(){
        return {
            user:{},
            errors:{},
            is_password_hidden: true,
            next_update: '',
            card_information: {},
        }
    },
    components:{
        Header
    },
    computed:{
        isAuthenticated(){
            return this.$store.getters.user;
        }
    },
    methods:{
        getUser(){
            this.user.id = this.isAuthenticated.id;
            this.user.name = this.isAuthenticated.name;
            this.user.email = this.isAuthenticated.email;
            this.user.status = this.isAuthenticated.status;
            this.user.stripe_id = this.isAuthenticated.stripe_id;
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
        },
        getNextUpdate(){
            axios.get('/api/subscription/get_next_update')
            .then((res) => {
                this.next_update = res.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        getCardInformation(){
            axios.get('/api/subscription/get_card_information')
            .then((res) => {
                this.card_information = res.data;
            });
        },
    },
    created(){
        this.getUser();
        this.getNextUpdate();
        this.getCardInformation();
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