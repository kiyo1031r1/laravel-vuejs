<template>
    <transition 
        name="fade"
        :css="false" 
        @before-enter="beforeEnter"
        @enter="enter"
        @after-enter="afterEnter"
        >
        <div v-show="message" :class="'alert alert-' + color" role="alert">
            {{message}}
        </div>
    </transition>
</template>

<script>
import Velocity from 'velocity-animate'

export default {
    computed: {
        message(){
            return this.$store.getters.flashMessage;
        },
        color(){
            return this.$store.getters.flashColor;
        },
        time(){
            return this.$store.getters.flashTime;
        }
    },
    methods: {
        beforeEnter(el) {
            el.style.opacity = 0;
        },
        enter(el){
            el.style.opacity = 1;
        },
        afterEnter(el) {
            Velocity(el,
                {opacity: 0},
                {
                    duration: this.time,
                    easing: 'easelnQuint'
                }
            );
        }
    }
}
</script>

<style scoped>
.alert{
    position: fixed;
    width: 100%;
    text-align: center;
    z-index: 30;
}
</style>


