<template>
    <div v-show="message" class="alert alert-primary" role="alert">
        {{message}}
    </div>
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