<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
        </div>
    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'

export default {
    data(){
        return{
            video: null,
        }
    },
    props: ['id']
    ,
    components:{
        AdminHeader
    },
    methods:{
        getVideo(){
            axios.get('/api/videos/watch/' + this.id)
            .then(res => {
                this.video = res.data;
            })
        }
    },
    created(){
        this.getVideo();
    }
}
</script>