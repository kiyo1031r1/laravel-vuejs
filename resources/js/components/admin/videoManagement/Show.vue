<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
        </div>
    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'

export default {
    props: ['id']
    ,
    components:{
        AdminHeader
    }
}
</script>