import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        auth: null,
        admin_auth: null
    },
    getters: {
        auth: state => state.auth,
        admin_auth: state => state.admin_auth
    },
    mutations: {
        updateAuth(state, auth){
            state.auth = auth;
        },
        updateAdminAuth(state, admin_auth){
            state.admin_auth = admin_auth;
        },
    },
    actions: {
        updateAuth({ commit}, auth){
            commit('updateAuth', auth);
        },
        updateAdminAuth({ commit}, admin_auth){
            commit('updateAuth', admin_auth);
        }
    }
});