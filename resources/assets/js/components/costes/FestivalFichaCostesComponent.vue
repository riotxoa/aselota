<template>
  <div class="festival-costes">
    <b-form @submit="onSubmit">
      <b-row>

        <div class="col-md-1">&nbsp;</div>

        <div class="col-md-5">
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
                <b-form-input id="coste_texto_libre"
                              type="text"
                              v-model="_costes.cliente_txt">
                </b-form-input>
              </b-form-group>
            </div>
          </div>
          <div class="card mb-3">
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
              <b-row>
                <label class="col-md-7">Nº entradas:</label>
                <b-form-input id="coste_num_entradas"
                              class="col-md-5 text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0.00"
                              v-model="_costes.num_entradas"
                              v-on:focus.native="$event.target.select()"
                              @input="updatePrecioTotal">
                </b-form-input>
              </b-row>
              <b-row>
                <label class="col-md-7">Precio entradas:</label>
                <b-form-input id="coste_precio_entradas"
                              class="col-md-5 text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0.00"
                              v-model="_costes.precio_entradas"
                              v-on:focus.native="$event.target.select()"
                              v-on:blur.native="formatCurrency"
                              @input="updatePrecioTotal">
                </b-form-input>
              </b-row>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-body">
              <b-row>
                <label class="col-md-7">Precio total:</label>
                <b-form-input id="coste_precio_total"
                              class="col-md-5 text-right"
                              type="text"
                              :value="_costes.precio_total.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})"
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
  </div>
</template>

<script>
  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';
  import { mapState } from 'vuex';

  export default {
    mixins: [APIGetters, Utils],
    data () {
      return {}
    },
    created: function () {
      console.log("FestivalFichaCostesComponent created");
      this.getClientes();
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
      updatePrecioTotal() {
        this._costes.precio_total = parseFloat(this._costes.aportacion) + (this._costes.num_entradas * this._costes.precio_entradas);
      },
      updateTotal() {
        this._costes.total = parseFloat(this._costes.ingreso_taquilla) + parseFloat(this._costes.ingreso_ayto) + parseFloat(this._costes.ingreso_otros);
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
      bottom:15px;
      right:30px;
      position:absolute;
    }
  }
</style>
