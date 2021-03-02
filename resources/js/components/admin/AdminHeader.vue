<template>
    <div class="container-fluif bg-dark">
        <div class="container">
            <nav class="navbar">
                <router-link :to="{ name: 'home'}">
                    <span class="h1 logo text-light">どうぷろ！</span>
                </router-link>
                    <span class="name">管理者 : {{isAuthenticated.name}}さん</span>

                <!-- 画面サイズが大きければ通常変更 -->
                <div v-if="width > 800">
                    <router-link :to="{ name: 'user_management'}">
                        <a>ユーザー管理</a>
                    </router-link>

                    <router-link :to="{ name: 'video_management'}">
                        <a>ビデオ管理</a>
                    </router-link>
                    
                    <router-link :to="{ name: ''}">
                        <a @click="logout" >ログアウト</a>
                    </router-link>
                </div>

                <!-- 画面サイズが小さければドロップダウンで表示 -->
                <div v-else class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Menu
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <router-link :to="{ name: 'user_management'}">
                            <a class="dropdown-item">ユーザー管理</a>
                        </router-link>

                        <router-link :to="{ name: 'video_management'}">
                            <a class="dropdown-item">ビデオ管理</a>
                        </router-link>
                        
                        <router-link :to="{ name: ''}">
                            <a class="dropdown-item" @click="logout" >ログアウト</a>
                        </router-link>
                    </div>
                </div>

            </nav>
        </div>
    </div>
</template>

<script>
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
                axios.post('/api/admin/logout')
                .then(() => {
                    this.$router.push({name: 'admin_login'});
                });
            })
            //自動ログアウト時の処理
            .catch(() => {
                this.$router.push({name: 'admin_login'});
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
    font-size: 16px;
    margin-right: 5px;
    vertical-align: bottom;
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
    padding: 0 10px;
}

a.dropdown-item:hover {
    background-color: inherit;
}

</style>