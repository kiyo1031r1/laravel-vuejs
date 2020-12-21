<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
            <div class="row justify-content-center">
                <!-- メイン画面 -->
                <div class="col-md-10">
                    <!-- ビデオ画面 -->
                    <div class="embed-responsive embed-responsive-16by9">
                        <video class="embed-responsive-item img-thumbnail" 
                        controls :src="video.video">
                        </video>
                    </div>
                    <!-- ビデオ情報 -->
                    <div class="card">
                        <div class="card-body">
                            <!-- タイトル -->
                            <div class="card-title border-bottom">
                                <h4>{{video.title}}</h4>
                                <p class="text-right">{{video.created_at | moment}}</p>
                            </div>
                            <!-- 概要 -->
                            <div class="card-title border-bottom" :style="aboutContentHeight">
                                <div class="col-md-7 video-about p-0 mb-2" :style="aboutHeight"
                                ref="about">{{video.about}}</div>
                                <p v-if="about.see_more" class="see-more" @click="aboutToggle()">{{about.toggle_word}}</p>
                            </div>
                            <!-- コメント -->
                            <div class="card-title">
                                <h5 class="comment-title border-bottom font-weight-bold pb-2 mb-0">Comment</h5>
                                <div v-for="comment in comments" :key="comment.id" class="border-bottom py-3">
                                    <p>{{comment.user.name}}</p>
                                    <p class="col-md-7 px-0">{{comment.comment}}</p>
                                    
                                    <!-- 返信コメント -->
                                    <div v-if="comment.re_video_comments.length > 0" class="col-md-7">
                                        <a>▼このコメントへの返信を表示</a>
                                        <div v-for="re_video_comment in comment.re_video_comments" 
                                        :key="re_video_comment.id" class="border-top pt-3">
                                            <p>{{re_video_comment.user.name}}</p>
                                            <p class="co-md-7 px-0">{{re_video_comment.comment}}</p>
                                        </div>
                                    </div>
                                </div>
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
                see_more : false,
                toggle: false,
                toggle_word: 'もっと見る',
                height: '',
                content_height: ''
            },
            comments: '',
        }
    },
    props: ['id']
    ,
    components:{
        AdminHeader
    },
    computed:{
        //概要が多い場合に「もっと見る」を表示
        aboutHeight(){
            if(this.about.height > 50) {
                this.about.see_more = true;
                this.about.content_height = '100%';
                this.about.height = '50px';
            }
            //表示が少ない場合でも一定領域を表示
            if(this.about.see_more == false) {
                this.about.content_height = '100px';
            }
            
            return 'height:' + this.about.height;
        },
        aboutContentHeight(){
            return 'height:' + this.about.content_height;
        }
    },
    methods:{
        getVideo(){
            axios.get('/api/videos/watch/' + this.id)
            .then(res => {
                this.video = res.data.video;
                this.comments = res.data.comments;
                //概要の高さを取得
                this.$nextTick(() => {
                    const rect = this.$refs.about.getBoundingClientRect();
                    this.about.height = rect.height;
                 })
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