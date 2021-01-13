<template>

</template>

<script>
import VueCookies from 'vue-cookies'

export default {
    beforeRouteEnter(to, from, next){
        axios.get('/api/user')
        .then(res => {
            next();
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