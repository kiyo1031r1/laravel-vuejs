<template>
    <div>
        <div class="col-md-6 mx-auto my-4">
            <div class="card">
                <div class="card-header">ビデオ新規作成</div>
                <div class="card-body">
                    <form @submit.prevent="">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="title">タイトル</label>
                            <div class="col-md-8">
                                <input class="form-control" id="title">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="category">カテゴリー</label>
                            <div class="col-md-6">
                                <select  v-model="selectCategory" class="form-control" id="category">
                                    <option v-for="category in categories" :key="category.id" :value="category">{{category.name}}</option>
                                </select>
                            </div>
                            <button @click="addCategory()" class="btn btn-primary ml-2">追加</button>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="create_category">カテゴリー作成</label>
                            <div class="col-md-6">
                                <input class="form-control" id="create_category">
                            </div>
                            <button class="btn btn-primary ml-2">作成</button>
                        </div>

                        <div class="col-md-8 offset-md-2 mb-2">
                            <button v-for="selectCategory in selectCategories" :key="selectCategory.id" 
                                @click="removeCategory(selectCategory)" class="btn btn-success mr-2 my-2">
                                {{selectCategory.name}}<v-icon class="ml-2" name="times"/>
                            </button>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="about">概要</label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="about" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="thumbnail">サムネイル</label>
                            <input class="form-control-file col-md-8" type="file" id="thumbnail">
                            <img src="" class="img-thumbnail">
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="capture">動画ファイル</label>
                            <input class="form-control-file col-md-8" type="file">
                        </div>

                        <div class="col-md-4 mx-auto mt-5">
                            <button class="btn btn-primary btn-block" type="submit">作成</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            categories:[],
            selectCategory: null,
            selectCategories: []
        }
    },
    methods:{
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
        }
    },
    created(){
        this.getCategory();
    }

}
</script>