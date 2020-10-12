<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">パスワード再設定</div>
                        <div class="card-body">
                            <p class="text-center mb-0 my-4">パスワードを変更しました</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '../users/UsersHeaderComoponent'

export default {
    components:{
        Header
    },
    beforeRouteEnter(to, from, next){
        axios.get('/api/user')
        .then(res => {
            if(localStorage.getItem(process.env.MIX_APP_NAME)){
                next({name: 'home'});
            }
            else{
                localStorage.setItem(process.env.MIX_APP_NAME, res.data.token);
                next();
            }
        })
        .catch(() => {
            next({name: 'login'});
        });
    }
}
</script>