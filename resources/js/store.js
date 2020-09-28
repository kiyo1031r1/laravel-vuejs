import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        auth: null
    },
    mutations: {
        updateAuth(state, auth){
            state.auth = auth;
        }
    },
    actions: {
        login({ commit }, authData ){
            axios.get('sanctum/csrf-cookie')
            .then(res => {
                axios.post('/api/login',authData)
                .then(res => {
                    commit('updateAuth', true);
                    this.$router.push('/tasks/list');
                })
            })
        }
    }
});