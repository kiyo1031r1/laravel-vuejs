<template>
    <div class="container-fluif bg-dark mb-3">
        <div class="container">
            <nav class="navbar navbar-dark">
                <span class="nabvar-brand mb-0 h1 text-light">Laravel-vuejs</span>
                <div>
                    <router-link :to="{ name: 'task.list'}">
                        <button class="btn btn-success">一覧</button>
                    </router-link>

                    <router-link :to="{ name: 'task.create'}">
                        <button class="btn btn-success">新規作成</button>
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
            this.$store.dispatch('logout')
            .then(() => {
                this.$router.push({ name: 'login'});
            });
        }
    }
}
</script>