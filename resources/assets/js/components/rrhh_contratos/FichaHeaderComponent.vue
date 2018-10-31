<template>
  <div>

    <b-form @submit="onSubmit" @reset="onReset" v-if="show">

      <b-row class="mb-3 bg-danger py-3">
        <div class="col-12 text-white"><strong>{{ pelotariAlias }}</strong></div>
      </b-row>

      <b-row>
        <div class="col-md-12">
          <b-row>
            <b-form-group label="Identificador:"
                          label-for="identificadorInput"
                          class="col-sm-4">
              <b-form-input id="identificadorInput"
                            class="text-left"
                            type="text"
                            v-model="header.name"
                            maxlength="50">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Fecha Inicio:"
                          label-for="fecha_iniInput"
                          class="col-sm-4">
              <b-form-input id="fecha_iniInput"
                            type="date"
                            v-model="header.fecha_ini"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Fecha Fin:"
                          label-for="fecha_finInput"
                          class="col-sm-4">
              <b-form-input id="fecha_finInput"
                            type="date"
                            v-model="header.fecha_fin"
                            required
                            placeholder="dd/mm/yyyy">
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
    props: ['pelotariId', 'pelotariAlias', 'onCancel', 'getHeaderRow', 'isNewHeader', 'formatAmount'],
    data () {
      return {
        header: {
          id: null,
          pelotari_id: null,
          name: '',
          fecha_ini: null,
          fecha_fin: null,
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
      console.log("FichaHeaderComponent created");

      this.header.pelotari_id = this.pelotariId;

      if( this.isNewHeader() ) {
        this.edit = false;
      } else {
        this.edit = true;

        var rowHeader = this.getHeaderRow();

        this.header.id = rowHeader.id;
        this.header.name = rowHeader.name;
        this.header.fecha_ini = rowHeader.fecha_ini;
        this.header.fecha_fin = rowHeader.fecha_fin;
      }
    },
    methods: {
      onSubmit (evt) {
        evt.preventDefault();

        let uri = '/www/contratos/header';

        if(this.edit) {
          console.log("[onSumbimt] this.header: " + JSON.stringify(this.header));
          this.axios.post(uri + '/' + this.header.id + '/update', this.header)
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
          this.axios.post(uri, this.header)
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
        this.header.fecha_ini = null;
        this.header.fecha_fin = null;
        this.header.name = '';
      },
    }
  }
</script>
