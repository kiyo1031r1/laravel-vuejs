import App from './App.vue'
import router from './router'
import store from './store'
import moment from 'moment'
import 'vue-awesome/icons'
import Icon from 'vue-awesome/components/Icon'
import FlashMessage from './components/FlashMessage.vue'
import { localize, extend, ValidationProvider, ValidationObserver } from 'vee-validate'
import ja from 'vee-validate/dist/locale/ja.json'
import { required } from 'vee-validate/dist/rules'
extend('required', required);
localize('ja', ja);

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

Vue.component('v-icon', Icon);
Vue.component('FlashMessage', FlashMessage);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);


const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});