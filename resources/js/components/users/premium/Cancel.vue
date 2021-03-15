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
                        <button @click="cancel()" class="btn btn-success mt-4">解約する</button>
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
        }
    },
    components: {
        Header
    },
    methods: {
        cancel(){
            if(this.user.status === 'premium'){
                axios.post('/api/subscription/cancel')
                .then(() => {
                    this.$router.push({name: 'changed_normal'});
                });
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