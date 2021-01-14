<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center mt-3">
                <div v-for="category in categories" :key="category.id" class="icon col-md-3">
                    <router-link :to="{name:'video_show', params: { category: category}}">
                        <img :src="'/images/' + category + '.png'" class="image img-fluid">
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '../Header'

export default {
    data(){
        return{
            //基本的に固定なので直接記載
            categories: [
                'php',
                'ruby',
                'javascript',
                'python',
                'csharp',
                'laravel',
                'rails',
                'vuejs',
                'django'
            ],
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