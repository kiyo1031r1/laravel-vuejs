import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        auth: null
    },
    getters: {
        auth: state => state.auth
    },
    mutations: {
        updateAuth(state, auth){
            state.auth = auth;
        }
    },
    actions: {
        login({ commit }) {
            commit('updateAuth', 'true');
        },
        logout({ commit }) {
            commit('updateAuth', 'false');
        }
    }
});