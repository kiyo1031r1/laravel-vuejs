import App from './App.vue'
import router from './router'
import store from './store'
import 'vue-awesome/icons'
import Icon from 'vue-awesome/components/Icon'
import FlashMessage from './components/FlashMessage.vue'

//vee-validation
import { localize, extend, ValidationProvider, ValidationObserver } from 'vee-validate'
import ja from 'vee-validate/dist/locale/ja.json'
import { required, max, excluded} from 'vee-validate/dist/rules'
extend('required', required);
extend('max', max);
extend('excluded', {
    ...excluded,
    message: "入力された{_field_}は既に存在します"
});
localize('ja', ja);

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
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});