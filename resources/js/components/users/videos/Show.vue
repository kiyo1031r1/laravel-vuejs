<template>
    <div>
        <Header></Header>
        <div class="container">
            <!-- ビデオサムネイル -->
            <div class="row mt-3">
                <div v-for="video in videos" :key="video.id" class="col-md-3 px-2 mb-3">
                    <div class="card">
                        <div class="card-img-top">
                            <img class="img-fluid" :src="video.thumbnail" style="position:relative">
                        </div>
                        <div class="card-body p-2">
                            <p class="card-title">{{video.title}}</p>
                            <p class="card-tag">
                                <span v-for="category in video.video_category" :key="category.id"
                                class="badge badge-secondary mr-1" style="font-size:100%">{{category.name}}
                                </span>
                            </p>
                            <p class="text-right mb-0">{{video.created_at | moment}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ページネーション -->
            <nav>
                <ul class="pagination justify-content-center">
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
import Header from '../UsersHeaderComoponent'

export default {
    data(){
        return{
            //ビデオ
            videos:[],
            is_videos: true,

            //ページネーション
            current_page: 1,
            last_page: null,
            page_length: 9,
            focus_page_index: 0,
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
                select: 'created_at_desc',
                per_page: 12,
            },
        }
    },
    computed:{
        createPageColumn() {
            const column = [];
            let start;
            let last;
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
            if(this.focus_page_index > 0) {
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
            //urlからカテゴリー情報を取得
            const category_id = this.$route.path.split('/').slice(-1)[0];
            this.search.categories.push({id: category_id})

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
    },
    created(){
        this.getVideo();
    }
}
</script>

<style scoped>
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
</style>