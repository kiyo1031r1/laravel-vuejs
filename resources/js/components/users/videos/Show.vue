<template>
    <div>
        <Header></Header>
        <div class="container">
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
                per_page: 10,
            },
        }
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
        }
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