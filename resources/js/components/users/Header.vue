<template>
    <div class="container-fluif bg-dark">
        <div class="container px-0">
            <nav class="navbar">
                <router-link :to="{ name: 'home'}">
                    <span class="h1 logo text-light">どうぷろ！</span>
                </router-link>

                <!-- 画面サイズが大きければ通常変更 -->
                <div v-if="width > 990">
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

                <!-- 画面サイズが小さければドロップダウンで表示 -->
                <div v-else class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Menu
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <span v-if="isAuthenticated" class="dropdown-item">{{isAuthenticated.name}}さん</span>

                        <router-link :to="{ name: 'video'}">
                            <a class="dropdown-item">ビデオ一覧</a>
                        </router-link>

                        <router-link :to="{ name: 'my_page'}">
                            <a class="dropdown-item">マイページ</a>
                        </router-link>
                        
                        <router-link :to="{ name: 'premium_register'}">
                            <a class="dropdown-item">プレミアム登録</a>
                        </router-link>

                        <template v-if="!isAuthenticated">
                            <router-link :to="{ name: 'login'}">
                                <a class="dropdown-item" data-testid='login'>ログイン</a>
                            </router-link>
                        </template>

                        <template v-else>
                            <router-link :to="{ name: ''}">
                                <a class="dropdown-item" @click="logout" data-testid="logout">ログアウト</a>
                            </router-link>
                        </template>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            width: window.innerWidth,
        }
    },
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
        },
        handleResize(){
            this.width = window.innerWidth;
        }
    },
    mounted(){
        window.addEventListener('resize', this.handleResize);
    },
    beforeDestroy(){
        window.removeEventListener('resize', this.handleResize);
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

.dropdown-menu {
    background-color: #212529;
    left : -100%;
}

.dropdown-item {
    color: white;
    padding: 0 10px;
}

.dropdown-item:hover {
    background-color: inherit;
}

</style>