<template>
    <div>
        <Header></Header>
        <div class="container">
            <!-- ナビバー -->
            <div class="row justify-content-center my-3">
                <div class="col-md-12">
                    <div class="form-inline mx-2">
                        <!-- ビデオ検索 -->
                        <div class="input-group col-md-6 mr-auto p-0">
                            <input v-model="search.title" class="form-control" type="text" placeholder="検索">
                            <div class="input-group-prepend">
                                <div class="input-group-text p-0">
                                    <button @click="changeFirstPage()" class="btn btn-default" type="submit">
                                        <v-icon name="search"/>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 表示件数 -->
                        <label class="col-form-label p-2" for="per_page">表示件数</label>
                        <select @change="changeFirstPage()" v-model="sort.per_page" class="form-control" id="per_page">
                            <option value="10">10件</option>
                            <option value="20">20件</option>
                            <option value="50">50件</option>
                            <option value="100">100件</option>
                        </select>

                        <!-- 並び替え -->
                        <label class="col-form-label p-2" for="per_page">並び替え</label>
                        <select @change="changeFirstPage()" v-model="sort.sort" class="form-control">
                            <option value="created_at_desc">新しい順</option>
                            <option value="created_at_asc">古い順</option>
                            <option value="evaluation_desc">評価の多い順</option>
                            <option value="evaluation_asc">評価の少ない順</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ビデオサムネイル -->
            <div v-show="is_videos" class="row px-3 mt-5">
                <div v-for="video in videos" :key="video.id" class="col-md-15 p-0 mb-5">
                    <div @click="moveVideoWatch(video)" class="card mx-2">
                        <div class="card-img-top"  style="position:relative">
                            <img class="img-fluid" :src="video.thumbnail !== null ? video.thumbnail : '/images/default_thumbnail.jpg' ">
                            <span v-if="video.status == 'premium'" class="badge badge-warning" 
                            style="position: absolute; top:4px; right:4px; font-size:100%">{{video.status}}
                            </span>
                            <span class="badge badge-dark" 
                            style="position: absolute; bottom:4px; right:4px; font-size:100%">{{video.video_time}}
                            </span>
                        </div>
                        <div class="card-body p-2">
                            <p class="card-title">{{video.title}}</p>
                            <p class="card-tag">
                                <span v-for="category in video.video_categories" :key="category.id"
                                class="badge badge-secondary mr-1" style="font-size:100%">{{category.name}}
                                </span>
                            </p>
                                <div class="text-right">
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
                                    <span v-else>
                                        <div class="evaluation-blank"></div>
                                    </span>
                                </div>
                            <p class="text-right mb-0">{{video.created_at | moment}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ビデオ非存在時(検索結果が0件) -->
            <div v-show="!is_videos" class="col-md-8 mx-auto mb-5">
                <div class="card">
                    <div class="card-header">ビデオ検索結果</div>
                    <div class="card-body">
                        <p class="text-center text-danger mb-0 my-4">一致するビデオがありませんでした</p>
                    </div>
                </div>
            </div>

            <!-- ページネーション -->
            <nav>
                <ul class="pagination justify-content-center pb-4">
                    <li @click="changePage(1)" class="page-item mx-2"><a class="page-link" href="#">先頭</a></li>
                    <li @click="changePreviousPage()" class="page-item"><a class="page-link" href="#">前</a></li>
                    <li v-show="leftMorePage" class="mx-2">...</li>
                    <li v-for="(page, index) in createPageColumn" :key="page.index" @click="changePage(page)" :class="isCurrent(page, index) ? 'page-item active' : 'page-item inactive'">
                        <a  ref="focus_page" class="page-link" href="#">{{page}}</a>
                    </li>
                    <li v-show="rightMorePage" class="mx-2">...</li>
                    <li @click="changeNextPage()" class="page-item"><a class="page-link" href="#">次</a></li>
                    <li @click="changePage(last_page)" class="page-item mx-2"><a class="page-link" href="#">最終</a></li>
                </ul>
            </nav>

        </div>
    </div>
</template>

<script>
import Header from '@/components/users/Header'

export default {
    data(){
        return{
            //ビデオ
            videos:[],
            is_videos: true,

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

            //ページネーション
            current_page: 1,
            last_page: null,
            page_length: '',
            focus_page_index: -1,
            leftMorePage: false,
            rightMorePage: false,

            //検索
            search:{
                title: '',
                created_at_start: '',
                created_at_end: '',
                categories: [],
            },

            //ソート
            sort:{
                sort: 'created_at_desc',
                per_page: 10,
            },
        }
    },
    props: ['category']
    ,
    computed:{
        createPageColumn() {
            const column = [];
            let start;
            let last;

            //画面サイズに合わせてpage_lengthを変更
            window.innerWidth > 767 ? this.page_length = 5 : this.page_length = 3;

            //指定ページ数以上
            if(this.last_page > this.page_length){
                //ページ冒頭処理
                if(this.current_page <= Math.floor(this.page_length / 2) + 1){
                    start = 1;
                    last = this.page_length;
                    this.leftMorePage = false;
                    this.rightMorePage = true;
                }
                //ページ末尾処理
                else if(this.current_page >= this.last_page - Math.floor(this.page_length / 2)){
                    start = this.last_page - this.page_length + 1;
                    last = this.last_page;
                    this.leftMorePage = true;
                    this.rightMorePage = false;
                }
                //通常処理
                else{
                    start = this.current_page - Math.floor(this.page_length / 2);
                    last = this.current_page + Math.floor(this.page_length / 2);
                    this.leftMorePage = true;
                    this.rightMorePage = true;
                }
            }
            //指定ページ数未満
            else{
                start = 1;
                last = this.last_page;
                this.leftMorePage = false;
                this.rightMorePage = false;
            }
            
            for(let i = start; i <= last; i++){
                column.push(i);
            }

            //ページ番号へフォーカス
            if(this.focus_page_index >= 0) {
                this.$refs.focus_page[this.focus_page_index].focus();
            }

            return column;
        },
    },
    components:{
        Header,
    },
    methods:{
        getVideo(){
            //初回のみ登録
            if(this.search.categories.length === 0) {
                //カテゴリー名からidを取得
                const category_id = this.categories.findIndex(category => category === this.category) + 1;
                this.search.categories.push({id: category_id})
            }

            axios.post('/api/videos/search?page=' + this.current_page, {
                search: this.search,
                sort: this.sort,
            })
            .then(res => {
                this.videos = res.data.data;
                if(this.videos.length > 0) {
                    this.is_videos = true;
                }
                else{
                    this.is_videos = false;
                }

                this.last_page = res.data.last_page;
            });
        },
        //ページネーション
        changeFirstPage(){
            //最初のユーザーから表示する仕様
            this.current_page = 1;
            this.focus_page_index = 0;
            this.getVideo();
        },
        changePage(page){
            this.current_page = page;
            this.getVideo();
        },
        changePreviousPage(){
            if(this.current_page > 1) {
                this.changePage(this.current_page - 1);
            }
        },
        changeNextPage(){
            if(this.current_page < this.last_page) {
                this.changePage(this.current_page + 1);
            }
        },
        isCurrent(page, index){
            if(page === this.current_page){
                this.focus_page_index = index;
            }
            return page === this.current_page;
        },
        moveVideoWatch(video){
            this.$router.push({name: 'video_watch', params: { id: video.id} });
        }
    },
    created(){
        this.getVideo();
    }
}
</script>

<style scoped>
.card{
    cursor: pointer;
}

.card-title{
    height: 40px;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;
    margin-bottom: 8px;
}

.card-tag{
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
    overflow: hidden;
    margin-bottom: 6px;
}

.evaluation-blank{
    height: 23px;
}

.btn-default{
    padding: 5px 12px;
}

.col-15, .col-sm-15, .col-md-15, .col-lg-15 {
	position: relative;
	min-height: 1px;
	padding-right: 15px;
	padding-left: 15px;
	width: 100%;
}

@media (min-width: 768px) {
.col-sm-15 {
	width: 20%;
	flex: 0 0 20%;
}
}

@media (min-width: 992px) {
.col-md-15 {
	width: 20%;
	flex: 0 0 20%;
}
}

@media (min-width: 1200px) {
.col-lg-15 {
	width: 20%;
	flex: 0 0 20%;
}
}

</style>