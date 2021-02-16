<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="col-md-6 mt-3 mx-auto">
                <div class="card">
                    <div class="card-header">プレミアム登録</div>
                    <div class="card-body text-center">
                        <h3>{{user.name}}さんは</h3>
                        <h3>現在プレミアム会員です。</h3>
                        <button @click="cancel()" class="btn btn-success mt-4">解約する</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '@/components/users/Header'

export default {
    data(){
        return{
            user: {}
        }
    },
    components: {
        Header
    },
    methods: {
        cancel(){
            if(this.user.status === 'premium'){
                axios.post('/api/users/cancel_premium/' + this.user.id)
                .then(() => {
                    this.$router.push({name: 'changed_normal'});
                });
            }
            else{
                alert('すでに一般会員です');
            }
        }
    },
    created(){
        this.user = this.$store.getters.user;
    }
}
</script>