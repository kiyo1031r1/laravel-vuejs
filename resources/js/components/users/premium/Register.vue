<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="col-md-8 mt-3 mx-auto">
                <div class="card mb-3">
                    <div class="card-header">プレミアム登録</div>
                    <div class="card-body text-center">
                        <h3>プレミアム会員は</h3>
                        <h3>全ての動画が見放題!!</h3>

                        <!-- カード登録 -->
                        <div class="card-form">
                            <!-- カード登録エラー -->
                            <div v-if="errors.payment">
                                <p class="text-danger">{{ errors.payment.message }}</p>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-lg-3 col-form-label">名前</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="name" v-model="card_details.name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="card-element" class="col-lg-3 col-form-label">カード番号</label>
                                <div class="col-lg-8">
                                    <div class="card-number" id="card-element">
                                    </div>
                                </div>
                            </div>

                            <button @click="register" class="btn btn-success mt-4">プレミアム登録する</button>
                        </div>

                        <p>※月額500円(税込)</p>
                        <p>※サンプルの為、ボタンを押しても実際に課金はされません</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3 mx-auto">
                <div class="card">
                    <div class="card-header">アプリ確認用</div>
                    <div class="card-body text-center">
                        <button @click="cancelNow" class="btn btn-primary">プレミアム即解除</button>
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
            stripe : Stripe(process.env.MIX_STRIPE_KEY),
            card : '',
            card_details: {
                name : '',
            },
            intent: '',
            errors : {},
        }
    },
    components: {
        Header
    },
    methods: {
        async register(){
            if(this.user.status === 'normal'){
                this.errors = {};
                const paymentMethod = await this.getPayment();

                if(!this.errors.payment && paymentMethod){
                    axios.post('/api/subscription/subscribe', {
                        payment_method : paymentMethod,
                    })
                    .then(() => {
                        this.$router.push({name: 'changed_premium'});
                    });
                }
            }
            else{
                alert('すでにプレミアム会員です');
            }
        },
        getIntent(){
            axios.get('/api/subscription')
            .then((res) => {
                this.intent = res.data;
            });
        },
        async getPayment(){
            const { setupIntent, error} = await this.stripe.confirmCardSetup(
                this.intent.client_secret, {
                    payment_method : {
                        card : this.card,
                        billing_details : this.card_details,
                    }
                }
            );
            if(error) {
                this.$set(this.errors, 'payment' , error);
            }
            else{
                return setupIntent.payment_method;
            }
        },
        cancelNow(){
            axios.post('/api/subscription/cancelNow')
            .then(() => {
                this.$router.push({name: 'video'});
            });
        }
    },
    created(){
        this.user = this.$store.getters.user;
        this.getIntent();
    },
    mounted(){
        const elements = this.stripe.elements();
        this.card = elements.create('card');
        this.card.mount('#card-element');
    }
}
</script>

<style scoped>
.card-form{
    margin: 20px;
    padding: 20px;
    border: 1px solid #ccc;
}

.card-number{
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

</style>