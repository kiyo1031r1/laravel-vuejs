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

                    <template v-if="!isAuthenticated">
                        <router-link :to="{ name: ''}">
                            <button class="btn btn-success">ログイン</button>
                        </router-link>
                    </template>

                    <template v-else>
                        <button @click="logout" class="btn btn-success">ログアウト</button>
                    </template>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
export default {
    computed:{
        isAuthenticated(){
            return this.$store.getters.admin_auth === 'true';
        }
    },
    methods:{
        logout(){
            axios.post('/api/admin/logout')
            .then(() => {
                localStorage.removeItem('admin_auth');
                this.$store.dispatch('updateAdminAuth', null);
            });
        }
    }
}
</script>