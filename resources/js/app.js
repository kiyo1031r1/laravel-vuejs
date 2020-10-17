import App from './App.vue'
import router from './router'
import store from './store'
import moment from 'moment'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

require('./bootstrap');

window.Vue = require('vue');

Vue.prototype.moment = moment;

Vue.filter('role', (value) => {
    if(value == 1){
        return '一般ユーザー';
    }
    else if(value == 2){
        return '管理者';
    }
});

Vue.use(Vuetify);

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
    vuetify: new Vuetify(),
});