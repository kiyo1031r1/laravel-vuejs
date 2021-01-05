<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center mt-3">
                <div v-for="category in categories" :key="category.id" class="icon col-md-3">
                    <router-link :to="{name:'video_show', params: { category: category.id }}">
                        <img :src="'/images/' + category.file_name + '.png'" class="image img-fluid">
                    </router-link>
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
            categories: '',
        }
    },
    components:{
        Header,
    },
    methods:{
        getCategory(){
            axios.get('/api/video_categories')
            .then((res) => {
                this.categories = res.data;
            });
        }
    },
    created(){
        this.getCategory();
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