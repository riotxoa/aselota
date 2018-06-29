<template>
  <div class="container-fluid">
    <b-row>

      <b-col class="col-sm-6 float-left my-1 mb-3">
        <b-form-group v-if="filter" horizontal label="Filtro" class="mb-0">
          <b-input-group>
            <b-form-input v-model="filter" placeholder="Texto de búsqueda" />
            <b-input-group-append>
              <b-btn :disabled="!filter" @click="filter = ''" title="Limpiar filtro">Limpiar</b-btn>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
      </b-col>

      <b-col class="col-sm-6 text-right float-right my-1 mb-3">
        <b-btn variant="default" class="mb-0" size="sm" title="Crear Contrato" @click="showContratoForm(0)">Nuevo Contrato</b-btn>
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
          <b-button v-if="remove" size="sm" variant="danger" @click.stop="onClickDelete(row.item.id, row.item.fecha_ini, row.item.fecha_fin)" title="Eliminar">
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

      <template v-if="this.display" slot="row-details" slot-scope="row">
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
        <b-form-group horizontal label="NºLíneas" class="mb-0">
          <b-form-select :options="pageOptions" v-model="perPage" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-modal v-if="remove" ref="modalDelete" title="BORRAR Contrato" hide-footer>
      <div class="modal-body">
        <p>Se va a borrar el contrato de <strong id="deleteAlias"></strong>. ¿Desea continuar?</p>
      </div>
      <div class="modal-footer">
        <b-btn variant="danger" @click="removeItem">Borrar</b-btn>
        <b-btn @click="hideModalDelete">Cancelar</b-btn>
      </div>
    </b-modal>

    <b-modal id="contratoForm" ref="modalEdit" :title="formTitle" size="lg" hide-footer lazy>
      <contrato-pelotari :pelotari-id="pelotariId" :on-cancel="cancelContratoForm" :get-contrato-row="getContratoRow" :is-new-contrato="isNewContrato" :format-amount="formatRowAmount"></contrato-pelotari>
    </b-modal>

  </div>
</template>

<script>
  import moment from 'moment';

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
      props: ['pelotariId'],
      data () {
        return {
          create: true,
          remove: true,
          update: true,
          display: false,
          filter: false,
          sortBy: 'fecha_ini',
          sortDesc: true,
          fields: [
            { key: 'fecha_ini', label: '<span title="Fecha de Inicio">F. Inicio</span>', formatter: 'formatDate', sortable: true },
            { key: 'fecha_fin', label: '<span title="Fecha de Finalización">F. Fin</span>', formatter: 'formatDate', sortable: true },
            { key: 'dieta_mes', label: '<span title="Dieta básica mensual">D. Mensual</span>', formatter: 'formatAmount', class: 'text-right', variant: 'success', sortable: false },
            { key: 'dieta_partido', label: '<span title="Dieta por partido jugado">D. Partido</span>', formatter: 'formatAmount', class: 'text-right', variant: 'success', sortable: false },
            { key: 'prima_partido', label: '<span title="Prima por partido jugado">Pr. Partido</span>', formatter: 'formatAmount', class: 'text-right', variant: 'warning', sortable: false },
            { key: 'prima_estelar', label: '<span title="Prima por partido estelar jugado">Pr. Estelar</span>', formatter: 'formatAmount', class: 'text-right', variant: 'warning', sortable: false },
            { key: 'prima_manomanista', label: '<span title="Prima por Campeón de manomanista">Pr. Cpto.Mano</span>', formatter: 'formatAmount', class: 'text-right', variant: 'warning', sortable: false },
            { key: 'garantia', label: '<span title="Partidos garantía">Garantía</span>', class: 'text-right', sortable: false },
            { key: 'garantia_disp', label: '<span title="Garantía según disponibilidad">Garantía s/disp.</span>', class: 'text-right', sortable: false },
            { key: 'actions', label: 'Acciones', sortable: false },
          ],
          items: [],
          totalRows: 0,
          perPage: 10,
          currentPage: 1,
          pageOptions: [ 10, 25, 50 ],
          filter: null,
          deleteId: null,
          formTitle: '',
          rowContrato: null,
          newContrato: true,
          cancelContratoForm: () => { this.hideContratoForm(); },
          getContratoRow: () => { return this.rowContrato; },
          isNewContrato: () => { return this.newContrato; },
          formatRowAmount: (amount) => { return this.formatAmount(amount); },
        }
      },
      created() {
        this.fetchContratos();
      },
      methods: {
        fetchContratos() {
          let uri = '/www/contratos';
          this.axios.get(uri, {
              params: {
                pelotari_id: this.pelotariId
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
          this.rowContrato = item;
          this.showContratoForm(item.id);
        },
        removeItem () {
          let uri = '/www/contratos/' + this.deleteId;
          console.log("BORRAR CONTRATO: " + uri);
          this.axios.delete(uri)
            .then((response) => {
              this.deleteId = null;
              this.$refs.modalDelete.hide();
              this.fetchContratos();
              showSnackbar("Contrato BORRADO");
            })
            .catch((error) => {
              console.log("[removeItem] error: " + error);
              this.deleteId = null;
              this.$refs.modalDelete.hide();
              showSnackbar("ERROR al borrar");
            });
        },
        onClickDelete (id, fecha_ini, fecha_fin) {
          this.deleteId = id;
          jQuery('#deleteAlias').html(this.formatDate(fecha_ini) + " al " + this.formatDate(fecha_fin));
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
        showContratoForm ($id = 0) {
          if($id) {
            this.formTitle = 'Editar Contrato';
            this.newContrato = false;
            this.$refs.modalEdit.show();
          } else {
            this.formTitle = 'Nuevo Contrato';
            this.newContrato = true;
            this.$refs.modalEdit.show();
          }
        },
        hideContratoForm () {
          this.$refs.modalEdit.hide();
          this.fetchContratos();
        }
      }
  }
</script>
