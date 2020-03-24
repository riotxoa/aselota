<template>
  <div class="header">

      <b-form @submit="onSubmit" @reset="onReset" v-if="show">
        <div class="toolbar mb-0 py-1">
          <div class="container">
            <b-row>
              <div class="col-sm-4"></div>
              <div class="col-sm-4"><h4 class="text-white text-uppercase text-center font-weight-bold m-0">{{ this.formTitle }}</h4></div>
              <div class="col-sm-4 text-right">
                <b-button type="submit" variant="danger" :disabled="disableSaving()">Guardar</b-button>
                <b-button type="reset" variant="default">Volver</b-button>
              </div>
            </b-row>
          </div>
        </div>
        <div class="container">

          <b-row class="pt-4">
            <!-- Fecha parte -->
            <b-form-group label="Fecha parte:"
                          label-for="fechaParteInput"
                          class="col-sm-3">
              <b-form-input id="fechaParteInput"
                            type="date"
                            v-model="_header.fecha_parte"
                            @input="updateParteFechaParte"
                            required>
              </b-form-input>
            </b-form-group>

            <!-- Pelotari -->
            <b-form-group label="Pelotari:"
                          label-for="pelotariInput"
                          class="col-sm-3">
              <b-form-select id="pelotariInput"
                             :options="pelotaris"
                             v-model="_header.pelotari_id"
                             @input="updatePartePelotari"
                             required>
              </b-form-select>
            </b-form-group>

            <!-- Fecha del accidente -->
            <b-form-group label="Fecha accidente:"
                          label-for="fechaAccidenteInput"
                          class="col-sm-3">
              <b-form-input id="fechaAccidenteInput"
                            type="date"
                            v-model="_header.fecha_accidente"
                            @input="updateParteFechaAccidente">
              </b-form-input>
            </b-form-group>

            <!-- Recaída -->
            <b-form-group label="&nbsp;"
                          label-for="recaidaInput"
                          class="col-sm-1">
              <b-form-checkbox
                v-model="_header.is_recaida"
                name="check-button"
                value="1"
                unchecked-value="0"
                @input="updateParteIsRecaida">
                  Recaída
              </b-form-checkbox>
            </b-form-group>

            <!-- Baja médica -->
            <b-form-group label="&nbsp;"
                          label-for="bajaInput"
                          class="col-sm-2 text-right">
              <b-form-checkbox
                v-model="_header.is_baja"
                name="check-button"
                value="1"
                unchecked-value="0"
                @input="updateParteIsBaja">
                Baja médica
              </b-form-checkbox>
            </b-form-group>
          </b-row>

          <b-row>
            <!-- Diagnóstico inicial -->
            <b-form-group label="Diagnóstico inicial:"
                          label-for="diagnosticoInicialInput"
                          class="col-sm-3">
              <b-form-select id="diagnosticoInicialInput"
                             :options="_partes_aux.diagnosticos"
                             v-model="_header.med_diagnostico_id"
                             @input="updateParteDiagnostico">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Obs. diagnóstico:"
                          label-for="diagnosticoInicialInput"
                          class="col-sm-3">
              <b-form-input id="diagnosticoInicialInput"
                            v-model="_header.med_diagnostico_txt"
                            @input="updateParteDiagnosticoTxt">
              </b-form-input>
            </b-form-group>

            <!-- Asistencia en otro centro -->
            <b-form-group label="Asistencia en otro centro:"
                          label-for="asistenciaCentroInput"
                          class="col-sm-3">
              <b-form-select id="asistenciaCentroInput"
                             :options="_partes_aux.centros"
                             v-model="_header.med_centro_id"
                             @input="updateParteCentro">
              </b-form-select>
            </b-form-group>

            <!-- Tipo de asistencia externa -->
            <b-form-group label="Tipo de asistencia externa:"
                          label-for="asistenciaExtInput"
                          class="col-sm-3">
              <b-form-select id="asistenciaExtInput"
                             :options="_partes_aux.tipos_asistencia"
                             v-model="_header.med_tipo_asistencia_id"
                             @input="updateParteTipoAsistencia">
              </b-form-select>
            </b-form-group>
          </b-row>

          <b-row>
            <!-- Evolución -->
            <b-form-group label="Evolución:"
                          label-for="evolucionInput"
                          class="col-sm-3">
              <b-form-select id="evolucionInput"
                             :options="_partes_aux.evoluciones"
                             v-model="_header.med_evolucion_id"
                             @input="updateParteEvolucion">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Obs. evolución:"
                          label-for="evolucion_txtInput"
                          class="col-sm-3">
              <b-form-input id="evolucion_txtInput"
                            v-model="_header.med_evolucion_txt"
                            @input="updateParteEvolucionTxt">
              </b-form-input>
            </b-form-group>

            <!-- Tratamiento -->
            <b-form-group label="Tratamiento:"
                          label-for="tratamientoInput"
                          class="col-sm-3">
              <b-form-select id="tratamientoInput"
                             :options="_partes_aux.tratamientos"
                             v-model="_header.med_tratamiento_id"
                             @input="updateParteTratamiento">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Obs. tratamiento:"
                          label-for="tratamiento_txtInput"
                          class="col-sm-3">
              <b-form-input id="tratamiento_txtInput"
                            v-model="_header.med_tratamiento_txt"
                            @input="updateParteTratamientoTxt">
              </b-form-input>
            </b-form-group>
          </b-row>

          <b-row>
            <!-- Fecha de próxima consulta -->
            <b-form-group label="Fecha próxima consulta:"
                          label-for="fechaProxConsultaInput"
                          class="col-sm-3">
              <b-form-input id="fechaProxConsultaInput"
                            type="date"
                            v-model="_header.fecha_proxima_consulta"
                            @input="updateParteFechaProximaConsulta">
              </b-form-input>
            </b-form-group>

            <!-- Fecha de siguiente consulta -->
            <b-form-group label="Siguiente:"
                          label-for="fechaSiguienteConsultaInput"
                          class="col-sm-3">
              <b-form-input id="fechaSiguienteConsultaInput"
                            type="date"
                            v-model="_header.fecha_siguiente"
                            @input="updateParteFechaSiguiente">
              </b-form-input>
            </b-form-group>

            <!-- Fecha de alta médica -->
            <b-form-group label="Fecha alta médica:"
                          label-for="fechaAltaInput"
                          class="col-sm-3">
              <b-form-input id="fechaAltaInput"
                            type="date"
                            v-model="_header.fecha_alta"
                            @input="updateParteFechaAlta">
              </b-form-input>
            </b-form-group>

            <!-- Motivo del alta -->
            <b-form-group label="Motivo:"
                          label-for="motivoInput"
                          class="col-sm-3">
              <b-form-select id="motivoInput"
                             :options="_partes_aux.motivos_alta"
                             v-model="_header.med_motivo_alta_id"
                             @input="updateParteMotivoAlta">
              </b-form-select>
            </b-form-group>
          </b-row>

        </div>
      </b-form>
  </div>
</template>

<script>
  import { store } from '../store/store';
  import { mapActions, mapState } from 'vuex';

  import APIGetters from '../utils/getters.js';
  import Nav from '../utils/nav.js';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [APIGetters, Nav, Utils],
    props: ['formTitle', 'onSave'],
    data () {
      return {
        loaded: false,
        show: false,
        destroy: true,
      }
    },
    computed: mapState({
      _header: 'parte',
      _parte_lesion: 'parte_lesion',
      _parte_delta: 'parte_delta',
      _edit: 'parte_edit',
      _partes_aux: 'partes_aux',
    }),
    created: function () {
      console.log("Parte Header created");

      this.getPelotaris();

      if (this._edit) {
        this.show = true;
      } else {
        this._header.fecha_parte = this.formatDateEN();
        this.show = true;
        }
    },
    methods: {
      ...mapActions([
        'updateParteFechaParte',
        'updateParteIsBaja',
        'updatePartePelotari',
        'updateParteFechaAccidente',
        'updateParteFechaAlta',
        'updateParteFechaProximaConsulta',
        'updateParteFechaSiguiente',
        'updateParteDiagnostico',
        'updateParteDiagnosticoTxt',
        'updateParteCentro',
        'updateParteTipoAsistencia',
        'updateParteMotivoAlta',
        'updateParteEvolucion',
        'updateParteEvolucionTxt',
        'updateParteTratamiento',
        'updateParteTratamientoTxt',
        'updateParteIsRecaida',
      ]),
      disableSaving() {
        return (null == this._header.fecha_parte || null == this._header.pelotari_id);
      },
      onSubmit (evt) {
        evt.preventDefault();
        this.onSave();
      },
      onReset (evt) {
        evt.preventDefault();
        this.goBack();
      }
    }
  }
</script>

<style focused>
  .header {
    background-color:white;
    border-bottom:10px solid slategray;
    margin-top:-1.45rem;
    padding-bottom:.25rem;
    padding-top:0;
  }
  .header .toolbar {
    background-color:slategray;
  }
  .header .toolbar.lightgray {
    background-color:lightgray;
  }
  .header h4 {
    line-height:1.75;
  }
  .header label {
    font-weight:bold;
  }
  .header .form-group {
    margin-bottom:.5rem;
  }
  .header .form-control {
    padding: 0.075rem 0.75rem;
  }
  .header select.form-control:not([size]):not([multiple]) {
    height: calc(1.71rem + 2px);
  }
</style>
