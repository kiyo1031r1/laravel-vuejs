<template>
    <div class="container-fluif bg-dark mb-3">
        <div class="container">
            <nav class="navbar navbar-dark">
                <span class="nabvar-brand mb-0 h1 text-light">Laravel-vuejs-Admin</span>
                <div>
                    <router-link :to="{ name: ''}">
                        <button class="btn btn-success">ユーザー管理</button>
                    </router-link>

                    <router-link :to="{ name: ''}">
                        <button class="btn btn-success">ビデオ管理</button>
                    </router-link>
                    
                    <button @click="logout" class="btn btn-success">ログアウト</button>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
export default {
    methods:{
        logout(){
            axios.post('/api/admin/logout')
            .then(() => {
                this.$router.push({name: 'admin_login'});
            });
        }
    }
}
</script>