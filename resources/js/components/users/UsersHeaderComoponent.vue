<template>
    <div class="container-fluif bg-dark">
        <div class="container">
            <nav class="navbar">
                <router-link :to="{ name: 'home'}">
                    <span class="h1 text-light">Laravel-vuejs</span>
                </router-link>
                
                <div>
                    <router-link :to="{ name: 'video'}">
                        <button class="btn btn-success">ビデオ一覧</button>
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
            if(localStorage.getItem(process.env.MIX_APP_NAME)
            || localStorage.getItem(process.env.MIX_APP_NAME + '-admin')){
                return true;
            }
            else{
                return false;
            }
        }
    },
    methods:{
        logout(){
            axios.post('/api/logout')
            .then(() => {
                localStorage.removeItem(process.env.MIX_APP_NAME);
                localStorage.removeItem(process.env.MIX_APP_NAME + '-admin');
                this.$router.push({name: 'login'});
            });
        }
    }
}
</script>