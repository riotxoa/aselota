<template>
  <div>
    <div class="main-header">
      <div class="toolbar mb-0 py-1">
        <div class="container">
          <b-row>
            <div class="col-sm-3">&nbsp;</div>
            <h4 class="col-sm-6 text-white font-weight-bold">GESTIÓN DE FESTIVALES</h4>
            <div class="col-sm-3 text-right home-icon">
              <b-button v-if="this.$route.params.userRole === 'admin'" size="sm" variant="outline" href="/" class="mt-1"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Admin Menú</b-button>
            </div>
          </b-row>
        </div>
      </div>
    </div>
    <div class="container">
      <b-row>

        <b-col class="col-sm-8 float-left my-1 mb-3">
          <filtro-festivales></filtro-festivales>
        </b-col>

        <b-col class="col-sm-4 text-right my-1 mb-3">
          <router-link v-if="1 == isGerente" to="/gerente/calendario" class="text-white"><b-btn variant="outline-link" class="mb-0" title="Calendario de Pelotaris"><div class="icon voyager-calendar"></div></b-btn></router-link>
          <router-link v-if="1 == isGerente" to="/gerente/festival/new" class="text-white"><b-btn variant="danger" class="mb-0" title="Crear Festival">Nuevo Festival</b-btn></router-link>
        </b-col>

      </b-row>

      <b-table striped hover small responsive
        @row-clicked="onClickRow"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :per-page="perPage"
        :current-page="currentPage"
        :items="_items"
        :fields="fields">

        <template slot="actions" slot-scope="row">
          <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
          <b-button-group>
            <b-button v-if="remove && 1 == isGerente" size="sm" variant="danger" @click.stop="onClickDelete(row.item)" title="Eliminar">
              <span class="icon voyager-trash"></span>
            </b-button>
            <b-button v-if="update && 1 == isGerente" size="sm" variant="primary" @click.stop="onClickEdit_G(row.item.id)" title="Editar">
              <span class="icon voyager-edit"></span>
            </b-button>
            <b-button v-if="update && 0 == isGerente" size="sm" variant="primary" @click.stop="onClickEdit_I(row.item.id)" title="Editar">
              <span class="icon voyager-edit"></span>
            </b-button>
            <b-button v-if="display && 1 == isGerente && (row.item.contact_01_name || row.item.contact_02_name)" size="sm" variant="secondary" @click.stop="row.toggleDetails" title="Mostrar/Ocultar Detalle">
              <span class="icon" v-bind:class="{ 'voyager-x': row.detailsShowing, 'voyager-eye': !row.detailsShowing }"></span>
            </b-button>
          </b-button-group>
        </template>

        <template v-if="display" slot="row-details" slot-scope="row">
          <b-card v-if="row.item.contact_01_name || row.item.contact_02_name">
            <b-row v-if="row.item.contact_01_name" class="mb-2">
              <b-col class="col-sm-12"><strong>Contacto 1:</strong> {{ row.item.contact_01_name }} ({{ row.item.contact_01_desc}})</b-col>
            </b-row>
            <b-row v-if="row.item.contact_01_name">
              <b-col class="col-sm-4"><i class="fa fa-envelope pr-2"></i><a href="mailto:row.item.contact_01_email_1">{{ row.item.contact_01_email_1 }}</a></b-col>
              <b-col class="col-sm-4"><i class="fa fa-envelope pr-2"></i><a href="mailto:row.item.contact_01_email_2">{{ row.item.contact_01_email_2 }}</a></b-col>
              <b-col class="col-sm-2"><i class="fa fa-phone pr-2"></i>{{ row.item.contact_01_telephone_1}}</b-col>
              <b-col class="col-sm-2"><i class="fa fa-phone pr-2"></i>{{ row.item.contact_01_telephone_2 }}</b-col>
            </b-row>
            <hr v-if="row.item.contact_01_name && row.item.contact_02_name" />
            <b-row v-if="row.item.contact_02_name" class="mb-2">
              <b-col class="col-sm-12"><strong>Contacto 2:</strong> {{ row.item.contact_02_name }} ({{ row.item.contact_02_desc}})</b-col>
            </b-row>
            <b-row v-if="row.item.contact_02_name">
              <b-col class="col-sm-4"><i class="fa fa-envelope pr-2"></i><a href="mailto:row.item.contact_02_email_1">{{ row.item.contact_02_email_1 }}</a></b-col>
              <b-col class="col-sm-4"><i class="fa fa-envelope pr-2"></i><a href="mailto:row.item.contact_02_email_2">{{ row.item.contact_02_email_2 }}</a></b-col>
              <b-col class="col-sm-2"><i class="fa fa-phone pr-2"></i>{{ row.item.contact_02_telephone_1}}</b-col>
              <b-col class="col-sm-2"><i class="fa fa-phone pr-2"></i>{{ row.item.contact_02_telephone_2 }}</b-col>
            </b-row>
          </b-card>
        </template>

      </b-table>

      <b-row>
        <b-col md="5" class="my-1">
          <b-pagination :total-rows="_totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
        </b-col>
        <b-col md="3" class="my-1 text-right">
          <b-form-group horizontal class="mb-0" label="Total: ">
            <b-form-input readonly plaintext v-model="_totalRows"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4" class="my-1">
          <b-form-group horizontal label="Mostrar" class="mb-0">
            <b-form-select :options="pageOptions" v-model="perPage" />
          </b-form-group>
        </b-col>
      </b-row>
      <delete-modal object-name="Festival" :remove-item="removeItem"></delete-modal>
    </div>
  </div>
</template>

<script>
  import { store } from '../store/store';
  import Utils from '../utils/utils.js';
  Vue.component('delete-modal', require('../modals/ModalDeleteComponent.vue'));
  Vue.component('filtro-festivales', require('./FiltroFestivalComponent.vue'));
  export default {
      mixins: [ Utils ],
      props: ['isGerente', 'isPrensa'],
      data () {
        return {
          filter: true,
          remove: true,
          update: true,
          display: true,
          sortBy: 'fecha_ini',
          sortDesc: true,
          fields: [
            { key: 'fecha', label: '<span title="Fecha">Fecha</span>', formatter: 'formatDateES', sortable: true },
            { key: 'hora', label: '<span title="Hora">Hora</span>', sortable: false },
            { key: 'fronton', label: '<span title="Frontón">Frontón</span>', sortable: true },
            { key: 'municipio', label: '<span title="Municipio">Municipio</span>', sortable: true },
            { key: 'organizador', label: '<span title="Organizador">Organizador</span>', sortable: true },
            { key: 'television_txt', label: '<span title="Televisión">Televisión</span>', sortable: true },
            { key: 'estado', label: '<span title="Estado del Festival">Estado</span>', sortable: true },
            { key: 'actions', label: '<span title="Acciones">Acciones</span>', sortable: false, class: "text-left" },
          ],
          items: [],
          totalRows: 0,
          perPage: 10,
          currentPage: 1,
          pageOptions: [ 10, 25, 50 ],
          deleteId: null,
        }
      },
      created() {
        store.dispatch('loadFestivales');
      },
      computed: {
        _items () {
          store.getters.festivales.map( (val, key) => {
            val.organizador = ( "gugeu" == val.organizador ? "Baiko Pilota" : "Aspe");
          });

          return store.getters.festivales;
        },
        _totalRows: {
          get () {
            return store.getters.total_festivales;
          },
          set (value) {
            store.commit('SET_TOTAL_FESTIVALES', value);
          }
        }
      },
      methods: {
        fetchFestivales () {
          store.dispatch('loadFestivales');
        },
        onClickDelete (item) {
          this.$root.$emit('bv::show::modal','modalDelete')
          var msg = "Se va a borrar el Festival que se celebrará en el frontón <strong>" + item.fronton + "</strong> de <strong>" + item.municipio + "</strong> el próximo <strong>" + this.formatDateES(item.fecha) + "</strong> a las <strong>" + item.hora + "</strong>.";
          this.deleteId = item.id;
          jQuery('#deleteMsg').html(msg);
        },
        onClickEdit_G (id) {
          this.$router.push('/gerente/festival/' + id + '/edit/');
        },
        onClickEdit_I (id) {
          if( this.isPrensa )
            this.$router.push('/prensa/festival/' + id + '/view/');
          else
            this.$router.push('/intendente/festival/' + id + '/edit/');
        },
        onClickRow(item, index, ev) {
          if( this.update && 1 == this.isGerente ) {
            this.onClickEdit_G(item.id);
          }
          if( this.update && 0 == this.isGerente ) {
            this.onClickEdit_I(item.id);
          }
        },
        removeItem () {
          let uri = '/www/festivales/' + this.deleteId;
          this.axios.delete(uri)
            .then((response) => {
              this.deleteId = null;
              this.$root.$emit('bv::hide::modal','modalDelete')
              this.fetchFestivales();
              this.showSnackbar("Festival BORRADO");
            })
            .catch((error) => {
              console.log("[remove] error: " + error);
              this.deleteId = null;
              this.$root.$emit('bv::hide::modal','modalDelete')
              this.showSnackbar("ERROR al borrar");
            });
        }
      }
  }
</script>

<style>
  .main-header {
    margin-bottom:2rem;
    margin-top:-1.45rem;
  }
  .main-header .toolbar {
    background-color:slategray;
    padding:10px 0;
    text-align:center;
  }
  .main-header h4 {
    line-height:1.75;
    margin:0 auto;
  }
</style>
