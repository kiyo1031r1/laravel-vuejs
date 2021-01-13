<template>
    <div>
        <Header></Header>
        <div class="container">
            <div class="col-md-6 mt-3 mx-auto">
                <div class="card">
                    <div class="card-header">プレミアム登録</div>
                    <div class="card-body text-center">
                        <h3>プレミアム会員は</h3>
                        <h3>全ての動画が見放題!!</h3>
                        <button @click="register()" class="btn btn-success mt-4">プレミアム登録する</button>
                        <p>※月額500円(税込)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '../UsersHeaderComoponent'

export default {
    components: {
        Header
    },
    methods: {
        register(){
            if(this.$store.getters.user.status === 'normal'){
                axios.post('/api/users/register_premium/' + this.$store.getters.user.id)
                .then(() => {
                    this.$router.push({name: 'changed_premium'});
                });
            }
            else{
                alert('すでにプレミアム会員です');
            }
        }
    }
}
</script>