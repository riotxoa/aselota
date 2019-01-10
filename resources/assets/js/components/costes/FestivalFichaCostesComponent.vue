<template>
  <div class="festival-costes">
    <b-form @submit="onSubmit">
      <b-row>

        <div class="col-md-1">&nbsp;</div>

        <div class="col-md-5">
          <div class="card mb-3">
            <div class="card-body p-2">
              <b-row class="m-0 p-0">
                <label class="ml-0 mr-2">Porcentaje Asegarce:</label>
                <div class="">{{ _costes.porcentaje }}&nbsp;%</div>
              </b-row>
              <b-row class="px-3">
                <b-form-input id="coste_porcentaje"
                              class="col-12 px-0"
                              type="range"
                              min="0"
                              max="100"
                              step="5"
                              v-model="_costes.porcentaje">
                </b-form-input>
              </b-row>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-body">
              <b-row>
                <label class="col-md-7">Coste empresa festival:</label>
                <b-form-input id="coste_empresa"
                              class="col-md-5 text-right"
                              type="text"
                              maxlength="8"
                              placeholder="0.00"
                              readonly
                              :value="_coste.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                </b-form-input>
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
                              v-on:blur.native="formatCurrency">
                </b-form-input>
              </b-row>
              <b-row>
                <label class="col-md-7">Vendido a:</label>
                <b-form-select id="coste_vendido_a"
                               class="col-md-5"
                               :options="clientes"
                               v-model="_costes.cliente_id">
                </b-form-select>
              </b-row>
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
                              v-if="_costes.total"
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
        precio_total: 0,
        isNewCosteEntradas: false,
        editCosteEntradas: null,
        delCosteEntradas: null,
        cancelCosteEntradasForm: () => { this.hideCosteEntradasForm(); },
        submitCosteEntradasForm: (entradas) => { this.saveCosteEntradasForm(entradas); },
      }
    },
    created: function () {
      console.log("FestivalFichaCostesComponent created");
      this.getClientes();
      this.$store.dispatch('loadCostes');
    },
    computed: mapState({
      _costes: 'costes',
      _coste: 'coste',
    }),
    methods: {
      onSubmit() {
        this.$store.dispatch('addCostes', this._costes)
          .then((response) => {
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
        this._costes.total = parseFloat(this._costes.ingreso_taquilla) + parseFloat(this._costes.ingreso_ayto) + parseFloat(this._costes.ingreso_otros);
      },
      formatPrice(value) {
        return parseFloat(value).toFixed(2);
      },
      formatCurrency(ev) {
        let value = ev.target.value;
        ev.target.value = parseFloat(value).toFixed(2);
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
