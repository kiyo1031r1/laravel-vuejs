import App from './App.vue'
import router from './router'
import store from './store'
import 'vue-awesome/icons'
import Icon from 'vue-awesome/components/Icon'
import FlashMessage from './components/FlashMessage.vue'

//moment
import moment from 'moment'
moment.locale('ja');

require('./bootstrap');
window.Vue = require('vue');

//filter
Vue.filter('moment', (value) => {
    return moment(value).format('YYYY-MM-DD (dd)');
});
Vue.filter('moment_details', (value) => {
    return moment(value).format('YYYY-MM-DD (dd) HH:MM:SS');
});
Vue.filter('moment_ago', (value) => {
    return moment(value).fromNow();
});
Vue.filter('role', (value) => {
    if(value == 1){
        return '一般ユーザー';
    }
    else if(value == 2){
        return '管理者';
    }
});

//component
Vue.component('v-icon', Icon);
Vue.component('FlashMessage', FlashMessage);

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});