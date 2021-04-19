import Vue from 'vue'
require('./bootstrap');

import Home from './Views/home.vue'
import Login from './Views/login.vue'


const app = new Vue({
    el: '#app',
    components: { Home, Login }
});
