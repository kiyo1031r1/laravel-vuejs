<template>
    <div>
        <FlashMessage></FlashMessage>
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
                                <button v-if="!is_loading" @click="editCard" class="btn btn-success mt-4 btn-block" :disabled="is_loading">変更</button>
                                <button v-else class="btn btn-success mt-4 btn-block" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- アプリ確認用 -->
            <div class="border-top"></div>
            <div class="col-md-8 my-3 mx-auto">
                <div class="card">
                    <div class="card-header">アプリ確認用</div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-hover text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">カード番号</th>
                                    <th scope="col">ブランド</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>4242424242424242</td>
                                    <td>visa</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>5555555555554444</td>
                                    <td>Mastercard</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>3566002020360505</td>
                                    <td>JCB</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="m-0">※[名前]は任意の値を入力してください。</p>
                        <p class="m-0">※[有効期限]は現在よりも未来の年月日を指定してください。</p>
                        <p class="m-0">※[CVC]は任意の数字3桁を入力してください。</p>
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
                    this.$store.dispatch('setFlashMessage', {
                        message:'クレジットカード情報を更新しました'
                    });
                    setTimeout(() => {
                        this.$router.push({name: 'my_page'});
                    }, 3000);
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