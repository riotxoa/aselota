<template>
  <div class="festival-costes">
    <b-form @submit="onSubmit">
      <b-row>
        <div class="col-md-6">
          <div class="card mb-3">
            <div class="card-body">
              <b-form-group label="Coste empresa festival (IVA inc.):"
                            label-for="coste_empresa">
                <b-form-input id="coste_empresa"
                              class="text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
              <b-form-group label="Importe venta (precio cerrado):"
                            label-for="coste_importe_venta">
                <b-form-input id="coste_importe_venta"
                              class="text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
              <b-form-group label="Festival vendido a:"
                            label-for="coste_vendido_a">
                <b-form-select id="coste_vendido_a"
                                :options="{ '0': 'No', '1': 'Sí' }">
                </b-form-select>
              </b-form-group>
              <b-form-group label="Observaciones:"
                            label-for="coste_texto_libre">
                <b-form-input id="coste_texto_libre"
                              type="text">
                </b-form-input>
              </b-form-group>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-body">
              <b-form-group label="Aportación:"
                            label-for="coste_aportacion">
                <b-form-input id="coste_aportacion"
                              class="text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
              <b-form-group label="Nº entradas:"
                            label-for="coste_num_entradas">
                <b-form-input id="coste_num_entradas"
                              class="text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
              <b-form-group label="Precio entradas:"
                            label-for="coste_precio_entradas">
                <b-form-input id="coste_precio_entradas"
                              class="text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-body">
              <b-form-group label="Precio total:"
                            label-for="coste_precio_total">
                <b-form-input id="coste_precio_total"
                              class="text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card mb-3 background-gray">
            <div class="card-body">
              <b-form-group label="Nº espectadores:"
                            label-for="coste_num_espectadores">
                <b-form-input id="coste_num_espectadores"
                              class="text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
              <b-form-group label="Ingreso en taquilla:"
                            label-for="coste_ingreso_taquilla">
                <b-form-input id="coste_ingreso_taquilla"
                              class="text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
              <b-form-group label="Ingreso por ayuntamiento:"
                            label-for="coste_ingreso_ayuntamiento">
                <b-form-input id="coste_ingreso_ayuntamiento"
                              class="text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
              <b-form-group label="Otros:"
                            label-for="coste_otros">
                <b-form-input id="coste_otros"
                              class="text-right"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
            </div>
          </div>
          <div class="card mb-3 background-red">
            <div class="card-body">
              <b-form-group label="Total:"
                            label-for="coste_total">
                <b-form-input id="coste_total"
                              class="text-center"
                              type="number"
                              maxlength="8"
                              placeholder="0">
                </b-form-input>
              </b-form-group>
            </div>
          </div>
        </div>
      </b-row>
    </b-form>
  </div>
</template>

<script>
  export default {
    data () {
      return {

      }
    },
    created: function () {
      console.log("FestivalFichaCostesComponent created");
    },
    methods: {
      onSubmit() {
        console.log("[onSubmit]");
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
    line-height:1;
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
</style>
