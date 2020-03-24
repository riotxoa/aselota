<template>
  <div v-if="_header.is_baja">
    <b-button
      class="mb-0 no-border py-1 toolbar w-100"
      v-bind:class="{ 'collapsed' : !visible }"
      :aria-expanded="visible ? 'true' : 'false'"
      aria-controls="collapse-4"
      @click="visible = !visible"
    >
        <div class="container">
          <h4 class="text-white text-uppercase text-left font-weight-bold m-0 py-2">
            Parte DELTA
            <span class="float-right">
              <i v-if="visible" class="fa fa-caret-up" aria-hidden="true"></i>
              <i v-else class="fa fa-caret-down" aria-hidden="true"></i>
            </span>
          </h4>
        </div>
    </b-button>
    <b-collapse id="collapse-4" v-model="visible" class="header mt-0 pb-0">
      <b-card>
        <div class="container">

          <b-row>
            <!-- Fecha del accidente -->
            <b-form-group label="Fecha accidente:"
                          label-for="fechaAccidenteInput"
                          class="col-sm-3 font-weight-bold">
              <b-form-input id="fechaAccidenteInput"
                            type="date"
                            v-model="_header.fecha_accidente">
              </b-form-input>
            </b-form-group>

            <!-- Fecha baja médica -->
            <b-form-group label="Fecha baja médica:"
                          label-for="fechaBajaMedicaInput"
                          class="col-sm-3 font-weight-bold">
              <b-form-input id="fechaBajaMedicaInput"
                            type="date"
                            v-model="_header.fecha_baja">
              </b-form-input>
            </b-form-group>

            <!-- Fecha baja médica -->
            <b-form-group label="Fecha recaída:"
                          label-for="fechaRecaidaInput"
                          class="col-sm-3 font-weight-bold">
              <b-form-input id="fechaRecaidaInput"
                            type="date"
                            v-model="_delta.fecha_recaida">
              </b-form-input>
            </b-form-group>

            <!-- Fecha del accidente recaída-->
            <b-form-group label="Fecha accidente recaída:"
                          label-for="fechaAccidenteInput"
                          class="col-sm-3 font-weight-bold">
              <b-form-input id="fechaAccidenteInput"
                            type="date"
                            v-model="_delta.fecha_accidente_recaida">
              </b-form-input>
            </b-form-group>
          </b-row>

          <b-row>
            <!-- Lugar del accidente -->
            <b-form-group label="Lugar del accidente:"
                          label-for="lugarAccidenteInput"
                          class="col-sm-3 font-weight-bold">
              <b-form-select id="lugarAccidenteInput"
                             :options="_partes_aux.lugares"
                             v-model="_delta.fronton_id">
              </b-form-select>
            </b-form-group>

            <!-- Material o agente causante del accidente -->
            <b-form-group label="Material o agente causante del accidente:"
                          label-for="causanteAccidenteInput"
                          class="col-sm-9 font-weight-bold">
              <b-form-select id="causanteAccidenteInput" class="col-sm-4 d-inline px-0-rk mx-0"
                             :options="_partes_aux.causantes"
                             v-model="_delta.med_causante_id">
              </b-form-select>
              <b-form-input id="causanteAccidenteTxtInput" class="col-sm-8 d-inline float-right px-0-rk mx-0"
                            v-model="_delta.med_causante_txt">
              </b-form-input>
            </b-form-group>
          </b-row>

          <b-row>
            <!-- Tiempo trabajo del accidente -->
            <b-form-group label="Tiempo trabajo accidente:"
                          label-for="tiempoTrabajoAccidenteInput"
                          class="col-sm-3 font-weight-bold">
              <b-form-select id="tiempoTrabajoAccidenteInput"
                             :options="_partes_aux.tiempos_trabajo"
                             v-model="_delta.med_tiempo_trabajo_id">
              </b-form-select>
            </b-form-group>

            <!-- Descripción del Accidente -->
            <b-form-group label="Descripción del accidente:"
                          label-for="descrAccidenteInput"
                          class="col-sm-9 font-weight-bold">
              <b-form-input id="descrAccidenteInput"
                            v-model="_delta.desc_accidente">
              </b-form-input>
            </b-form-group>
          </b-row>

          <b-row>
            <!-- Parte del cuerpo -->
            <b-form-group label="Parte del cuerpo:"
                          label-for="parteCuerpoInput"
                          class="col-sm-3 font-weight-bold">
              <b-form-select id="parteCuerpoInput"
                             :options="_partes_aux.partes_cuerpo"
                             v-model="_delta.med_parte_cuerpo_id">
              </b-form-select>
            </b-form-group>

            <!-- Descripción de la lesión -->
            <b-form-group label="Descripción lesión:"
                          label-for="descripcionLesionInput"
                          class="col-sm-9 font-weight-bold">
              <b-form-select id="descripcionLesionInput" class="col-sm-4 d-inline px-0-rk mx-0"
                             :options="_partes_aux.lesiones_dsc"
                             v-model="_delta.med_lesion_id">
              </b-form-select>
              <b-form-input id="descripcionLesionTxtInput" class="col-sm-8 d-inline float-right px-0-rk mx-0"
                            v-model="_delta.med_lesion_txt">
              </b-form-input>
            </b-form-group>
          </b-row>

          <b-row>
            <!-- Grado de lesión -->
            <b-form-group label="Grado de lesión:"
                          label-for="gradoLesionInput"
                          class="col-sm-3 font-weight-bold">
              <b-form-select id="gradoLesionInput"
                             :options="_partes_aux.grados_lesion"
                             v-model="_delta.med_grado_lesion_id">
              </b-form-select>
            </b-form-group>

            <!-- Médico 1ª asistencia -->
            <b-form-group label="Médico 1ª asistencia:"
                          label-for="medicoAsistenciaInput"
                          class="col-sm-6 font-weight-bold">
              <b-form-select id="medicoAsistenciaInput" class="col-sm-6 d-inline px-0-rk mx-0"
                             :options="_partes_aux.medicos"
                             v-model="_delta.med_medico_id">
              </b-form-select>
              <b-form-input id="medicoAsistenciaTxtInput" class="col-sm-6 d-inline float-right px-0-rk mx-0"
                            v-model="_delta.med_medico_txt">
              </b-form-input>
            </b-form-group>

            <!-- Hora AT -->
            <b-form-group label="Hora AT:"
                          label-for="horaATInput"
                          class="col-sm-3 font-weight-bold">
              <b-form-input id="horaATInput"
                            type="time"
                            v-model="_delta.hora_at">
              </b-form-input>
            </b-form-group>
          </b-row>

          <b-row>
            <!-- Tipo de atención -->
            <b-form-group label="Tipo de atención:"
                          label-for="tipoAtencionInput"
                          class="col-sm-3 font-weight-bold">
              <b-form-select id="tipoAtencionInput"
                             :options="_partes_aux.tipos_atencion"
                             v-model="_delta.med_tipo_atencion_id">
              </b-form-select>
            </b-form-group>

            <!-- Tiempo duración prevista AT -->
            <b-form-group label="Tiempo duración prevista lt por AT:"
                          label-for="tiempoDuracionPrvtaInput"
                          class="col-sm-9 font-weight-bold">
              <b-form-input id="tiempoDuracionPrvtaInput" class="col-sm-4 d-inline px-0-rk mx-0"
                             v-model="_delta.tiempo_previsto">
              </b-form-input>
              <b-form-input id="tiempoDuracionPrvtaTxtInput" class="col-sm-8 d-inline float-right px-0-rk mx-0"
                            v-model="_delta.tiempo_previsto_txt">
              </b-form-input>
            </b-form-group>
          </b-row>

          <b-row>
            <b-col cols="12" class="mt-3 text-right">
              <small v-if="disableSaving()" class="mx-3">Debe introducir una <strong>Fecha de Parte</strong> y un <strong>Pelotari</strong> para poder guardar los partes</small>
              <b-button variant="danger" @click="onClickSave" :disabled="disableSaving()">Guardar</b-button>
            </b-col>
          </b-row>

        </div>
      </b-card>
    </b-collapse>

  </div>
</template>

<script>
  import { mapState } from 'vuex';

  export default {
    props: ['onSave'],
    data() {
      return {
        visible: true,
      }
    },
    computed: mapState({
      _delta: 'parte_delta',
      _edit: 'edit',
      _header: 'parte',
      _partes_aux: 'partes_aux',
    }),
    created: function() {
      console.log("Parte Delta created")
    },
    methods: {
      disableSaving() {
        return (null == this._header.fecha_parte || null == this._header.pelotari_id);
      },
      onClickSave(evt) {
        evt.preventDefault();
        this.onSave();
      }
    }

  }
</script>

<style focused>
  .header .form-control {
    padding:0.135rem 0.75rem;
  }
  .no-border {
    border:none!important;
  }
  .toolbar {
    background-color:#1d3c5a;
  }
</style>
