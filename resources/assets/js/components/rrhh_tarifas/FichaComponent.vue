<template>
  <div>

    <b-form @submit="onSubmit" @reset="onReset" v-if="show">

      <b-row class="mb-3 bg-danger py-3">
        <div class="col-12 text-white"><strong>{{ pelotariAlias }} - {{ campeonatoName }}</strong></div>
      </b-row>

      <b-row>
        <b-form-group label="Fecha Inicio:"
                      label-for="fecha_iniInput"
                      class="col-sm-4">
          <b-form-input id="fecha_iniInput"
                        type="date"
                        v-model="tarifa.fecha_ini"
                        required
                        placeholder="dd/mm/yyyy">
          </b-form-input>
        </b-form-group>
        <b-form-group label="Fecha Fin:"
                      label-for="fecha_finInput"
                      class="col-sm-4">
          <b-form-input id="fecha_finInput"
                        type="date"
                        v-model="tarifa.fecha_fin"
                        required
                        placeholder="dd/mm/yyyy">
          </b-form-input>
        </b-form-group>
      </b-row>
      <b-row>
        <b-form-group label="Campeón:"
                      label-for="campeonInput"
                      class="col-sm-4">
          <b-form-input id="campeonInput"
                        class="text-right"
                        type="number"
                        v-model="tarifa.campeon"
                        maxlength="8"
                        placeholder="0">
          </b-form-input>
        </b-form-group>
        <b-form-group label="Subampeón:"
                      label-for="dieta_partidoInput"
                      class="col-sm-4">
          <b-form-input id="dieta_partidoInput"
                        class="text-right"
                        type="number"
                        v-model="tarifa.subcampeon"
                        maxlength="8"
                        placeholder="0">
          </b-form-input>
        </b-form-group>
      </b-row>
      <b-row>
        <b-form-group label="Liguilla Semifinales:"
                      label-for="liga_semifinalInput"
                      class="col-sm-4">
          <b-form-input id="liga_semifinalInput"
                        class="text-right"
                        type="number"
                        v-model="tarifa.liga_semifinal"
                        maxlength="8"
                        placeholder="0">
          </b-form-input>
        </b-form-group>
        <b-form-group label="Liguilla Cuartos:"
                      label-for="liga_cuartosInput"
                      class="col-sm-4">
          <b-form-input id="liga_cuartosInput"
                        class="text-right"
                        type="number"
                        v-model="tarifa.liga_cuartos"
                        maxlength="8"
                        placeholder="0">
          </b-form-input>
        </b-form-group>
      </b-row>
      <b-row>
        <b-form-group label="Semifinales:"
                      label-for="semfifinalInput"
                      class="col-sm-4">
          <b-form-input id="semfifinalInput"
                        class="text-right"
                        type="number"
                        v-model="tarifa.semifinal"
                        maxlength="8"
                        placeholder="0">
          </b-form-input>
        </b-form-group>
        <b-form-group label="Cuartos:"
                      label-for="cuartosInput"
                      class="col-sm-4">
          <b-form-input id="cuartosInput"
                        class="text-right"
                        type="number"
                        v-model="tarifa.cuartos"
                        maxlength="8"
                        placeholder="0">
          </b-form-input>
        </b-form-group>
        <b-form-group label="Octavos:"
                      label-for="octavosInput"
                      class="col-sm-4">
          <b-form-input id="octavosInput"
                        class="text-right"
                        type="number"
                        v-model="tarifa.octavos"
                        maxlength="8"
                        placeholder="0">
          </b-form-input>
        </b-form-group>
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
    props: ['pelotariId', 'pelotariAlias', 'campeonatoId', 'campeonatoName', 'onCancel', 'getTarifaRow', 'isNewTarifa', 'formatAmount'],
    data () {
      return {
        tarifa: {
          id: null,
          pelotari_id: null,
          campeonato_id: null,
          fecha_ini: null,
          fecha_fin: null,
          campeon: null,
          subcampeon: null,
          liga_semifinal: null,
          liga_cuartos: null,
          semifinal: null,
          cuartos: null,
          octavos: null,
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

      this.tarifa.pelotari_id = this.pelotariId;
      this.tarifa.campeonato_id = this.campeonatoId;

      if( this.isNewTarifa() ) {
        this.edit = false;
      } else {
        this.edit = true;

        var rowTarifa = this.getTarifaRow();

        this.tarifa.id = rowTarifa.id;
        this.tarifa.fecha_ini = rowTarifa.fecha_ini;
        this.tarifa.fecha_fin = rowTarifa.fecha_fin;
        this.tarifa.campeon = this.formatAmount(rowTarifa.campeon);
        this.tarifa.subcampeon = this.formatAmount(rowTarifa.subcampeon);
        this.tarifa.liga_semifinal = this.formatAmount(rowTarifa.liga_semifinal);
        this.tarifa.liga_cuartos = this.formatAmount(rowTarifa.liga_cuartos);
        this.tarifa.semifinal = this.formatAmount(rowTarifa.semifinal);
        this.tarifa.cuartos = this.formatAmount(rowTarifa.cuartos);
        this.tarifa.octavos = this.formatAmount(rowTarifa.octavos);
      }
    },
    methods: {
      onSubmit (evt) {
        evt.preventDefault();

        let uri = '/www/tarifas';
        if(this.edit) {
          this.axios.post(uri + '/' + this.tarifa.id + '/update', this.tarifa)
            .then((response) => {
              showSnackbar("Tarifa actualizada");
              this.goBack();
            })
            .catch((error) => {
              console.log(error);
              showSnackbar("Se ha producido un ERROR");
            });
        } else {
          this.axios.post(uri, this.tarifa)
            .then((response) => {
              showSnackbar("Tarifa creada");
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
        this.tarifa.fecha_ini = null;
        this.tarifa.fecha_fin = null;
        this.tarifa.campeon = null;
        this.tarifa.subcampeon = null;
        this.tarifa.liga_semifinal = null;
        this.tarifa.liga_cuartos = null;
        this.tarifa.semifinal = null;
        this.tarifa.cuartos = null;
        this.tarifa.octavos = null;
      },
    }
  }
</script>
