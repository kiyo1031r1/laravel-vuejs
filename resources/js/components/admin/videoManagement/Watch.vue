<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="embed-responsive embed-responsive-16by9">
                        <video class="embed-responsive-item img-thumbnail" 
                        controls :src="video.video">
                        </video>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title border-bottom">
                                <h4>{{video.title}}</h4>
                                <p class="text-right">{{video.created_at | moment}}</p>
                            </div>
                            <div class="card-title border-bottom">
                                <div class="video-about mb-2" :style="aboutHeightStyle">{{video.about}}</div>
                                <p class="see-more" @click="aboutToggle()">{{about.toggle_word}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'

export default {
    data(){
        return{
            video: {},
            about:{
                toggle: false,
                toggle_word: 'もっと見る',
                height: '50px',
            }
        }
    },
    props: ['id']
    ,
    components:{
        AdminHeader
    },
    computed:{
        aboutHeightStyle(){
            return 'height:' + this.about.height;
        }
    },
    methods:{
        getVideo(){
            axios.get('/api/videos/watch/' + this.id)
            .then(res => {
                this.video = res.data;
            })
        },
        aboutToggle(){
            if(!this.about.toggle){
                this.about.toggle = true;
                this.about.toggle_word = '一部を表示';
                this.about.height = '100%';
            }
            else{
                this.about.toggle = false;
                this.about.toggle_word = 'もっと見る';
                this.about.height = '50px';
            }
        }
    },
    created(){
        this.getVideo();
    }
}
</script>

<style scoped>
.video-about{
    white-space: pre-wrap;
    overflow: hidden;
}

.see-more{
    color: #3490dc;
    cursor: pointer;
}

</style>