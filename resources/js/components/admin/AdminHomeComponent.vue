<template>
    <div>
        dashboad
        <button @click="logout" class="btn btn-success">ログアウト</button>
        <button @click="getUser" class="btn btn-success">ユーザー取得</button>
        <p>{{user.email}}</p>
    </div>
</template>

<script>
export default {
    data(){
        return{
            user:{
                email: 'default'
            }
        }
    },
    methods:{
        logout(){
            axios.post('/api/admin/logout');
            // .then(() => {
            //     localStorage.removeItem('auth');
            //     this.$store.dispatch('updateAuth', null);
            // });
        },
        getUser(){
            axios.get('/api/user')
            .then(res => {
                this.user = res.data;
            });
        }
    }
}
</script>