<template>
  <div>
    <div class="main-header">
      <div class="toolbar mb-0 py-1">
        <div class="container">
          <b-row>
            <div class="col-sm-3">&nbsp;</div>
            <h4 class="col-sm-6 text-white font-weight-bold">PARTES MÉDICOS</h4>
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
          <router-link to="/medico/parte/new" class="text-white"><b-btn variant="danger" class="mb-0" title="Crear Parte">Nuevo Parte</b-btn></router-link>
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
            <b-row>
              <b-col cols="12" sm="2" class="align-self-center">
                  <img :src="row.item.pelotari_foto" class="img-responsive" style="width:100%;">
                  <p class="font-weight-bold mt-2 text-center">{{ row.item.pelotari_alias }}</p>
                  <b-button v-if="update" size="sm" variant="primary" class="font-weight-bold py-1 text-uppercase w-100" @click.stop="onClickEdit(row.item)" title="Editar">
                    Editar
                  </b-button>
              </b-col>
              <b-col cols="12" sm="10">
                <b-row>
                  <b-col cols="3"><span class="font-weight-bold">Baja:</span><br>{{ (row.item.is_baja == true || row.item.is_baja == 1 ? "SÍ" : "NO") }}</b-col>
                  <b-col cols="3"><span class="font-weight-bold">Fecha parte:</span><br>{{ formatDateES(row.item.fecha_parte) }}</b-col>
                  <b-col cols="3"><span class="font-weight-bold">Fecha baja:</span><br>{{ formatDateES(row.item.fecha_baja) }}</b-col>
                  <b-col cols="3"><span class="font-weight-bold">Fecha alta:</span><br>{{ formatDateES(row.item.fecha_alta) }}</b-col>
                </b-row>
                <hr class="my-2">
                <b-row>
                  <b-col cols="3"><span class="font-weight-bold">Diagnóstico:</span><br>{{ row.item.diagnostico }}</b-col>
                  <b-col cols="9"><span class="font-weight-bold">Desc.diagnóstico:</span><br>{{ row.item.med_diagnostico_txt }}</b-col>
                </b-row>
                <hr class="my-2">
                <b-row>
                  <b-col cols="3"><span class="font-weight-bold">Evolución:</span><br>{{ row.item.evolucion }}</b-col>
                  <b-col cols="9"><span class="font-weight-bold">Desc.evolución:</span><br>{{ row.item.med_evolucion_txt }}</b-col>
                </b-row>
                <hr class="my-2">
                <b-row>
                  <b-col cols="12"><span class="font-weight-bold">Observaciones:</span><br>{{ row.item.observaciones }}</b-col>
                </b-row>
              </b-col>
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

    </div>

    <delete-modal object-name="Parte" :remove-item="removeItem"></delete-modal>

  </div>
</template>

<script>
  import { store } from '../store/store';
  import { mapState } from 'vuex';
  import Utils from '../utils/utils.js';

  Vue.component('delete-modal', require('../modals/ModalDeleteComponent.vue'));

  export default {
      mixins: [ Utils ],
      data () {
        return {
          filter: true,
          remove: true,
          update: true,
          display: true,
          sortBy: 'fecha_parte',
          sortDesc: true,
          fields: [
            { key: 'fecha_parte', label: '<span title="Fecha Parte">Fecha del Parte</span>', formatter: 'formatDateES', sortable: true },
            { key: 'pelotari_alias', label: '<span title="Pelotari">Pelotari</span>', sortable: true },
            { key: 'diagnostico', label: '<span title="Diagnóstico">Diagnóstico</span>', sortable: true },
            { key: 'evolucion', label: '<span title="Evolución">Evolución</span>', sortable: true },
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
      computed: {
        _items () {
          return store.getters.partes;
        },
        _totalRows: {
          get () {
            return store.getters.partes.length;
          },
          set (value) {
            // do nothing
          }
        },
        _edit: {
          get() {
            return store.getters.parte_edit;
          },
          set (value) {
            store.commit('SET_PARTE_EDIT', value);
          }
        },
      },
      created() {
        store.dispatch('loadPartes');
      },
      methods: {
        onClickDelete (item) {
          this.$root.$emit('bv::show::modal','modalDelete')
          var msg = "Se va a borrar el Parte de fecha <strong>" + this.formatDateES(item.fecha_parte) + "</strong> del pelotari <strong>" + item.pelotari_alias + "</strong>.";
          this.deleteId = item.id;
          jQuery('#deleteMsg').html(msg);
        },
        onClickEdit( parte ) {
          this._edit = 1;
          this.$router.push('/medico/parte/' + parte.id + '/edit/');
        },
        onClickRow(item, index, ev) {
          this.onClickEdit(item);
        },
        removeItem () {
          let uri = '/www/partes/' + this.deleteId;
          this.axios.delete(uri)
            .then((response) => {
              this.deleteId = null;
              this.$root.$emit('bv::hide::modal','modalDelete');
              store.dispatch('loadPartes');
              this.showSnackbar("Parte BORRADO");
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

<style scoped>
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
