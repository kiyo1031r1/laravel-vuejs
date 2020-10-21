import App from './App.vue'
import router from './router'
import store from './store'
import moment from 'moment'

require('./bootstrap');

window.Vue = require('vue');

moment.locale('ja');
Vue.filter('moment', (value) => {
    return moment(value).format('YYYY-MM-DD (dd)');
});
Vue.filter('moment_details', (value) => {
    return moment(value).format('YYYY-MM-DD (dd) HH:MM:SS');
});

Vue.filter('role', (value) => {
    if(value == 1){
        return '一般ユーザー';
    }
    else if(value == 2){
        return '管理者';
    }
});

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});