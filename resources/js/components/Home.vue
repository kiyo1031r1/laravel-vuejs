<template>
    <div>
        <Header></Header>
        <div class="bg container-fluid">
            <div class="bg-mask">
                <div class="title text-center">
                    <h1 class="message">さぁ、動画で</h1>
                    <h1 class="message">プログラミング学習だ！</h1>
                    <router-link :to="{ name:'login' }">
                        <button class="btn btn-primary btn-lg mt-4">無料会員登録</button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from './users/Header'

export default {
    components:{
        Header
    }    
}
</script>

<style scoped>
.bg{
    background-image: url('/images/toppage.jpeg');
    background-size: cover;
    height: 100vh;
    padding: 0;
    position: relative;
}

.bg-mask{
    height: 100%;
    background: rgba(255,255,255,0.5);
}

.title{
    position:absolute;
    top: 25%;
    left: 25%;
}

.message{
    font-size: 80px;
    font-weight:bold;
}

</style>