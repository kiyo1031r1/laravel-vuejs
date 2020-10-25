import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        flashMessage: null,
    },
    getters: {
        flashMessage: state => state.flashMessage
    },
    mutations: {
        setFlashMessage(state, {message, time}) {
            state.flashMessage = message;
            setTimeout(() => {
                state.flashMessage = null
            }, time);
        }
    },
    actions: {
        setFlashMessage({commit}, {message, time = 3000}) {
            commit('setFlashMessage', {message, time});
        }
    }
});