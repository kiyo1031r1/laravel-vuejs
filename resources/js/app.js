import App from './App.vue'
import router from './router'
import store from './store'
import HeaderComponent from './components/HeaderComponent'


require('./bootstrap');

window.Vue = require('vue');

Vue.component('header-component', HeaderComponent);

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
});