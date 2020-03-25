<template>
  <div id="fichaTramoComponent">

    <b-form @submit="onSubmit" @reset="onReset">

      <b-row class="bg-danger mb-3 py-2">
        <div class="col-12 text-white"><strong>{{ pelotariAlias }}</strong></div>
        <div class="col-6 text-white"><strong>Contrato: {{ header.name }}</strong></div>
        <div class="col-6 text-white text-right"><strong><span class="icon voyager-calendar mr-2"></span>{{ header.fecha_ini | formatDate }} - {{ header.fecha_fin | formatDate }}</strong></div>
      </b-row>

      <b-row>
        <div class="col-md-12">
          <b-row>
            <b-form-group label="Fecha Inicio:"
                          label-for="fecha_iniInput"
                          class="col-sm-3">
              <b-form-input id="fecha_iniInput"
                            type="date"
                            v-model="tramo.fecha_ini"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Fecha Fin:"
                          label-for="fecha_finInput"
                          class="col-sm-3">
              <b-form-input id="fecha_finInput"
                            type="date"
                            v-model="tramo.fecha_fin"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Ficha:"
                          label-for="fichaInput"
                          class="col-sm-3">
              <b-form-input id="fichaInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.ficha"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffca9b">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Sueldo:"
                          label-for="sueldoInput"
                          class="col-sm-3">
              <b-form-input id="sueldoInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.sueldo"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffca9b">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Partidos garantía:"
                          label-for="garantiaInput"
                          class="col-sm-3">
              <b-form-input id="garantiaInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.garantia"
                            maxlength="4"
                            placeholder="0"
                            style="background-color:#d5e8ff">
              </b-form-input>
            </b-form-group>
            <b-form-group label="&nbsp;"
                          label-for="formacionInput"
                          class="col-sm-3">
              <b-form-checkbox id="formacionInput"
                               v-model="tramo.formacion"
                               value="true"
                               unchecked-value="false">
                    <span class="d-inline-block" style="padding-top:0.35rem;">Formación</span>
              </b-form-checkbox>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Dieta mes:"
                          label-for="d_basicaInput"
                          class="col-sm-3">
              <b-form-input id="d_basicaInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.dieta_mes"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#c3e6cb">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Dieta partido:"
                          label-for="dieta_partidoInput"
                          class="col-sm-3">
              <b-form-input id="dieta_partidoInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.dieta_partido"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#c3e6cb">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Prima partido:"
                          label-for="prima_partidoInput"
                          class="col-sm-3">
              <b-form-input id="prima_partidoInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.prima_partido"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffeeba">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Prima partido > Gtía.:"
                          label-for="coste2Input"
                          class="col-sm-3">
              <b-form-input id="coste2Input"
                            class="text-right"
                            type="number"
                            v-model="tramo.prima_partido_no_gar"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffeeba">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Plus estelar:"
                          label-for="prima_estelarInput"
                          class="col-sm-3">
              <b-form-input id="prima_estelarInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.prima_estelar"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffeeba">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Plus Campeón Mano. 1ª:"
                          label-for="prima_manomanistaInput"
                          class="col-sm-3">
              <b-form-input id="prima_manomanistaInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.prima_manomanista"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffeeba">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Plus Campeón Mano.Prom.:"
                          label-for="prima_manomanistaPromoInput"
                          class="col-sm-3">
              <b-form-input id="prima_manomanistaPromoInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.prima_manomanista_promo"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#ffeeba">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Dchos.Imagen / Mes:"
                          label-for="d_imagenInput"
                          class="col-sm-3">
              <b-form-input id="d_imagenInput"
                            class="text-right"
                            type="number"
                            v-model="tramo.d_imagen"
                            maxlength="8"
                            placeholder="0"
                            style="background-color:#fadde3">
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
        header: null,
        tramo: {
          id: null,
          header_id: null,
          pelotari_id: null,
          fecha_ini: null,
          fecha_fin: null,
          ficha: null,
          sueldo: null,
          dieta_mes: null,
          dieta_partido: null,
          prima_partido: null,
          prima_partido_no_gar: null,
          prima_estelar: null,
          prima_manomanista: null,
          prima_manomanista_promo: null,
          garantia: null,
          formacion: "false",
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

      this.header = this.getHeaderRow();
      this.tramo.pelotari_id = this.pelotariId;
      this.tramo.header_id = this.getHeaderRow().id;

      if( this.isNewTramo() ) {
        this.edit = false;
      } else {
        this.edit = true;

        var rowTramo = this.getTramoRow();

        this.tramo.id = rowTramo.id;
        this.tramo.fecha_ini = rowTramo.fecha_ini;
        this.tramo.fecha_fin = rowTramo.fecha_fin;
        this.tramo.ficha = this.formatAmount(rowTramo.ficha);
        this.tramo.sueldo = this.formatAmount(rowTramo.sueldo);
        this.tramo.dieta_mes = this.formatAmount(rowTramo.dieta_mes);
        this.tramo.dieta_partido = this.formatAmount(rowTramo.dieta_partido);
        this.tramo.prima_partido = this.formatAmount(rowTramo.prima_partido);
        this.tramo.prima_partido_no_gar = this.formatAmount(rowTramo.prima_partido_no_gar);
        this.tramo.prima_estelar = this.formatAmount(rowTramo.prima_estelar);
        this.tramo.prima_manomanista = this.formatAmount(rowTramo.prima_manomanista);
        this.tramo.prima_manomanista_promo = this.formatAmount(rowTramo.prima_manomanista_promo);
        this.tramo.garantia = rowTramo.garantia;
        this.tramo.formacion = ( 1 === rowTramo.formacion ? "true" : "false");
        this.tramo.d_imagen = this.formatAmount(rowTramo.d_imagen);
      }
    },
    methods: {
      onSubmit (evt) {
        evt.preventDefault();

        let uri = '/www/contratos/tramo';

        this.tramo.formacion = ("true" === this.tramo.formacion ? 1 : 0);

        if(this.edit) {
          this.axios.post(uri + '/' + this.tramo.id + '/update', this.tramo)
            .then((response) => {
              showSnackbar("Contrato actualizado");
              this.goBack();
            })
            .catch((error) => {
              console.log(error);
              showSnackbar("Se ha producido un ERROR");
            });
        } else {
          this.axios.post(uri, this.tramo)
            .then((response) => {
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
        this.tramo.ficha = null;
        this.tramo.sueldo = null;
        this.tramo.dieta_mes = null;
        this.tramo.dieta_partido = null;
        this.tramo.prima_partido = null;
        this.tramo.prima_partido_no_gar = null;
        this.tramo.prima_estelar = null;
        this.tramo.prima_manomanista = null;
        this.tramo.prima_manomanista_promo = null;
        this.tramo.garantia = null;
        this.tramo.formacion = false;
        this.tramo.d_imagen = null;
      },
    }
  }
</script>

<style>
#fichaTramoComponent label {
  font-weight:600;
  letter-spacing:-.75px;
  line-height:1;
}
</style>
