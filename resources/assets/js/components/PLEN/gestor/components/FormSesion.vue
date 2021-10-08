<template>
  <div>
    <b-form v-if="show">
      <b-row class="macrociclo-wrap">
        <b-col cols="2">
          <label class="font-weight-bold">Macrociclo:</label>
        </b-col>
        <b-col cols="3" class="px-0">
          <p class="border px-2">{{ macrociclo_dates }}</p>
        </b-col>
        <b-col cols="7">
          <p class="border px-2">{{ macrociclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row class="mesociclo-wrap">
        <b-col cols="2">
          <label class="font-weight-bold">Mesociclo:</label>
        </b-col>
        <b-col cols="3" class="px-0">
          <p class="border px-2">{{ mesociclo_dates }}</p>
        </b-col>
        <b-col cols="7">
          <p class="border px-2">{{ mesociclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row class="border-bottom mb-3 microciclo-wrap">
        <b-col cols="2">
          <label class="font-weight-bold">Microciclo:</label>
        </b-col>
        <b-col cols="3" class="px-0">
          <p class="border px-2">{{ microciclo_dates }}</p>
        </b-col>
        <b-col cols="7">
          <p class="border px-2">{{ microciclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="3">
          <b-form-group label="Fecha:" label-for="fecha" label-class="font-weight-bold">
            <b-form-input
              id="fecha"
              v-model="sesion.fecha"
              v-on:input="onInputFecha"
              type="date"
              required
            ></b-form-input>
            <p id="errFecha" class="font-weight-bold m-0 position-absolute small">{{ error_msg.fecha }}</p>
          </b-form-group>
        </b-col>
        <b-col cols="3" class="pl-0">
          <b-form-group label="Hora:" label-for="hora" label-class="font-weight-bold">
            <b-form-input
              id="hora"
              v-model="sesion.hora"
              type="time"
              required
            ></b-form-input>
            <p id="errHora" class="font-weight-bold m-0 position-absolute small">{{ error_msg.hora }}</p>
          </b-form-group>
        </b-col>
        <b-col cols="6" class="pl-0">
          <b-form-group label="Lugar:" label-for="lugar" label-class="font-weight-bold">
            <b-form-select
              id="fronton"
              v-model="sesion.fronton_id"
              :options="frontones"
              required
            ></b-form-select>
          </b-form-group>
        </b-col>
      </b-row>
    </b-form>
  </div>
</template>

<script>
  import { mapState } from 'vuex';
  import APIGetters from '../../../utils/getters.js';
  import moment from 'moment';

  export default {
    mixins: [ APIGetters ],
    data() {
      return {
        error_msg: null,
        macrociclo_dates: '',
        macrociclo_desc: '',
        mesociclo_dates: '',
        mesociclo_desc: '',
        microciclo_dates: '',
        microciclo_desc: '',
        show: true
      }
    },
    computed: mapState({
      macrociclo: state => state.plen.macrociclo,
      mesociclo: state => state.plen.mesociclo,
      microciclo: state => state.plen.microciclo,
      sesion: state => state.plen.sesion,
    }),
    created() {
      this.resetErrorMsg();
      this.getFrontones();
    },
    mounted() {
      this.$root.$on('bv::modal::show', (bvEvent, modalId) => {
        if( 'editSesion' == bvEvent.target.id ) {
          this.resetErrorMsg();
        }
      });
    },
    updated() {
      this.macrociclo_desc = (this.macrociclo.description ? this.macrociclo.description : '-');
      this.macrociclo_dates = moment(this.macrociclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.macrociclo.fecha_fin).format('DD/MM/YYYY');
      this.mesociclo_desc = (this.mesociclo.description ? this.mesociclo.description : '-');
      this.mesociclo_dates = moment(this.mesociclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.mesociclo.fecha_fin).format('DD/MM/YYYY');
      this.microciclo_desc = (this.microciclo.description ? this.microciclo.description : '-');
      this.microciclo_dates = moment(this.microciclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.microciclo.fecha_fin).format('DD/MM/YYYY');
    },
    methods: {
      onInputFecha(fecha) {
        this.error_msg.fecha = '';
        if( fecha < this.macrociclo.fecha_ini || fecha > this.macrociclo.fecha_fin ||
            fecha < this.mesociclo.fecha_ini  || fecha > this.mesociclo.fecha_fin  ||
            fecha < this.microciclo.fecha_ini || fecha > this.microciclo.fecha_fin ) {
          this.error_msg.fecha = "La fecha debe estar dentro del intervalo";
        }
        this.setErrorMsgExists();
      },
      resetErrorMsg() {
        this.error_msg = {
          exists: false,
          fecha: ''
        };
      },
      setErrorMsgExists() {
        this.error_msg.exists = this.error_msg.fecha.length > 0;
        this.$root.$emit('disable-modal-sesion-save-button', this.error_msg.exists);
      },
      setFrontones(arr) {
        this.tipos.push({
          value: null,
          text: "Seleccionar Frontón"
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
#errFecha {
  color:red;
  line-height:1.1;
  padding-top:5px;
}
.macrociclo-wrap p {
  background-color:rgba(255, 192, 203, 0.55);
}
.mesociclo-wrap p {
  background-color:rgba(173, 216, 230, 0.55);
}
.microciclo-wrap p {
  background-color:rgba(173, 230, 183, 0.55);
}
</style>
