<template>
    <div>
        <AdminHeader></AdminHeader>
    </div>
</template>

<script>
import AdminHeader from './AdminHeaderComponent'

export default {
    components:{
        AdminHeader
    },
    created(){
        this.$router.push({name:'video_management'});
    }
}
</script>