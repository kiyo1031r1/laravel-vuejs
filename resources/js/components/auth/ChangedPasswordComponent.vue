<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center mt-3">
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
        if(from.name === 'reset_password'){
            next();
        }
        else{
            axios.get('/api/user')
            .then(() => {
                next({name: 'video'});
            })
            .catch(() => {
                next({name: 'login'});
            });
        }
    }
}
</script>