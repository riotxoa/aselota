<template>
  <div>
    <b-form v-if="show">
      <b-row>
        <b-col cols="12">
          <b-form-group label="Macrociclo:" label-for="descMacrociclo" label-class="font-weight-bold">
            <b-row>
              <b-col cols="4">
                <b-form-input
                  id="datesMacrociclo"
                  v-model="macrociclo_dates"
                  disabled
                ></b-form-input>
              </b-col>
              <b-col cols="8">
                <b-form-input
                  id="descMacrociclo"
                  v-model="macrociclo_desc"
                  maxLength="50"
                  disabled
                ></b-form-input>
              </b-col>
            </b-row>
          </b-form-group>
        </b-col>
        <b-col cols="4">
          <b-form-group label="Tipo Mesociclo:" label-for="tipoMesociclo" label-class="font-weight-bold">
            <b-form-select
              id="tipoMesociclo"
              v-model="mesociclo.tipo_mesociclo_id"
              :options="tipos"
              required
            ></b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="4">
          <b-form-group label="Fecha inicio:" label-for="fechaInicio" label-class="font-weight-bold">
            <b-form-input
              id="fechaInicio"
              v-model="mesociclo.fecha_ini"
              type="date"
              required
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="4">
          <b-form-group label="Fecha fin:" label-for="fechaFin" label-class="font-weight-bold">
            <b-form-input
              id="fechaFin"
              v-model="mesociclo.fecha_fin"
              type="date"
              required
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="8">
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
        <b-col cols="12" md="4">
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
      this.getTiposMesociclo().then( (res) => {
        this.setTiposMesociclo(res);
        this.show = true;
      })
    },
    updated() {
      this.macrociclo_desc = this.macrociclo.description;
      this.macrociclo_dates = moment(this.macrociclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.macrociclo.fecha_fin).format('DD/MM/YYYY');
    },
    methods: {
      ...mapActions({
        getTiposMesociclo: 'plen/getTiposMesociclo'
      }),
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
</style>
