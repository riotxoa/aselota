<template>
  <div class="festival-facturacion">
    <b-row class="align-items-end">
      <b-col cols="12" md="8">
        <strong class="d-inline-block" style="width:120px;">Total facturas:</strong>
        <input class="ml-2 text-right" v-bind:class="{ 'text-green' : total_facturaciones == _costes.importe_venta, 'text-blue' : total_facturaciones > _costes.importe_venta, 'text-red' : total_facturaciones < _costes.importe_venta }" type="number" v-model="total_facturaciones.toFixed(2)" disabled />
        <br>
        <strong class="d-inline-block" style="width:120px;">Total a facturar:</strong>
        <input class="ml-2 text-right" v-bind:class="{ 'text-green' : total_facturaciones == _costes.importe_venta }"  type="number" v-model="_costes.importe_venta" disabled />
      </b-col>
      <b-col cols="12" md="4" class="text-center text-md-right">
        <b-button size="sm" variant="danger" @click.stop="onClickAddFactura()" title="Nueva Factura" :disabled="edit">
          Nueva Factura
        </b-button>
      </b-col>
    </b-row>
    <b-table striped hover small responsive
      class="mt-3"
      :items="facturaciones"
      :fields="fields">

      <template slot="fecha" slot-scope="row">
        <div v-if="row.item.fecha">{{ formatDateES(row.item.fecha) }}</div>
      </template>
      <template slot="importe" slot-scope="row">
        {{ parseFloat(row.item.importe).toFixed(2) }}
      </template>
      <template slot="pagado" slot-scope="row">
        <i v-if="row.item.pagado" class="far fa-check-circle" style="color:green;font-size:18px;" title="PAGADA"></i>
        <i v-else class="fas fa-times-circle" style="color:red;font-size:18px;" title="NO PAGADA"></i>
      </template>
      <template slot="explotacion_id" slot-scope="row">
        {{ getExplotacionDesc(row.item.explotacion_id) }}
      </template>
      <template slot="file_factura" slot-scope="row">
        <b-button v-if="facturaciones[row.index].file_factura" size="sm" variant="primary" class="mt-0 font-weight-bold" v-on:click="downloadFactura(row.index)" title="Descargar documento factura">
          <span class="icon voyager-download"></span>
        </b-button>
      </template>

      <template slot="actions" slot-scope="row">
        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
        <b-button-group v-if="edit && edit_index == row.index">
          <b-button size="sm" variant="success" @click.stop="onClickSaveEdit(row)" title="Guardar">
            <i class="far fa-save"></i>
          </b-button>
          <b-button size="sm" variant="secondary" @click.stop="onClickCancelEdit(row)" title="Cancelar">
            <i class="fas fa-times"></i>
          </b-button>
        </b-button-group>
        <b-button-group v-else>
          <b-button size="sm" variant="danger" @click.stop="onClickDeleteFactura(row.item.id)" title="Eliminar" :disabled="edit">
            <span class="icon voyager-trash"></span>
          </b-button>
          <b-button size="sm" variant="primary" @click.stop="onClickEditFactura(row)" title="Editar" :disabled="edit">
            <span class="icon voyager-edit"></span>
          </b-button>
          <b-button size="sm" variant="secondary" @click.stop="row.toggleDetails" title="Mostrar/Ocultar Detalle" :disabled="edit">
            <span class="icon" v-bind:class="{ 'voyager-x': row.detailsShowing, 'voyager-eye': !row.detailsShowing }"></span>
          </b-button>
        </b-button-group>
      </template>

      <template slot="row-details" slot-scope="row">
          <b-row class="mx-0 px-0 py-3">

            <div class="col-md-1">&nbsp;</div>

            <div class="col-md-5">
              <div class="card mb-3">
                <div class="card-body">
                  <b-row>
                    <label class="col-md-6">Fecha:</label>
                    <b-form-input id="fact_fecha"
                                        :disabled="!edit"
                                  class="col-md-6 text-right"
                                  type="date"
                                  v-model="facturaciones[row.index].fecha">
                    </b-form-input>
                  </b-row>
                  <b-row>
                    <label class="col-md-6">Importe:</label>
                    <b-form-input id="fact_importe"
                                        :disabled="!edit"
                                  class="col-md-6 text-right"
                                  type="number"
                                  maxlength="8"
                                  placeholder="0.00"
                                  v-model="facturaciones[row.index].importe"
                                  v-on:focus.native="$event.target.select()"
                                  v-on:blur.native="formatCurrency">
                    </b-form-input>
                  </b-row>
                  <b-row>
                    <label class="col-md-6">Explotación:</label>
                    <b-form-select id="fact_explotacion"
                                   :disabled="!edit"
                                   class="col-md-6"
                                   :options="explotaciones"
                                   v-model="facturaciones[row.index].explotacion_id">
                    </b-form-select>
                  </b-row>
                  <b-row>
                    <label class="col-md-6">Enviar factura a:</label>
                    <b-form-select id="fact_enviar_a"
                                        :disabled="!edit"
                                   class="col-md-6"
                                   :options="envio_facturas"
                                   v-model="facturaciones[row.index].enviar_id">
                    </b-form-select>
                  </b-row>
                  <b-form-group label="Observaciones:"
                                label-for="fact_observaciones">
                    <b-form-textarea id="fact_observaciones"
                                     :disabled="!edit"
                                     :rows="3"
                                     :max-rows="6"
                                     v-model="facturaciones[row.index].observaciones">
                    </b-form-textarea>
                  </b-form-group>
                </div>
              </div>
            </div>

            <div class="col-md-5 position-relative">
              <div class="card mb-3">
                <div class="card-body">
                  <b-row style="border-bottom:1px solid lightgray; margin-bottom:1rem;">
                    <b-form-group label="Documento Factura"
                                  class="col-12">
                      <b-row>
                        <b-form-file class="mt-0 col-sm-10"
                                     v-on:change="onDocFacturaChange"
                                     accept=".doc, .docx, .pdf, .rtf"
                                     plain>
                        </b-form-file>
                      </b-row>
                    </b-form-group>
                    <b-form-group v-if="edit && facturaciones[row.index].file_factura"
                                  class="col-12"
                                  style="color:transparent;">
                      <b-button block size="md" variant="success" class="mt-0 font-weight-bold" v-on:click="downloadFactura(row.index)" title="Descargar documento factura"><span class="icon voyager-download mr-2"></span>{{ (facturaciones[row.index].file_name ? facturaciones[row.index].file_name : getFacturaFileName(row.index)) }}</b-button>
                    </b-form-group>
                  </b-row>
                  <b-row>
                    <label class="col-md-6">Pagado:</label>
                    <b-form-radio-group class="col-md-6"
                                        :disabled="!edit"
                                        v-model="facturaciones[row.index].pagado"
                                        :options="[{ text: 'No', value: 0}, {text: 'Sí', value: 1}]"
                                        name="radioInline">
                    </b-form-radio-group>
                  </b-row>
                  <b-form-group label="Seguimiento:"
                                label-for="fact_seguimiento">
                    <b-form-textarea id="fact_seguimiento"
                                        :disabled="!edit"
                                     :rows="3"
                                     :max-rows="6"
                                     v-model="facturaciones[row.index].seguimiento">
                    </b-form-textarea>
                  </b-form-group>
                </div>
              </div>

            </div>
          </b-row>

      </template>
    </b-table>
  </div>
</template>

<script>
  import { mapState } from 'vuex';
  import { store } from '../store/store';
  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';
  import _ from 'lodash';

  export default {
    mixins: [APIGetters, Utils],
    data () {
      return {
        edit: false,
        edit_index: null,
        explotaciones: [],
        facturacion: {
          fpago_id: null,
          fecha: null,
          importe: 0,
          enviar_id: null,
          observaciones: '',
          pagado: 0,
          seguimiento: '',
          explotacion_id: null,
          file_factura: null,
          file_name: '',
        },
        facturaciones: [],
        fields: [
          { key: 'fecha', label: '<span title="Fecha de Factura">Fecha</span>', sortable: true },
          { key: 'importe', label: '<span title="Importe">Importe</span>', class: 'text-right', sortable: true },
          { key: 'pagado', label: '<span title="Pagado">Pagado</span>', class: 'text-center', sortable: true },
          { key: 'explotacion_id', label: '<span title="Explotación">Explotación</span>', sortable: true },
          { key: 'file_factura', label: '<span title="Documento Factura">Factura</span>', class: 'text-center', sortable: false },
          { key: 'actions', label: 'Acciones', sortable: false, class: 'text-center' },
        ],
        total_facturaciones: 0,
      }
    },
    computed: mapState({
      _costes: 'costes',
    }),
    created: function () {
      console.log("FestivalFichaFacturacionComponent created");
      this.getEnvioFacturas();
      this.loadExplotaciones();
      this.loadFacturas();
    },
    methods: {
      cancelEdit(row) {
        this.edit = false;
        this.edit_index = null;
        row.toggleDetails();
      },
      deleteFactura(id) {
        this.$store.dispatch('delFacturacion', id).then( () => {
          this.loadFacturas();
          this.showSnackbar("Facturación ELIMINADA");
        })
      },
      downloadFactura (index) {
        window.open('/www/festival-facturacion/' + this.facturaciones[index].id + '/download');
      },
      formatCurrency(ev) {
        let value = ev.target.value;
        ev.target.value = parseFloat(value).toFixed(2);
      },
      getExplotacionDesc( explotacion_id ) {
        if( explotacion_id ) {
          var explotacion = _.find(this.explotaciones, { 'value': explotacion_id });

          return explotacion.text;
        }
      },
      getFacturaFileName( index ) {
        return this.facturaciones[index].file_factura.replace('/storage/facturas/', '')
      },
      loadExplotaciones() {
        this.$store.dispatch('loadExplotaciones').then( (res) => {
          this.explotaciones = res;
        });
      },
      loadFacturas() {
        this.total_facturaciones = 0;
        this.$store.dispatch('loadFacturacion').then( facturaciones => {
            this.facturaciones = facturaciones;
            this.facturaciones.map( (val) => {
              this.total_facturaciones += parseFloat(val.importe);
            })
        });
      },
      onClickAddFactura() {
        this.resetFacturacion();
        this.facturaciones.splice(0,0,this.facturacion);
        this.edit = true;
        this.edit_index = 0;
        this.facturaciones[0]._showDetails = true;
      },
      onClickCancelEdit(row) {
        this.cancelEdit(row);
      },
      onClickDeleteFactura( id ) {
        if( confirm("Va a proceder al borrado de una Factura. ¿Desea continuar?") ) {
          this.deleteFactura(id);
        }
      },
      onClickEditFactura( row ) {
        this.edit = true;
        this.edit_index = row.index;
        row.toggleDetails();
      },
      onClickSaveEdit(row) {
        this.$store.dispatch('addFacturacion', this.facturaciones[row.index])
          .then((response) => {
            this.cancelEdit(row);
            this.loadFacturas();
            this.showSnackbar("Facturación GUARDADA");
          })
          .catch((error) => {
            console.log(error);
            this.showSnackbar("Se ha producido un ERROR al guardar la FACTURACIÓN");
          });
      },
      onDocFacturaChange (e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
            return;
        this.createFileFactura(files[0]);
      },
      createFileFactura (file) {
          let reader = new FileReader();
          let vm = this;
          reader.onload = (e) => {
            vm.facturaciones[this.edit_index].file_factura = file;
            vm.facturaciones[this.edit_index].file_name = file.name;
          };
          reader.readAsDataURL(file);
      },
      resetFacturacion() {
        this.facturacion = {
          fpago_id: null,
          fecha: null,
          importe: 0,
          enviar_id: null,
          observaciones: '',
          pagado: 0,
          seguimiento: '',
          explotacion_id: null,
          file_factura: null,
          file_name: '',
        }
      },
    }
  }
</script>

<style>
  .festival-facturacion .card {
    border-color:#707f8f;
  }
  .festival-facturacion label {
    font-weight:bold;
    margin-right:-15px;
  }
  .festival-facturacion input {
    height:27px;
  }
  .festival-facturacion input[type="text"],
  .festival-facturacion input[type="number"] {
    padding:0 5px;
  }
  .festival-facturacion input.text-blue {
    border:2px solid blue;
    color:blue;
    margin-bottom:2px;
  }
  .festival-facturacion input.text-green {
    border:2px solid green;
    color:green;
    margin-bottom:2px;
  }
  .festival-facturacion input.text-red {
    border:2px solid red;
    color:red;
    margin-bottom:2px;
  }
  .festival-facturacion div[role="radiogroup"] .custom-radio:first-child {
    margin-right:35px;
  }
  .festival-facturacion .botonera {
    margin-left:0;
  }
  @media screen and (min-width:768px) {
    .festival-facturacion .botonera {
      bottom:15px;
      right:30px;
      position:absolute;
    }
  }
</style>
