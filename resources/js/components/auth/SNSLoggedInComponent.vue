<template>

</template>

<script>
import VueCookies from 'vue-cookies'

export default {
    created(){
        if(VueCookies.get('SOCIAL_LOGIN_SUCCESS')) {
            VueCookies.remove('SOCIAL_LOGIN_SUCCESS');
            axios.get('/api/user')
            .then(res => {
                localStorage.setItem(process.env.MIX_APP_NAME, res.data.token);
                this.$router.push({name: 'home'});
            });
        }
    }
}
</script>