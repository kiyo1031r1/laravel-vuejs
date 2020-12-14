<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
            <!-- ビデオ新規作成 -->
            <div class="row justify-content-center my-4">
                <div class="col-md-6">
                    <router-link :to="{ name: 'video_management_create'}">
                        <button class="btn btn-primary btn-block">ビデオ新規作成</button>
                    </router-link>
                </div>
            </div>

            <!-- ビデオ一覧 -->
            <div class="row justify-content-center">
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img class="bd-placeholder-img">
                            </div>
                            <div class="col-md-6">
                                <div class="card-body">
                                    <h5 class="card-title">タイトル</h5>
                                    <p class="card-text">概要</p>
                                </div>
                            </div>
                            <div class="col-md-2 my-auto px-2">
                                <button class="btn btn-warning btn-block">編集</button>
                                <button class="btn btn-danger btn-block">削除</button>
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
        }
    },
    created(){
        this.getVideo();
    }
}
</script>