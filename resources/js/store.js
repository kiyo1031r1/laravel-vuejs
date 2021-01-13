import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        flashMessage: null,
        flashColor: null,
        flashTime: null,

        user: {}
    },
    getters: {
        flashMessage: state => state.flashMessage,
        flashColor: state => state.flashColor,
        flashTime: state => state.flashTime,

        user: state => state.user,
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
        },

        //認証チェック時に取得したユーザーデータを保存
        setUser(state, user){
            state.user = user;
        }
    },
    actions: {
        setFlashMessage({commit}, {message, time = 3000, color = 'primary'}) {
            commit('setFlashMessage', {message, time, color});
        },
        setUser({commit}, user){
            commit('setUser', user);
        }
    }
});