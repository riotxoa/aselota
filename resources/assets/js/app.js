
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.BootstrapVue = require('bootstrap-vue');

Vue.use(window.BootstrapVue);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('rrhh-component', require('./components/RRHHComponent.vue'));
Vue.component('listado-pelotaris', require('./components/rrhh_pelotaris/ListadoComponent.vue'));
Vue.component('ficha-pelotari', require('./components/rrhh_pelotaris/FichaComponent.vue'));

const ListPelotaris = { template: '<transition><listado-pelotaris></listado-pelotaris></transition>'};
const CreatePelotari = { template: '<transition><ficha-pelotari form-title="Nuevo Pelotari"></ficha-pelotari></transition>'};
const EditPelotari = { template: '<transition><ficha-pelotari form-title="Editar Pelotari"></ficha-pelotari></transition>'};

const routes = [
  {
    path: '/',
    component: ListPelotaris
  },
  {
    path: '/pelotaris',
    component: ListPelotaris
  },
  {
    path: '/pelotari/new',
    component: CreatePelotari
  },
  {
    path: '/pelotari/:id/edit',
    component: EditPelotari
  }
];

const router = new VueRouter({ mode: 'history', routes: routes});

const app = new Vue({
    el: '#app',
    router,
});
