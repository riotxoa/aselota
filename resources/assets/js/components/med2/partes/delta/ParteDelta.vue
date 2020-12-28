<template>
  <div v-if="show">
    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha accidente:"
                      label-for=""
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_accidente"
                        type="date"
                        v-model="delta.fecha_accidente"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha baja:"
                      label-for="fecha_baja"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_baja"
                        type="date"
                        v-model="delta.fecha_baja"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha recaída:"
                      label-for="fecha_recaida"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_recaida"
                        type="date"
                        v-model="delta.fecha_recaida"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha accidente recaída:"
                      label-for="fecha_accidente_rec"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_accidente_rec"
                        type="date"
                        v-model="delta.fecha_accidente_rec"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Lugar:"
                      label-for="lugar"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-select id="lugar"
                        :options="lugares"
                        v-model="delta.med2_lugar_id"
                        class="select pl-2 py-0">
          </b-form-select>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Material o agente causante del acc.:"
                      label-for="causante"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-select id="causante"
                        :options="causantes"
                        v-model="delta.med2_causante_id"
                        class="select pl-2 py-0">
          </b-form-select>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Tiempo trabajo accidente:"
                      label-for="tiempo_trabajo"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-select id="tiempo_trabajo"
                        :options="tiempos_trabajo"
                        v-model="delta.med2_tiempo_trabajo_id"
                        class="select pl-2 py-0">
          </b-form-select>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="8">
        <b-form-group label="Parte del cuerpo lesionada:"
                      label-for="parte_cuerpo"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="parte_cuerpo"
                        type="text"
                        v-model="delta.parte_cuerpo"
                        maxlength="50"
                        placeholder="Parte del cuerpo lesionada"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Grado de lesión:"
                      label-for="grado_lesion"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-select id="grado_lesion"
                        :options="grados_lesion"
                        v-model="delta.med2_grado_lesion_id"
                        class="select pl-2 py-0">
          </b-form-select>
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group label="Descripción de la lesión:"
                      label-for="descripcion"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-textarea id="descripcion"
                        v-model="delta.descripcion_lesion"
                        maxlength="3500"
                        placeholder="Descripción de la lesión"
                        rows="3"
                        max-rows="6"
                        size="sm">
          </b-form-textarea>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Médico 1ª asistencia:"
                      label-for="medico_id"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-select id="medico_id"
                        :options="medicos"
                        v-model="delta.med2_medico_id"
                        class="select pl-2 py-0">
          </b-form-select>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="8">
        <b-form-group label="&nbsp;"
                      label-for="medico_txt"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="medico_txt"
                        type="text"
                        v-model="delta.med2_medico_txt"
                        maxlength="150"
                        placeholder="Otro"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Tipo de atención:"
                      label-for="tipo_atencion"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-select id="tipo_atencion"
                        :options="tipos_asistencia"
                        v-model="delta.med2_tipo_asistencia_id"
                        class="select pl-2 py-0">
          </b-form-select>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Hora del AT:"
                      label-for="hora_at"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="hora_at"
                        type="time"
                        v-model="delta.hora_at"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Tiempo duración previso IT:"
                      label-for="tiempo_previsto"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="tiempo_previsto"
                        type="text"
                        v-model="delta.tiempo_previsto"
                        maxlength="150"
                        placeholder="Tiempo previsto"
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
        grados_lesion: [],
        lugares: [],
        medicos: [],
        tiempos_trabajo: [],
        tipos_asistencia: [],
        show: false
      }
    },
    computed: mapGetters({
      delta: 'med2/p_delta',
      _causantes: 'med2/causantes',
      _grados_lesion: 'med2/grados_lesion',
      _lugares: 'med2/lugares',
      _medicos: 'med2/medicos',
      _tiempos_trabajo: 'med2/tiempos_trabajo',
      _tipos_asistencia: 'med2/tipos_asistencia',
    }),
    created() {
      this.causantes = this._causantes;
      this.causantes = JSON.stringify(this.causantes).replace(/id/g, 'value').replace(/desc/g, 'text');
      this.causantes = JSON.parse(this.causantes);
      this.causantes.unshift({ value: null, text: "Seleccionar causante" });

      this.grados_lesion = this._grados_lesion;
      this.grados_lesion = JSON.stringify(this.grados_lesion).replace(/id/g, 'value').replace(/desc/g, 'text');
      this.grados_lesion = JSON.parse(this.grados_lesion);
      this.grados_lesion.unshift({ value: null, text: "Seleccionar grado" });

      this.lugares = this._lugares;
      this.lugares = JSON.stringify(this.lugares).replace(/id/g, 'value').replace(/desc/g, 'text');
      this.lugares = JSON.parse(this.lugares);
      this.lugares.unshift({ value: null, text: "Seleccionar lugar" });

      this.medicos = this._medicos;
      this.medicos = JSON.stringify(this.medicos).replace(/id/g, 'value').replace(/desc/g, 'text');
      this.medicos = JSON.parse(this.medicos);
      this.medicos.unshift({ value: null, text: "Seleccionar médico" });

      this.tiempos_trabajo = this._tiempos_trabajo;
      this.tiempos_trabajo = JSON.stringify(this.tiempos_trabajo).replace(/id/g, 'value').replace(/desc/g, 'text');
      this.tiempos_trabajo = JSON.parse(this.tiempos_trabajo);
      this.tiempos_trabajo.unshift({ value: null, text: "Seleccionar tiempo" });

      this.tipos_asistencia = this._tipos_asistencia;
      this.tipos_asistencia = JSON.stringify(this.tipos_asistencia).replace(/id/g, 'value').replace(/desc/g, 'text');
      this.tipos_asistencia = JSON.parse(this.tipos_asistencia);
      this.tipos_asistencia.unshift({ value: null, text: "Seleccionar tipo de asistencia" });

      this.show = true;


    }
  }
</script>

<style scoped>
  .select {
    height:28px!important;
  }
</style>
