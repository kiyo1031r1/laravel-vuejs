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
                                <span v-if="about.see_more" class="see-more" @click="aboutToggle()">{{about.toggle_word}}</span>
                            </div>
                            <!-- コメント -->
                            <div class="border-top py-3">
                                <h5 class="comment-title font-weight-bold pb-3 mb-0">Comment : {{comments.length}}件</h5>
                                <!-- コメント投稿フォーム -->
                                <span>{{user.name}} としてコメントする</span>
                                <div class="input-group mb-4">
                                    <textarea :class="errors.comment ? 'form-control is-invalid' : 'form-control'" rows="2" v-model="my_video_comment"></textarea>
                                    <div class="input-group-append">
                                        <button @click="createComment()" class="btn btn-success" style="border-radius:3px">コメント</button>
                                    </div>
                                    <div v-if="errors.comment" class="invalid-feedback">{{ errors.comment[0]}}</div>
                                </div>

                                <!-- コメント一覧 -->
                                <div v-for="comment in comments" :key="comment.id" class="border-top py-3">
                                    <div class="mb-2">
                                        <span>{{comment.user.name}}</span>
                                        <span class="text-secondary ml-2">{{comment.created_at | moment_ago}}</span>
                                    </div>
                                    <p class="mb-0" style="white-space: pre-wrap">{{comment.comment}}</p>
                                    <div class="mt-2">
                                        <template>
                                            <!-- 返信ボタン -->
                                            <button v-if="!comment.re_comment_form" 
                                            @click="reCommentFormToggle(comment)" class="btn btn-outline-secondary btn-sm mr-3"
                                            :disabled="is_re_video_comment_form || is_edit_video_comment_form" >返信
                                            </button>
                                            <button v-else
                                            @click="reCommentFormToggle(comment)" class="btn btn-outline-secondary btn-sm mr-3">返信をキャンセル
                                            </button>
                                        </template>
                                    
                                        <template v-if="isCommentUser(comment)">
                                            <!-- 編集ボタン -->
                                            <button v-if="!comment.edit_comment_form"
                                            @click="editCommentFormToggle(comment)" class="btn btn-outline-primary btn-sm mr-3"
                                            :disabled="is_edit_video_comment_form || is_re_video_comment_form">編集
                                            </button>
                                            <!-- 編集キャンセルボタン -->
                                            <button v-else
                                            @click="editCommentFormToggle(comment)" class="btn btn-outline-primary btn-sm mr-3">編集をキャンセル
                                            </button>
                                            <!-- 削除ボタン -->
                                            <button 
                                            @click="deleteComment(comment.id)" class="btn btn-outline-danger btn-sm mr-3"
                                            :disabled="is_edit_video_comment_form || is_re_video_comment_form">削除
                                            </button>
                                        </template>
                                    </div>

                                    <!-- コメント返信フォーム -->
                                    <template v-if="comment.re_comment_form">
                                        <div class="input-group mt-2 mb-4">
                                            <textarea :class="errors.re_comment ? 'form-control is-invalid' : 'form-control'" rows="2" v-model="my_re_video_comment"></textarea>
                                            <div class="input-group-append">
                                                <button @click="createReComment(comment)" class="btn btn-success" style="border-radius:3px">コメント</button>
                                            </div>
                                            <div v-if="errors.re_comment" class="invalid-feedback">{{ errors.re_comment[0]}}</div>
                                        </div>
                                    </template>

                                    <!-- コメント編集フォーム -->
                                    <template v-if="comment.edit_comment_form">
                                        <div class="input-group mt-2 mb-4">
                                            <textarea :class="errors.edit_comment ? 'form-control is-invalid' : 'form-control'" rows="2" v-model="my_edit_video_comment"></textarea>
                                            <div class="input-group-append">
                                                <button @click="editComment(comment)" class="btn btn-success" style="border-radius:3px">コメント</button>
                                            </div>
                                            <div v-if="errors.edit_comment" class="invalid-feedback">{{ errors.edit_comment[0]}}</div>
                                        </div>
                                    </template>

                                    <!-- 返信コメント -->
                                    <div v-if="comment.re_video_comments.length > 0" class="px-3">
                                        <!-- 表示切り替え -->
                                        <div @click="commentToggle(comment)" class="btn btn-link" data-toggle="collapse" :href="'#comment'+ comment.id" role="button" 
                                        aria-expanded="false" :aria-controls="'comment' + comment.id">
                                            <a v-if="!comment.re_comment_toggle">▼このコメントへの返信を表示</a>
                                            <a v-else>▼このコメントへの返信を非表示</a>
                                        </div>

                                        <!-- 返信コメント一覧 -->
                                        <div class="px-3">
                                            <div v-for="re_video_comment in comment.re_video_comments" 
                                            :key="re_video_comment.id" class="collapse border-top" :id="'comment' + comment.id">
                                                <div class="mt-3 mb-2">
                                                    <span>{{re_video_comment.user.name}}</span>
                                                    <span class="text-secondary ml-2">{{re_video_comment.created_at | moment_ago}}</span>
                                                </div>
                                                <p style="white-space: pre-wrap">{{re_video_comment.re_comment}}</p>
                                                
                                                <template v-if="isCommentUser(re_video_comment)">
                                                    <!-- 編集ボタン -->
                                                    <button v-if="!re_video_comment.edit_re_comment_form"
                                                    @click="editReCommentFormToggle(re_video_comment)" class="btn btn-outline-primary btn-sm mr-3"
                                                    :disabled="is_edit_re_video_comment_form || is_re_video_comment_form">編集
                                                    </button>
                                                    <!-- 編集キャンセルボタン -->
                                                    <button v-else
                                                    @click="editReCommentFormToggle(re_video_comment)" class="btn btn-outline-primary btn-sm mr-3">編集をキャンセル
                                                    </button>
                                                    <!-- 削除ボタン -->
                                                    <button 
                                                    @click="deleteReComment(re_video_comment.id)" class="btn btn-outline-danger btn-sm mr-3"
                                                    :disabled="is_edit_re_video_comment_form || is_re_video_comment_form">削除
                                                    </button>
                                                </template>

                                                <!-- 返信コメント編集フォーム -->
                                                <template v-if="re_video_comment.edit_re_comment_form">
                                                    <div class="input-group mt-2 mb-4">
                                                        <textarea :class="errors.edit_re_comment ? 'form-control is-invalid' : 'form-control'" rows="2" v-model="my_edit_re_video_comment"></textarea>
                                                        <div class="input-group-append">
                                                            <button @click="editReComment(re_video_comment)" class="btn btn-success" style="border-radius:3px">コメント</button>
                                                        </div>
                                                        <div v-if="errors.edit_re_comment" class="invalid-feedback">{{ errors.edit_re_comment[0]}}</div>
                                                    </div>
                                                </template>
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
                    <p class="h4 text-center my-3">あなたへのオススメ</p>
                    <div v-for="recommend in recommends" :key="recommend.id">
                        <div @click="moveRecommend(recommend)" class="recommend card mb-2">
                            <div class="row no-gutters">
                                <div class="col-md-5">
                                    <img class="img-fluid" style="position:relative" :src="recommend.thumbnail !== null ? recommend.thumbnail : '/images/default_thumbnail.jpg' ">
                                    <span v-if="recommend.status == 'premium'" class="badge badge-warning" 
                                    style="position: absolute; top:4px; right:4px; font-size:100%">{{recommend.status}}
                                    </span>
                                    <span class="badge badge-dark" 
                                    style="position: absolute; bottom:4px; right:4px; font-size:100%">{{recommend.video_time}}
                                    </span>
                                </div>
                                <div class="col-md-7 p-2">
                                    <p class="card-title">{{recommend.title}}</p>
                                    <p class="card-tag">
                                        <span v-for="category in recommend.video_categories" :key="category.id"
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
import Header from '@/components/users/Header'
import InfiniteLoading from 'vue-infinite-loading'

export default {
    data(){
        return{
            user: '',
            subscription_status: '',
            is_direct: false,
            video: {},
            about:{
                see_more : false,
                toggle: false,
                toggle_word: 'もっと見る',
                height: '',
                content_height: ''
            },
            //コメント
            my_video_comment: '',
            my_re_video_comment: '',
            my_edit_video_comment: '',
            my_edit_re_video_comment: '',
            is_re_video_comment_form: false,
            is_edit_video_comment_form: false,
            is_edit_re_video_comment_form: false,
            comments: [],
            errors:{},
            
            //無限スクロール
            start_page: 1,
            end_page: 1,
            last_page: 0,
            per_page: 20,
            initialized: false,

            recommends: [],
        }
    },
    props: ['id', 'status']
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
    beforeRouteEnter(to,from,next){
        axios.post('/api/videos/exist', {
            id: to.params.id
        })
        .then(() => {
            next();
        })
        .catch(() => {
            next({name: 'video'});
        })
    },
    watch: {
        $route(to, from){
            this.my_video_comment = '';
            this.my_re_video_comment = '';
            this.my_edit_video_comment = '';
            this.my_edit_re_video_comment = '';
            this.is_re_video_comment_form = false;
            this.is_edit_video_comment_form = false;
            this.is_edit_re_video_comment_form = false;
            this.comments = [];
            this.getVideo();
        }
    },
    methods:{
        getVideo(){
            axios.post('/api/videos/watch/' + this.id)
            .then(res => {
                this.video = res.data.video;
                this.recommends = res.data.recommends;

                //直接遷移の場合のプレミアム判別
                if(this.is_direct){
                    if(!this.isPremium(this.video.status)){
                        this.$router.push({name: 'premium_register'});
                    }
                    else{
                        //初回コメント読み込み
                        this.getComment(null, this.start_page, false);
                    }
                }
                else{
                    //初回コメント読み込み
                    this.getComment(null, this.start_page, false);
                }
            });
        },
        isPremium(status){
            this.video.status = status;
            if(this.video.status === 'premium' && this.user.status === 'normal'){
                //課金キャンセル中は視聴可能
                if(this.subscription_status === 'cancel'){
                    return true;
                }
                else{
                    return false;
                }
            }
            else {
                return true;
            }
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
                //追加データをマージ
                this.new = res.data.data;
                let new_data = res.data.data;
                new_data.forEach((data) => {
                    this.comments.push(data);
                })

                this.last_page = res.data.last_page;
                this.end_page = page;
                if($state) $state.loaded();

                this.comments.forEach((object, index) => {
                    //返信表示の切り替え属性を付与
                    this.$set(this.comments[index], 're_comment_toggle', false);
                    //返信フォームの切り替え属性を付与
                    this.$set(this.comments[index], 're_comment_form', false);
                    //編集フォームの切り替え属性を付与
                    this.$set(this.comments[index], 'edit_comment_form', false);
                    //返信編集フォームの切り替え属性を付与
                    this.$set(this.comments[index], 'edit_re_comment_form', false);
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
        commentToggle(comment){
            comment.re_comment_toggle = !comment.re_comment_toggle;
        },
        createComment(){
            axios.post('/api/video_comments', {
                comment: this.my_video_comment,
                video_id: this.video.id,
                user_id: this.user.id
            })
            .then(() => {
                this.initializedComment();
            })
            .catch((error) => {
                this.errors = error.response.data.errors;
            });
        },
        reCommentFormToggle(comment){
            comment.re_comment_form = !comment.re_comment_form;

            //返信フォームを１つだけ表示
            this.is_re_video_comment_form = !this.is_re_video_comment_form;
        },
        createReComment(comment){
            axios.post('/api/re_video_comments', {
                re_comment: this.my_re_video_comment,
                video_comment_id: comment.id,
                user_id: this.user.id
            })
            .then(() => {
                this.initializedComment();
            })
            .catch((error) => {
                this.errors = error.response.data.errors;
            });
        },
        isCommentUser(comment){
            return comment.user_id === this.user.id;
        },
        editCommentFormToggle(comment){
            comment.edit_comment_form = !comment.edit_comment_form;
            this.my_edit_video_comment = comment.comment;

            //編集フォームを１つだけ表示
            this.is_edit_video_comment_form = !this.is_edit_video_comment_form;
        },
        editComment(comment){
            axios.put('/api/video_comments/' + comment.id, {
                edit_comment: this.my_edit_video_comment,
            })
            .then(() => {
                this.initializedComment();
            })
            .catch((error) => {
                this.errors = error.response.data.errors;
            });
        },
        editReCommentFormToggle(re_comment){
            re_comment.edit_re_comment_form = !re_comment.edit_re_comment_form;
            this.my_edit_re_video_comment = re_comment.re_comment;

            //返信編集フォームを１つだけ表示
            this.is_edit_re_video_comment_form = !this.is_edit_re_video_comment_form;
        },
        editReComment(re_comment){
            axios.put('/api/re_video_comments/' + re_comment.id, {
                edit_re_comment: this.my_edit_re_video_comment,
            })
            .then(() => {
                this.initializedComment();
            })
            .catch((error) => {
                this.errors = error.response.data.errors;
            });
        },
        deleteComment(id){
            const result = confirm('コメントを削除します。よろしいですか？');
            if(result){
                axios.delete('/api/video_comments/' + id)
                .then(() => {
                    this.initializedComment();
                })
            }
        },
        deleteReComment(id){
            const result = confirm('コメントを削除します。よろしいですか？');
            if(result){
                axios.delete('/api/re_video_comments/' + id)
                .then(() => {
                    this.initializedComment();
                })
            }
        },
        initializedComment(){
            this.my_video_comment = '';
            this.my_re_video_comment = '';
            this.my_edit_video_comment = '';
            this.my_edit_re_video_comment = '';
            this.is_re_video_comment_form = false;
            this.is_edit_video_comment_form = false;
            this.is_edit_re_video_comment_form = false;
            this.comments = [];
            this.getComment(null, this.start_page, false);
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
            this.$router.push({name: 'video_watch', params: { id: video.id, status: video.status} });
        },
        getStatus(){
            axios.get('/api/subscription/get_status')
            .then((res) => {
                this.subscription_status = res.data.status;

                //vuexに再度課金状況を登録
                this.$store.dispatch('setSubscriptionStatus', res.data.status);
            });
        },
    },
    created(){
        this.user = this.$store.getters.user;
        this.subscription_status = this.$store.getters.subscription_status;

        //動画一覧ページから遷移の場合
        if(this.status && this.subscription_status){
            if(!this.isPremium(this.status)){
                this.$router.push({name: 'premium_register'});
            }
            else{
                this.getVideo();
            }
        }
        //直接遷移の場合
        else{
            this.is_direct = true;
            this.getStatus();
            this.getVideo();
        }
    },
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

.recommend{
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