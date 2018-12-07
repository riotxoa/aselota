<template>
  <div>
    <div class="main-header">
      <div class="toolbar mb-0 py-1">
        <div class="container">
          <b-row>
            <div class="col-sm-3">&nbsp;</div>
            <h4 class="col-sm-6 text-white font-weight-bold">EVENTOS PRENSA</h4>
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
          <!-- <filtro-festivales></filtro-festivales> -->
        </b-col>

        <b-col class="col-sm-4 text-right my-1 mb-3">
          <router-link to="/prensa/evento/new" class="text-white"><b-btn variant="danger" class="mb-0" title="Crear Evento">Nuevo Evento</b-btn></router-link>
        </b-col>

        <b-table striped hover responsive
          @row-clicked="onClickRow"
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          :per-page="perPage"
          :current-page="currentPage"
          :items="_eventos"
          :fields="fields">

          <template slot="actions" slot-scope="row">
            <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
            <b-button-group>
              <b-button v-if="remove" size="sm" variant="danger" @click.stop="onClickDelete(row.item)" title="Eliminar">
                <span class="icon voyager-trash"></span>
              </b-button>
              <b-button v-if="update" size="sm" variant="primary" @click.stop="onClickEdit(row.item)" title="Editar">
                <span class="icon voyager-edit"></span>
              </b-button>
              <b-button v-if="display" size="sm" variant="secondary" @click.stop="row.toggleDetails" title="Mostrar/Ocultar Detalle">
                <span class="icon" v-bind:class="{ 'voyager-x': row.detailsShowing, 'voyager-eye': !row.detailsShowing }"></span>
              </b-button>
            </b-button-group>
          </template>

          <template v-if="display" slot="row-details" slot-scope="row">
            <b-card>
              <b-row class="info-pelotari-row">
                <div v-for="pelotari in row.item.pelotaris" class="info-pelotari col-sm-6">
                  <b-row class="mb-3">
                    <b-col class="col-sm-2"><img :src="pelotari.foto"/></b-col>
                    <b-col class="col-sm-5"><div class="ml-3 mt-3"><p class="mb-0 font-weight-bold">Pelotari:</p>{{ pelotari.alias }}</div></b-col>
                    <b-col class="col-sm-5"><div class="ml-3 mt-3"><p class="mb-0 font-weight-bold">Asiste:</p>{{ (1 == pelotari.asiste ? "Sí" : "No") }}</div></b-col>
                  </b-row>
                  <b-row>
                    <b-col class="col-sm-6 font-weight-bold mt-2">Observaciones:</b-col>
                    <b-col class="col-sm-12 motivo">{{ pelotari.motivo }}</b-col>
                  </b-row>
                </div>
              </b-row>
            </b-card>
          </template>

        </b-table>

        <b-row class="col-12">
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

      </b-row>
      <delete-modal object-name="Evento" :remove-item="removeItem"></delete-modal>
    </div>
  </div>
</template>

<script>
  import { store } from '../store/store';
  import Utils from '../utils/utils.js';

  Vue.component('delete-modal', require('../modals/ModalDeleteComponent.vue'));

  export default {
      mixins: [Utils],
      data () {
        return {
          remove: true,
          update: true,
          display: true,
          sortBy: 'age',
          sortDesc: false,
          fields: [
            { key: 'fecha', label: 'Fecha', sortable: true },
            { key: 'hora', label: 'Hora', sortable: true },
            { key: 'provincia_name', label: 'Provincia', sortable: true },
            { key: 'municipio_name', label: 'Municipio', sortable: true },
            { key: 'motivo_name', label: 'Motivo', sortable: true },
            { key: 'desc', label: 'Descripción', sortable: true },
            { key: 'actions', label: 'Acciones', sortable: false, class: "text-center" },
          ],
          perPage: 10,
          currentPage: 1,
          pageOptions: [ 10, 25, 50 ],
          filter: null,
          deleteId: null,
        }
      },
      computed: {
        _eventos() {
          return store.getters.eventos;
        },
        _totalRows: {
          get () {
            return store.getters.eventos.length;
          },
          set (value) {
            // do nothing
          }
        }
      },
      created() {
        var myDate = new Date();
        var month = ('0' + (myDate.getMonth() + 1)).slice(-2);
        var day = ('0' + myDate.getDate()).slice(-2);
        var year = myDate.getFullYear();
        var formattedDate = year + '/' + month + '/' + day;

        store.dispatch('loadProvincias');
        store.dispatch('loadMunicipios');
        store.dispatch('loadPelotaris', year + '-' + month + '-' + day);
        store.dispatch('loadCampeonatos');
        store.dispatch('loadEventoMotivos');
        store.dispatch('loadEventos');
      },
      methods: {
        onClickDelete(item) {
          this.$root.$emit('bv::show::modal','modalDelete')
          var msg = "Se va a borrar el Evento de Prensa que se celebrará  en <strong>" + item.municipio_name + "</strong> el próximo <strong>" + this.formatDateES(item.fecha) + "</strong> a las <strong>" + item.hora + "</strong>.";
          this.deleteId = item.id;
          jQuery('#deleteMsg').html(msg);
        },
        onClickEdit(item) {
          this.$router.push({ path: `/prensa/evento/${item.id}/edit/`, query: { evento: item } });
        },
        onClickRow(item, index, ev) {
          this.onClickEdit(item);
        },
        removeItem () {
          let uri = '/www/eventos/' + this.deleteId;
          this.axios.delete(uri)
            .then((response) => {
              this.deleteId = null;
              this.$root.$emit('bv::hide::modal','modalDelete');
              store.dispatch('loadEventos');
              this.showSnackbar("Evento BORRADO");
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
  .info-pelotari {
    margin-bottom:1rem;
    padding:1.5rem;
  }
  .info-pelotari-row .info-pelotari:nth-child(odd) {
    border-right:1px solid #efefef;
  }
</style>
