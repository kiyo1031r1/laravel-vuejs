<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <!-- メイン画面 -->
                <div class="main">
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

                                <!-- 評価 -->
                                <div class="text-right mb-2">
                                    <div>
                                        <span v-if="video.evaluation >= 5">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="video.evaluation >= 4.5">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half-alt" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="video.evaluation >= 4">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="video.evaluation >= 3.5">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half-alt" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="video.evaluation >= 3">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="video.evaluation >= 2.5">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half-alt" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="video.evaluation >= 2">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="video.evaluation >= 1.5">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half-alt" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="video.evaluation >= 1">
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else class="evaluation-back">
                                            未評価
                                        </span>
                                    </div>
                                </div>

                                <!-- 作成日時 -->
                                <p class="text-right">{{video.created_at | moment}}</p>
                            </div>
                            <!-- 概要 -->
                            <div class="border-top py-3" :style="aboutContentHeight">
                                <div class="video-about p-0 mb-2" :style="aboutHeight"
                                ref="about">{{video.about}}</div>
                                <span v-if="about.see_more" class="see-more" @click="aboutToggle()">{{about.toggle_word}}</span>
                            </div>
                            <!-- コメント一覧 -->
                            <div class="border-top py-3">
                                <h5 class="comment-title font-weight-bold pb-3 mb-0">Comment : {{total_comments}}件</h5>
                                <div v-for="video_comment in video_comments" :key="video_comment.id" class="border-top py-3">
                                    <div class="mb-2">
                                        <span>{{video_comment.user.name}}</span>
                                        <span class="text-secondary ml-2">{{video_comment.created_at | moment_ago}}</span>
                                    </div>
                                    <p class="mb-0" style="white-space: pre-wrap">{{video_comment.video_comment}}</p>
                                    <div class="text-right">
                                        <button @click="deleteComment(video_comment.id)" class="btn btn-danger m-3">コメント削除</button>
                                    </div>

                                    <!-- 返信コメント -->
                                    <div v-if="video_comment.re_video_comments.length > 0" class="px-3">
                                        <!-- 表示切り替え -->
                                        <div @click="commentToggle(video_comment)" class="btn btn-link" data-toggle="collapse" :href="'#video_comment'+ video_comment.id" role="button" 
                                        aria-expanded="false" :aria-controls="'video_comment' + video_comment.id">
                                            <a v-if="!video_comment.re_video_comment_toggle">▼このコメントへの返信を表示</a>
                                            <a v-else>▼このコメントへの返信を非表示</a>
                                        </div>

                                        <!-- 返信コメント一覧 -->
                                        <div class="px-3">
                                            <div v-for="re_video_comment in video_comment.re_video_comments" 
                                            :key="re_video_comment.id" class="collapse border-top" :id="'video_comment' + video_comment.id">
                                                <div class="mt-3 mb-2">
                                                    <span >{{re_video_comment.user.name}}</span>
                                                    <span class="text-secondary ml-2">{{re_video_comment.created_at | moment_ago}}</span>
                                                </div>
                                                <p style="white-space: pre-wrap">{{re_video_comment.re_video_comment}}</p>
                                                <div class="text-right">
                                                    <button @click="deleteReComment(re_video_comment.id)" class="btn btn-danger m-3">返信コメント削除</button>
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
                <div class="recommends">
                    <p class="h4 text-center my-3">あなたへのオススメ</p>
                    <div v-for="recommend in recommends" :key="recommend.id">
                        <div @click="moveRecommend(recommend)" class="recommend card mb-2">
                            <div class="row no-gutters">
                                <div class="col-lg-5">
                                    <img class="img-fluid" style="position:relative" :src="recommend.thumbnail !== null ? recommend.thumbnail : '/images/default_thumbnail.jpg' ">
                                    <span v-if="recommend.status == 'premium'" class="badge badge-warning" 
                                    style="position: absolute; top:4px; right:4px; font-size:100%">{{recommend.status}}
                                    </span>
                                    <span class="badge badge-dark" 
                                    style="position: absolute; bottom:4px; right:4px; font-size:100%">{{recommend.video_time}}
                                    </span>
                                </div>
                                <div class="col-lg-7 p-2">
                                    <p class="card-title">{{recommend.title}}</p>
                                    <p class="card-tag">
                                        <span v-for="category in recommend.video_categories" :key="category.id"
                                        class="badge badge-secondary mr-1">{{category.name}}
                                        </span>
                                    </p>
                                    <div class="text-right">
                                        <span v-if="recommend.evaluation >= 5">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="recommend.evaluation >= 4.5">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half-alt" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="recommend.evaluation >= 4">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="recommend.evaluation >= 3.5">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half-alt" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="recommend.evaluation >= 3">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="recommend.evaluation >= 2.5">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half-alt" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="recommend.evaluation >= 2">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="recommend.evaluation >= 1.5">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half-alt" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="recommend.evaluation >= 1">
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else>
                                            <div class="evaluation-blank"></div>
                                        </span>
                                    </div>
                                    <p class="text-right mb-0">{{recommend.created_at | moment}}</p>
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
import AdminHeader from '@/components/admin/AdminHeader'
import InfiniteLoading from 'vue-infinite-loading'

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
            video_comments: [],
            total_comments: '',
            
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
        AdminHeader,
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
            this.video_comments = [];
            this.getVideo();
        }
    },
    methods:{
        getVideo(){
            axios.post('/api/videos/watch/' + this.id)
            .then(res => {
                this.video = res.data.video;
                this.recommends = res.data.recommends;

                //初回コメント読み込み
                this.getComment(null, this.start_page, false);
            });
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
        //コメント
        getComment($state, page, next){
            axios.post('/api/video_comments/get_comment' + '?page=' + page, {
                video_id : this.video.id,
                per_page :  this.per_page,
            })
            .then(res => {
                //トータルコメント数を取得
                this.total_comments = res.data.total;

                //追加データをマージ
                this.new = res.data.data;
                let new_data = res.data.data;
                new_data.forEach((data) => {
                    this.video_comments.push(data);
                })

                this.last_page = res.data.last_page;
                this.end_page = page;
                if($state) $state.loaded();

                //返信表示の切り替え属性を付与
                this.video_comments.forEach((object, index) => {
                    this.$set(this.video_comments[index], 're_video_comment_toggle', false);
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
        commentToggle(video_comment){
            video_comment.re_video_comment_toggle = !video_comment.re_video_comment_toggle;
        },
        deleteComment(id){
            const result = confirm('コメントを削除します。よろしいですか？');
            if(result){
                axios.delete('/api/video_comments/' + id)
                .then(()=>{
                    this.video_comments = [];
                    this.getComment(null, this.start_page, false);
                });
            }
        },
        deleteReComment(id){
            const result = confirm('コメントを削除します。よろしいですか？');
            if(result){
                axios.delete('/api/re_video_comments/' + id)
                .then(() => {
                    this.video_comments = [];
                    this.getComment(null, this.start_page, false);
                })
            }
        },
        infiniteHandler($state){
            if(this.end_page >= this.total_page){
                $state.complete();
            }
            else{
                this.getComment($state, this.end_page + 1, true);
            }
        },
        moveRecommend(video){
            this.$router.push({name: 'video_management_watch', params: { id: video.id, status: video.status} });
        }

    },
    created(){
        this.getVideo();
    }
}
</script>

<style scoped>
.evaluation-back{
    border:#A9A9A9 1px solid;
    padding: 5px 10px 5px 10px;
    border-radius: 20px;
}

.video-about{
    white-space: pre-wrap;
    overflow: hidden;
}

.see-more{
    color: #3490dc;
    cursor: pointer;
}

.main {
	width: 100%;
    margin: 0px 10px 0px 10px;
}

@media (min-width: 1300px) {
.main {
    width: 60%
}
}

.recommends{
    width: 500px;
}

.recommend{
    cursor: pointer;
}

.evaluation-blank{
    height: 23px;
}

.card-title{
    height: 40px;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;
    margin-bottom: 5px;
}

.card-tag{
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
    overflow: hidden;
    margin-bottom: 0px;
}

</style>