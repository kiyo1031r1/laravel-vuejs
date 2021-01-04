<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center mt-3">
                <div v-for="icon in icons" :key="icon.index" class="icon col-md-3">
                    <img :src="'/images/' + icon + '.png'" class="image img-fluid">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '../UsersHeaderComoponent'

export default {
    data(){
        return{
            icons: ['php','ruby','js','python','csharp','laravel','rails','vue','django'],
        }
    },
    components:{
        Header,
    }
}
</script>

<style scoped>
.icon{
    margin: 20px;
    padding: 0;
}

.image{
    padding: 30px;
    border: 2px #dee2e6 solid;
    border-radius: 20px;
}
</style>