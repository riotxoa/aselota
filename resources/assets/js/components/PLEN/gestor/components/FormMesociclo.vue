<template>
  <div>
    <b-form v-if="show">
      <b-row class="border-bottom mb-3 macrociclo-wrap">
        <b-col sm="4" lg="2">
          <label class="font-weight-bold">Macrociclo:</label>
        </b-col>
        <b-col sm="8" lg="3" class="pl-sm-1 px-lg-0">
          <p class="border mb-1 px-2">{{ macrociclo_dates }}</p>
        </b-col>
        <b-col sm="8" lg="7" class="offset-sm-4 offset-lg-0 pl-sm-1">
          <p class="border px-2">{{ macrociclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" lg="4" class="mb-2">
          <label for="tipoMesociclo" class="col-12 col-md-4 col-lg-12 d-inline d-lg-block font-weight-bold mb-1 pl-0">Tipo Mesociclo:</label>
          <b-form-select
            id="tipoMesociclo"
            class="col-12 col-md-8 col-lg-12 d-inline d-lg-block float-right"
            v-model="mesociclo.tipo_mesociclo_id"
            :options="tipos"
            size="sm"
            required
          ></b-form-select>
        </b-col>
        <b-col cols="12" sm="6" md="12" lg="4" class="mb-2">
          <label for="fechaInicio" class="col-12 col-md-4 col-lg-12 d-inline d-lg-block font-weight-bold mb-1 pl-0">Fecha inicio:</label>
          <b-form-input
            id="fechaInicio"
            class="col-12 col-md-8 col-lg-12 d-inline d-lg-block float-right"
            v-model="mesociclo.fecha_ini"
            v-on:input="onInputFechaIni"
            type="date"
            size="sm"
            required
          ></b-form-input>
          <p id="errFechaIni" class="font-weight-bold m-0 position-absolute small">{{ error_msg.fecha_ini }}</p>
        </b-col>
        <b-col cols="12" sm="6" md="12" lg="4" class="mb-2">
          <label for="fechaFin" class="col-12 col-md-4 col-lg-12 d-inline d-lg-block font-weight-bold mb-1 pl-0">Fecha fin:</label>
          <b-form-input
            id="fechaFin"
            class="col-12 col-md-8 col-lg-12 d-inline d-lg-block float-right"
            v-model="mesociclo.fecha_fin"
            v-on:change="onInputFechaFin"
            type="date"
            size="sm"
            required
          ></b-form-input>
          <p id="errFechaFin" class="font-weight-bold m-0 position-absolute small">{{ error_msg.fecha_fin }}</p>
        </b-col>
        <b-col cols="12" lg="8">
          <b-form-group label="Descripción:" label-for="descMesociclo" label-class="font-weight-bold">
            <b-form-textarea
              id="descMesociclo"
              v-model="mesociclo.description"
              maxLength="1500"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
        </b-col>
        <b-col cols="12" lg="4">
          <b-form-group label="Objetivos:" label-for="objMesociclo" label-class="font-weight-bold">
            <b-form-textarea
              id="objMesociclo"
              v-model="mesociclo.objetivos"
              maxLength="1500"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
        </b-col>
      </b-row>
    </b-form>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import moment from 'moment';

  export default {
    data() {
      return {
        error_msg: null,
        macrociclo_dates: '',
        macrociclo_desc: '',
        show: false,
        tipos: []
      }
    },
    computed: mapState({
      macrociclo: state => state.plen.macrociclo,
      mesociclo: state => state.plen.mesociclo
    }),
    created() {
      this.resetErrorMsg();
      this.getTiposMesociclo().then( (res) => {
        this.setTiposMesociclo(res);
        this.show = true;
      })
    },
    mounted() {
      this.$root.$on('bv::modal::show', (bvEvent, modalId) => {
        if( 'editMesociclo' == bvEvent.target.id ) {
          this.resetErrorMsg();
        }
      });
    },
    updated() {
      this.macrociclo_desc = this.macrociclo.description;
      this.macrociclo_dates = moment(this.macrociclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.macrociclo.fecha_fin).format('DD/MM/YYYY');
    },
    methods: {
      ...mapActions({
        getTiposMesociclo: 'plen/getTiposMesociclo'
      }),
      onInputFechaFin(f_fin) {
        this.error_msg.fecha_fin = '';
        if( f_fin <= this.macrociclo.fecha_ini || f_fin > this.macrociclo.fecha_fin || f_fin <= this.mesociclo.fecha_ini ) {
          this.error_msg.fecha_fin = "La fecha debe estar dentro del intervalo";
        }
        this.setErrorMsgExists();
      },
      onInputFechaIni(f_ini) {
        this.error_msg.fecha_ini = '';
        if( f_ini < this.macrociclo.fecha_ini || f_ini > this.macrociclo.fecha_fin || f_ini >= this.mesociclo.fecha_fin ) {
          this.error_msg.fecha_ini = "La fecha debe estar dentro del intervalo";
        }
        this.setErrorMsgExists();
      },
      resetErrorMsg() {
        this.error_msg = {
          exists: false,
          fecha_ini: '',
          fecha_fin: '',
        };
      },
      setErrorMsgExists() {
        this.error_msg.exists = this.error_msg.fecha_ini.length > 0 || this.error_msg.fecha_fin.length > 0
        this.$root.$emit('disable-modal-mesociclo-save-button', this.error_msg.exists)
      },
      setTiposMesociclo(arr) {
        this.tipos.push({
          value: null,
          text: "Seleccionar Tipo"
        });
        arr.map( (val, res) => {
          this.tipos.push({
            value: val.id,
            text:  val.desc
          });
        });
      }
    }
  }
</script>

<style scoped>
#errFechaIni,
#errFechaFin {
  color:red;
}
.macrociclo-wrap p {
  background-color:rgba(255, 192, 203, 0.55);
}
</style>
