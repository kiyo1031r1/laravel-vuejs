<template>
    <div class="container-fluif bg-dark">
        <div class="container">
            <nav class="navbar">
                <router-link :to="{ name: 'home'}">
                    <span class="h1 text-light">Laravel-vuejs</span>
                </router-link>
                
                <div>
                    <span v-if="isAuthenticated" class="name">{{isAuthenticated.name}}さん</span>
                    <router-link :to="{ name: 'video'}">
                        <button class="btn btn-success">ビデオ一覧</button>
                    </router-link>

                    <router-link :to="{ name: 'my_page'}">
                        <button class="btn btn-success">マイページ</button>
                    </router-link>

                    <router-link :to="{ name: 'premium_register'}">
                        <button class="btn btn-success">プレミアム登録</button>
                    </router-link>

                    <template v-if="!isAuthenticated">
                        <router-link :to="{ name: 'login'}">
                            <button class="btn btn-success login">ログイン</button>
                        </router-link>
                    </template>

                    <template v-else>
                        <button @click="logout" class="btn btn-success logout">ログアウト</button>
                    </template>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

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
                axios.post('/api/logout')
                .then(() => {
                    this.$router.push({name: 'login'});
                });
            })
            //自動ログアウト時の処理
            .catch(() => {
                this.$router.push({name: 'login'});
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