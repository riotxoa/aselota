<template>
  <div class="festival-facturacion">
    <b-form @submit="onSubmit">
      <b-row>

        <div class="col-md-1">&nbsp;</div>

        <div class="col-md-5">
          <div class="card mb-3">
            <div class="card-body">
              <b-row>
                <label class="col-md-6">Forma de pago:</label>
                <b-form-select id="fact_forma_pago"
                               class="col-md-6"
                               :options="formas_pago"
                               v-model="_facturacion.fpago_id">
                </b-form-select>
              </b-row>
              <b-row>
                <label class="col-md-6">Fecha:</label>
                <b-form-input id="fact_fecha"
                              class="col-md-6 text-right"
                              type="date"
                              v-model="_facturacion.fecha">
                </b-form-input>
              </b-row>
              <b-row>
                <label class="col-md-6">Importe:</label>
                <b-form-input id="fact_importe"
                              class="col-md-6 text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0.00"
                              v-model="_facturacion.importe"
                              v-on:focus.native="$event.target.select()"
                              v-on:blur.native="formatCurrency">
                </b-form-input>
              </b-row>
              <b-row>
                <label class="col-md-6">Enviar factura a:</label>
                <b-form-select id="fact_enviar_a"
                               class="col-md-6"
                               :options="envio_facturas"
                               v-model="_facturacion.enviar_id">
                </b-form-select>
              </b-row>
              <b-form-group label="Observaciones:"
                            label-for="fact_observaciones">
                <b-form-textarea id="fact_observaciones"
                                 :rows="3"
                                 :max-rows="6"
                                 v-model="_facturacion.observaciones">
                </b-form-textarea>
              </b-form-group>
            </div>
          </div>
        </div>

        <div class="col-md-5 position-relative">
          <div class="card mb-3">
            <div class="card-body">
              <b-row>
                <label class="col-md-6">Pagado:</label>
                <b-form-radio-group class="col-md-6"
                                    v-model="_facturacion.pagado"
                                    :options="[{ text: 'No', value: 0}, {text: 'Sí', value: 1}]"
                                    name="radioInline">
                </b-form-radio-group>
              </b-row>
              <b-form-group label="Seguimiento:"
                            label-for="fact_seguimiento">
                <b-form-textarea id="fact_seguimiento"
                                 :rows="3"
                                 :max-rows="6"
                                 v-model="_facturacion.seguimiento">
                </b-form-textarea>
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
      console.log("FestivalFichaFacturacionComponent created");
      this.getFormasPago();
      this.getEnvioFacturas();
    },
    computed: mapState({
      _facturacion: 'facturacion',
      _costes: 'costes',
    }),
    methods: {
      onSubmit() {
        this.$store.dispatch('addFacturacion', this._facturacion)
          .then((response) => {
            this.showSnackbar("Facturación GUARDADA");
          })
          .catch((error) => {
            console.log(error);
            this.showSnackbar("Se ha producido un ERROR al guardar la FACTURACIÓN");
          });
      },
      onReset() {
        this.$store.dispatch('loadFacturacion');
      },
      formatCurrency(ev) {
        let value = ev.target.value;
        ev.target.value = parseFloat(value).toFixed(2);
      }
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
