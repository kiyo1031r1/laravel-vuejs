<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="col-md-6 mt-3 mx-auto">
                <div class="card">
                    <div class="card-header">プレミアム登録</div>
                    <div class="card-body text-center">
                        <h3>プレミアム会員の解約が</h3>
                        <h3>完了いたしました。</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '@/components/users/Header'

export default {
    components: {
        Header
    },
    beforeRouteEnter(to, from, next){
        if(from.name === 'premium_cancel'){
            next();
        }
        else{
            axios.get('/api/user')
            .then(res => {
                const user = res.data;
                if(user.status === 'normal'){
                    next({name: 'premium_register'});
                }
                else{
                    next();
                }
            })
            .catch(() => {
                next({name: 'login'});
            });
        }
    }
}
</script>