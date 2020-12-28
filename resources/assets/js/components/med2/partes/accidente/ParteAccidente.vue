<template>
  <div v-if="show">
    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha parte:"
                      label-for="fecha_parte"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_parte"
                        type="date"
                        v-model="parte.fecha_parte"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha accidente:"
                      label-for="fecha_accidente"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_accidente"
                        type="date"
                        v-model="parte.fecha_accidente"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4" class="pt-1">
        <b-row>
          <b-col cols="12">
            <b-form-checkbox id="check_baja"
                             class="align-middle mr-0"
                             v-model="parte.is_baja"
                             value="1"
                             unchecked-value="0">
            </b-form-checkbox>
            <label for="check_baja">Baja médica</label>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-checkbox id="check_recaida"
                             class="align-middle mr-0"
                             v-model="parte.is_recaida"
                             value="1"
                             unchecked-value="0">
            </b-form-checkbox>
            <label for="check_recaida" class="mb-md-2">Recaída</label>
          </b-col>
        </b-row>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Centro de asistencia:"
                      label-for="centro_asistencia"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-select id="centro_asistencia"
                        :options="centros"
                        v-model="parte.med2_centro_id"
                        class="select pl-2 py-0">
          </b-form-select>
        </b-form-group>
      </b-col>
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
    </b-row>

    <b-row>
      <b-col cols="12" md="8">
        <b-form-group label="Parte del cuerpo:"
                      label-for="parte_cuerpo"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="parte_cuerpo"
                        type="text"
                        v-model="parte.parte_cuerpo"
                        maxlength="50"
                        placeholder="Parte del cuerpo"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Agente causante del accidente:"
                      label-for="agente_causante"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-select id="agente_causante"
                        :options="causantes"
                        v-model="parte.med2_causante_id"
                        class="select pl-2 py-0">
          </b-form-select>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="8">
        <b-form-group label="Diagnóstico inicial complementarias:"
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
        <b-form-group label="Pruebas:"
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
      <b-col cols="12">
        <b-form-group label="Observaciones diagnóstico:"
                      label-for="diagnostico_obs"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="diagnostico_obs"
                        type="text"
                        v-model="parte.diagnostico_obs"
                        maxlength="250"
                        placeholder="Observaciones"
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
      <b-col cols="12">
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
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    data() {
      return {
        causantes: [],
        centros: [],
        pruebas: [],
        tipos_asistencia: [],

        show: false
      }
    },
    computed: mapGetters({
      parte: 'med2/p_accidente',
      _causantes: 'med2/causantes',
      _centros: 'med2/centros',
      _pruebas: 'med2/pruebas',
      _tipos_asistencia: 'med2/tipos_asistencia',
    }),
    created() {
      this.causantes = this._causantes;
      this.causantes = JSON.stringify(this.causantes).replace(/id/g, 'value').replace(/desc/g, 'text');
      this.causantes = JSON.parse(this.causantes);
      this.causantes.unshift({ value: null, text: "Seleccionar causante" });

      this.centros = this._centros;
      this.centros = JSON.stringify(this.centros).replace(/id/g, 'value').replace(/desc/g, 'text');
      this.centros = JSON.parse(this.centros);
      this.centros.unshift({ value: null, text: "Seleccionar centro" });

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
