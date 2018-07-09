<template>
  <div id="tarifas-campeonato" class="container-fluid p-0">
    <b-row>

      <b-col class="col-sm-6 float-left mt-0 mb-1">
        <b-form-group v-if="filter" horizontal label="Filtro" class="mb-0">
          <b-input-group>
            <b-form-input v-model="filter" placeholder="Texto de búsqueda" />
            <b-input-group-append>
              <b-btn :disabled="!filter" @click="filter = ''" title="Limpiar filtro">Limpiar</b-btn>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
        <div v-else class="mt-2 mb-0">
          <h6 class="text-danger font-weight-bold">{{ campeonatoDesc }}</h6>
        </div>
      </b-col>

      <b-col class="col-sm-6 text-right float-right mt-0 mb-3">
        <b-btn variant="default" class="mb-0" size="sm" title="Crear Tarifa" @click="showTarifaForm(0)">Nueva Tarifa</b-btn>
      </b-col>

    </b-row>

    <b-table striped hover small responsive
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :per-page="perPage"
      :current-page="currentPage"
      :items="items"
      :fields="fields"
      :filter="filter"
      @filtered="onFiltered">

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
        <b-card id="tarifaDetails">
          <b-row class="mb-2 py-2 border-bottom border-top border-secondary">
            <b-col sm="2" class="font-weight-bold">Campeón:</b-col>
            <b-col sm="2">{{ formatAmount(row.item.campeon) }}&nbsp;&euro;</b-col>
            <b-col sm="2" class="font-weight-bold">Subcampeón:</b-col>
            <b-col sm="2">{{ formatAmount(row.item.subcampeon) }}&nbsp;&euro;</b-col>
          </b-row>
          <b-row class="mb-2 pb-2 border-bottom border-secondary">
            <b-col sm="2" class="font-weight-bold">L. Semifinales:</b-col>
            <b-col sm="2">{{ formatAmount(row.item.liga_semifinal) }}&nbsp;&euro;</b-col>
            <b-col sm="2" class="font-weight-bold">L. Cuartos:</b-col>
            <b-col sm="2">{{ formatAmount(row.item.liga_cuartos) }}&nbsp;&euro;</b-col>
          </b-row>
          <b-row class="mb-2 pb-2 border-bottom border-secondary">
            <b-col sm="2" class="font-weight-bold">Semifinales:</b-col>
            <b-col sm="2">{{ formatAmount(row.item.semifinal) }}&nbsp;&euro;</b-col>
            <b-col sm="2" class="font-weight-bold">1/4&nbsp;Final:</b-col>
            <b-col sm="2">{{ formatAmount(row.item.cuartos) }}&nbsp;&euro;</b-col>
          </b-row>
          <b-row class="mb-2 pb-2 border-bottom border-secondary">
            <b-col sm="2" class="font-weight-bold">1/8&nbsp;Final::</b-col>
            <b-col sm="2">{{ formatAmount(row.item.octavos) }}&nbsp;&euro;</b-col>
            <b-col sm="2" class="font-weight-bold">1/16&nbsp;Final:</b-col>
            <b-col sm="2">{{ formatAmount(row.item.dieciseisavos) }}&nbsp;&euro;</b-col>
            <b-col sm="2" class="font-weight-bold">1/32&nbsp;Final:</b-col>
            <b-col sm="2">{{ formatAmount(row.item.treintaidosavos) }}&nbsp;&euro;</b-col>
          </b-row>
        </b-card>
      </template>

    </b-table>

    <b-row>
      <b-col md="5" class="my-1">
        <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" size="sm" />
      </b-col>
      <b-col md="3" class="my-1 text-right">
        <b-form-group horizontal class="mb-0" label="Total: ">
          <b-form-input readonly plaintext v-model="totalRows" size="sm"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col md="4" class="my-1">
        <b-form-group horizontal label="Mostrar" class="mb-0">
          <b-form-select :options="pageOptions" v-model="perPage" size="sm" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-modal v-if="remove" ref="modalDelete" title="BORRAR Tarifa" hide-footer>
      <div class="modal-body">
        <p>Se va a borrar la tarifa de <span id="deleteTarifaAlias"></span>¿Desea continuar?</p>
      </div>
      <div class="modal-footer">
        <b-btn variant="danger" @click="removeItem">Borrar</b-btn>
        <b-btn @click="hideModalDelete">Cancelar</b-btn>
      </div>
    </b-modal>

    <b-modal id="tarifaForm" ref="modalEdit" :title="formTitle" size="lg" hide-footer lazy>
      <tarifa-pelotari :pelotari-id="pelotariId" :pelotari-alias="pelotariAlias" :campeonato-id="campeonatoId" :campeonato-name="campeonatoName" :on-cancel="cancelTarifaForm" :get-tarifa-row="getTarifaRow" :is-new-tarifa="isNewTarifa" :format-amount="formatRowAmount"></tarifa-pelotari>
    </b-modal>

  </div>
</template>

<script>
  import moment from 'moment';

  Vue.component('tarifa-pelotari', require('./FichaComponent.vue'));

  const showSnackbar = (msg) => {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";
    x.innerHTML = msg;

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  }
  export default {
      props: [
        'pelotariId',
        'pelotariAlias',
        'campeonatoId',
        'campeonatoName',
        'campeonatoDesc'
      ],
      data () {
        return {
          create: true,
          remove: true,
          update: true,
          display: true,
          filter: false,
          sortBy: 'fecha_ini',
          sortDesc: true,
          fields: [
            { key: 'fecha_ini', label: '<span title="Fecha de Inicio">F. Inicio</span>', formatter: 'formatDate', sortable: true },
            { key: 'fecha_fin', label: '<span title="Fecha de Finalización">F. Fin</span>', formatter: 'formatDate', sortable: true },
            { key: 'campeon', label: '<span title="Tarifa Campeón">Campeón</span>', formatter: 'formatAmount', class: 'text-right', sortable: false },
            { key: 'subcampeon', label: '<span title="Tarifa Subcampeón">Subcampeón</span>', formatter: 'formatAmount', class: 'text-right', sortable: false },
            { key: 'liga_semifinal', label: '<span title="Tarifa Liguilla Semifinales">L.Semifinales</span>', formatter: 'formatAmount', class: 'text-right', sortable: false },
            { key: 'semifinal', label: '<span title="Tarifa Semifinal">Semifinal</span>', formatter: 'formatAmount', class: 'text-right', sortable: false },
            { key: 'actions', label: 'Acciones', sortable: false, class: 'text-center' },
          ],
          items: [],
          totalRows: 0,
          perPage: 10,
          currentPage: 1,
          pageOptions: [ 10, 25, 50 ],
          filter: null,
          deleteId: null,
          formTitle: '',
          rowTarifa: null,
          newTarifa: true,
          cancelTarifaForm: () => { this.hideTarifaForm(); },
          getTarifaRow: () => { return this.rowTarifa; },
          isNewTarifa: () => { return this.newTarifa; },
          formatRowAmount: (amount) => { return this.formatAmount(amount); },
        }
      },
      created() {
        this.fetchTarifas();
      },
      methods: {
        fetchTarifas() {
          let uri = '/www/tarifas';
          this.axios.get(uri, {
              params: {
                pelotari_id: this.pelotariId,
                campeonato_id: this.campeonatoId,
              }
          })
          .then((response) => {
            var stringified = JSON.stringify(response.data);
            this.items = JSON.parse(stringified);
            this.totalRows = this.items.length;
          });
        },
        onFiltered (filteredItems) {
          // Trigger pagination to update the number of buttons/pages due to filtering
          this.totalRows = filteredItems.length;
          this.currentPage = 1;
        },
        onClickEdit (item) {
          this.rowTarifa = item;
          this.showTarifaForm(item.id);
        },
        removeItem () {
          let uri = '/www/tarifas/' + this.deleteId;
          this.axios.delete(uri)
            .then((response) => {
              this.deleteId = null;
              this.$refs.modalDelete.hide();
              this.fetchTarifas();
              showSnackbar("Tarifa BORRADA");
            })
            .catch((error) => {
              console.log("[removeItem] error: " + error);
              this.deleteId = null;
              this.$refs.modalDelete.hide();
              showSnackbar("ERROR al borrar");
            });
        },
        onClickDelete (tarifa) {
          this.deleteId = tarifa.id;

          var msg = " \
            <div class='px-5 py-2'> \
              <p class='mb-0'><strong>Pelotari:</strong> " + this.pelotariAlias + "</p> \
              <p class='mb-0'><strong>Campeonato:</strong> " + this.campeonatoName + "</p> \
              <p class='mb-0'><strong>Fecha inicio:</strong> " + this.formatDate(tarifa.fecha_ini) + " - <strong>Fecha fin:</strong> " + this.formatDate(tarifa.fecha_fin) + "</p> \
            </div>";

          jQuery('#deleteTarifaAlias').html(msg);

          this.$refs.modalDelete.show();
        },
        hideModalDelete() {
          this.deleteId = null;
          this.$refs.modalDelete.hide();
        },
        formatDate (date) {
          if(date)
            return moment(String(date)).format('DD/MM/YYYY');
          else {
            return "";
          }
        },
        formatAmount (amount) {
          if(amount)
            return parseFloat(amount).toFixed(2);
          else {
            return "";
          }
        },
        showTarifaForm ($id = 0) {
          if($id) {
            this.formTitle = 'Editar Tarifa';
            this.newTarifa = false;
            this.$refs.modalEdit.show();
          } else {
            this.formTitle = 'Nueva Tarifa';
            this.newTarifa = true;
            this.$refs.modalEdit.show();
          }
        },
        hideTarifaForm () {
          this.$refs.modalEdit.hide();
          this.fetchTarifas();
        }
      }
  }
</script>

<style>
  #tarifas-campeonato legend {
    font-size:0.7875rem;
    padding: 0.25rem 0rem;
  }
  #tarifaDetails .border-secondary {
    border-color:lightgray!important;
  }
</style>
