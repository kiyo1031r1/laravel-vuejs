import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        flashMessage: null,
        flashColor: null
    },
    getters: {
        flashMessage: state => state.flashMessage,
        flashColor: state => state.flashColor
    },
    mutations: {
        setFlashMessage(state, {message, time, color}) {
            state.flashColor = color;
            state.flashMessage = message;
            setTimeout(() => {
                state.flashMessage = null
            }, time);
        }
    },
    actions: {
        setFlashMessage({commit}, {message, time = 3000, color = 'primary'}) {
            commit('setFlashMessage', {message, time, color});
        }
    }
});