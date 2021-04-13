import Vue from 'vue'
require('./bootstrap');

import App from './Views/app.vue'

const app = new Vue({
    el: '#app',
    components: { App }
});
