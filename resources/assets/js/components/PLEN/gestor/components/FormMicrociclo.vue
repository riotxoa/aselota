<template>
  <div>
    <b-form v-if="show" @submit="onSubmit" @reset="onReset">
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
      <b-row class="border-bottom mb-3 mesociclo-wrap">
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
      <b-row>
        <b-col cols="4">
          <b-form-group label="Tipo Microciclo:" label-for="tipoMicrociclo" label-class="font-weight-bold">
            <b-form-select
              id="tipoMicrociclo"
              v-model="microciclo.tipo_microciclo_id"
              :options="tipos"
              required
            ></b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="3" class="pl-0">
          <b-form-group label="Fecha inicio:" label-for="fechaInicio" label-class="font-weight-bold">
            <b-form-input
              id="fechaInicio"
              v-model="microciclo.fecha_ini"
              type="date"
              required
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="3" class="pl-0">
          <b-form-group label="Fecha fin:" label-for="fechaFin" label-class="font-weight-bold">
            <b-form-input
              id="fechaFin"
              v-model="microciclo.fecha_fin"
              type="date"
              required
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="1" class="pl-0">
          <b-form-group label="Vol." label-for="volumen" label-class="font-weight-bold">
            <b-form-input
              id="volumen"
              v-model="microciclo.volumen"
              type="number"
              min="1"
              max="5"
              required
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="1" class="pl-0">
          <b-form-group label="Int." label-for="intensidad" label-class="font-weight-bold">
            <b-form-input
              id="intensidad"
              v-model="microciclo.intensidad"
              type="number"
              min="1"
              max="5"
              required
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" md="8">
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
        <b-col cols="12" md="4">
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
      this.getTiposMicrociclo().then( (res) => {
        this.setTiposMicrociclo(res);
        this.show = true;
      })
    },
    updated() {
      this.macrociclo_desc = this.macrociclo.description;
      this.macrociclo_dates = moment(this.macrociclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.macrociclo.fecha_fin).format('DD/MM/YYYY');
      this.mesociclo_desc = this.mesociclo.description;
      this.mesociclo_dates = moment(this.mesociclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.mesociclo.fecha_fin).format('DD/MM/YYYY');
    },
    methods: {
      ...mapActions({
        getTiposMicrociclo: 'plen/getTiposMicrociclo'
      }),
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
      },
      onReset() {
        alert("RESET")
      },
      onSubmit() {
        alert("SUBMIT")
      }
    }
  }
</script>

<style scoped>
.macrociclo-wrap p {
  background-color:rgba(255, 192, 203, 0.55);
}
.mesociclo-wrap p {
  background-color:rgba(173, 216, 230, 0.55);
}
</style>
