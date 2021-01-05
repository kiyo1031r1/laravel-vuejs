<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="row justify-content-center mt-3">

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