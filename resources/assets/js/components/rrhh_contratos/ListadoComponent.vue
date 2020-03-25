<template>
  <div id="listado-contratos" class="container-fluid">
    <b-row>

      <b-col class="col-sm-6 float-left my-1 mb-3"></b-col>

      <b-col class="col-sm-6 text-right float-right my-1 mb-3">
        <b-btn variant="default" class="mb-0" size="sm" title="Crear Contrato" @click="showContratoHeaderForm(0)">Nuevo Contrato</b-btn>
      </b-col>

    </b-row>

    <div role="tablist">
      <b-card no-body class="mb-1" v-for="contrato in contratos" :key="contrato.id">
        <b-card-header header-tag="header" class="p-0" role="tab">
          <b-row class="m-0">
            <div class="col-sm-2 p-0" style="max-width:115px;">
              <b-btn block href="#" v-b-toggle="'#contrato-' + contrato.id" variant="secondary"><span class="icon voyager-plus float-left" style="font-size:18px;"></span>Tramos</b-btn>
            </div>
            <div class="col-sm-2 pt-2 text-center" title="Descripción de contrato" style="font-size:13px;">
              <strong>{{ contrato.name }}</strong>
            </div>
            <div class="col-sm-4 pt-2 text-left" title="Periodo de contrato" style="font-size:13px;">
              <span class="icon voyager-calendar mr-1"></span>{{ contrato.fecha_ini | formatDate }} - {{ contrato.fecha_fin | formatDate }}
            </div>
            <div class="col-sm-4 p-0 text-right" style="right:0;position:absolute;">
              <b-button-group>
                <b-button v-if="contrato.file" variant="success" @click.stop="onClickDownloadContrato(contrato.id)" title="Descargar contrato deportivo">
                  <span class="icon voyager-documentation"></span>
                </b-button>
                <b-button v-if="contrato.file_derechos" variant="secondary" @click.stop="onClickDownloadContratoDerechos(contrato.id)" title="Descargar contrato derechos de imagen">
                  <span class="icon voyager-polaroid"></span>
                </b-button>
                <b-button v-if="updateHeader" variant="primary" @click.stop="onClickEditHeader(contrato)" title="Editar" class="ml-2">
                  <span class="icon voyager-edit"></span>
                </b-button>
                <b-button v-if="removeHeader" variant="danger" @click.stop="onClickDeleteHeader(contrato.id, contrato.fecha_ini, contrato.fecha_fin)" title="Eliminar">
                  <span class="icon voyager-trash"></span>
                </b-button>
              </b-button-group>
            </div>
          </b-row>
        </b-card-header>
        <b-collapse :id="'#contrato-' + contrato.id" accordion="my-accordion" role="tabpanel">

          <b-card-body>
            <!-- TRAMOS -->
            <b-row>
              <b-col class="col-sm-6">
                <h5 class="font-weight-bold pt-1">Tramos</h5>
              </b-col>
              <b-col class="col-sm-6 text-right float-right my-1 mb-3">
                <b-btn variant="default" class="mb-0" size="sm" title="Crear Tramo" @click="showContratoTramoForm(contrato, 0)">Nuevo Tramo</b-btn>
              </b-col>
            </b-row>

            <b-table striped hover small responsive
              :items="contrato.tramos"
              :fields="fields_tramo">
              <template slot="actions" slot-scope="row">
                <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
                <b-button-group>
                  <b-button v-if="removeTramo" size="sm" variant="danger" @click.stop="onClickDeleteTramo(row.item.id, row.item.fecha_ini, row.item.fecha_fin)" title="Eliminar">
                    <span class="icon voyager-trash"></span>
                  </b-button>
                  <b-button v-if="updateTramo" size="sm" variant="primary" @click.stop="onClickEditTramo(contrato, row.item)" title="Editar">
                    <span class="icon voyager-edit"></span>
                  </b-button>
                  <b-button v-if="displayTramo" size="sm" variant="secondary" @click.stop="row.toggleDetails" title="Mostrar/Ocultar Detalle">
                    <span class="icon" v-bind:class="{ 'voyager-x': row.detailsShowing, 'voyager-eye': !row.detailsShowing }"></span>
                  </b-button>
                </b-button-group>
              </template>
            </b-table>

            <!-- COSTES COMERCIALES -->
            <hr/>
            <b-row>
              <b-col class="col-sm-6">
                <h5 class="font-weight-bold pt-1">Costes Comerciales</h5>
              </b-col>
              <b-col class="col-sm-6 text-right float-right my-1 mb-3">
                <b-btn variant="default" class="mb-0" size="sm" title="Crear Tramo" @click="showContratoComercialForm(contrato, 0)">Nuevo Periodo</b-btn>
              </b-col>
            </b-row>

            <b-table striped hover small responsive
              :items="contrato.comerciales"
              :fields="fields_comercial">
              <template slot="actions" slot-scope="row">
                <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
                <b-button-group>
                  <b-button v-if="removeComercial" size="sm" variant="danger" @click.stop="onClickDeleteComercial(row.item.id, row.item.fecha_ini, row.item.fecha_fin)" title="Eliminar">
                    <span class="icon voyager-trash"></span>
                  </b-button>
                  <b-button v-if="updateComercial" size="sm" variant="primary" @click.stop="onClickEditComercial(contrato, row.item)" title="Editar">
                    <span class="icon voyager-edit"></span>
                  </b-button>
                  <b-button v-if="displayComercial" size="sm" variant="secondary" @click.stop="row.toggleDetails" title="Mostrar/Ocultar Detalle">
                    <span class="icon" v-bind:class="{ 'voyager-x': row.detailsShowing, 'voyager-eye': !row.detailsShowing }"></span>
                  </b-button>
                </b-button-group>
              </template>
            </b-table>

          </b-card-body>

        </b-collapse>
      </b-card>
    </div>

    </b-row>

    <b-modal v-if="removeHeader" ref="modalDeleteHeader" title="BORRAR Contrato" hide-footer>
      <div class="modal-body">
        <p>Se va a borrar un contrato de <strong id="deleteContratoAlias"></strong>¿Desea continuar?</p>
      </div>
      <div class="modal-footer">
        <b-btn variant="danger" @click="removeItemHeader">Borrar</b-btn>
        <b-btn @click="hideModalDeleteHeader">Cancelar</b-btn>
      </div>
    </b-modal>

    <b-modal id="headerContratoForm" ref="modalEditHeader" :title="formHeaderTitle" size="lg" hide-footer lazy>
      <contrato-header-pelotari :pelotari-id="pelotariId" :pelotari-alias="pelotariAlias" :on-cancel="cancelHeaderForm" :get-header-row="getHeaderRow" :is-new-header="isNewHeader" :format-amount="formatRowAmount"></contrato-header-pelotari>
    </b-modal>

    <b-modal v-if="removeTramo" ref="modalDeleteTramo" title="BORRAR Tramo de Contrato" hide-footer>
      <div class="modal-body">
        <p>Se va a borrar un tramo de contrato de <strong id="deleteTramoAlias"></strong>¿Desea continuar?</p>
      </div>
      <div class="modal-footer">
        <b-btn variant="danger" @click="removeItemTramo">Borrar</b-btn>
        <b-btn @click="hideModalDeleteTramo">Cancelar</b-btn>
      </div>
    </b-modal>

    <b-modal id="tramoContratoForm" ref="modalEditTramo" :title="formTramoTitle" size="lg" hide-footer lazy>
      <contrato-tramo-pelotari :pelotari-id="pelotariId" :pelotari-alias="pelotariAlias" :on-cancel="cancelTramoForm" :get-header-row="getHeaderRow" :get-tramo-row="getTramoRow" :is-new-tramo="isNewTramo" :format-amount="formatRowAmount"></contrato-tramo-pelotari>
    </b-modal>

    <b-modal v-if="removeComercial" ref="modalDeleteComercial" title="BORRAR Periodo Comercial de Contrato" hide-footer>
      <div class="modal-body">
        <p>Se va a borrar un Periodo Comercial de <strong id="deleteComercialAlias"></strong>¿Desea continuar?</p>
      </div>
      <div class="modal-footer">
        <b-btn variant="danger" @click="removeItemComercial">Borrar</b-btn>
        <b-btn @click="hideModalDeleteComercial">Cancelar</b-btn>
      </div>
    </b-modal>

    <b-modal id="comercialContratoForm" ref="modalEditComercial" :title="formComercialTitle" size="md" hide-footer lazy>
      <periodo-comercial-pelotari :pelotari-id="pelotariId" :pelotari-alias="pelotariAlias" :on-cancel="cancelComercialForm" :get-header-row="getHeaderRow" :get-comercial-row="getComercialRow" :is-new-comercial="isNewComercial" :format-amount="formatRowAmount"></periodo-comercial-pelotari>
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
      props: ['pelotariId', 'pelotariAlias'],
      data () {
        return {
          create: true,
          removeHeader: true,
          removeTramo: true,
          removeComercial: true,
          updateHeader: true,
          updateTramo: true,
          updateComercial: true,
          displayTramo: false,
          displayComercial: false,
          sortBy: 'fecha_ini',
          sortDesc: true,
          fields_tramo: [
            { key: 'fecha_ini', label: '<span title="Fecha de Inicio">F. Inicio</span>', formatter: this.formatDate, sortable: true },
            { key: 'fecha_fin', label: '<span title="Fecha de Finalización">F. Fin</span>', formatter: this.formatDate, sortable: true },
            { key: 'ficha', label: '<span title="Ficha">Ficha</span>', formatter: this.formatAmount, class: 'text-right', variant: 'custom-ficha-sueldo', sortable: false },
            { key: 'sueldo', label: '<span title="Sueldo">Sueldo</span>', formatter: this.formatAmount, class: 'text-right', variant: 'custom-ficha-sueldo', sortable: false },
            { key: 'dieta_mes', label: '<span title="Dieta básica mensual">D. Mes</span>', formatter: this.formatAmount, class: 'text-right', variant: 'success', sortable: false },
            { key: 'dieta_partido', label: '<span title="Dieta por partido jugado">D. Partido</span>', formatter: this.formatAmount, class: 'text-right', variant: 'success', sortable: false },
            { key: 'prima_partido', label: '<span title="Prima por partido jugado">Pr. Partido</span>', formatter: this.formatAmount, class: 'text-right', variant: 'warning', sortable: false },
            { key: 'prima_estelar', label: '<span title="Prima por partido estelar jugado">Pr. Estelar</span>', formatter: this.formatAmount, class: 'text-right', variant: 'warning', sortable: false },
            { key: 'd_imagen', label: '<span title="Dchos.Imagen">Dchos.Imagen</span>', formatter: this.formatAmount, class: 'text-right', variant: 'custom-imagen', sortable: false },
            { key: 'garantia', label: '<span title="Partidos garantía">Garantía</span>', class: 'text-right', variant: 'custom-garantia', sortable: false },
            { key: 'formacion', label: '<span title="Periodo de Formación">Formación</span>', formatter: this.formatCheckbox, class: 'text-center', sortable: true },
            { key: 'actions', label: 'Acciones', sortable: false, class: 'text-center' },
          ],
          fields_comercial: [
            { key: 'fecha_ini', label: '<span title="Fecha de Inicio">F. Inicio</span>', formatter: this.formatDate, sortable: true },
            { key: 'fecha_fin', label: '<span title="Fecha de Finalización">F. Fin</span>', formatter: this.formatDate, sortable: true },
            { key: 'coste', label: '<span title="Coste">Coste</span>', formatter: this.formatAmount, class: 'text-right', sortable: false },
            { key: 'actions', label: 'Acciones', sortable: false, class: 'text-center' },
          ],
          contratos: [],
          totalRows: 0,
          deleteIdHeader: null,
          deleteIdTramo: null,
          deleteIdComercial: null,
          formHeaderTitle: '',
          formTramoTitle: '',
          formComercialTitle: '',
          rowHeader: null,
          rowTramo: null,
          rowComercial: null,
          newHeader: true,
          newTramo: true,
          newComercial: true,
          cancelHeaderForm: () => { this.hideHeaderForm(); },
          cancelTramoForm: () => { this.hideTramoForm(); },
          cancelComercialForm: () => { this.hideComercialForm(); },
          getHeaderRow: () => { return this.rowHeader; },
          getTramoRow: () => { return this.rowTramo; },
          getComercialRow: () => { return this.rowComercial; },
          isNewHeader: () => { return this.newHeader; },
          isNewTramo: () => { return this.newTramo; },
          isNewComercial: () => { return this.newComercial; },
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
            this.contratos = JSON.parse(stringified);
            this.totalRows = this.contratos.length;
          });
        },
        onClickDownloadContrato (id) {
          window.open('/www/contratos/header/' + id + '/download');
        },
        onClickDownloadContratoDerechos (id) {
          window.open('/www/contratos/header/' + id + '/derechos/download');
        },
        onClickEditHeader (header) {
          this.rowHeader = header;
          this.showContratoHeaderForm(header.id);
        },
        onClickEditTramo (header, tramo) {
          this.rowTramo = tramo;
          this.rowHeader = header;

          this.showContratoTramoForm(this.rowHeader, tramo.id);
        },
        onClickEditComercial (header, comercial) {
          this.rowComercial = comercial;
          this.rowHeader = header;

          this.showContratoComercialForm(this.rowHeader, comercial.id);
        },
        removeItemHeader () {
          let uri = '/www/contratos/header/' + this.deleteIdHeader;

          this.axios.delete(uri)
            .then((response) => {
              this.deleteIdHeader = null;
              this.$refs.modalDeleteHeader.hide();
              this.fetchContratos();
              showSnackbar("Contrato BORRADO");
            })
            .catch((error) => {
              console.log("[removeHeader] error: " + error);
              this.deleteIdHeader = null;
              this.$refs.modalDeleteHeader.hide();
              showSnackbar("ERROR al borrar contrato");
            });
        },
        removeItemTramo () {
          let uri = '/www/contratos/' + this.deleteIdTramo;

          this.axios.delete(uri)
            .then((response) => {
              this.deleteIdTramo = null;
              this.$refs.modalDeleteTramo.hide();
              this.fetchContratos();
              showSnackbar("Tramo de Contrato BORRADO");
            })
            .catch((error) => {
              console.log("[removeTramo] error: " + error);
              this.deleteIdTramo = null;
              this.$refs.modalDeleteTramo.hide();
              showSnackbar("ERROR al borrar tramo");
            });
        },
        removeItemComercial () {
          let uri = '/www/contratos/comercial/' + this.deleteIdComercial;

          this.axios.delete(uri)
            .then((response) => {
              this.deleteIdComercial = null;
              this.$refs.modalDeleteComercial.hide();
              this.fetchContratos();
              showSnackbar("Periodo Comercial BORRADO");
            })
            .catch((error) => {
              console.log("[removeComercial] error: " + error);
              this.deleteIdComercial = null;
              this.$refs.modalDeleteComercial.hide();
              showSnackbar("ERROR al borrar Periodo Comercial");
            });
        },
        onClickDeleteHeader (id, fecha_ini, fecha_fin) {
          this.deleteIdHeader = id;

          var msg = " \
            <div class='px-5 py-2'> \
              <p class='mb-0'><strong>Pelotari:</strong> " + this.pelotariAlias + "</p> \
              <p class='mb-0'><strong>Fecha inicio:</strong> " + this.formatDate(fecha_ini) + " - <strong>Fecha fin:</strong> " + this.formatDate(fecha_fin) + "</p> \
            </div>";

          jQuery('#deleteContratoAlias').html(msg);

          this.$refs.modalDeleteHeader.show();
        },
        hideModalDeleteHeader() {
          this.deleteIdHeader = null;
          this.$refs.modalDeleteHeader.hide();
        },
        onClickDeleteTramo (id, fecha_ini, fecha_fin) {
          this.deleteIdTramo = id;

          var msg = " \
            <div class='px-5 py-2'> \
              <p class='mb-0'><strong>Pelotari:</strong> " + this.pelotariAlias + "</p> \
              <p class='mb-0'><strong>Fecha inicio:</strong> " + this.formatDate(fecha_ini) + " - <strong>Fecha fin:</strong> " + this.formatDate(fecha_fin) + "</p> \
            </div>";

          jQuery('#deleteTramoAlias').html(msg);

          this.$refs.modalDeleteTramo.show();
        },
        hideModalDeleteTramo() {
          this.deleteIdTramo = null;
          this.$refs.modalDeleteTramo.hide();
        },
        onClickDeleteComercial (id, fecha_ini, fecha_fin) {
          this.deleteIdComercial = id;

          var msg = " \
            <div class='px-5 py-2'> \
              <p class='mb-0'><strong>Pelotari:</strong> " + this.pelotariAlias + "</p> \
              <p class='mb-0'><strong>Fecha inicio:</strong> " + this.formatDate(fecha_ini) + " - <strong>Fecha fin:</strong> " + this.formatDate(fecha_fin) + "</p> \
            </div>";

          jQuery('#deleteComercialAlias').html(msg);

          this.$refs.modalDeleteComercial.show();
        },
        hideModalDeleteComercial() {
          this.deleteIdComercial = null;
          this.$refs.modalDeleteComercial.hide();
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
        formatCheckbox (checked) {
          if(checked)
            return "Sí";
          else {
            return "-";
          }
        },
        showContratoHeaderForm ($id = 0) {
          if($id) {
            this.formHeaderTitle = 'Editar Contrato';
            this.newHeader = false;
            this.$refs.modalEditHeader.show();
          } else {
            this.formHeaderTitle = 'Nuevo Contrato';
            this.newHeader = true;
            this.$refs.modalEditHeader.show();
          }
        },
        hideHeaderForm () {
          this.$refs.modalEditHeader.hide();
          this.fetchContratos();
        },
        showContratoTramoForm ($header, $tramo_id = 0) {
          this.rowHeader = $header;
          if($tramo_id) {
            this.formTramoTitle = 'Editar Tramo';
            this.newTramo = false;
            this.$refs.modalEditTramo.show();
          } else {
            this.formTramoTitle = 'Nuevo Tramo';
            this.newTramo = true;
            this.$refs.modalEditTramo.show();
          }
        },
        hideTramoForm () {
          this.$refs.modalEditTramo.hide();
          this.fetchContratos();
        },
        showContratoComercialForm ($header, $comercial_id = 0) {
          this.rowHeader = $header;
          if($comercial_id) {
            this.formComercialTitle = 'Editar Periodo';
            this.newComercial = false;
            this.$refs.modalEditComercial.show();
          } else {
            this.formComercialTitle = 'Nuevo Periodo';
            this.newComercial = true;
            this.$refs.modalEditComercial.show();
          }
        },
        hideComercialForm () {
          this.$refs.modalEditComercial.hide();
          this.fetchContratos();
        }
      }
  }
</script>

<style>
  .table-custom-ficha-sueldo {
    background-color:#ffca9b;
  }
  .table-custom-garantia {
    background-color:#d5e8ff;
  }
  .table-custom-imagen {
    background-color:#fadde3;
  }
  #listado-contratos .b-table th,
  #listado-contratos .b-table td {
    border-color:#e5e5e5;
  }
  #listado-contratos .b-table th.table-success,
  #listado-contratos .b-table th.table-warning {
    border-bottom-color:white;
  }
  #listado-contratos .b-table td.table-success,
  #listado-contratos .b-table td.table-warning {
    border-color:white;
  }
  #listado-contratos .card-header a.btn:not(.collapsed) {
    background:#28a745;
    border-color:#28a745;
  }
  #listado-contratos .card-header a.btn:not(.collapsed) .icon.voyager-plus:before {
    content: "\70";
    position:relative;
    top:4px;
  }
  #listado-contratos .card-header a.btn:focus {
    -webkit-box-shadow:none;
    box-shadow:none;
  }
</style>
