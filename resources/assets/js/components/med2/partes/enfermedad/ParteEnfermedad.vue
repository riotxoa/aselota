<template>
  <div v-if="show">
    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha asistencia:"
                      label-for="fecha_asistencia"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_asistencia"
                        type="date"
                        v-model="parte.fecha_asistencia"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha inicio:"
                      label-for="fecha_inicio"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_inicio"
                        type="date"
                        v-model="parte.fecha_inicio"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Tipo de asistencia:"
                      label-for="tipo_asistencia"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-select id="tipo_asistencia"
                        :options="tipos_asistencia"
                        v-model="parte.med2_tipo_asistencia_id"
                        class="select pl-2 py-0">
          </b-form-select>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Pruebas complementarias:"
                      label-for="pruebas"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-select id="pruebas"
                        :options="pruebas"
                        v-model="parte.med2_prueba_id"
                        class="select pl-2 py-0">
          </b-form-select>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Diagnóstico inicial:"
                      label-for="diagnostico_inicial"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="diagnostico_inicial"
                        type="text"
                        v-model="parte.diagnostico_ini"
                        maxlength="150"
                        placeholder="Diagnóstico inicial"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Tratamiento:"
                      label-for="tratamiento"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="tratamiento"
                        type="text"
                        v-model="parte.tratamiento"
                        maxlength="250"
                        placeholder="Tratamiento"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12">
        <b-form-group label="Evolución:"
                      label-for="evolucion"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-textarea id="evolucion"
                        v-model="parte.evolucion"
                        maxlength="3500"
                        placeholder="Evolución"
                        rows="3"
                        max-rows="6"
                        size="sm">
          </b-form-textarea>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha alta:"
                      label-for="fecha_alta"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_alta"
                        type="date"
                        v-model="parte.fecha_alta"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    data() {
      return {
        pruebas: [],
        tipos_asistencia: [],
        show: false
      }
    },
    computed: mapGetters({
      parte: 'med2/p_enfermedad',
      _pruebas: 'med2/pruebas',
      _tipos_asistencia: 'med2/tipos_asistencia',
    }),
    created() {
      this.pruebas = this._pruebas;
      this.pruebas = JSON.stringify(this.pruebas).replace(/id/g, 'value').replace(/desc/g, 'text');
      this.pruebas = JSON.parse(this.pruebas);
      this.pruebas.unshift({ value: null, text: "Seleccionar prueba" });

      this.tipos_asistencia = this._tipos_asistencia;
      this.tipos_asistencia = JSON.stringify(this.tipos_asistencia).replace(/id/g, 'value').replace(/desc/g, 'text');
      this.tipos_asistencia = JSON.parse(this.tipos_asistencia);
      this.tipos_asistencia.unshift({ value: null, text: "Seleccionar tipo de asistencia" });

      this.show = true;
    },
  }
</script>

<style scoped>
  .select {
    height:28px!important;
  }
</style>
