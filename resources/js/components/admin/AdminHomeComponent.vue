<template>
    <div>
        dashboad
        <button @click="logout" class="btn btn-success">ログアウト</button>
    </div>
</template>

<script>
export default {
    methods:{
        logout(){
            axios.post('/api/admin/logout');
            // .then(() => {
            //     localStorage.removeItem('auth');
            //     this.$store.dispatch('updateAuth', null);
            // });
        }
    }
}
</script>