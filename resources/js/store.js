import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        flashMessage: null,
        flashColor: null,
        flashTime: null,

        user: {},
        subscription_status: null,

        video: {},
        recommends: {},
    },
    getters: {
        flashMessage: state => state.flashMessage,
        flashColor: state => state.flashColor,
        flashTime: state => state.flashTime,

        user: state => state.user,
        subscription_status: state => state.subscription_status,
        video : state => state.video,
        recommends : state => state.recommends,

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
        },
        resetUser(state){
            state.user = null;
            state.subscription_status = null;
        },

        //ログイン時に取得した課金ステータスを保存
        setSubscriptionStatus(state, subscription_status){
            state.subscription_status = subscription_status;
        },

        setVideo(state, {video, recommends}){
            state.video = video,
            state.recommends = recommends
        },
    },
    actions: {
        setFlashMessage({commit}, {message, time = 3000, color = 'primary'}) {
            commit('setFlashMessage', {message, time, color});
        },

        setUser({commit}, user){
            commit('setUser', user);
        },
        resetUser({commit}){
            commit('resetUser');
        },

        setSubscriptionStatus({commit}, subscription_status){
            commit('setSubscriptionStatus', subscription_status);
        },

        setVideo({commit}, {video, recommends}){
            commit('setVideo', {video, recommends});
        }
    }
});