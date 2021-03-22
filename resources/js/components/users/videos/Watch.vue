<template>
    <div>
        <FlashMessage></FlashMessage>
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
                                <!-- 評価 -->
                                <div class="text-right">
                                    <div>
                                        <span v-if="evaluation >= 5" class="evaluation-back">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="evaluation >= 4.5" class="evaluation-back">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half" style="color:#FFD700"/>
                                        </span>
                                        <span v-else-if="evaluation >= 4" class="evaluation-back">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                        </span>
                                        <span v-else-if="evaluation >= 3.5" class="evaluation-back">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                        </span>
                                        <span v-else-if="evaluation >= 3" class="evaluation-back">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                        </span>
                                        <span v-else-if="evaluation >= 2.5" class="evaluation-back">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                        </span>
                                        <span v-else-if="evaluation >= 2" class="evaluation-back">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                        </span>
                                        <span v-else-if="evaluation >= 1.5" class="evaluation-back">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star-half" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                        </span>
                                        <span v-else-if="evaluation >= 1" class="evaluation-back">
                                            <v-icon name="star" style="color:#FFD700"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                            <v-icon name="star" style="color:#FFFFE0"/>
                                        </span>
                                        <span v-else class="evaluation-back">
                                            未評価
                                        </span>
                                    </div>

                                    <!-- 評価フォーム切り替え -->
                                    <div class="btn btn-link px-0" data-toggle="collapse" href="#evaluation" role="button" 
                                    aria-expanded="false" aria-controls="evaluation">
                                        <a>▼この動画を評価する</a>
                                    </div>

                                    <!-- 評価フォーム -->
                                    <div class="collapse form-inline" id="evaluation">
                                        <div class="col-auto ml-auto px-0">
                                            <select v-model="my_evaluation" :class="errors.evaluation ? 'form-control is-invalid' : 'form-control'">
                                                <option value="1">★</option>
                                                <option value="2">★★</option>
                                                <option value="3">★★★</option>
                                                <option value="4">★★★★</option>
                                                <option value="5">★★★★★</option>
                                            </select>
                                            <div v-if="errors.evaluation" class="invalid-feedback">{{errors.evaluation[0]}}</div>
                                            <div v-else-if="errors.user_id" class="evaluation-userId-error">{{errors.user_id[0]}}</div>
                                            <div>
                                                <button v-if="!is_evaluate" @click="evaluateVideo" class="btn btn-primary btn-sm my-2 ml-2">評価</button>
                                                <button v-else @click="editEvaluateVideo" class="btn btn-primary btn-sm my-2 ml-2">評価を変更</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
                                <h5 class="comment-title font-weight-bold pb-3 mb-0">Comment : {{total_comments}}件</h5>
                                <!-- コメント投稿フォーム -->
                                <span>{{user.name}} としてコメントする</span>
                                <div class="input-group mb-4">
                                    <textarea :class="errors.video_comment ? 'form-control is-invalid' : 'form-control'" rows="2" v-model="my_video_comment"></textarea>
                                    <div class="input-group-append">
                                        <button @click="createComment()" class="btn btn-success" style="border-radius:3px">コメント</button>
                                    </div>
                                    <div v-if="errors.video_comment" class="invalid-feedback">{{ errors.video_comment[0]}}</div>
                                </div>

                                <!-- コメント一覧 -->
                                <div v-for="video_comment in video_comments" :key="video_comment.id" class="border-top py-3">
                                    <div class="mb-2">
                                        <span>{{video_comment.user.name}}</span>
                                        <span class="text-secondary ml-2">{{video_comment.created_at | moment_ago}}</span>
                                    </div>
                                    <p class="mb-0" style="white-space: pre-wrap">{{video_comment.video_comment}}</p>
                                    <div class="mt-2">
                                        <template>
                                            <!-- 返信ボタン -->
                                            <button v-if="!video_comment.re_video_comment_form" 
                                            @click="reCommentFormToggle(video_comment)" class="btn btn-outline-secondary btn-sm mr-3"
                                            :disabled="is_re_video_comment_form || is_edit_video_comment_form" >返信
                                            </button>
                                            <button v-else
                                            @click="reCommentFormToggle(video_comment)" class="btn btn-outline-secondary btn-sm mr-3">返信をキャンセル
                                            </button>
                                        </template>
                                    
                                        <template v-if="isCommentUser(video_comment)">
                                            <!-- 編集ボタン -->
                                            <button v-if="!video_comment.edit_video_comment_form"
                                            @click="editCommentFormToggle(video_comment)" class="btn btn-outline-primary btn-sm mr-3"
                                            :disabled="is_edit_video_comment_form || is_re_video_comment_form">編集
                                            </button>
                                            <!-- 編集キャンセルボタン -->
                                            <button v-else
                                            @click="editCommentFormToggle(video_comment)" class="btn btn-outline-primary btn-sm mr-3">編集をキャンセル
                                            </button>
                                            <!-- 削除ボタン -->
                                            <button 
                                            @click="deleteComment(video_comment.id)" class="btn btn-outline-danger btn-sm mr-3"
                                            :disabled="is_edit_video_comment_form || is_re_video_comment_form">削除
                                            </button>
                                        </template>
                                    </div>

                                    <!-- コメント返信フォーム -->
                                    <template v-if="video_comment.re_video_comment_form">
                                        <div class="input-group mt-2 mb-4">
                                            <textarea :class="errors.re_video_comment ? 'form-control is-invalid' : 'form-control'" rows="2" v-model="my_re_video_comment"></textarea>
                                            <div class="input-group-append">
                                                <button @click="createReComment(video_comment)" class="btn btn-success" style="border-radius:3px">コメント</button>
                                            </div>
                                            <div v-if="errors.re_video_comment" class="invalid-feedback">{{ errors.re_video_comment[0]}}</div>
                                        </div>
                                    </template>

                                    <!-- コメント編集フォーム -->
                                    <template v-if="video_comment.edit_video_comment_form">
                                        <div class="input-group mt-2 mb-4">
                                            <textarea :class="errors.edit_video_comment ? 'form-control is-invalid' : 'form-control'" rows="2" v-model="my_edit_video_comment"></textarea>
                                            <div class="input-group-append">
                                                <button @click="editComment(video_comment)" class="btn btn-success" style="border-radius:3px">コメント</button>
                                            </div>
                                            <div v-if="errors.edit_video_comment" class="invalid-feedback">{{ errors.edit_video_comment[0]}}</div>
                                        </div>
                                    </template>

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
                                            <div v-for="(re_video_comment, index) in video_comment.re_video_comments" 
                                            :key="re_video_comment.id" class="collapse border-top" :id="'video_comment' + video_comment.id">
                                                <div class="mt-3 mb-2">
                                                    <span>{{re_video_comment.user.name}}</span>
                                                    <span class="text-secondary ml-2">{{re_video_comment.created_at | moment_ago}}</span>
                                                </div>
                                                <p style="white-space: pre-wrap">{{re_video_comment.re_video_comment}}</p>
                                                
                                                <div v-if="isCommentUser(re_video_comment)" :class="index === video_comment.re_video_comments.length - 1 ?  '' : 'pb-3'">
                                                    <!-- 編集ボタン -->
                                                    <button v-if="!re_video_comment.edit_re_video_comment_form"
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
                                                </div>

                                                <!-- 返信コメント編集フォーム -->
                                                <template v-if="re_video_comment.edit_re_video_comment_form">
                                                    <div class="input-group mt-2 mb-4">
                                                        <textarea :class="errors.edit_re_video_comment ? 'form-control is-invalid' : 'form-control'" rows="2" v-model="my_edit_re_video_comment"></textarea>
                                                        <div class="input-group-append">
                                                            <button @click="editReComment(re_video_comment)" class="btn btn-success" style="border-radius:3px">コメント</button>
                                                        </div>
                                                        <div v-if="errors.edit_re_video_comment" class="invalid-feedback">{{ errors.edit_re_video_comment[0]}}</div>
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
            video: {},
            evaluation: '',
            my_evaluation: '1',
            is_evaluate: false,
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
            video_comments: [],
            total_comments: '',
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
            this.my_video_comment = '';
            this.my_re_video_comment = '';
            this.my_edit_video_comment = '';
            this.my_edit_re_video_comment = '';
            this.is_re_video_comment_form = false;
            this.is_edit_video_comment_form = false;
            this.is_edit_re_video_comment_form = false;
            this.video_comments = [];
            this.getVideo();
        }
    },
    methods:{
        getVideo(){
            this.video = this.$store.getters.video;
            this.recommends = this.$store.getters.recommends;
            this.getEvaluation();
            this.isEvaluate();
            
            //初回コメント読み込み
            this.getComment(null, this.start_page, false);
        },
        getEvaluation(){
            axios.get('/api/video_evaluations/get_evaluation/' + this.video.id)
            .then(res => {
                this.evaluation = res.data;
            });
        },
        isEvaluate(){
            axios.get('/api/video_evaluations/is_evaluate/' + this.video.id)
            .then(res => {
                this.is_evaluate = res.data.is_evaluate;
                this.my_evaluation = res.data.evaluation;
            });
        },
        evaluateVideo(){
            axios.post('/api/video_evaluations/evaluate/' + this.video.id, {
                evaluation: this.my_evaluation,
            })
            .then(() => {
                this.is_evaluate = true;
                this.getEvaluation();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        editEvaluateVideo(){
            axios.put('/api/video_evaluations/' + this.video.id, {
                evaluation: this.my_evaluation,
            })
            .then(() => {
                this.is_evaluate = true;
                this.$store.dispatch('setFlashMessage', {
                    message:'評価の値を変更しました',
                });
                this.getEvaluation();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
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

                this.video_comments.forEach((object, index) => {
                    //返信表示の切り替え属性を付与
                    this.$set(this.video_comments[index], 're_video_comment_toggle', false);
                    //返信フォームの切り替え属性を付与
                    this.$set(this.video_comments[index], 're_video_comment_form', false);
                    //編集フォームの切り替え属性を付与
                    this.$set(this.video_comments[index], 'edit_video_comment_form', false);
                    //返信編集フォームの切り替え属性を付与
                    this.$set(this.video_comments[index], 'edit_re_video_comment_form', false);
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
        createComment(){
            axios.post('/api/video_comments', {
                video_comment: this.my_video_comment,
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
        reCommentFormToggle(video_comment){
            video_comment.re_video_comment_form = !video_comment.re_video_comment_form;

            //返信フォームを１つだけ表示
            this.is_re_video_comment_form = !this.is_re_video_comment_form;
        },
        createReComment(re_video_comment){
            axios.post('/api/re_video_comments', {
                re_video_comment: this.my_re_video_comment,
                video_comment_id: re_video_comment.id,
                user_id: this.user.id
            })
            .then(() => {
                this.initializedComment();
            })
            .catch((error) => {
                this.errors = error.response.data.errors;
            });
        },
        isCommentUser(video_comment){
            return video_comment.user_id === this.user.id;
        },
        editCommentFormToggle(video_comment){
            video_comment.edit_video_comment_form = !video_comment.edit_video_comment_form;
            this.my_edit_video_comment = video_comment.video_comment;

            //編集フォームを１つだけ表示
            this.is_edit_video_comment_form = !this.is_edit_video_comment_form;
        },
        editComment(video_comment){
            axios.put('/api/video_comments/' + video_comment.id, {
                edit_video_comment: this.my_edit_video_comment,
            })
            .then(() => {
                this.initializedComment();
            })
            .catch((error) => {
                this.errors = error.response.data.errors;
            });
        },
        editReCommentFormToggle(re_video_comment){
            re_video_comment.edit_re_video_comment_form = !re_video_comment.edit_re_video_comment_form;
            this.my_edit_re_video_comment = re_video_comment.re_video_comment;

            //返信編集フォームを１つだけ表示
            this.is_edit_re_video_comment_form = !this.is_edit_re_video_comment_form;
        },
        editReComment(re_video_comment){
            axios.put('/api/re_video_comments/' + re_video_comment.id, {
                edit_re_video_comment: this.my_edit_re_video_comment,
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
            this.video_comments = [];
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
            this.$router.push({name: 'video_watch', params: { id: video.id} });
        }
    },
    created(){
        this.user = this.$store.getters.user;
        this.getVideo();
    },
}
</script>

<style scoped>
.evaluation-back{
    background-color:#A9A9A9;
    padding: 5px 10px 5px 10px;
    border-radius: 20px;
}

.evaluation-userId-error{
    color: #e3342f;
    font-size: 80%;
    font-family: "Nunito", sans-serif;
    margin-top: 4px;
}

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