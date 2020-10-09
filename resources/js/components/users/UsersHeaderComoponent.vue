<template>
    <div class="container-fluif bg-dark mb-3">
        <div class="container">
            <nav class="navbar navbar-dark">
                <span class="nabvar-brand mb-0 h1 text-light">Laravel-vuejs</span>
                <div>
                    <form>
                        <label name="search">検索</label>
                        <input type="text" id="search">
                    </form>
                    <router-link :to="{ name: ''}">
                        <button class="btn btn-success">一覧</button>
                    </router-link>

                    <router-link :to="{ name: ''}">
                        <button class="btn btn-success">プレミアム登録</button>
                    </router-link>

                    <template v-if="!isAuthenticated">
                        <router-link :to="{ name: 'login'}">
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
            return this.$store.getters.auth === 'true';
        }
    },
    methods:{
        logout(){
            axios.post('/api/logout')
            .then(() => {
                localStorage.removeItem('auth');
                this.$store.dispatch('updateAuth', null);
            });
        }
    }
}
</script>