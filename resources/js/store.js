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
        login({ commit }, user ){
            axios.get('sanctum/csrf-cookie')
            .then(res => {
                axios.post('/api/login',user)
                .then(res => {
                    //console.log(res);
                    commit('updateAuth', 'true');
                })
                .catch(error => {
                    console.log(error);
                });
            })
        },
        logout({ commit }) {
            axios.post('/api/logout')
            .then(res => {
                console.log(res);
                commit('updateAuth', 'false');
            })
            .catch((error) => {
                console.log(error);
            });
        }
    }
});