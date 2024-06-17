
require('./bootstrap');
import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';

Vue.use(Vuetify);

window.Vue = require('vue').default;



Vue.component('modalcreat-component', require('./components/ModalCreatComponent.vue').default);

Vue.component('modalshow-component', require('./components/ModalShowComponent.vue').default);

Vue.component('filter-component', require('./components/FilterComponent.vue').default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
