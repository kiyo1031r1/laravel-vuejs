<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container-fluid px-0">
            <!-- サイドバー　画面サイズが小さい場合は折り畳む -->
            <div v-if="sidebar_active && width < change_sidebar_width" @click.self="sidebar_active = !sidebar_active" class="sidebar-active-mask">
                <div class="sidebar-active">
                    <!-- ビデオ検索 -->
                    <div class="sidebar-body px-4">
                        <form @submit.prevent="changeFirstPage()">
                            <!-- ユーザー名 -->
                            <div class="form-group">
                                <label class="col-form-label" for="name">ユーザー名</label>
                                <input v-model="search.name" class="form-control" type="text" id="name">
                            </div>

                            <!-- メールアドレス -->
                            <div class="form-group">
                                <label class="col-form-label" for="email">メールアドレス</label>
                                <input  v-model="search.email" class="form-control" type="text" id="email">
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

                            <!-- ステータス -->
                            <div class="form-group">
                                <label class="col-form-label">ステータス</label>
                                <select v-model="search.status" class="form-control">
                                    <option selected></option>
                                    <option value="normal">normal</option>
                                    <option value="premium">premium</option>
                                </select>
                            </div>

                            <!-- 次回更新日 -->
                            <div class="form-group">
                                <label class="col-form-label">次回更新日(※現在不可)</label>
                                <Datepicker
                                    v-model="search.next_update_start"
                                    :language="datepicker.language"
                                    :format="datepicker.format"
                                    :input-class="datepicker.input_class"
                                    :bootstrap-styling="true"
                                    :clear-button="true"
                                    :placeholder="'〜から(未指定可)'"
                                    :disabled-dates="{from: search.next_update_end}">
                                </Datepicker>
                                <Datepicker
                                    v-model="search.next_update_end"
                                    :language="datepicker.language"
                                    :format="datepicker.format"
                                    :input-class="datepicker.input_class"
                                    :bootstrap-styling="true"
                                    :clear-button="true"
                                    :placeholder="'〜まで(未指定可)'"
                                    :disabled-dates="{to: search.next_update_start}">
                                </Datepicker>
                            </div>

                            <!-- 権限 -->
                            <div class="form-group">
                                <label class="col-form-label">権限</label>
                                <select v-model="search.role" class="form-control">
                                    <option selected></option>
                                    <option value="1">一般ユーザー</option>
                                    <option value="2">管理者</option>
                                </select>
                            </div>
                            
                            <!-- 検索ボタン -->
                            <div class="col-md-8 mx-auto my-4">
                                <button class="btn btn-primary btn-block" type="submit">検索</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 表示件数 -->
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <!-- 画面のサイズによる表示崩れを防ぐ -->
                    <template v-if="width > change_menu_width">
                        <div class="form-inline my-2">
                            <!-- 画面サイズが小さい場合にサイドバーの表示を切り替えるボタンを表示 -->
                            <div class="col-auto mr-auto">
                            <button v-if="width < change_sidebar_width" @click="sidebar_active = !sidebar_active" class="btn btn-secondary search-user-btn">ユーザー検索</button>
                            </div>

                            <div class="per-page form-inline col-auto">
                                <label class="col-form-label p-2" for="per_page">表示件数</label>
                                <select @change="changeFirstPage()" v-model="sort.per_page" class="form-control" id="per_page">
                                    <option value="10">10件</option>
                                    <option value="20">20件</option>
                                    <option value="50">50件</option>
                                    <option value="100">100件</option>
                                </select>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="form-row m-2">
                            <div class="col-5">
                                <button @click="sidebar_active = !sidebar_active" class="btn btn-secondary">ユーザー検索</button>
                            </div>
                            <div class="col-3 text-right">
                                <label class="col-form-label" for="per_page">表示件数</label>
                            </div>
                            <div class="col-4">
                                <select @change="changeFirstPage()" v-model="sort.per_page" class="form-control" id="per_page">
                                    <option value="10">10件</option>
                                    <option value="20">20件</option>
                                    <option value="50">50件</option>
                                    <option value="100">100件</option>
                                </select>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- サイドバー　 通常は画面左部に表示-->
                <div v-if="width >= change_sidebar_width" class="sidebar">
                    <!-- ビデオ検索 -->
                    <p class="sidebar-head">ユーザー検索</p>
                    <div class="sidebar-body px-4 mb-3">
                        <form @submit.prevent="changeFirstPage()">
                            <!-- ユーザー名 -->
                            <div class="form-group">
                                <label class="col-form-label" for="name">ユーザー名</label>
                                <input v-model="search.name" class="form-control" type="text" id="name">
                            </div>

                            <!-- メールアドレス -->
                            <div class="form-group">
                                <label class="col-form-label" for="email">メールアドレス</label>
                                <input  v-model="search.email" class="form-control" type="text" id="email">
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

                            <!-- ステータス -->
                            <div class="form-group">
                                <label class="col-form-label">ステータス</label>
                                <select v-model="search.status" class="form-control">
                                    <option selected></option>
                                    <option value="normal">normal</option>
                                    <option value="premium">premium</option>
                                </select>
                            </div>

                            <!-- 次回更新日 -->
                            <div class="form-group">
                                <label class="col-form-label">次回更新日(※現在不可)</label>
                                <Datepicker
                                    v-model="search.next_update_start"
                                    :language="datepicker.language"
                                    :format="datepicker.format"
                                    :input-class="datepicker.input_class"
                                    :bootstrap-styling="true"
                                    :clear-button="true"
                                    :placeholder="'〜から(未指定可)'"
                                    :disabled-dates="{from: search.next_update_end}">
                                </Datepicker>
                                <Datepicker
                                    v-model="search.next_update_end"
                                    :language="datepicker.language"
                                    :format="datepicker.format"
                                    :input-class="datepicker.input_class"
                                    :bootstrap-styling="true"
                                    :clear-button="true"
                                    :placeholder="'〜まで(未指定可)'"
                                    :disabled-dates="{to: search.next_update_start}">
                                </Datepicker>
                            </div>

                            <!-- 権限 -->
                            <div class="form-group">
                                <label class="col-form-label">権限</label>
                                <select v-model="search.role" class="form-control">
                                    <option selected></option>
                                    <option value="1">一般ユーザー</option>
                                    <option value="2">管理者</option>
                                </select>
                            </div>
                            
                            <!-- 検索ボタン -->
                            <div class="col-md-8 mx-auto my-4">
                                <button class="btn btn-primary btn-block" type="submit">検索</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- メイン -->
                <div class="main px-3">
                    <!-- ユーザー存在時(読み込み時に見出しとページネーション非表示) -->
                    <div v-show="isUsers">
                        <!-- ユーザーリスト 通常はテーブル形式で表示-->
                        <template v-if="width > change_main_width">
                            <table class="table table-sm table-bordered table-hover text-center ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">
                                            <span class="mr-1">ID</span>
                                            <v-icon @click="sortList('id', 'asc');" name="caret-square-up"/>
                                            <v-icon @click="sortList('id', 'desc')" name="caret-square-down"/>
                                        </th>
                                        <th scope="col">ユーザー名</th>
                                        <th scope="col">email</th>
                                        <th scope="col">
                                            <span class="mr-1">登録日</span>
                                            <v-icon @click="sortList('created_at', 'asc');" name="caret-square-up"/>
                                            <v-icon @click="sortList('created_at', 'desc')" name="caret-square-down"/>
                                        </th>
                                        <th scope="col">
                                            <span class="mr-1">ステータス</span>
                                            <v-icon @click="sortList('status', 'asc');" name="caret-square-up"/>
                                            <v-icon @click="sortList('status', 'desc')" name="caret-square-down"/>
                                        </th>
                                        <th scope="col">
                                            <span class="mr-1">次回更新日</span>
                                        </th>
                                        <th scope="col">
                                            <span class="mr-1">権限</span>
                                            <v-icon @click="sortList('role', 'asc');" name="caret-square-up"/>
                                            <v-icon @click="sortList('role', 'desc')" name="caret-square-down"/>
                                        </th>
                                        <th scope="col">編集</th>
                                        <th scope="col">削除</th>
                                    </tr>
                                </thead>
                                <tbody v-for="user in users" :key="user.id">
                                    <tr>
                                        <th scope="row">{{user.id}}</th>
                                        <td class="name">{{user.name}}</td>
                                        <td class="email">{{user.email}}</td>
                                        <td>{{user.created_at | moment}}</td>
                                        <td>{{user.status}}</td>
                                        <td></td>
                                        <td>{{user.role_id | role}}</td>
                                        <td>
                                            <router-link :to="{name: 'user_management_edit', params: { id: user.id}}">
                                                <button class="btn btn-primary px-2 py-0">編集</button>
                                            </router-link>
                                        </td>
                                        <td><button @click="deleteUser(user.id)" class="btn btn-danger px-2 py-0">削除</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <!-- ユーザーリスト　画面が小さい場合は、カード形式で表示 -->
                        <template v-else>
                            <div class="row px-3">
                                <div v-for="user in users" :key="user.id" class="col-15">
                                    <div class="card">
                                        <div class="card-header text-center">id : {{user.id}}</div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">name : {{user.name}}</li>
                                            <li class="list-group-item">email : {{user.email}}</li>
                                            <li class="list-group-item">登録日 : {{user.created_at | moment}}</li>
                                            <li class="list-group-item">status : {{user.status}}</li>
                                            <li class="list-group-item">権限 : {{user.role_id | role}}</li>
                                            <li class="list-group-item text-center">
                                                <router-link :to="{name: 'user_management_edit', params: { id: user.id}}">
                                                    <button class="btn btn-primary px-2 py-0 mr-4">編集</button>
                                                </router-link>
                                                <button @click="deleteUser(user.id)" class="btn btn-danger px-2 py-0">削除</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- ページネーション -->
                        <nav>
                            <ul class="pagination justify-content-center pb-4">
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

                    <!-- ユーザー非存在時(検索結果が0件) -->
                    <div v-show="!isUsers" class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">ユーザー検索結果</div>
                                <div class="card-body">
                                    <p class="text-center text-danger mb-0 my-4">一致するユーザーがいませんでした</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminHeader from '@/components/admin/AdminHeader'
import Datepicker from 'vuejs-datepicker'
import {ja} from 'vuejs-datepicker/dist/locale'

export default {
    data(){
        return{
            users:[],
            isUsers: true,

            //ページネーション
            current_page: 1,
            last_page: null,
            page_length: '',
            focus_page_index: -1,
            leftMorePage: false,
            rightMorePage: false,
            
            search:{
                name: null,
                email: null,
                role: '',
                status: '',
                created_at_start: null,
                created_at_end: null,
                next_update_start: null,
                next_update_end: null
            },

            datepicker:{
                language: ja,
                format: 'yyyy-MM-dd (D)',
                input_class: 'bg-white mb-2',
            },

            sort:{
                id: null,
                created_at: null,
                status: null,
                role: null,
                per_page: '10',
            },

            width: window.innerWidth,
            change_sidebar_width: 1330,
            change_menu_width: 575,
            change_main_width: 1170,
            change_pagination_width: 767,
            sidebar_active: false,
        }
    },
    computed:{
        createPageColumn() {
            const column = [];
            let start;
            let last;

            //画面サイズに合わせてpage_lengthを変更
            this.width > this.change_pagination_width ? this.page_length = 5 : this.page_length = 3;

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
            if(this.focus_page_index >= 0) {
                this.$refs.focus_page[this.focus_page_index].focus();
            }

            return column;
        },
    },
    methods:{
        getUser(){
            axios.post('/api/users/search?page=' + this.current_page,{
                search: this.search, 
                sort: this.sort
            })
            .then(res => {
                const resData = res.data;

                this.users = resData.data;
                if(this.users.length > 0){
                    this.isUsers = true;
                }
                else{
                    this.isUsers = false;
                }

                this.last_page = resData.last_page;
            });
        },
        deleteUser(id){
            const result = confirm('ユーザーを削除します。よろしいですか？');
            if(result){
                axios.delete('/api/users/'+ id)
                .then(() => {
                    this.getUser();
                });
            }
        },
        changeFirstPage(){
            if(this.sidebar_active) this.sidebar_active = false;

            //最初のユーザーから表示する仕様
            this.current_page = 1;
            this.focus_page_index = 0;
            this.getUser();
        },
        changePage(page){
            this.current_page = page;
            this.getUser();
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
        sortList(column, order){
            this.sort.id = null;
            this.sort.created_at = null;
            this.sort.status = null;
            this.sort.role = null;
            if(column === 'id'){
                this.sort.id = order;
            }
            if(column === 'created_at'){
                this.sort.created_at = order;
            }
            if(column === 'status'){
                this.sort.status = order;
            }
            if(column === 'role'){
                this.sort.role = order;
            }
            this.getUser();
        },
        handleResize(){
            this.width = window.innerWidth;
        }
    },
    components:{
        AdminHeader,
        Datepicker
    },
    created(){
        this.getUser();
    },
    mounted(){
        window.addEventListener('resize', this.handleResize);
    },
    beforeDestroy(){
        window.removeEventListener('resize', this.handleResize);
    }
}
</script>

<style scoped>
.sidebar{
    width: 280px;
    padding: 0 20px;
}

.sidebar-body{
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

.sidebar-active-mask{
    width: 100vw;
    height: 100vh;
    position: absolute;
    z-index: 20;
}

.sidebar-active{
    width: 280px;
    color: white;
    background-color: #343a40;
}

.vdp-datepicker >>> .vdp-datepicker__calendar{
    width: 110%;
    color: black;
}
.vdp-datepicker >>> .vdp-datepicker__clear-button{
    height: calc(1.6em + 0.75rem + 2px);
    margin-bottom: 0.5rem;
}

.main {
	width: 100%;
}

@media (min-width: 1170px) {
.main {
    width: 75%
}
}

th, td{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.list-group li{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 10px;
}

.name, .email{
    width: 200px;
    max-width: 200px;
}

@media (min-width: 1470px) {
.name, .email {
    width: 300px;
    max-width: 300px;
}
}

.col-15 {
	width: 100%;
    padding: 15px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (min-width: 768px) {
.col-15 {
	width: 25%;
}
}


</style>