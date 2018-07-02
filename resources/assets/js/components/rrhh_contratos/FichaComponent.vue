<template>
  <div>

    <b-form @submit="onSubmit" @reset="onReset" v-if="show">

      <b-row class="mb-3 bg-danger py-3">
        <div class="col-12 text-white"><strong>{{ pelotariAlias }}</strong></div>
      </b-row>

      <b-row>
        <div class="col-md-12">
          <b-row>
            <b-form-group label="Fecha Inicio:"
                          label-for="fecha_iniInput"
                          class="col-sm-4">
              <b-form-input id="fecha_iniInput"
                            type="date"
                            v-model="contrato.fecha_ini"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Fecha Fin:"
                          label-for="fecha_finInput"
                          class="col-sm-4">
              <b-form-input id="fecha_finInput"
                            type="date"
                            v-model="contrato.fecha_fin"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Dieta básica mensual:"
                          label-for="d_basicaInput"
                          class="col-sm-4">
              <b-form-input id="d_basicaInput"
                            class="text-right"
                            type="number"
                            v-model="contrato.dieta_mes"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#c3e6cb">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Dieta por partido:"
                          label-for="dieta_partidoInput"
                          class="col-sm-4">
              <b-form-input id="dieta_partidoInput"
                            class="text-right"
                            type="number"
                            v-model="contrato.dieta_partido"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#c3e6cb">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Prima por partido ganado:"
                          label-for="prima_partidoInput"
                          class="col-sm-4">
              <b-form-input id="prima_partidoInput"
                            class="text-right"
                            type="number"
                            v-model="contrato.prima_partido"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffeeba">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Prima por estelar ganado:"
                          label-for="prima_estelarInput"
                          class="col-sm-4">
              <b-form-input id="prima_estelarInput"
                            class="text-right"
                            type="number"
                            v-model="contrato.prima_estelar"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffeeba">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Prima Campeón Manomanista:"
                          label-for="prima_manomanistaInput"
                          class="col-sm-4">
              <b-form-input id="prima_manomanistaInput"
                            class="text-right"
                            type="number"
                            v-model="contrato.prima_manomanista"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffeeba">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Partidos garantía:"
                          label-for="garantiaInput"
                          class="col-sm-4">
              <b-form-input id="garantiaInput"
                            class="text-right"
                            type="number"
                            v-model="contrato.garantia"
                            maxlength="4"
                            placeholder="0">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Garantía según disponibilidad:"
                          label-for="garantia_dispInput"
                          class="col-sm-4">
              <b-form-input id="garantia_dispInput"
                            class="text-right"
                            type="number"
                            v-model="contrato.garantia_disp"
                            maxlength="4"
                            placeholder="0">
              </b-form-input>
            </b-form-group>
          </b-row>
        </div>
      </b-row>

      <b-button type="submit" variant="primary">Guardar</b-button>
      <b-button variant="default" @click="onCancel">Cancelar</b-button>
      <b-button v-if="!edit" type="reset" variant="danger" class="float-right mr-1">Reset</b-button>

    </b-form>

  </div>
</template>

<script>
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
    props: ['pelotariId', 'pelotariAlias', 'onCancel', 'getContratoRow', 'isNewContrato', 'formatAmount'],
    data () {
      return {
        contrato: {
          id: null,
          pelotari_id: null,
          fecha_ini: null,
          fecha_fin: null,
          dieta_mes: null,
          dieta_partido: null,
          prima_partido: null,
          prima_estelar: null,
          prima_manomanista: null,
          garantia: null,
          garantia_disp: null,
          created_at: null,
          updated_at: null,
        },
        edit: false,
        show: true,
        goBack: () => {
          this.onCancel();
        }
      }
    },
    created: function() {
      console.log("FichaComponent created");

      this.contrato.pelotari_id = this.pelotariId;

      if( this.isNewContrato() ) {
        this.edit = false;
      } else {
        this.edit = true;

        var rowContrato = this.getContratoRow();

        this.contrato.id = rowContrato.id;
        this.contrato.fecha_ini = rowContrato.fecha_ini;
        this.contrato.fecha_fin = rowContrato.fecha_fin;
        this.contrato.dieta_mes = this.formatAmount(rowContrato.dieta_mes);
        this.contrato.dieta_partido = this.formatAmount(rowContrato.dieta_partido);
        this.contrato.prima_partido = this.formatAmount(rowContrato.prima_partido);
        this.contrato.prima_estelar = this.formatAmount(rowContrato.prima_estelar);
        this.contrato.prima_manomanista = this.formatAmount(rowContrato.prima_manomanista);
        this.contrato.garantia = rowContrato.garantia;
        this.contrato.garantia_disp = rowContrato.garantia_disp;
      }
    },
    methods: {
      onSubmit (evt) {
        evt.preventDefault();

        let uri = '/www/contratos';

        if(this.edit) {
          console.log("[onSumbimt] this.contrato: " + JSON.stringify(this.contrato));
          this.axios.post(uri + '/' + this.contrato.id + '/update', this.contrato)
            .then((response) => {
              console.log("[onSubmit] response.data: " + JSON.stringify(response.data));
              showSnackbar("Contrato actualizado");
              this.goBack();
            })
            .catch((error) => {
              console.log(error);
              showSnackbar("Se ha producido un ERROR");
            });
        } else {
          this.axios.post(uri, this.contrato)
            .then((response) => {
              console.log("[onSubmit] response.data: " + JSON.stringify(response.data));
              showSnackbar("Contrato creado");
              this.goBack();
            })
            .catch((error) => {
              console.log(error);
              showSnackbar("Se ha producido un ERROR");
            });
        }
      },
      onReset (evt) {
        evt.preventDefault();
        /* Reset our form values */
        this.contrato.fecha_ini = null;
        this.contrato.fecha_fin = null;
        this.contrato.dieta_mes = null;
        this.contrato.dieta_partido = null;
        this.contrato.prima_partido = null;
        this.contrato.prima_estelar = null;
        this.contrato.prima_manomanista = null;
        this.contrato.garantia = null;
        this.contrato.garantia_disp = null;
      },
    }
  }
</script>
