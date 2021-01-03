import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        flashMessage: null,
        flashColor: null,
        flashTime: null
    },
    getters: {
        flashMessage: state => state.flashMessage,
        flashColor: state => state.flashColor,
        flashTime: state => state.flashTime
    },
    mutations: {
        setFlashMessage(state, {message, time, color}) {
            state.flashTime = time;
            state.flashColor = color;
            state.flashMessage = message;
            setTimeout(() => {
                state.flashMessage = null;
                state.flashColor = null;
                state.flashTime = null;
            }, time);
        }
    },
    actions: {
        setFlashMessage({commit}, {message, time = 3000, color = 'primary'}) {
            commit('setFlashMessage', {message, time, color});
        }
    }
});