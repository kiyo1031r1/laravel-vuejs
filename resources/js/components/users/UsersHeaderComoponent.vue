<template>
    <div class="container-fluif bg-dark mb-3">
        <div class="container">
            <nav class="navbar navbar-dark">
                <router-link :to="{ name: 'home'}">
                    <span class="h1 text-light">Laravel-vuejs</span>
                </router-link>

                <form class="form-inline">
                    <input class="form-controll mr-sm-2" type="search">
                    <button class="btn btn-outline-success my-sm-0" type="submit">検索</button>
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
            </nav>
        </div>
    </div>
</template>

<script>
export default {
    computed:{
        isAuthenticated(){
            return localStorage.getItem(process.env.MIX_APP_NAME);
        }
    },
    methods:{
        logout(){
            axios.post('/api/logout')
            .then(() => {
                localStorage.removeItem(process.env.MIX_APP_NAME);
                this.$router.push({name: 'login'});
            });
        }
    }
}
</script>