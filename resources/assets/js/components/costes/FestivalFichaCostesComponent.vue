<template>
  <div v-if="show" class="festival-costes">
    <b-form @submit="onSubmit">
      <b-row>

        <div class="col-md-1">&nbsp;</div>

        <div class="col-md-5">
          <div class="card mb-3">
            <div class="card-body p-2">
              <b-row class="m-0 p-0">
                <label class="ml-0 mr-2">Porcentaje Baiko:</label>
                <div v-if="_header.tipo_festival || true">{{ calcularPorcentaje() }}%</div>
              </b-row>
              <b-row class="px-3">
                <b-form-input id="coste_porcentaje"
                              class="col-12 px-0"
                              type="range"
                              min="0"
                              max="100"
                              step="5"
                              @change="updatePorcentaje"
                              v-model="_costes.porcentaje">
                </b-form-input>
              </b-row>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-body">
              <b-row>
                <b-col cols="4" class="text-center">
                  <label>NºAuxiliares:</label>
                  <b-form-input id="num_auxiliares"
                              class="text-right w-50 mx-auto"
                              type="number"
                              max="20"
                              min="1"
                              maxlength="2"
                              placeholder="1"
                              :value="_costes.num_auxiliares"
                              @input="updateCosteAuxiliares()">
                  </b-form-input>
                </b-col>

                <b-col cols="4" class="text-center">
                  <label>NºTaquilleros:</label>
                  <b-form-input id="num_taquilleros"
                                class="text-right w-50 mx-auto"
                                type="number"
                                max="3"
                                min="0"
                                maxlength="2"
                                placeholder="0"
                                :value="_costes.num_taquilleros"
                                @input="updateCosteTaquillera">
                  </b-form-input>
                </b-col>

                <b-col cols="4" class="text-center">
                  <label>Serv.Sanitario:</label><br>
                  <b-form-checkbox id="check_sanidad"
                                class="text-right"
                                style="margin-right:0px !important;"
                                v-model="_costes.sanidad"
                                value="1"
                                unchecked-value="0"
                                @change="updateCosteSanidad">
                  </b-form-checkbox>
                </b-col>
              </b-row>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-body">

              <b-row>
                <label class="col-md-7">
                  <a class="icon voyager-plus align-middle" href="#" v-b-toggle="'#acordeonCostes'" variant="secondary"></a>
                  Coste empresa festival:
                </label>
                <b-form-input id="coste_empresa"
                              class="col-md-5 text-right"
                              type="text"
                              maxlength="8"
                              placeholder="0.00"
                              readonly
                              :value="_coste.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                </b-form-input>

                <div style="clear: both;"></div>

                <b-collapse :id="'#acordeonCostes'" class="ml-5 my-3 pr-3 w-100" accordion="my-accordion" role="tabpanel">
                  <b-row>
                    <label class="col-md-9">Pelotaris:</label>
                    <b-form-input id="coste_pelotaris"
                                  class="col-md-3 text-right"
                                  type="text"
                                  maxlength="8"
                                  readonly
                                  placeholder="0.00"
                                  :value="_coste_pelotaris.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </b-form-input>
                  </b-row>
                  <b-row>
                    <label class="col-md-9">Jueces:</label>
                    <b-form-input id="coste_jueces"
                                  class="col-md-3 text-right"
                                  type="text"
                                  maxlength="8"
                                  readonly
                                  placeholder="0.00"
                                  :value="_coste_jueces.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </b-form-input>
                  </b-row>
                  <b-row>
                    <label class="col-md-9">Cancha:</label>
                    <b-form-input id="coste_cancha"
                                  class="col-md-3 text-right"
                                  type="text"
                                  maxlength="8"
                                  readonly
                                  placeholder="0.00"
                                  :value="_coste_cancha.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </b-form-input>
                  </b-row>
                  <b-row>
                    <label class="col-md-9">Material:</label>
                    <b-form-input id="coste_material"
                                  class="col-md-3 text-right"
                                  type="text"
                                  maxlength="8"
                                  readonly
                                  placeholder="0.00"
                                  :value="_coste_material.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </b-form-input>
                  </b-row>
                  <b-row>
                    <label class="col-md-7">Auxiliares:</label>
                    <b-form-input id="num_auxiliares"
                                class="col-md-2 text-right"
                                type="number"
                                max="20"
                                min="1"
                                maxlength="2"
                                placeholder="1"
                                readonly
                                :value="_costes.num_auxiliares">
                    </b-form-input>
                    <b-form-input id="coste_auxiliares"
                                  class="col-md-3 text-right"
                                  type="text"
                                  maxlength="8"
                                  readonly
                                  placeholder="0.00"
                                  :value="_coste_auxiliares.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </b-form-input>
                  </b-row>
                  <b-row>
                    <label class="col-md-7">Taquillera:</label>
                    <b-form-input id="num_taquilleros"
                                  class="col-md-2 text-right"
                                  type="number"
                                  max="3"
                                  min="0"
                                  maxlength="2"
                                  readonly
                                  placeholder="0"
                                  :value="_costes.num_taquilleros">
                    </b-form-input>
                    <b-form-input id="coste_taquilleros"
                                  class="col-md-3 text-right"
                                  type="text"
                                  maxlength="8"
                                  readonly
                                  placeholder="0"
                                  :value="_coste_taquillera.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </b-form-input>
                  </b-row>
                  <b-row>
                    <label class="col-md-9">Tasa de juego:</label>
                    <b-form-input id="coste_tasa"
                                  class="col-md-3 text-right"
                                  type="text"
                                  maxlength="8"
                                  readonly
                                  placeholder="0.00"
                                  :value="_coste_tasa.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </b-form-input>
                  </b-row>
                  <b-row>
                    <label class="col-md-9">Servicio sanitario:</label>
                    <b-form-input id="coste_sanitario"
                                  class="col-md-3 text-right"
                                  type="text"
                                  maxlength="8"
                                  readonly
                                  placeholder="0.00"
                                  :value="_coste_sanidad.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </b-form-input>
                  </b-row>
                  <b-row>
                    <label class="col-md-7">Televisión:</label>
                    <b-form-input id="coste_tv"
                                  class="col-md-2 text-right"
                                  type="text"
                                  readonly
                                  placeholder="N/A"
                                  :value="_coste_tv + '%'">
                    </b-form-input>
                    <b-form-input id="importe_tv"
                                  class="col-md-3 text-right"
                                  type="text"
                                  maxlength="8"
                                  readonly
                                  placeholder="0"
                                  :value="_importe_tv.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                    </b-form-input>
                  </b-row>
                </b-collapse>

              </b-row>

              <b-row>
                <label class="col-md-7">Importe venta:</label>
                <b-form-input id="coste_importe_venta"
                              class="col-md-5 text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0.00"
                              v-model="_costes.importe_venta"
                              v-on:focus.native="$event.target.select()"
                              :formatter="formatCurrency"
                              @change="updateImporteVenta">
                </b-form-input>
              </b-row>
              <div id="coste_importe_venta_aviso" style="display: none;">
                <p class="col-md-12 text-right" style="color:gray; font-weight:bold;padding-right:0px;">
                  El importe de venta debe ser confirmado por el supervisor
                </p>
                <div class="col-sm-2 p-0 text-right float-right" style="max-width:165px;margin-bottom:20px;">
                  <b-btn block @click="solicitarConfirmacion" variant="primary">Solicitar confirmación</b-btn>
                </div>
                <div style="clear: both;"></div>
              </div>
              <!--
              <b-row>
                <label class="col-md-7">Vendido a:</label>
                <b-form-select id="coste_vendido_a"
                               class="col-md-5"
                               :options="clientes"
                               v-model="_costes.cliente_id">
                </b-form-select>
              </b-row>
               -->
              <b-form-group label="Observaciones:"
                            label-for="coste_texto_libre">
                <b-form-textarea id="coste_texto_libre"
                                 v-model="_costes.cliente_txt"
                                 :rows="3"
                                 :max-rows="6">
                </b-form-textarea>
              </b-form-group>
            </div>
          </div>
          <div v-if="false" class="card mb-3">
            <div class="card-body">
              <b-row>
                <label class="col-md-7">Aportación:</label>
                <b-form-input id="coste_aportacion"
                              class="col-md-5 text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0.00"
                              v-model="_costes.aportacion"
                              v-on:focus.native="$event.target.select()"
                              v-on:blur.native="formatCurrency"
                              @input="updatePrecioTotal">
                </b-form-input>
              </b-row>

              <b-row class="mt-4">
                <div class="col-12">
                  <b-table striped hover small :items="_costes.entradas" :fields="fields">
                    <template slot="actions" slot-scope="row">
                      <b-button size="sm" variant="danger" title="Borrar Entradas" @click.stop="onClickDeleteEntradas(row.item)">
                        <span class="icon voyager-trash"></span>
                      </b-button>
                      <b-button size="sm" variant="primary" title="Editar Entradas" @click.stop="onClickEditEntradas(row.item)">
                        <span class="icon voyager-edit"></span>
                      </b-button>
                    </template>
                  </b-table>
                </div>
                <div class="col-md-7">&nbsp;</div>
                <div class="col-md-5 text-right">
                  <b-btn size="sm" @click="onClickAddEntradas">Añadir Entradas</b-btn>
                </div>
              </b-row>

            </div>
          </div>
          <div v-if="false" class="card mb-3">
            <div class="card-body">
              <b-row>
                <label class="col-md-7">Precio total:</label>
                <b-form-input id="coste_precio_total"
                              class="col-md-5 text-right"
                              type="text"
                              v-model="precio_total"
                              v-if="_costes.precio_total"
                              tabindex="-1"
                              readonly>
                </b-form-input>
              </b-row>
            </div>
          </div>
        </div>

        <div class="col-md-5 position-relative">
          <div class="card mb-3 background-gray">
            <div class="card-body">
              <b-row>
                <label class="col-md-8">Nº espectadores:</label>
                <b-form-input id="coste_num_espectadores"
                              class="col-md-4 text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0.00"
                              v-model="_costes.num_espectadores"
                              v-on:focus.native="$event.target.select()"
                              @input="updateTotal">
                </b-form-input>
              </b-row>
              <b-row>
                <label class="col-md-8">Ingreso en taquilla:</label>
                <b-form-input id="coste_ingreso_taquilla"
                              class="col-md-4 text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0.00"
                              v-model="_costes.ingreso_taquilla"
                              v-on:focus.native="$event.target.select()"
                              v-on:blur.native="formatCurrency"
                              @input="updateTotal">
                </b-form-input>
              </b-row>
              <b-row>
                <label class="col-md-8">Ingreso por ayto.:</label>
                <b-form-input id="coste_ingreso_ayuntamiento"
                              class="col-md-4 text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0.00"
                              v-model="_costes.ingreso_ayto"
                              v-on:focus.native="$event.target.select()"
                              v-on:blur.native="formatCurrency"
                              @input="updateTotal">
                </b-form-input>
              </b-row>
              <b-row>
                <label class="col-md-8">Otros:</label>
                <b-form-input id="coste_otros"
                              class="col-md-4 text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0.00"
                              v-model="_costes.ingreso_otros"
                              v-on:focus.native="$event.target.select()"
                              v-on:blur.native="formatCurrency"
                              @input="updateTotal">
                </b-form-input>
              </b-row>
            </div>
          </div>
          <div class="card mb-3 background-red">
            <div class="card-body">
              <b-form-group label="Total:"
                            label-for="coste_total">
                <b-form-input id="coste_total"
                              class="text-center"
                              type="text"
                              v-if="_costes.total >= 0"
                              :value="_costes.total.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})"
                              readonly
                              tabindex="-1">
                </b-form-input>
              </b-form-group>
            </div>
          </div>

          <b-row class="botonera">
            <b-button variant="default" @click="onReset">Restablecer</b-button>
            <b-button variant="danger" @click="onSubmit" class="ml-1">Guardar</b-button>
          </b-row>

        </div>

        <div class="col-md-1">&nbsp;</div>

      </b-row>
    </b-form>

    <b-modal id="costeEntradasForm" ref="costeEntradasModal" title="Coste Entradas" size="sm" hide-footer lazy>
      <ficha-coste-entradas :festival-id="_costes.festival_id" :on-cancel="cancelCosteEntradasForm" :on-submit="submitCosteEntradasForm" :is-new-coste="isNewCosteEntradas" :data="editCosteEntradas"></ficha-coste-entradas>
    </b-modal>
    <b-modal id="delEntradas" ref="delEntradasModal" title="Eliminar Coste Entradas" size="md" @shown="focusModal" hide-footer lazy>
        <b-row class="mx-0">
          Se van a eliminar las siguientes entradas:
        </b-row>
        <b-row class="mx-0">
          <ul v-if="delCosteEntradas" class="mt-2">
            <li><strong>Entradas:</strong> {{ this.delCosteEntradas.name }}</li>
            <li><strong>Cantidad:</strong> {{ this.delCosteEntradas.amount }}</li>
            <li><strong>Precio:</strong> {{ parseFloat(this.delCosteEntradas.price).toFixed(2) }}&nbsp;&euro;</li>
          </ul>
        </b-row>
        <b-row class="mx-0">
          ¿Está seguro?
        </b-row>
        <hr/>
        <b-row class="mx-0 float-right">
          <b-btn class="mr-3" variant="primary" @click.stop="deleteCosteEntradas">Borrar</b-btn>
          <b-btn variant="default" ref="focusThis" @click.stop="hideDeleteEntradasModal">Cancelar</b-btn>
        </b-row>
    </b-modal>
  </div>
</template>

<script>
  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';
  import { store } from '../store/store';
  import { mapState } from 'vuex';

  export default {
    mixins: [APIGetters, Utils],
    data () {
      return {
        fields: [
          { key: 'id', label: '', class: 'sr-only' },
          { key: 'name', label: 'Entradas', sortable: true, class: 'text-left' },
          { key: 'amount', label: 'Cant.', sortable: true, class: 'text-right' },
          { key: 'price', label: 'Precio', sortable: true, class: 'text-right', formatter: 'formatPrice' },
          { key: 'actions', label: 'Acciones', class: 'text-right' },
        ],
        porcentaje: null,
        precio_total: 0,
        isNewCosteEntradas: false,
        editCosteEntradas: null,
        delCosteEntradas: null,
        cancelCosteEntradasForm: () => { this.hideCosteEntradasForm(); },
        submitCosteEntradasForm: (entradas) => { this.saveCosteEntradasForm(entradas); },
        show: false,
      }
    },
    created: function () {
      console.log("FestivalFichaCostesComponent created");
      // this.getClientes();
      this.$store.dispatch('loadCostes').then( () => {
        this.porcentaje = this._costes.porcentaje;
        this.calcularPorcentaje();
        this.updateTotal();
        this.show = true;
      });
    },
    computed: mapState({
      _costes: 'costes',
      _coste_pelotaris: 'coste_pelotaris',
      _coste_jueces: 'coste_jueces',
      _coste_cancha: 'coste_cancha',
      _coste_material: 'coste_material',
      _coste_auxiliares: 'coste_auxiliares',
      _coste_taquillera: 'coste_taquillera',
      _coste_tasa: 'coste_tasa',
      _coste_sanidad: 'coste_sanidad',
      _coste: 'coste',
      _header: 'header',
      _margen_beneficio: 'margen_beneficio',
      _coste_tv: 'coste_tv',
      _importe_tv: 'importe_tv',
    }),
    methods: {
      calcularPorcentaje() {
        if( this.porcentaje == null ) {
          switch( this._header.tipo_festival ) {
            case 'EMPRESA':
              this._costes.porcentaje = 100;
              break;
            case 'CAMPEONATO':
            case 'TORNEO':
            default:
              this._costes.porcentaje = 50;
              break;
          }
        }
        return this._costes.porcentaje;
      },
      onSubmit() {
        this.$store.dispatch('addCostes', this._costes)
          .then((response) => {
            this.porcentaje = this._costes.porcentaje;
            this.showSnackbar("Costes GUARDADOS");
          })
          .catch((error) => {
            console.log(error);
            this.showSnackbar("Se ha producido un ERROR al guardar los COSTES");
          });
      },
      onReset() {
        this.$store.dispatch('loadCostes');
      },
      onClickAddEntradas() {
        this.isNewCosteEntradas = true;
        this.$refs.costeEntradasModal.show();
      },
      onClickEditEntradas(data) {
        this.isNewCosteEntradas = false;
        this.editCosteEntradas = data;
        this.$refs.costeEntradasModal.show();
      },
      onClickDeleteEntradas(data) {
        this.delCosteEntradas = data;
        this.$refs.delEntradasModal.show();
      },
      hideCosteEntradasForm() {
        this.$refs.costeEntradasModal.hide();
      },
      hideDeleteEntradasModal() {
        this.$refs.delEntradasModal.hide();
      },
      focusModal() {
        this.$refs.focusThis.focus();
      },
      saveCosteEntradasForm(entrada) {
        if( this.isNewCosteEntradas ) {
          store.dispatch('addCosteEntradas', entrada).then( () => {
            this.showSnackbar("Precio de entradas añadido");
            this.updatePrecioTotal();
            this.$refs.costeEntradasModal.hide();
          });
        } else {
          store.dispatch('updateCosteEntradas', entrada).then( () => {
            this.showSnackbar("Precio de entradas actualizado");
            this.updatePrecioTotal();
            this.$refs.costeEntradasModal.hide();
          });
        }
      },
      deleteCosteEntradas(entrada) {
        store.dispatch('deleteCosteEntradas', this.delCosteEntradas).then( () => {
          this.delCosteEntradas = null;
          this.showSnackbar("Precio de entradas eliminado");
          this.updatePrecioTotal();
          this.$refs.delEntradasModal.hide();
        });
      },
      updatePrecioTotal() {
        var total = parseFloat(this._costes.aportacion);
        this._costes.entradas.forEach( function(value, index) {
          total += (value.amount * value.price);
        });
        this._costes.precio_total = total;
        this.precio_total = total.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
      },
      updateTotal() {
        var ingreso_taquilla = (this._costes.ingreso_taquilla ? parseFloat(this._costes.ingreso_taquilla) : 0);
        var ingreso_ayto = (this._costes.ingreso_ayto ? parseFloat(this._costes.ingreso_ayto) : 0);
        var ingreso_otros = (this._costes.ingreso_otros ? parseFloat(this._costes.ingreso_otros) : 0);
        this._costes.total = ingreso_taquilla + ingreso_ayto + ingreso_otros;
      },
      updateCosteAuxiliares() {
        var value = document.getElementById("num_auxiliares").value;
        store.dispatch('updateCosteAuxiliares', value)
      },
      updateCosteTaquillera() {
        var value = document.getElementById("num_taquilleros").value;
        store.dispatch('updateCosteTaquilleros', value)
      },
      updateCosteSanidad() {
        var checked = document.getElementById("check_sanidad").checked;
        if(checked){
          store.dispatch('updateCosteSanidad', 1);
        }else{
          store.dispatch('updateCosteSanidad', 0);
        }
      },
      updateImporteVenta() {
        var value = document.getElementById("coste_importe_venta").value;

        var importeSugerido = parseInt(this._coste)+parseInt(((parseInt(this._coste)*parseInt(this._margen_beneficio))/100));
        if(value<importeSugerido){
          document.getElementById("coste_importe_venta_aviso").style.display = "block";
        }else{
          document.getElementById("coste_importe_venta_aviso").style.display = "none";
        }
      },
      updatePorcentaje() {
        this.porcentaje = this._costes.porcentaje;
      },
      solicitarConfirmacion() {
        store.dispatch('envioCorreoConfirmacion');
      },
      formatPrice(value) {
        return parseFloat(value).toFixed(2);
      },
      formatCurrency(value) {
        return parseFloat(value).toFixed(2);
      }
    }
  }
</script>

<style>
  .festival-costes .card {
    border-color:#707f8f;
  }
  .festival-costes label {
    font-weight:bold;
    margin-right:-15px;
  }
  .festival-costes input {
    height:27px;
  }
  .festival-costes input[type="text"],
  .festival-costes input[type="number"] {
    padding:0 5px;
  }
  .festival-costes .background-gray {
    background-color:darkgray;
    border-color:darkgray;
  }
  .festival-costes .background-red {
    background-color:#dd3545;
    border-color:#dd3545;
    color:white;
    font-size:1.15rem;
  }
  .festival-costes .background-red input {
    font-size:1.15rem;
    font-weight:bold;
  }
  .festival-costes .botonera {
    margin-left:0;
  }
  @media screen and (min-width:768px) {
    .festival-costes .botonera {
      float:right;
      margin-top:3rem;
      padding-right:15px;
    }
  }
</style>
