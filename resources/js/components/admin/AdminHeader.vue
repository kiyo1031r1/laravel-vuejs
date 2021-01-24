<template>
    <div class="container-fluif bg-dark">
        <div class="container">
            <nav class="navbar">
                <router-link :to="{ name: 'home'}">
                    <span class="h1 text-light">Laravel-vuejs</span>
                </router-link>
                    <span class="name">管理者 : {{isAuthenticated.name}}さん</span>
                <div>
                    <router-link :to="{ name: 'user_management'}">
                        <button class="btn btn-success">ユーザー管理</button>
                    </router-link>

                    <router-link :to="{ name: 'video_management'}">
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
    computed:{
        isAuthenticated(){
            return this.$store.getters.user;
        }
    },
    methods:{
        logout(){
            axios.get('/api/user')
            .then(() => {
                axios.post('/api/admin/logout')
                .then(() => {
                    this.$router.push({name: 'admin_login'});
                });
            })
            //自動ログアウト時の処理
            .catch(() => {
                this.$router.push({name: 'admin_login'});
            });
        }
    }
}
</script>

<style scoped>
.name{
    color: white;
    font-size: 16px;
    margin-right: 5px;
    vertical-align: bottom;
}
</style>