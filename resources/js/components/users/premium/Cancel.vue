<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="col-md-6 mt-3 mx-auto">
                <div class="card">
                    <div class="card-header">プレミアム登録</div>
                    <div class="card-body text-center">
                        <h3 data-testid="name">{{user.name}}さんは</h3>
                        <h3>現在プレミアム会員です。</h3>
                        <p>次回更新日 : {{ next_update }}</p>

                        <!-- サブスクリプション登録エラー -->
                        <div v-if="errors.subscription">
                            <p class="text-danger">{{ errors.subscription[0] }}</p>
                        </div>

                        <div class="col-lg-4 my-4 mx-auto">
                            <button v-if="!is_loading" @click="cancel" class="btn btn-success btn-block" :disabled="is_loading">解約する</button>
                            <button v-else class="btn btn-success btn-block" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                        </div>
                        <p>※解約後も次回更新日までプレミアム動画を視聴することができます。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '@/components/users/Header'
import axios from 'axios'

export default {
    data(){
        return{
            user: {},
            next_update: '',
            is_loading: false,
            errors: {},
        }
    },
    components: {
        Header
    },
    methods: {
        cancel(){
            if(this.user.status === 'premium'){
                const result = confirm('プレミアム登録を解約します。よろしいですか？');
                if(result){
                    this.is_loading = true;
                    axios.post('/api/subscription/cancel')
                    .then(() => {
                        this.$router.push({name: 'changed_normal'});
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                        this.is_loading = false;
                    });
                }
            }
            else{
                alert('すでに一般会員です');
            }
        },
        getNextUpdate(){
            axios.get('/api/subscription/get_next_update')
            .then((res) => {
                this.next_update = res.data;
            })
        }
    },
    created(){
        this.user = this.$store.getters.user;
        this.getNextUpdate();
    }
}
</script>