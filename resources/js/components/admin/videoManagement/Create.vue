<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="row justify-content-center mt-4">
            <div class="col-md-5">
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
                                <button @click="addCategory()" :disabled="isSelectedCategory" class="btn btn-primary ml-2">追加</button>
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
            
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">カテゴリー管理</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="col-form-label" for="create_category">新規作成</label>
                            <div class="form-row">
                                <div class="col-md-8">
                                    <Validation-provider name="カテゴリー名" v-slot="{ errors }"
                                        :rules="{
                                            required: true,
                                            max: 255,
                                            excluded: categoryNames
                                        }">
                                        <input v-model="inputCategory.name" :class="errors.length ? 'form-control is-invalid' : 'form-control'" id="create_category">
                                        <div class="invalid-feedback">{{ errors[0] }}</div>
                                    </Validation-provider>
                                </div>
                                <div class="col-md-4">
                                    <button @click="createCategory()" class="btn btn-primary ml-2" :disabled="isMessage">作成</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label" for="delete_category">削除</label>
                            <div class="form-row">
                                <div class="col-md-8">
                                    <select  v-model="deleteSelectCategory" class="form-control" id="delete_category">
                                        <option v-for="category in categories" :key="category.id" :value="category">{{category.name}}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button @click="deleteCategory()" class="btn btn-primary ml-2" :disabled="isMessage || !deleteSelectCategory">削除</button>
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
            categories:[],
            selectCategory: null,
            selectCategories: [],
            inputCategory: {
                name: null
            },
            deleteSelectCategory: null,
            errors:{}
        }
    },
    components:{
        AdminHeader
    },
    computed: {
        isMessage(){
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
                this.deleteSelectCategory = null;
                this.getCategory();
            });
        }
    },
    created(){
        this.getCategory();
    }

}
</script>