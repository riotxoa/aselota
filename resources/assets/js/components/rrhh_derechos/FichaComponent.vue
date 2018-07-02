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
                            v-model="derecho.fecha_ini"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Fecha Fin:"
                          label-for="fecha_finInput"
                          class="col-sm-4">
              <b-form-input id="fecha_finInput"
                            type="date"
                            v-model="derecho.fecha_fin"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Importe:"
                          label-for="amountInput"
                          class="col-sm-4">
              <b-form-input id="amountInput"
                            class="text-right"
                            type="number"
                            v-model="derecho.amount"
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
    props: ['pelotariId', 'pelotariAlias', 'onCancel', 'getDerechoRow', 'isNewDerecho', 'formatAmount'],
    data () {
      return {
        derecho: {
          id: null,
          pelotari_id: null,
          contrato_id: null,
          fecha_ini: null,
          fecha_fin: null,
          amount: null,
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

      this.derecho.pelotari_id = this.pelotariId;

      if( this.isNewDerecho() ) {
        this.edit = false;
      } else {
        this.edit = true;

        var rowDerecho = this.getDerechoRow();

        this.derecho.id = rowDerecho.id;
        this.derecho.fecha_ini = rowDerecho.fecha_ini;
        this.derecho.fecha_fin = rowDerecho.fecha_fin;
        this.derecho.amount = this.formatAmount(rowDerecho.amount);
      }
    },
    methods: {
      onSubmit (evt) {
        evt.preventDefault();

        let uri = '/www/derechos';

        if(this.edit) {
          this.axios.post(uri + '/' + this.derecho.id + '/update', this.derecho)
            .then((response) => {
              showSnackbar("Derecho actualizado");
              this.goBack();
            })
            .catch((error) => {
              console.log(error);
              showSnackbar("Se ha producido un ERROR");
            });
        } else {
          this.axios.post(uri, this.derecho)
            .then((response) => {
              showSnackbar("Derecho creado");
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
        this.derecho.fecha_ini = null;
        this.derecho.fecha_fin = null;
        this.derecho.amount = null;
      },
    }
  }
</script>
