<template>
    <div class="container-fluif bg-dark">
        <div class="container px-0">
            <nav class="navbar">
                <router-link :to="{ name: 'home'}">
                    <span class="h1 logo text-light">どうぷろ！</span>
                </router-link>
                
                <div>
                    <span v-if="isAuthenticated" class="name">{{isAuthenticated.name}}さん</span>
                    <router-link :to="{ name: 'video'}">
                        <a>ビデオ一覧</a>
                    </router-link>

                    <router-link :to="{ name: 'my_page'}">
                        <a>マイページ</a>
                    </router-link>

                    <router-link :to="{ name: 'premium_register'}">
                        <a>プレミアム登録</a>
                    </router-link>

                    <template v-if="!isAuthenticated">
                        <router-link :to="{ name: 'login'}">
                            <a data-testid='login'>ログイン</a>
                        </router-link>
                    </template>

                    <template v-else>
                        <router-link :to="{ name: ''}">
                            <a @click="logout" data-testid="logout">ログアウト</a>
                        </router-link>
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
.logo {
    font-family: "ヒラギノ角ゴ Std W8",'Hiragino Kaku Gothic Std'
}

.name{
    color: white;
    padding: 0 5px;
}

a{
    padding: 0 5px;
    color: white;
    text-decoration: none;
}

a:hover {
    color: silver;
}
a:visited {
    color: inherit;
}

</style>