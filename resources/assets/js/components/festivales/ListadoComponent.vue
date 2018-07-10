<template>
  <div class="container">
    <b-row>

      <b-col class="col-sm-6 float-left my-1 mb-3">
        <!-- <b-form-group v-if="filter" horizontal label="Filtro" class="mb-0">
          <b-input-group>
            <b-form-input v-model="filterTxt" placeholder="Texto de búsqueda" />
            <b-input-group-append>
              <b-btn :disabled="!filterTxt" @click="filterTxt = ''" title="Limpiar filtro">Limpiar</b-btn>
            </b-input-group-append>
          </b-input-group>
        </b-form-group> -->
      </b-col>

      <b-col class="col-sm-6 text-right my-1 mb-3">
        <router-link to="/gerente/festival/new" class="text-white"><b-btn variant="danger" class="mb-0" title="Crear Festival">Nuevo Festival</b-btn></router-link>
      </b-col>

    </b-row>

    <b-table striped hover small responsive
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :per-page="perPage"
      :current-page="currentPage"
      :items="items"
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
            <b-col sm="6">
            </b-col>
            <b-col sm="6">
            </b-col>
          </b-row>
        </b-card>
      </template>

    </b-table>

    <b-row>
      <b-col md="5" class="my-1">
        <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
      </b-col>
      <b-col md="3" class="my-1 text-right">
        <b-form-group horizontal class="mb-0" label="Total: ">
          <b-form-input readonly plaintext v-model="totalRows"></b-form-input>
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
</template>

<script>
  import Utils from '../utils/utils.js';
  Vue.component('delete-modal', require('../modals/ModalDeleteComponent.vue'));
  export default {
      mixins: [ Utils ],
      data () {
        return {
          filter: true,
          remove: true,
          update: true,
          display: false,
          sortBy: 'fecha_ini',
          sortDesc: true,
          fields: [
            { key: 'fecha', label: '<span title="Fecha">Fecha</span>', formatter: 'formatDateES', sortable: true },
            { key: 'hora', label: '<span title="Hora">Hora</span>', sortable: false },
            { key: 'fronton', label: '<span title="Frontón">Frontón</span>', sortable: true },
            { key: 'municipio', label: '<span title="Municipio">Municipio</span>', sortable: true },
            { key: 'television_txt', label: '<span title="Televisión">Televisión</span>', sortable: true },
            { key: 'estado', label: '<span title="Estado del Festival">Estado</span>', sortable: true },
            { key: 'actions', label: '<span title="Acciones">Acciones</span>', sortable: false, class: "text-center" },
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
        this.fetchFestivales();
      },
      methods: {
        fetchFestivales () {
          let uri = '/www/festivales';
          this.axios.get(uri).then((response) => {
            var stringified = JSON.stringify(response.data);
            this.items = JSON.parse(stringified);
            this.totalRows = this.items.length;
          });
        },
        onClickDelete (item) {
          this.$root.$emit('bv::show::modal','modalDelete')
          var msg = "Se va a borrar el Festival que se celebrará en el frontón <strong>" + item.fronton + "</strong> de <strong>" + item.municipio + "</strong> el próximo <strong>" + this.formatDateES(item.fecha) + "</strong> a las <strong>" + item.hora + "</strong>.";
          this.deleteId = item.id;
          jQuery('#deleteMsg').html(msg);
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

</style>
