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
Vue.component('rrhh-tabs', require('./components/rrhh_pelotaris/PelotarisHomePageComponent.vue'));
Vue.component('listado-pelotaris-profesional', require('./components/rrhh_pelotaris/ListadoProfesionalComponent.vue'));
Vue.component('listado-pelotaris-promesa', require('./components/rrhh_pelotaris/ListadoPromesaComponent.vue'));
Vue.component('ficha-pelotari', require('./components/rrhh_pelotaris/FichaComponent.vue'));

const HomeRRHH = { template: '<home-rrhh></home-rrhh>' };
const RRHHTabs = { template: '<rrhh-tabs></rrhh-tabs>' };
const ListPelotarisProfesional = { template: '<listado-pelotaris-profesional></listado-pelotaris-profesional> '};
const ListPelotarisPromesa = { template: '<listado-pelotaris-promesa></listado-pelotaris-promesa> '};
const CreatePelotari = { template: '<ficha-pelotari form-title="Nuevo Pelotari"></ficha-pelotari> '};
const EditPelotari = { template: '<ficha-pelotari form-title="Editar Pelotari"></ficha-pelotari> '};

Vue.component('contrato-header-pelotari', require('./components/rrhh_contratos/FichaHeaderComponent.vue'));
Vue.component('contrato-tramo-pelotari', require('./components/rrhh_contratos/FichaTramoComponent.vue'));
Vue.component('periodo-comercial-pelotari', require('./components/rrhh_contratos/FichaComercialComponent.vue'));

Vue.component('home-gerente', require('./components/HomeGerenteComponent.vue'));
Vue.component('listado-festivales', require('./components/festivales/ListadoComponent.vue'));
Vue.component('ficha-festival', require('./components/festivales/FichaComponent.vue'));
Vue.component('ficha-coste-entradas', require('./components/costes/FichaCosteEntradasComponent.vue'));

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

Vue.component('home-prensa', require('./components/HomePrensaComponent.vue'));
Vue.component('prensa-tabs', require('./components/eventos/PrensaHomePageComponent.vue'));
Vue.component('listado-eventos', require('./components/eventos/ListadoComponent.vue'));
Vue.component('ficha-evento', require('./components/eventos/FichaComponent.vue'));
Vue.component('evento-pelotaris', require('./components/eventos/EventoPelotariComponent.vue'));
Vue.component('evento-header', require('./components/eventos/EventoHeaderComponent.vue'));
Vue.component('evento-body', require('./components/eventos/EventoBodyComponent.vue'));

const HomePrensa = { template: '<home-prensa></home-prensa>' };
const PrensaTabs = { template: '<prensa-tabs></prensa-tabs>' };
const ListEventos = { template: '<listado-eventos></listado-eventos>' };
const CreateEvento = { template: '<ficha-evento form-title="Nuevo Evento de Prensa" :is-new-evento=1></ficha-evento>' };
const EditEvento = { template: '<ficha-evento form-title="Editar Evento de Prensa" :is-new-evento=0></ficha-evento>' };
const ViewFestival = { template: '<ficha-festival form-title="Consultar Festival" :is-new-festival=0 :is-gerente=0 :is-prensa=1></ficha-festival>' };

Vue.component('home-cuadro', require('./components/HomeCuadroComponent.vue'));
Vue.component('listado-cuadro', require('./components/cuadro/ListadoComponent.vue'));

const HomeCuadro = { template: '<home-cuadro></home-cuadro>' };
const ListCuadro = { template: '<listado-cuadro></listado-cuadro>' };

// Módulo MÉDICO
Vue.component('home-medico', require('./components/HomeMedicoComponent.vue'));
Vue.component('listado-partes', require('./components/partes_medicos/ListadoComponent.vue'));
Vue.component('ficha-parte', require('./components/partes_medicos/FichaComponent.vue'));

const HomeMedico = { template: '<home-medico></home-medico>' };
const ListPartes = { template: '<listado-partes></listado-partes>' };
const CreateParte = { template: '<ficha-parte form-title="Nuevo Parte Médico" :is-new-parte=1></ficha-parte>' };
const EditParte = { template: '<ficha-parte form-title="Editar Parte Médico" :is-new-parte=0></ficha-parte>' };

// PLEN: PLANIFICACIÓN DE ENTRENAMIENTOS
Vue.component('plen-home', require('./components/HomePLENComponent.vue'));
Vue.component('plen-gestor-dashboard', require('./components/PLEN/gestor/00_DashboardComponent.vue'));
Vue.component('plen-entrenador-dashboard', require('./components/PLEN/entrenador/00_DashboardComponent.vue'));

const PLEN_Home = { template: '<plen-home></plen-home>' };
const PLEN_GESTOR_Dashboard = { template: '<plen-gestor-dashboard></plen-gestor-dashboard>' };
const PLEN_ENTRENADOR_Dashboard = { template: '<plen-entrenador-dashboard></plen-entrenador-dashboard>' };

const routes = [
  {
    path: '/administrador', component: HomeAdmin,
    children: [
      {
        path: '', component: ListModulos
      },
      {
        path: 'pelotari/new/:promesa', name: 'admin.pelotari.new', component: CreatePelotari
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
        path: '', name: 'RRHH', component: RRHHTabs
      },
      {
        path: 'pelotari/new/:promesa', name: 'rrhh.pelotari.new', component: CreatePelotari
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
  {
    path: '/prensa', component: HomePrensa,
    children: [
      {
        path: '', name: 'PRENSA', component: PrensaTabs // ListEventos
      },
      {
        path: 'evento/new', component: CreateEvento
      },
      {
        path: 'evento/:id/edit', component: EditEvento
      },
      {
        path: 'festival/:id/view', component: ViewFestival
      },
    ]
  },
  {
    path: '/cuadro', component: HomeCuadro,
    children: [
      {
        path: '', name: 'CUADRO', component: ListCuadro // ListEventos
      }
    ]
  },
  {
    path: '/medico', component: HomeMedico,
    children: [
      {
        path: '', name: 'MÉDICO', component: ListPartes
      },
      {
        path: 'parte/new', component: CreateParte
      },
      {
        path: 'parte/:id/edit', component: EditParte
      },
    ]
  },
  {
    path: '/PLEN', component: PLEN_Home,
    children: [
      {
        path: '', name: 'PLEN_GESTOR', component: PLEN_GESTOR_Dashboard
      },
      {
        path: '', name: 'PLEN_ENTRENADOR', component: PLEN_ENTRENADOR_Dashboard
      },
    ]
  }
];

const router = new VueRouter({ mode: 'history', routes: routes});

import { store } from './components/store/store';

const app = new Vue({
    el: '#app',
    store,
    router,
});
