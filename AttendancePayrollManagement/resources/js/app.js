require('./bootstrap');

window.Vue = require('vue');

import VueDndZone from 'vue-dnd-zone'
import 'vue-dnd-zone/vue-dnd-zone.css'
import VueApexCharts from 'vue-apexcharts'
import vuetify from './vuetify';
import VueRouter from 'vue-router';
import routes from './routes.js';
import Vue from 'vue'
import Vuelidate from 'vuelidate'
import store from './store'
import VideoBg from 'vue-videobg'
import moment from 'moment'
import VueHtml2pdf from 'vue-html2pdf'
import movable from "v-movable";
import Moveable from 'vue-moveable';

Vue.use(VueDndZone);
Vue.prototype.moment = moment
Vue.use(movable);
Vue.component('Moveable', Moveable)
Vue.component('apexchart', VueApexCharts)
Vue.component('video-bg', VideoBg)
Vue.component('attendance-component', require('./components/pages/attendance/Attendance_employee.vue').default);
Vue.component('login-component', require('./components/pages/Login.vue').default);
Vue.component('register-component', require('./components/pages/Register.vue').default);
Vue.component('main-component', require('./components/Main.vue').default);
Vue.component('layout-component', require('./components/user/Layout.vue').default);
Vue.use(VueRouter)
Vue.use(Vuelidate)
Vue.use(VueHtml2pdf)
Vue.use(VueApexCharts)



const app = new Vue({
    el: '#app',
    vuetify,
    router: new VueRouter(routes),
    store
});