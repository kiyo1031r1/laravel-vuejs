<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center mt-3">
                <div v-for="category in categories" :key="category.id" class="icon col-md-3">
                    <router-link :to="{name:'video_index', params: { category: category}}">
                        <img :src="'/images/categoryIcons/' + category + '.png'" class="image img-fluid">
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '@/components/users/Header'

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
@media (max-width: 767px) {
.icon {
	width: 25%;
}
}

.icon{
    margin: 2vw;
    padding: 0;
}

.image{
    padding: 2vw;
    border: 2px #dee2e6 solid;
    border-radius: 2vw;
}
</style>