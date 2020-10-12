import App from './App.vue'
import router from './router'
import store from './store'

require('./bootstrap');

window.Vue = require('vue');

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
});