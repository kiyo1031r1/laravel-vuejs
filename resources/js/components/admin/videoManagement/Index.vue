<template>
    <div>
        <AdminHeader></AdminHeader>
        <router-link :to="{ name: 'video_management_create'}">
            <button class="btn btn-primary">ビデオ新規作成</button>
        </router-link>
    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'

export default {
    components:{
        AdminHeader
    }
}
</script>