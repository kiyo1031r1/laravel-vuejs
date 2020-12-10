<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="row justify-content-center mt-4">
            <!-- ビデオ作成フォーム -->
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">ビデオ新規作成</div>
                    <div class="card-body">
                        <form @submit.prevent="">
                            <!-- タイトル -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-2" for="title">タイトル</label>
                                <div class="col-md-8">
                                    <input class="form-control" id="title">
                                </div>
                            </div>

                            <!-- カテゴリー追加 -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-2" for="category">カテゴリー</label>
                                <div class="col-md-6">
                                    <select  v-model="selectCategory" class="form-control" id="category">
                                        <option v-for="category in categories" :key="category.id" :value="category">{{category.name}}</option>
                                    </select>
                                </div>
                                <button @click="addCategory()" :disabled="isSelectedCategory" class="btn btn-primary ml-2">追加</button>
                            </div>

                            <!-- 選択カテゴリーからの削除 -->
                            <div class="col-md-8 offset-md-2 mb-2">
                                <button v-for="selectCategory in selectCategories" :key="selectCategory.id" 
                                    @click="removeCategory(selectCategory)" class="btn btn-success mr-2 my-2">
                                    {{selectCategory.name}}<v-icon class="ml-2" name="times"/>
                                </button>
                            </div>

                            <!-- 概要 -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-2" for="about">概要</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="about" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <!-- サムネイル -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">サムネイル</label>
                                <div class="col-md-3">
                                    <label class="thumbnail_label" for="thumbnail">ファイルを選択</label>
                                    <input @change="uploadThumbnail()" type="file" id="thumbnail" ref="thumbnail_preview">
                                </div>
                                <div class="col-md-5">
                                    <p>{{thumbnail_file_name}}</p>
                                    <div v-if="thumbnail_preview" style="position:relative">
                                        <img :src="thumbnail_preview" class="img-thumbnail" >
                                        <div @click="removeThumbnail()" style="position:absolute; top:0; right:0;">
                                            <v-icon class="ml-2" name="window-close" inverse/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 動画ファイル -->
                            <div class="form-group row mb-0">
                                <label class="col-form-label col-md-2" for="capture">動画</label>
                                <div class="col-md-3">
                                    <label class="thumbnail_label" for="video">ファイルを選択</label>
                                    <input @change="uploadVideo()" type="file" id="video" ref="video" multiple="multiple">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-2">
                                    <button class="btn btn-outline-secondary btn-block text-left py-0"
                                        style="position:relative"
                                        v-for="video in videos" :key="video.id">
                                        {{replaceFileName(video.name, 40)}}
                                        <v-icon class="ml-2" 
                                        style="position:absolute; top:3; right:5;" name="times"/>
                                    </button>
                                </div>
                            </div>

                            <!-- 作成ボタン -->
                            <div class="col-md-4 mx-auto mt-5">
                                <button class="btn btn-primary btn-block" type="submit">作成</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- カテゴリー作成フォーム -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">カテゴリー管理</div>
                    <div class="card-body">
                        <!-- カテゴリー新規作成 -->
                        <Validation-provider name="カテゴリー" v-slot="{ errors }"
                            :rules="{
                                required: true,
                                max: 255,
                                excluded: categoryNames
                            }"> 
                            <div class="form-group">
                                <label class="col-form-label" for="create_category">新規作成</label>
                                <div class="form-row">
                                    <div class="col-md-8">
                                        <input v-model="inputCategory.name" :class="errors.length ? 'form-control is-invalid' : 'form-control'" id="create_category">
                                        <div class="invalid-feedback">{{ errors[0] }}</div>
                                    </div>

                                    <div class="col-md-4">
                                        <button @click="createCategory()" class="btn btn-primary ml-2" :disabled="isFlashMessage || !inputCategory.name || errors.length > 0 ">作成</button>
                                    </div>
                                </div>
                            </div>
                        </Validation-provider>

                        <!-- カテゴリー削除 -->
                        <div class="form-group">
                            <label class="col-form-label" for="delete_category">削除</label>
                            <div class="form-row">
                                <div class="col-md-8">
                                    <select  v-model="deleteSelectCategory" class="form-control" id="delete_category">
                                        <option v-for="category in categories" :key="category.id" :value="category">{{category.name}}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button @click="deleteCategory()" class="btn btn-primary ml-2" :disabled="isFlashMessage || !deleteSelectCategory">削除</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <FlashMessage></FlashMessage>
            </div>
        </div>
    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'

export default {
    data(){
        return{
            //カテゴリー
            categories:[],
            selectCategory: null,
            selectCategories: [],
            inputCategory: {
                name: null
            },
            deleteSelectCategory: null,

            //サムネイル
            thumbnail_file: null,
            thumbnail_file_name: '選択されていません',
            thumbnail_preview: null,
            thumbnail_file_name_length: 20,

            //動画
            videos:[]
        }
    },
    components:{
        AdminHeader
    },
    computed: {
        //カテゴリー
        isFlashMessage(){
            return this.$store.getters.flashMessage;
        },
        isSelectedCategory(){
            return this.selectCategories.includes(this.selectCategory);
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
            axios.get('/api/video_category')
            .then((res) => {
                this.categories = res.data;
            });
        },
        addCategory(){
            this.selectCategories.push(this.selectCategory);
        },
        removeCategory(selectCategory){
            this.selectCategories = this.selectCategories.filter((removeCategory) => {
                return removeCategory !== selectCategory;
            });
        },
        createCategory(){
            axios.post('/api/video_category', this.inputCategory)
            .then(() => {
                this.$store.dispatch('setFlashMessage', {
                    message:'カテゴリーを作成しました'
                });
                this.inputCategory.name = null;
                this.getCategory();
            });
        },
        deleteCategory(){
            axios.delete('/api/video_category/' + this.deleteSelectCategory.id)
            .then(() => {
                this.$store.dispatch('setFlashMessage', {
                    message:'カテゴリーを削除しました',
                    color: 'danger'
                });
                this.removeCategory(this.deleteSelectCategory);
                this.deleteSelectCategory = null;
                this.getCategory();
            });
        },

        uploadThumbnail(){
            this.thumbnail_file = this.$refs.thumbnail_preview.files[0];
            this.thumbnail_file_name = this.replaceFileName(this.thumbnail_file.name, this.thumbnail_file_name_length);
            this.thumbnail_preview = URL.createObjectURL(this.thumbnail_file);
        },
        replaceFileName(file_name, length){
            //文字数制限
            const name_max_length = length;
            const name = file_name.split('.');
            const name_bytes = encodeURIComponent(name[0]).replace(/%../g, 'x').length;

            //※「…」の分を差し引いておく
            if(name_bytes > name_max_length - 2){
                let name_bytes = 0;
                let str_bytes = 0;
                for(let i = 0; i < name[0].length; i++){
                    //半角カナ
                    if(name[0][i].match(/[ｦ-ﾟ]/)){
                        str_bytes = 1;
                    }
                    else{
                        str_bytes = encodeURIComponent(name[0][i]).replace(/%../g, 'x').length;
                        //全角
                        if(str_bytes === 3) {
                            str_bytes = 2;
                        }
                    }
                    
                    if(name_bytes + str_bytes <= name_max_length){
                        name_bytes += str_bytes;
                    }
                    else{
                        return name[0].substring(0, i) + '…' + name[1];
                    }
                }
            }
            else{
                return file_name;
            }
        },
        removeThumbnail(){
            this.thumbnail_file = null;
            this.thumbnail_file_name = null;
            this.thumbnail_preview = null;
            this.$refs.thumbnail_preview.value = null;
        },
        uploadVideo(){
            for(let i = 0; i < this.$refs.video.files.length; i++){
                let video_file = this.$refs.video.files[i];
                this.videos.push(video_file);
            }
        }
    },
    created(){
        this.getCategory();
    }

}
</script>

<style scoped>
.thumbnail_label{
    border: solid 1px black;
    border-radius: 2px;
    background-color: #EFEFEF;
    padding: 2px 5px 2px 5px;
    width: 100%;
    text-align: center;
    cursor: pointer;
}

.thumbnail_label:hover{
    background-color: #e7e7e7;
}

#thumbnail, #video{
    display: none;
}

</style>