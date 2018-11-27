<template>
  <div>

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
                          class="col-sm-6">
              <b-form-input id="fecha_iniInput"
                            type="date"
                            v-model="comercial.fecha_ini"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Fecha Fin:"
                          label-for="fecha_finInput"
                          class="col-sm-6">
              <b-form-input id="fecha_finInput"
                            type="date"
                            v-model="comercial.fecha_fin"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Coste Comercial:"
                          label-for="costeInput"
                          class="col-sm-6">
              <b-form-input id="costeInput"
                            class="text-right"
                            type="number"
                            v-model="comercial.coste"
                            maxlength="8"
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
    props: ['pelotariId', 'pelotariAlias', 'onCancel', 'getHeaderRow', 'getComercialRow', 'isNewComercial', 'formatAmount'],
    data () {
      return {
        header: null,
        comercial: {
          id: null,
          header_id: null,
          pelotari_id: null,
          fecha_ini: null,
          fecha_fin: null,
          coste: null,
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
      this.comercial.header_id = this.header.id;
      this.comercial.pelotari_id = this.header.pelotari_id;

      if( this.isNewComercial() ) {
        this.edit = false;
      } else {
        this.edit = true;

        var rowComercial = this.getComercialRow();

        this.comercial.id = rowComercial.id;
        this.comercial.fecha_ini = rowComercial.fecha_ini;
        this.comercial.fecha_fin = rowComercial.fecha_fin;
        this.comercial.coste = this.formatAmount(rowComercial.coste);
      }
    },
    methods: {
      onSubmit (evt) {
        evt.preventDefault();

        let uri = '/www/contratos/comercial';

        if(this.edit) {
          this.axios.post(uri + '/' + this.comercial.id + '/update', this.comercial)
            .then((response) => {
              showSnackbar("Periodo actualizado");
              this.goBack();
            })
            .catch((error) => {
              console.log(error);
              showSnackbar("Se ha producido un ERROR");
            });
        } else {
          this.axios.post(uri, this.comercial)
            .then((response) => {
              showSnackbar("Periodo creado");
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
        this.comercial.fecha_ini = null;
        this.comercial.fecha_fin = null;
        this.comercial.coste = null;
      },
    }
  }
</script>
