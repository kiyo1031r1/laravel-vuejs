import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        flashMessage: null,
    },
    getters: {
    },
    mutations: {
        setFlashMessage(state, {message, time = 3000}) {
            state.flashMessage = message;
            setTimeout(() => {
                state.message = null;
            }, time);
        }
    },
    actions: {
        setFlashMessage({commit}, {message, time = 3000}) {
            commit('setFlashMessage', message, time);
        }
    }
});