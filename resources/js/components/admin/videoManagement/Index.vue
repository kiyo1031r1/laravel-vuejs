<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container-fluid">
            <!-- ナビバー -->
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="row justify-content-end my-3">
                            <div class="form-inline">
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
                                <select @change="changeFirstPage()" v-model="sort.select" class="form-control">
                                    <option value="created_at_desc">新しい順</option>
                                    <option value="created_at_asc">古い順</option>
                                </select>

                                <!-- ビデオ新規作成 -->
                                <router-link :to="{ name: 'video_management_create'}">
                                    <button class="btn btn-primary px-4 ml-4">ビデオ新規作成</button>
                                </router-link>
                            </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- サイドバー -->
                <div class="col-md-2">
                    <!-- ビデオ検索 -->
                    <p class="sidebar-head">ビデオ検索</p>
                    <div class="sidebar p-4 mb-3">
                        <form @submit.prevent="changeFirstPage()">
                            <!-- ビデオ名 -->
                            <div class="form-group">
                                <label class="col-form-label" for="name">ビデオ名</label>
                                <input v-model="search.title" class="form-control" type="text" id="name">
                            </div>

                            <!-- カテゴリー -->
                            <div class="form-group">
                                <label class="col-form-label" for="category">カテゴリー名</label>
                                <div class="form-row mb-1">
                                    <div class="col-md-8">
                                        <select  v-model="select_category" class="form-control" id="category">
                                            <option v-for="category in categories" :key="category.id" :value="category">{{category.name}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button @click="addCategory()" :disabled="isSelectedCategory" class="btn btn-primary btn-block">追加</button>
                                    </div>
                                </div>

                                <!-- 選択カテゴリーからの削除 -->
                                <div class="col-md-8 p-0" v-for="select_category in search.categories" :key="select_category.id">
                                    <button @click="removeCategory(select_category)" 
                                    class="btn btn-success btn-block text-left py-0 mt-1" style="position:relative">
                                    {{select_category.name}}
                                        <v-icon style="position:absolute; top:3px; right:6px" name="times" inverse/>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- 登録日 -->
                            <div class="form-group">
                                <label class="col-form-label">登録日</label>
                                <Datepicker
                                    v-model="search.created_at_start"
                                    :language="datepicker.language"
                                    :format="datepicker.format"
                                    :input-class="datepicker.input_class"
                                    :bootstrap-styling="true"
                                    :clear-button="true"
                                    :placeholder="'〜から(未指定可)'"
                                    :disabled-dates="{from: search.created_at_end}">
                                </Datepicker>
                                <Datepicker
                                    v-model="search.created_at_end"
                                    :language="datepicker.language"
                                    :format="datepicker.format"
                                    :input-class="datepicker.input_class"
                                    :bootstrap-styling="true"
                                    :clear-button="true"
                                    :placeholder="'〜まで(未指定可)'"
                                    :disabled-dates="{to: search.created_at_start}">
                                </Datepicker>
                            </div>
                            
                            <div class="col-md-8 mx-auto">
                                <button class="btn btn-primary btn-block" type="submit">検索</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- メイン -->
                <div class="col-md-7">
                    <!-- ビデオ一覧 -->
                    <div class="row justify-content-center">
                        <div v-for="video in videos" :key="video.id" class="mb-4">
                            <div class="card">
                                <div class="row no-gutters">
                                    <div class="col-md-5">
                                        <img class="img-thumbnail" :src="video.thumbnail" style="position:relative">
                                        <span v-if="video.status == 'premium'" class="badge badge-warning" 
                                        style="position: absolute; top:8px; right:8px; font-size:100%">{{video.status}}
                                        </span>
                                        <span class="badge badge-dark" 
                                        style="position: absolute; bottom:8px; right:8px; font-size:100%">{{video.video_time}}
                                        </span>
                                    </div>
                                    <div class="col-md-6 border-right">
                                        <div class="card-body p-3">
                                            <h5 class="card-title mb-1">{{video.title}}</h5>
                                            <p class="card-tag">
                                                <span v-for="category in video.video_category" :key="category.id"
                                                class="badge badge-secondary mr-1" style="font-size:100%">{{category.name}}
                                                </span>
                                            </p>
                                            <p class="card-text">{{video.about}}</p>
                                            <p class="text-right mb-0">{{video.created_at | moment}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-1 my-auto px-2">
                                        <router-link :to="{name: 'video_management_watch', params: { id: video.id}}">
                                            <button class="btn btn-success btn-block mb-2">視聴</button>
                                        </router-link>
                                        <router-link :to="{name: 'video_management_edit', params: { id: video.id}}">
                                            <button class="btn btn-warning btn-block mb-2">編集</button>
                                        </router-link>
                                        <button @click="deleteVideo(video.id)" class="btn btn-danger btn-block mb-2">削除</button>
                                    </div>
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
        </div>
    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'
import Datepicker from 'vuejs-datepicker'
import {ja} from 'vuejs-datepicker/dist/locale'

export default {
    data(){
        return{
            //ビデオ表示
            videos: [],
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

            //カテゴリー
            categories: [],
            select_category: '',

            //登録日
            datepicker:{
                language: ja,
                format: 'yyyy-MM-dd (D)',
                input_class: 'bg-white mb-2',
            },

            //ソート
            sort:{
                select: 'created_at_desc',
                per_page: 10,
            }
        }
    },
    components:{
        AdminHeader,
        Datepicker
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
        isSelectedCategory(){
            return this.search.categories.includes(this.select_category);
        },
    },
    methods:{
        getVideo(){
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
        deleteVideo(id){
            const result = confirm('ビデオを削除します。よろしいですか？');
            if(result){
                axios.delete('/api/videos/' + id)
                .then(() => {
                    this.getVideo();
                    this.getCategory();
                });
            }
        },
        getCategory(){
            axios.get('/api/video_categories')
            .then((res) => {
                this.categories = res.data;
            });
        },
        addCategory(){
            this.search.categories.push(this.select_category);
        },
        removeCategory(select_category){
            this.search.categories = this.search.categories.filter((removeCategory) => {
                return removeCategory !== select_category;
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
        this.getCategory();
    }
}
</script>

<style scoped>
.sidebar{
    border: 2px solid #dee2e6;
}

.sidebar-head{
    font-weight: bold;
    color: #f8f9fa;
    background-color: #343a40;
    text-align: center;
    padding: 4px 0px;
    margin-bottom: 0px;
}

.vdp-datepicker >>> .vdp-datepicker__calendar{
    width: 110%;
}
.vdp-datepicker >>> .vdp-datepicker__clear-button{
    height: calc(1.6em + 0.75rem + 2px);
    margin-bottom: 0.5rem;
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

.card-text{
    height: 70px;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    overflow: hidden;
    white-space: pre-wrap;
}

</style>