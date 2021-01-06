<template>
    <div>
        <Header></Header>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <!-- メイン画面 -->
                <div class="col-md-8">
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
                            <div>
                                <h4>{{video.title}}</h4>
                                <p class="text-right">{{video.created_at | moment}}</p>
                            </div>
                            <!-- 概要 -->
                            <div class="border-top py-3" :style="aboutContentHeight">
                                <div class="video-about p-0 mb-2" :style="aboutHeight"
                                ref="about">{{video.about}}</div>
                                <p v-if="about.see_more" class="see-more" @click="aboutToggle()">{{about.toggle_word}}</p>
                            </div>
                            <!-- コメント -->
                            <div class="border-top py-3">
                                <h5 class="comment-title font-weight-bold pb-3 mb-0">Comment</h5>
                                <div v-for="comment in comments" :key="comment.id" class="border-top py-3">
                                    <p>{{comment.user.name}}</p>
                                    <p class="mb-0">{{comment.comment}}</p>
                                    <div v-if="isCommentUser(comment)" class="text-right">
                                        <button @click="deleteComment(comment.id)" class="btn btn-danger m-4">コメント削除</button>
                                    </div>

                                    <!-- 返信コメント -->
                                    <div v-if="comment.re_video_comments.length > 0" class="px-3">
                                        <div @click="commentToggle(comment)" class="btn btn-link" data-toggle="collapse" :href="'#comment'+ comment.id" role="button" 
                                        aria-expanded="false" :aria-controls="'comment' + comment.id">
                                            <a v-if="!comment.re_comment_toggle">▼このコメントへの返信を表示</a>
                                            <a v-else>▼このコメントへの返信を非表示</a>
                                        </div>
                                        <div class="px-3">
                                            <div v-for="re_video_comment in comment.re_video_comments" 
                                            :key="re_video_comment.id" class="collapse border-top" :id="'comment' + comment.id">
                                                <p class="mt-3">{{re_video_comment.user.name}}</p>
                                                <p>{{re_video_comment.comment}}</p>
                                                <div v-if="isCommentUser(comment)" class="text-right">
                                                    <button @click="deleteReComment(re_video_comment.id)" class="btn btn-danger m-4">返信コメント削除</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <infinite-loading v-if="hasNext" @infinite="infiniteHandler" spinner="spiral" direction="bottom">
                            </infinite-loading>
                        </div>
                    </div>
                </div>
                <!-- レコメンド動画 -->
                <div class="col-md-3">
                    <div v-for="recommend in recommends" :key="recommend.id">
                        <div @click="moveRecommend(recommend.id)" class="card mb-2">
                            <div class="row no-gutters">
                                <div class="col-md-5">
                                    <img class="img-fluid" :src="recommend.thumbnail">
                                </div>
                                <div class="col-md-7 p-2">
                                    <p class="card-title">{{recommend.title}}</p>
                                    <p class="card-tag">
                                        <span v-for="category in recommend.video_category" :key="category.id"
                                        class="badge badge-secondary mr-1">{{category.name}}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="recommends == null">
                        <p class="mt-4">カテゴリ未登録の為、関連動画はありません。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '../UsersHeaderComoponent'
import InfiniteLoading from 'vue-infinite-loading'

export default {
    data(){
        return{
            user: '',
            video: {},
            about:{
                see_more : false,
                toggle: false,
                toggle_word: 'もっと見る',
                height: '',
                content_height: ''
            },
            comments: [],
            
            //無限スクロール
            start_page: 1,
            end_page: 1,
            last_page: 0,
            per_page: 20,
            initialized: false,

            recommends: [],
        }
    },
    props: ['id']
    ,
    components:{
        Header,
        InfiniteLoading,
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
        },
        hasNext(){
            return this.initialized && this.last_page > this.end_page;
        }
    },
    watch: {
        $route(to, from){
            //コメントは追加されるので初期化
            this.comments = [];
            this.getVideo(null, this.start_page, false);
        }
    },
    methods:{
        getVideo($state, page, next){
            axios.post('/api/videos/watch/' + this.id + '?page=' + page, {
                'per_page' :  this.per_page,
            })
            .then(res => {
                this.video = res.data.video;
                this.recommends = res.data.recommends;

                //追加データをマージ
                let new_data = res.data.comments.data;
                new_data.forEach((data) => {
                    this.comments.push(data);
                })

                this.last_page = res.data.comments.last_page;
                this.end_page = page;
                if($state) $state.loaded();

                //返信表示の切り替え属性を付与
                this.comments.forEach((object, index) => {
                    this.$set(this.comments[index], 're_comment_toggle', false);
                })

                //概要の高さを取得
                this.$nextTick(() => {
                    const rect = this.$refs.about.getBoundingClientRect();
                    this.about.height = rect.height;

                    //初回コメントデータアクセス完了フラグ
                    this.initialized = true;
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
        },
        commentToggle(comment){
            comment.re_comment_toggle = !comment.re_comment_toggle;
        },
        deleteComment(id){
            const result = confirm('コメントを削除します。よろしいですか？');
            if(result){
                let formData = new FormData();
                formData.append('id', id);
                axios.post('/api/video_comments/' + id, formData, {
                    headers: {
                        'X-HTTP-Method-Override': 'DELETE'
                    }
                })
                .then(()=>{
                    this.comments = [];
                    this.getVideo(null, this.start_page, false);
                });
            }
        },
        deleteReComment(id){
            const result = confirm('コメントを削除します。よろしいですか？');
            if(result){
                let formData = new FormData();
                formData.append('id', id);
                axios.post('/api/re_video_comments/' + id, formData, {
                    headers: {
                        'X-HTTP-Method-Override': 'DELETE'
                    }
                })
                .then(()=>{
                    this.comments = [];
                    this.getVideo(null, this.start_page, false);
                });
            }
        },
        infiniteHandler($state){
            if(this.end_page >= this.total_page){
                $state.complete();
            }
            else{
                this.getVideo($state, this.end_page + 1, true);
            }
        },
        moveRecommend(id){
            this.$router.push({name: 'video_watch', params: { id: id}});
        },
        getUser(){
            axios.get('/api/user')
            .then(res => {
                this.user = res.data;
            });
        },
        isCommentUser(comment){
            return comment.user_id === this.user.id;
        }
    },
    created(){
        this.getVideo(null, this.start_page, false);
        this.getUser();
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

.card{
    cursor: pointer;
}

.card-title{
    height: 40px;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;
}

.card-tag{
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
    overflow: hidden;
}

</style>