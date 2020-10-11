<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">管理者ログイン</div>
                        <div class="card-body">
                            <p class="text-center mb-0 my-4">ログインしました</p>
                            <p class="text-center mb-0 my-4">3秒後にホーム画面に遷移します</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminHeader from './AdminHeaderComponent'

export default {
    components:{
        AdminHeader
    },
    mounted(){
        let vm = this;
        setTimeout(()=> {
            vm.$router.push({name: 'admin'})}, 3000
        );
    }
}
</script>