<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">パスワード再設定用確認メール(開発環境のみ可)</div>
                        <div class="card-body">
                            <p class="text-center mb-0 my-4">メールを送信しました</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '../users/UsersHeaderComoponent'
import VueCookies from 'vue-cookies'

export default {
    beforeRouteEnter(to, from, next){
        if(VueCookies.get('SEND_MAIL')){
            VueCookies.remove('SEND_MAIL');
            next();
        }
        else{
            next({name: 'home'});
        }
    },
    components:{
        Header
    }
}
</script>