<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
            <!-- 表示件数 -->
            <div class="form-inline justify-content-end px-3 my-3">
                <label class="col-form-label p-2" for="per_page">表示件数</label>
                <select @change="changeFirstPage()" v-model="sort.per_page" class="form-control" id="per_page">
                    <option value="10">10件</option>
                    <option value="20">20件</option>
                    <option value="50">50件</option>
                    <option value="100">100件</option>
                </select>
            </div>

            <!-- ビデオ新規作成 -->
            <div class="row justify-content-center mb-3">
                <div class="col-md-6">
                    <router-link :to="{ name: 'video_management_create'}">
                        <button class="btn btn-primary btn-block">ビデオ新規作成</button>
                    </router-link>
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

            <!-- ビデオ一覧 -->
            <div class="row justify-content-center">
                <div v-for="video in videos" :key="video.id" class="col-md-8 mb-4">
                    <div class="card">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img class="img-thumbnail" :src="video.thumbnail">
                            </div>
                            <div class="col-md-6 border-right">
                                <div class="card-body">
                                    <h5 class="card-title mb-1">{{replaceText(video.title, 65)}}</h5>
                                    <span v-for="category in video.video_category" :key="category.id"
                                    class="badge badge-secondary mr-1" style="font-size:100%">{{category.name}}
                                    </span>
                                    <p class="card-text mt-2">{{replaceText(video.about, 130)}}</p>
                                </div>
                            </div>
                            <div class="col-md-2 my-auto px-2">
                                <router-link :to="{name: 'video_management_edit', params: { id: video.id}}">
                                    <button class="btn btn-warning btn-block">編集</button>
                                </router-link>
                                <button @click="deleteVideo(video.id)" class="btn btn-danger btn-block mt-2">削除</button>
                            </div>
                        </div>
                    </div>
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
                title: null,
            },

            //ソート
            sort:{
                id: null,
                created_at: null,
                per_page: 10,
            }
        }
    },
    components:{
        AdminHeader
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
        }
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
        replaceText(text, max_length){
            const text_bytes = encodeURIComponent(text).replace(/%../g, 'x').length;

            //※「…」の分を差し引いておく
            if(text_bytes > max_length - 2){
                let sum = 0;
                let str_bytes = 0;
                for(let i = 0; i < text.length; i++){
                    //半角カナ
                    if(text[i].match(/[ｦ-ﾟ]/)){
                        str_bytes = 1;
                    }
                    else{
                        str_bytes = encodeURIComponent(text[i]).replace(/%../g, 'x').length;
                        //全角
                        if(str_bytes === 3) {
                            str_bytes = 2;
                        }
                    }
                    
                    if(sum + str_bytes <= max_length){
                        sum += str_bytes;
                    }
                    else{
                        return text.substring(0, i) + '…';
                    }
                }
            }
            else{
                return text;
            }
        },
        deleteVideo(id){
            const result = confirm('ビデオを削除します。よろしいですか？');
            if(result){
                axios.delete('/api/videos/' + id)
                .then(() => {
                    this.getVideo();
                });
            }
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