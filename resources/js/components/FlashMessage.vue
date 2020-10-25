<template>
    <transition name="fade">
        <div v-show="message" class="alert alert-primary" role="alert">
            {{message}}
        </div>
    </transition>
</template>

<script>
export default {
    computed: {
        message(){
            return this.$store.getters.flashMessage;
        }
    }
}
</script>

<style scoped>
.fade-enter-to, .fade-leave{
    opacity: 0;
}
.fade-enter{
    opacity: 1;
}
.fade-enter-active{
    transition: opacity 3s cubic-bezier(1.0, 0.0, 1.0, 1.0); 
}
</style>