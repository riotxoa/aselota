
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

import moment from 'moment'
Vue.filter('formatDate', function(value) {
  if (value) {
    return moment(String(value)).format('DD/MM/YYYY');
  }
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('home-component', require('./components/HomeComponent.vue'));

Vue.component('profile-component', require('./components/common_profile/ProfileComponent.vue'));

Vue.component('home-rrhh', require('./components/rrhh_pelotaris/HomeRRHHComponent.vue'));
Vue.component('listado-pelotaris', require('./components/rrhh_pelotaris/ListadoComponent.vue'));
Vue.component('ficha-pelotari', require('./components/rrhh_pelotaris/FichaComponent.vue'));

const HomeRRHH = { template: '<home-rrhh></home-rrhh>' };
const ListPelotaris = { template: '<listado-pelotaris></listado-pelotaris> '};
const CreatePelotari = { template: '<ficha-pelotari form-title="Nuevo Pelotari"></ficha-pelotari> '};
const EditPelotari = { template: '<ficha-pelotari form-title="Editar Pelotari"></ficha-pelotari> '};

const routes = [
  {
    path: '/rrhh', component: HomeRRHH,
    children: [
      {
        path: '', component: ListPelotaris
      },
      {
        path: 'pelotari/new', component: CreatePelotari
      },
      {
        path: 'pelotari/:id/edit', component: EditPelotari
      }
    ]
  },

];

const router = new VueRouter({ mode: 'history', routes: routes});

const app = new Vue({
    el: '#app',
    router,
});
