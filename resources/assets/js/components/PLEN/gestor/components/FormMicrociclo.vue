<template>
  <div>
    <b-form v-if="show">
      <b-row class="macrociclo-wrap">
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
      <b-row class="border-bottom mb-3 mesociclo-wrap">
        <b-col sm="4" lg="2">
          <label class="font-weight-bold">Mesociclo:</label>
        </b-col>
        <b-col sm="8" lg="3" class="pl-sm-1 px-lg-0">
          <p class="border mb-1 px-2">{{ mesociclo_dates }}</p>
        </b-col>
        <b-col sm="8" lg="7" class="offset-sm-4 offset-lg-0 pl-sm-1">
          <p class="border px-2">{{ mesociclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" lg="4" class="mb-2">
          <label for="tipoMicrociclo" class="col-12 col-md-4 col-lg-12 d-inline d-lg-block font-weight-bold mb-1 pl-0">Tipo Microciclo:</label>
          <b-form-select
            id="tipoMicrociclo"
            class="col-12 col-md-8 col-lg-12 d-inline d-lg-block float-right"
            v-model="microciclo.tipo_microciclo_id"
            :options="tipos"
            size="sm"
            required
          ></b-form-select>
        </b-col>
        <b-col cols="12" sm="6" md="12" lg="3" class="mb-2">
          <label for="fechaInicio" class="col-12 col-md-4 col-lg-12 d-inline d-lg-block font-weight-bold mb-1 pl-0">Fecha inicio:</label>
          <b-form-input
            id="fechaInicio"
            class="col-12 col-md-8 col-lg-12 d-inline d-lg-block float-right"
            v-model="microciclo.fecha_ini"
            v-on:input="onInputFechaIni"
            type="date"
            size="sm"
            required
          ></b-form-input>
          <p id="errFechaIni" class="font-weight-bold m-0 position-absolute small">{{ error_msg.fecha_ini }}</p>
        </b-col>
        <b-col cols="12" sm="6" md="12" lg="3" class="mb-2">
          <label for="fechaFin" class="col-12 col-md-4 col-lg-12 d-inline d-lg-block font-weight-bold mb-1 pl-0">Fecha fin:</label>
          <b-form-input
            id="fechaFin"
            class="col-12 col-md-8 col-lg-12 d-inline d-lg-block float-right"
            v-model="microciclo.fecha_fin"
            v-on:change="onInputFechaFin"
            type="date"
            size="sm"
            required
          ></b-form-input>
          <p id="errFechaFin" class="font-weight-bold m-0 position-absolute small">{{ error_msg.fecha_fin }}</p>
        </b-col>
        <b-col cols="6" lg="1" class="mb-2 pl-lg-1">
          <label for="volumen" class="col-4 col-lg-12 d-inline d-lg-block font-weight-bold mb-1 pl-0">Vol.</label>
          <b-form-input
            id="volumen"
            class="col-6 col-sm-3 col-lg-12 d-inline d-lg-block float-right mr-2 mr-lg-0"
            v-model="microciclo.volumen"
            type="number"
            min="1"
            max="5"
            size="sm"
            required
          ></b-form-input>
        </b-col>
        <b-col cols="6" lg="1" class="mb-2 pl-lg-1">
          <label for="intensidad" class="col-4 col-lg-12 d-inline d-lg-block font-weight-bold mb-1 pl-0">Int.</label>
          <b-form-input
            id="intensidad"
            class="col-6 col-sm-3 col-lg-12 d-inline d-lg-block float-right"
            v-model="microciclo.intensidad"
            type="number"
            min="1"
            max="5"
            size="sm"
            required
          ></b-form-input>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" lg="7">
          <b-form-group label="Descripción:" label-for="descMesociclo" label-class="font-weight-bold">
            <b-form-textarea
              id="descMesociclo"
              v-model="microciclo.description"
              maxLength="1500"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
        </b-col>
        <b-col cols="12" lg="5">
          <b-form-group label="Objetivos:" label-for="objMesociclo" label-class="font-weight-bold">
            <b-form-textarea
              id="objMesociclo"
              v-model="microciclo.objetivos"
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
        mesociclo_dates: '',
        mesociclo_desc: '',
        show: false,
        tipos: []
      }
    },
    computed: mapState({
      macrociclo: state => state.plen.macrociclo,
      mesociclo: state => state.plen.mesociclo,
      microciclo: state => state.plen.microciclo
    }),
    created() {
      this.resetErrorMsg();
      this.getTiposMicrociclo().then( (res) => {
        this.setTiposMicrociclo(res);
        this.show = true;
      })
    },
    mounted() {
      this.$root.$on('bv::modal::show', (bvEvent, modalId) => {
        if( 'editMicrociclo' == bvEvent.target.id ) {
          this.resetErrorMsg();
        }
      });
    },
    updated() {
      this.macrociclo_desc = (this.macrociclo.description ? this.macrociclo.description : '-');
      this.macrociclo_dates = moment(this.macrociclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.macrociclo.fecha_fin).format('DD/MM/YYYY');
      this.mesociclo_desc = (this.mesociclo.description ? this.mesociclo.description : '-');
      this.mesociclo_dates = moment(this.mesociclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.mesociclo.fecha_fin).format('DD/MM/YYYY');
    },
    methods: {
      ...mapActions({
        getTiposMicrociclo: 'plen/getTiposMicrociclo'
      }),
      onInputFechaFin(f_fin) {
        this.error_msg.fecha_fin = '';
        if( f_fin <= this.macrociclo.fecha_ini || f_fin > this.macrociclo.fecha_fin ||
            f_fin < this.mesociclo.fecha_ini   || f_fin > this.mesociclo.fecha_fin  ||
            f_fin <= this.microciclo.fecha_ini ) {
          this.error_msg.fecha_fin = "La fecha debe estar dentro del intervalo";
        }
        this.setErrorMsgExists();
      },
      onInputFechaIni(f_ini) {
        this.error_msg.fecha_ini = '';
        if( f_ini < this.macrociclo.fecha_ini || f_ini > this.macrociclo.fecha_fin ||
            f_ini < this.mesociclo.fecha_ini  || f_ini > this.mesociclo.fecha_fin  ||
            f_ini >= this.microciclo.fecha_fin ) {
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
        this.$root.$emit('disable-modal-microciclo-save-button', this.error_msg.exists)
      },
      setTiposMicrociclo(arr) {
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
.mesociclo-wrap p {
  background-color:rgba(173, 216, 230, 0.55);
}
</style>
