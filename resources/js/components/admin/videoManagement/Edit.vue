<template>
    <div>
        <FlashMessage></FlashMessage>
        <AdminHeader></AdminHeader>
        <div class="container">
                <!-- カテゴリー管理 -->
                <div class="col-lg-9  mx-auto mt-4">
                    <div class="accordion" id="accordion">
                        <div class="card">
                            <div class="card-header bg-dark p-0" id="category_header">
                                <button class="btn btn-block text-white p-3" type="button" data-toggle="collapse" data-target="#category_body" aria-expanded="false" aria-controls="cateogory_body">
                                    ▼カテゴリー管理
                                </button>
                            </div>

                            <div class="collapse" id="category_body" aria-labelledby="category_header" data-parent="#accordion">
                                <!-- カテゴリー作成 -->
                                <div class="card-header">カテゴリー作成
                                </div>
                                <div class="card-body">
                                    <div class="col-md-8 mx-auto">
                                        <input v-model="input_category.name" :class="errors.name ? 'form-control is-invalid' : 'form-control'">
                                        <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
                                    </div>
                                    <div class="col-md-4 mx-auto">
                                        <button @click="createCategory()" class="btn btn-primary btn-block mt-4" :disabled="isFlashMessage || !input_category.name || errors.length > 0 ">作成</button>
                                    </div>
                                </div>

                                <!-- カテゴリー削除 -->
                                <div class="card-header border-top">カテゴリー削除
                                </div>
                                <div class="card-body">
                                    <div class="col-md-8 mx-auto mb-4">
                                        <select  v-model="delete_select_category" :class="errors.delete_category ? 'form-control is-invalid' : 'form-control'">
                                            <option v-for="category in categories" :key="category.id" :value="category">{{category.name}}</option>
                                        </select>
                                        <div v-if="errors.delete_category" class="invalid-feedback">{{ errors.delete_category[0] }}</div>
                                    </div>
                                    <div class="col-md-4 mx-auto">
                                        <button @click="deleteCategory()" class="btn btn-primary btn-block" :disabled="isFlashMessage || !delete_select_category">削除</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ビデオ作成フォーム -->
                <div class="col-lg-9  mx-auto my-4">
                    <div class="card">
                        <div class="card-header">ビデオ新規作成</div>
                        <div class="card-body">
                            <!-- タイトル -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-2" for="title">タイトル</label>
                                <div class="col-md-8">
                                    <input :class="errors.title ? 'form-control is-invalid' : 'form-control'" id="title" v-model="title">
                                    <div v-if="errors.title" class="invalid-feedback">{{ errors.title[0]}}</div>
                                </div>
                            </div>

                            <!-- カテゴリー追加 -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-2" for="category">カテゴリー</label>
                                <div class="col-md-6 mb-3">
                                    <select  v-model="select_category" class="form-control" id="category">
                                        <option v-for="category in categories" :key="category.id" :value="category">{{category.name}}</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button @click="addCategory()" :disabled="isSelectedCategory" class="btn btn-primary btn-block">追加</button>
                                </div>
                                <div v-if="errors.category" class="col-md-8 offset-md-2 upload_error">{{ errors.category[0]}}</div>
                            </div>

                            <!-- 選択カテゴリーからの削除 -->
                            <div class="col-md-8 offset-md-2 mb-2">
                                <button v-for="select_category in select_categories" :key="select_category.id" 
                                    @click="removeCategory(select_category)" class="btn btn-success mr-2 my-2">
                                    {{select_category.name}}<v-icon class="ml-2" name="times"/>
                                </button>
                            </div>

                            <!-- 概要 -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-2" for="about">概要</label>
                                <div class="col-md-8">
                                    <textarea :class="errors.about ? 'form-control is-invalid' : 'form-control'" id="about" rows="3" v-model="about"></textarea>
                                    <div v-if="errors.about" class="invalid-feedback">{{ errors.about[0]}}</div>
                                </div>
                            </div>

                            <!-- ステータス -->
                            <fieldset class="form-group">
                                <div class="row">
                                    <legend class="col-form-label col-md-2 py-0">ステータス</legend>
                                    <div class="col-md-8" style="padding:0 12px">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label" for="status_normal">ノーマル</label>
                                            <input class="form-check-input" type="radio" id="status_normal" value="normal" v-model="status">
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label" for="status_premium">プレミアム</label>
                                            <input class="form-check-input" type="radio" id="status_premium" value="premium" v-model="status">
                                        </div>
                                    </div>
                                </div>
                                <div v-if="errors.status" class="col-md-8 offset-md-2 status_error">{{ errors.status[0]}}</div>
                            </fieldset>
                            
                            <!-- サムネイル -->
                            <div class="form-group row mb-0">
                                <label class="col-form-label col-md-2">サムネイル</label>
                                <div class="col-md-3">
                                    <label class="file_upload_button" for="thumbnail">ファイルを選択</label>
                                    <input @change="uploadThumbnail()" type="file" id="thumbnail" ref="thumbnail_preview">
                                </div>
                                <div v-if="thumbnail_name" class="col-md-5">
                                    <img :src="thumbnail_preview" class="img-thumbnail" >
                                    <button class="btn btn-outline-secondary btn-block text-left py-0" style="position:relative"
                                        @click="removeThumbnail()">
                                        {{replaceFileName(thumbnail_name, upload_file_name_length)}}
                                        <v-icon class="ml-2" 
                                        style="position:absolute; top:3; right:5;" name="times"/>
                                    </button>
                                </div>
                                <div v-if="errors.thumbnail_name" class="col-md-8 offset-md-2 upload_error">{{ errors.thumbnail_name[0]}}</div>
                            </div>

                            <!-- 動画ファイル -->
                            <div class="form-group row mt-3 mb-0">
                                <label class="col-form-label col-md-2" for="capture">動画</label>
                                <div class="col-md-3 text-center">
                                    <label class="file_upload_button" for="video">ファイルを選択</label>
                                    <input @change="uploadVideo()" type="file" id="video" ref="video">
                                    <a :href="sample_video_url" download="sample_video">サンプル動画をDL</a>
                                </div>
                                <div v-if="video_name" class="col-md-5">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <video class="embed-responsive-item img-thumbnail" 
                                        controls :src="video_preview" ref="video_time"
                                        @loadedmetadata="getVideoTime()">
                                        </video>
                                    </div>
                                    <button class="btn btn-outline-secondary btn-block text-left py-0" style="position:relative"
                                        @click="removeVideo()">
                                        {{replaceFileName(video_name, upload_file_name_length)}}
                                        <v-icon class="ml-2" 
                                        style="position:absolute; top:3; right:5;" name="times"/>
                                    </button>
                                </div>
                                <div v-if="errors.video_name" class="col-md-8 offset-md-2 upload_error">{{ errors.video_name[0]}}</div>
                                <div v-else-if="errors.video_time" class="col-md-8 offset-md-2 upload_error">{{ errors.video_time[0]}}</div>
                                <div v-else-if="errors.video" class="col-md-8 offset-md-2 upload_error">{{ errors.video[0]}}</div>
                            </div>


                            <!-- 作成ボタン -->
                            <div class="col-md-4 mx-auto mt-5">
                                <button class="btn btn-primary btn-block" type="submit"
                                @click="createVideo()">更新</button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</template>

<script>
import AdminHeader from '@/components/admin/AdminHeader'

export default {
    data(){
        return{
            //カテゴリー
            categories: [],
            select_category: null,
            select_categories: [],
            before_select_categories: [],
            input_category: {
                name: '',
            },
            delete_select_category: null,

            //サムネイル
            thumbnail: '',
            thumbnail_name: '',
            thumbnail_preview: null,
            allow_thumbnail_ext: ['jpg', 'jpeg', 'png'],

            //動画
            video: '',
            video_name: '',
            video_preview: null,
            allow_video_ext: ['mov', 'mp4'],
            video_time: '',
            sample_video_url: '',

            //その他
            title: '',
            about: '',
            status: '',
            upload_file_name_length: 25,
            errors: {}
        }
    },
    props:['id']
    ,
    components:{
        AdminHeader
    },
    computed: {
        //カテゴリー
        isFlashMessage(){
            return this.$store.getters.flashMessage;
        },
        isSelectedCategory(){
            return this.select_categories.includes(this.select_category);
        },
        categoryNames(){
            return this.categories.map((value) => {
                return value.name;
            });
        }
    },
    methods:{
        //カテゴリー
        getCategory(){
            axios.get('/api/video_categories')
            .then((res) => {
                this.categories = res.data;
                this.setCategory();
            });
        },
        setCategory(){
            //選択中カテゴリsの情報を一時的に保存して、初期化
            const select_categories_caches = this.select_categories;
            this.select_categories = [];

            //新たに取得したカテゴリsから選択中カテゴリsを作成
            select_categories_caches.forEach((select_categories_cache) => {
                this.categories.forEach((category) => {
                    if(select_categories_cache.id === category.id){
                        this.select_categories.push(category);
                    }
                })
            })

            //選択中カテゴリも同様の処理
            if(this.select_category !== null){
                const select_category_cache = this.select_category;
                this.select_category = null;
                this.categories.forEach((category) => {
                    if(select_category_cache.id === category.id){
                        this.select_category = category;
                    }
                })
            }
            
            //初回or選択中カテゴリ削除時は、1番上のカテゴリーを代入
            if(this.select_category === null) {
                this.select_category = this.categories[0];
            }
        },
        addCategory(){
            this.select_categories.push(this.select_category);
        },
        removeCategory(select_category){
            this.select_categories = this.select_categories.filter((removeCategory) => {
                return removeCategory !== select_category;
            });
        },
        createCategory(){
            if(Object.keys(this.errors).length) this.errors = {};

            axios.post('/api/video_categories', this.input_category)
            .then(() => {
                this.$store.dispatch('setFlashMessage', {
                    message:'カテゴリーを作成しました'
                });
                this.input_category.name = '';
                this.getCategory();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        deleteCategory(){
            if(Object.keys(this.errors).length) this.errors = {};

            axios.delete('/api/video_categories/' + this.delete_select_category.id)
            .then(() => {
                this.$store.dispatch('setFlashMessage', {
                    message:'カテゴリーを削除しました',
                    color: 'danger'
                });
                this.removeCategory(this.delete_select_category);
                this.delete_select_category = null;
                this.getCategory();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },

        //サムネイル
        uploadThumbnail(){
            this.thumbnail = this.$refs.thumbnail_preview.files[0];
            //拡張子をチェック
            if(!this.checkExt(this.thumbnail.name, this.allow_thumbnail_ext)){
                alert(this.allow_thumbnail_ext + 'から選択してください');
                this.thumbnail = '';
                this.$refs.thumbnail_preview.value = null;
            }
            else{
                this.thumbnail_name = this.thumbnail.name;
                this.thumbnail_preview = URL.createObjectURL(this.thumbnail);
            }
        },
        replaceFileName(file_name, length){
            //文字数制限
            const name_max_length = length;
            let pos = file_name.lastIndexOf('.');
            const name =  file_name.substring(0, pos + 1);
            const ext = file_name.slice(pos + 1);
            const name_bytes = encodeURIComponent(name).replace(/%../g, 'x').length;

            //※「…」の分を差し引いておく
            if(name_bytes > name_max_length - 2){
                let name_bytes = 0;
                let str_bytes = 0;
                for(let i = 0; i < name.length; i++){
                    //半角カナ
                    if(name[i].match(/[ｦ-ﾟ]/)){
                        str_bytes = 1;
                    }
                    else{
                        str_bytes = encodeURIComponent(name[i]).replace(/%../g, 'x').length;
                        //全角
                        if(str_bytes === 3) {
                            str_bytes = 2;
                        }
                    }
                    
                    if(name_bytes + str_bytes <= name_max_length){
                        name_bytes += str_bytes;
                    }
                    else{
                        return name.substring(0, i) + '…' + ext;
                    }
                }
            }
            else{
                return file_name;
            }
        },
        removeThumbnail(){
            this.thumbnail = '';
            this.thumbnail_name = '';
            this.thumbnail_preview = null;
            this.$refs.thumbnail_preview.value = null;
        },

        //ビデオ
        uploadVideo(){
            this.video = this.$refs.video.files[0];
            if(!this.checkExt(this.video.name, this.allow_video_ext)){
                alert(this.allow_video_ext + 'から選択してください');
                this.video = '';
                this.$refs.video.value = null;
            }
            else{
                this.video_name = this.video.name;
                this.video_preview = URL.createObjectURL(this.video);
            }
        },
        getVideoTime(){
            const video = this.$refs.video_time;
            this.video_time = Math.floor(video.duration);
        },
        removeVideo(){
            this.video = '';
            this.video_name = '';
            this.video_time = '';
            this.video_preview = null;
            this.$refs.video.value = null;
        },
        checkExt(file_name, allow_ext){
            let ext = this.getExt(file_name).toLowerCase();
            if(allow_ext.indexOf(ext) === -1) return false;
            return true;
        },
        getExt(file_name){
            let pos = file_name.lastIndexOf('.');
            if(pos === -1) return '';
            return file_name.slice(pos + 1);
        },
        createVideo(){
            if(this.select_categories.length > 3){
                alert('カテゴリーは最大3つまでしか登録できません');
                return;
            }
            
            let formData = new FormData();
            formData.append('title', this.title);
            formData.append('about', this.about);
            formData.append('status', this.status);
            formData.append('thumbnail', this.thumbnail);
            formData.append('thumbnail_name', this.thumbnail_name);
            formData.append('video', this.video);
            formData.append('video_name', this.video_name);
            formData.append('video_time', this.video_time);
            this.select_categories.forEach( category => {
                formData.append('category' + '[]', category.id);
            });

            //サムネイル未設定の場合
            if(this.thumbnail === '' && this.thumbnail_name === '' || this.thumbnail_name === null){
                const result = confirm('サムネイルが設定されていません。よろしいですか？');
                if(!result) return;
            }

            axios.post('/api/videos/' + this.id, formData, { 
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(() => {
                this.$router.push({name:'video_management'});
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        getVideo(){
            axios.get('/api/videos/'+ this.id)
            .then(res => {
                this.title = res.data.title;
                this.about = res.data.about;
                this.status = res.data.status;
                this.thumbnail_name = res.data.thumbnail_name;
                this.thumbnail_preview = res.data.thumbnail;
                this.video_name = res.data.video_name;
                this.video_preview = res.data.video;

                //保存されてるカテゴリsを、選択カテゴリsに追加;
                this.before_select_categories = res.data.video_categories;
                this.before_select_categories.forEach((before_select_category) => {
                    this.categories.forEach((category) => {
                        if(before_select_category.id === category.id){
                            this.select_categories.push(category);
                        }
                    })
                })
            });
        },
        sampleVideoDownload(){
            axios.post('/api/videos/download',
            {
                file_name: 'sample_video.mov'
            },
            {
                responseType: 'blob'
            })
            .then(res => {
                this.sample_video_url = 
                window.URL.createObjectURL(new Blob([res.data],{type: res.headers['content-type']}));
            })
        }
    },
    created(){
        this.getCategory();
        this.getVideo();
        this.sampleVideoDownload();
    }

}
</script>

<style scoped>
.file_upload_button{
    border: solid 1px black;
    border-radius: 2px;
    background-color: #EFEFEF;
    padding: 6px 12px;
    margin-bottom: 0px;
    width: 100%;
    text-align: center;
    cursor: pointer;
}

.file_upload_button:hover{
    background-color: #e7e7e7;
}

#thumbnail, #video{
    display: none;
}

.status_error{
    color: #e3342f;
    font-size: 80%;
    font-family: "Nunito", sans-serif;
    padding-left: 4px;
}

.upload_error{
    color: #e3342f;
    font-size: 80%;
    font-family: "Nunito", sans-serif;
    padding: 0px 15px;
    margin-top: 4px;
}

</style>