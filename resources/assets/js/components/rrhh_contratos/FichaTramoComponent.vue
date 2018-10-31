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
                            v-model="tramo.fecha_ini"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Fecha Fin:"
                          label-for="fecha_finInput"
                          class="col-sm-4">
              <b-form-input id="fecha_finInput"
                            type="date"
                            v-model="tramo.fecha_fin"
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
                            v-model="tramo.dieta_mes"
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
                            v-model="tramo.dieta_partido"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#c3e6cb">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Prima por partido:"
                          label-for="prima_partidoInput"
                          class="col-sm-4">
              <b-form-input id="prima_partidoInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.prima_partido"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffeeba">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Prima por estelar:"
                          label-for="prima_estelarInput"
                          class="col-sm-4">
              <b-form-input id="prima_estelarInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.prima_estelar"
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
                            v-model="tramo.prima_manomanista"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffeeba">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Dchos.Imagen (mes):"
                          label-for="d_imagenInput"
                          class="col-sm-4">
              <b-form-input id="d_imagenInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.d_imagen"
                            maxlength="8"
                            placeholder="0">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Coste por partido:"
                          label-for="costeInput"
                          class="col-sm-4">
              <b-form-input id="costeInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.coste"
                            maxlength="8"
                            placeholder="0">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Partidos garantía:"
                          label-for="garantiaInput"
                          class="col-sm-4">
              <b-form-input id="garantiaInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.garantia"
                            maxlength="4"
                            placeholder="0">
              </b-form-input>
            </b-form-group>
          </b-row>
        </div>
      </b-row>

      <hr/>

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
    props: ['pelotariId', 'pelotariAlias', 'onCancel', 'getHeaderRow', 'getTramoRow', 'isNewTramo', 'formatAmount'],
    data () {
      return {
        tramo: {
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
          coste: null,
          formacion: false,
          d_imagen: null,
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

      this.tramo.pelotari_id = this.pelotariId;
      this.tramo.header_id = this.getHeaderRow().id;
      // var header = this.getHeaderRow();
console.log("[CREATED] this.tramo: " + JSON.stringify(this.tramo));
      if( this.isNewTramo() ) {
        this.edit = false;
      } else {
        this.edit = true;

        var rowTramo = this.getTramoRow();

        this.tramo.id = rowTramo.id;
        this.tramo.fecha_ini = rowTramo.fecha_ini;
        this.tramo.fecha_fin = rowTramo.fecha_fin;
        this.tramo.dieta_mes = this.formatAmount(rowTramo.dieta_mes);
        this.tramo.dieta_partido = this.formatAmount(rowTramo.dieta_partido);
        this.tramo.prima_partido = this.formatAmount(rowTramo.prima_partido);
        this.tramo.prima_estelar = this.formatAmount(rowTramo.prima_estelar);
        this.tramo.prima_manomanista = this.formatAmount(rowTramo.prima_manomanista);
        this.tramo.garantia = rowTramo.garantia;
        this.tramo.coste = this.formatAmount(rowTramo.coste);
        this.tramo.formacion = rowTramo.formacion;
        this.tramo.d_imagen = this.formatAmount(rowTramo.d_imagen);
      }
    },
    methods: {
      onSubmit (evt) {
        evt.preventDefault();

        let uri = '/www/contratos/tramo';

        if(this.edit) {
          console.log("[onSumbimt] this.tramo: " + JSON.stringify(this.tramo));
          this.axios.post(uri + '/' + this.tramo.id + '/update', this.tramo)
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
          console.log("[onSubmit NEW] this.tramo: " + JSON.stringify(this.tramo));
          this.axios.post(uri, this.tramo)
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
        this.tramo.fecha_ini = null;
        this.tramo.fecha_fin = null;
        this.tramo.dieta_mes = null;
        this.tramo.dieta_partido = null;
        this.tramo.prima_partido = null;
        this.tramo.prima_estelar = null;
        this.tramo.prima_manomanista = null;
        this.tramo.garantia = null;
        this.tramo.coste = null;
        this.tramo.formacion = false;
        this.tramo.d_imagen = null;
      },
    }
  }
</script>
