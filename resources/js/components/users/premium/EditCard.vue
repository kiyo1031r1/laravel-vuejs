<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="col-md-8 mt-3 mx-auto">
                <div class="card mb-3">
                    <div class="card-header">クレジットカード情報の変更</div>
                    <div class="card-body text-center">
                        <div class="card-form">
                            <!-- カード登録エラー -->
                            <div v-if="errors.payment">
                                <p class="text-danger">{{ errors.payment.message }}</p>
                            </div>
                            <!-- サブスクリプション登録エラー -->
                            <div v-else-if="errors.subscription">
                                <p class="text-danger">{{ errors.subscription[0] }}</p>
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

                            <div class="col-lg-4 mx-auto">
                                <button v-if="!is_loading" @click="editCard" class="btn btn-primary mt-4 btn-block" :disabled="is_loading">変更</button>
                                <button v-else class="btn btn-primary mt-4 btn-block" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            </div>
                        </div>
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

            //課金情報
            stripe : Stripe(process.env.MIX_STRIPE_KEY),
            card : '',
            card_details: {
                name : '',
            },
            intent: '',
            is_loading: false,
            errors : {},
        }
    },
    components: {
        Header
    },
    methods: {
        async editCard(){
            this.is_loading = true;
            this.errors = {};
            const paymentMethod = await this.getPayment();

            if(!this.errors.payment && paymentMethod){
                axios.post('/api/subscription/edit_card', {
                    payment_method : paymentMethod,
                })
                .then(() => {
                    this.$router.push({name: 'my_page'});
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.is_loading = false;
                });
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
                this.is_loading = false;
            }
            else{
                return setupIntent.payment_method;
            }
        },
    },
    created(){
        this.user = this.$store.getters.user;
        this.getIntent();
    },
    mounted(){
        const elements = this.stripe.elements();
        this.card = elements.create('card', {hidePostalCode: true});
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