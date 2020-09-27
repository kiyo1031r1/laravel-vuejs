import App from './App.vue'
import router from './router'
import HeaderComponent from './components/HeaderComponent'
import { ajaxTransport } from 'jquery';

require('./bootstrap');

window.Vue = require('vue');

Vue.component('header-component', HeaderComponent);

const app = new Vue({
    el: '#app',
    router,
    render: h => h(App),
});