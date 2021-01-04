<template>

</template>

<script>
import VueCookies from 'vue-cookies'

export default {
    beforeRouteEnter(to, from, next){
        axios.get('/api/user')
        .then(res => {
            if(localStorage.getItem(process.env.MIX_APP_NAME)){
                next({name: 'video'});
            }
            else{
                localStorage.setItem(process.env.MIX_APP_NAME, res.data.token);
                next();
            }
        })
        .catch(() => {
            next({name: 'login'});
        });
    },

    created(){
        if(VueCookies.get('SOCIAL_LOGIN_SUCCESS')) {
            VueCookies.remove('SOCIAL_LOGIN_SUCCESS');
        }

        this.$router.push({name: 'video'});
    },
}
</script>