/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./script');

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
Vue.component('calendar-component', require('./components/calendario/CalendarMainViewComponent.vue'));

Vue.component('home-admin', require('./components/HomeAdminComponent.vue'));
Vue.component('listado-modulos', require('./components/admin/ListadoModulosComponent.vue'));

const HomeAdmin = { template: '<home-admin></home-admin>' };
const ListModulos = { template: '<listado-modulos></listado-modulos>' };

Vue.component('home-rrhh', require('./components/HomeRRHHComponent.vue'));
Vue.component('listado-pelotaris', require('./components/rrhh_pelotaris/ListadoComponent.vue'));
Vue.component('ficha-pelotari', require('./components/rrhh_pelotaris/FichaComponent.vue'));

const HomeRRHH = { template: '<home-rrhh></home-rrhh>' };
const ListPelotaris = { template: '<listado-pelotaris></listado-pelotaris> '};
const CreatePelotari = { template: '<ficha-pelotari form-title="Nuevo Pelotari"></ficha-pelotari> '};
const EditPelotari = { template: '<ficha-pelotari form-title="Editar Pelotari"></ficha-pelotari> '};

Vue.component('contrato-header-pelotari', require('./components/rrhh_contratos/FichaHeaderComponent.vue'));
Vue.component('contrato-tramo-pelotari', require('./components/rrhh_contratos/FichaTramoComponent.vue'));

Vue.component('home-gerente', require('./components/HomeGerenteComponent.vue'));
Vue.component('listado-festivales', require('./components/festivales/ListadoComponent.vue'));
Vue.component('ficha-festival', require('./components/festivales/FichaComponent.vue'));

const HomeGerente = { template: '<home-gerente></home-gerente>' };
const ListFestivales_G = { template: '<listado-festivales is-gerente=1></listado-festivales>' };
const Calendario = { template: '<calendar-component></calendar-component>' };
const CreateFestival_G = { template: '<ficha-festival form-title="Nuevo Festival" :is-new-festival=1 :is-gerente=1></ficha-festival>'};
const EditFestival_G = { template: '<ficha-festival form-title="Editar Festival" :is-new-festival=0 :is-gerente=1></ficha-festival>'};

Vue.component('home-intendente', require('./components/HomeIntendenteComponent.vue'));

const HomeIntendente = { template: '<home-intendente></home-intendente>' };
const ListFestivales_I = { template: '<listado-festivales is-gerente=0></listado-festivales>' };
const EditFestival_I = { template: '<ficha-festival form-title="Editar Festival" :is-new-festival=0 :is-gerente=0></ficha-festival>'};

Vue.component('home-entrenador', require('./components/HomeEntrenadorComponent.vue'));
Vue.component('listado-entrenos', require('./components/entrenos/ListadoComponent.vue'));
Vue.component('ficha-entreno', require('./components/entrenos/FichaComponent.vue'));

const HomeEntrenador = { template: '<home-entrenador></home-entrenador>' };
const ListEntrenos = { template: '<listado-entrenos></listado-entrenos>' };
const CreateEntreno = { template: '<ficha-entreno form-title="Nuevo Entrenamiento" :is-new-entreno=1></ficha-entreno>' };
const EditEntreno = { template: '<ficha-entreno form-title="Editar Entrenamiento" :is-new-entreno=0></ficha-entreno>' };

const routes = [
  {
    path: '/administrador', component: HomeAdmin,
    children: [
      {
        path: '', component: ListModulos
      },
      {
        path: 'pelotari/new', component: CreatePelotari
      },
      {
        path: 'pelotari/:id/edit', component: EditPelotari
      },
    ]
  },
  {
    path: '/rrhh', component: HomeRRHH,
    children: [
      {
        path: '', name: 'RRHH', component: ListPelotaris
      },
      {
        path: 'pelotari/new', component: CreatePelotari
      },
      {
        path: 'pelotari/:id/edit', component: EditPelotari
      },
    ]
  },
  {
    path: '/gerente', component: HomeGerente,
    children: [
      {
        path: '', name: 'GERENTE', component: ListFestivales_G
      },
      {
        path: 'calendario', component: Calendario
      },
      {
        path: 'festival/new', component: CreateFestival_G
      },
      {
        path: 'festival/:id/edit', component: EditFestival_G
      },
    ]
  },
  {
    path: '/intendente', component: HomeIntendente,
    children: [
      {
        path: '', name: 'INTENDENTE', component: ListFestivales_I
      },
      {
        path: 'festival/:id/edit', component: EditFestival_I
      },
    ]
  },
  {
    path: '/entrenador', component: HomeEntrenador,
    children: [
      {
        path: '', name: 'ENTRENADOR', component: ListEntrenos
      },
      {
        path: 'entrenamiento/new', component: CreateEntreno
      },
      {
        path: 'entrenamiento/:id/edit', component: EditEntreno
      },
    ]
  },
];

const router = new VueRouter({ mode: 'history', routes: routes});

import { store } from './components/store/store';

const app = new Vue({
    el: '#app',
    store,
    router,
});
