<template>
    <div>
        <div class="bg">
            <div class="bg-mask">
                <Header></Header>
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
import Header from '@/components/users/Header'

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
}

.bg-mask{
    height: 100%;
    background: rgba(255,255,255,0.5);
}

.title{
    padding-top: 30vh;
}

.message{
    font-size: calc(10px + 4vw);
    font-weight:bold;
}

</style>