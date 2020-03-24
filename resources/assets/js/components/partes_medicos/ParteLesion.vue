<template>
  <div class="container">
    <b-card
      header="Lesión | Asistencia"
      header-class="parte-header mx-5"
      header-tag="h5"
      body-class="parte-body background-white border"
      class="header mt-4 no-background no-border"
    >

      <b-row>
        <!-- Parte del cuerpo -->
        <b-form-group label="Parte del cuerpo:"
                      label-for="parteCuerpoInput"
                      class="col-sm-3 font-weight-bold">
          <b-form-select id="parteCuerpoInput"
                         :options="_partes_aux.partes_cuerpo"
                         v-model="_lesion.med_partes_cuerpo_id"
                         @input="updateLesionPartesCuerpo">
          </b-form-select>
        </b-form-group>

        <!-- Descripción de la lesión -->
        <b-form-group label="Descripción lesión:"
                      label-for="descripcionLesionInput"
                      class="col-sm-9 font-weight-bold">
          <b-form-select id="descripcionLesionInput" class="col-sm-4 d-inline px-0-rk mx-0"
                         :options="_partes_aux.lesiones_dsc"
                         v-model="_lesion.med_lesion_desc_id"
                         @input="updateLesionLesionDesc">
          </b-form-select>
          <b-form-input id="descripcionLesionTxtInput" class="col-sm-8 d-inline float-right px-0-rk mx-0"
                        v-model="_lesion.med_partes_cuerpo_txt"
                        @input="updateLesionPartesCuerpoTxt">
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
                         v-model="_lesion.med_grado_lesion_id"
                         @input="updateLesionGradoLesion">
          </b-form-select>
        </b-form-group>

        <!-- Médico 1ª asistencia -->
        <b-form-group label="Médico 1ª asistencia:"
                      label-for="medicoAsistenciaInput"
                      class="col-sm-9 font-weight-bold">
          <b-form-select id="medicoAsistenciaInput" class="col-sm-4 d-inline px-0-rk mx-0"
                         :options="_partes_aux.medicos"
                         v-model="_lesion.med_medico_id"
                         @input="updateLesionMedico">
          </b-form-select>
          <b-form-input id="medicoAsistenciaTxtInput" class="col-sm-8 d-inline float-right px-0-rk mx-0"
                        v-model="_lesion.med_medico_txt"
                        @input="updateLesionMedicoTxt">
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
                         v-model="_lesion.med_tipo_atencion_id"
                         @input="updateLesionTipoAtencion">
          </b-form-select>
        </b-form-group>

        <!-- Material o agente causante del accidente -->
        <b-form-group label="Material o agente causante del accidente:"
                      label-for="causanteAccidenteInput"
                      class="col-sm-9 font-weight-bold">
          <b-form-select id="causanteAccidenteInput" class="col-sm-4 d-inline px-0-rk mx-0"
                         :options="_partes_aux.causantes"
                         v-model="_lesion.med_causante_id"
                         @input="updateLesionCausante">
          </b-form-select>
          <b-form-input id="causanteAccidenteTxtInput" class="col-sm-8 d-inline float-right px-0-rk mx-0"
                        v-model="_lesion.med_causante_txt"
                        @input="updateLesionCausanteTxt">
          </b-form-input>
        </b-form-group>
      </b-row>

      <b-row>
        <b-form-group label="Descripción del accidente:"
                      label-for="descrAccidenteInput"
                      class="col-12 font-weight-bold">
          <b-form-textarea
            id="descrAccidenteInput"
            v-model="_lesion.descripcion"
            placeholder="Descripción del accidente"
            rows="3"
            max-rows="6"
            @input="updateLesionDescripcion"
          ></b-form-textarea>
        </b-form-group>
      </b-row>

      <b-row>
        <b-form-group label="Anotaciones:"
                      label-for="anotAccidenteInput"
                      class="col-12 font-weight-bold">
          <b-form-textarea
            id="anotAccidenteInput"
            v-model="_lesion.observaciones"
            placeholder="Anotaciones"
            rows="3"
            max-rows="6"
            @input="updateLesionObservaciones"
          ></b-form-textarea>
        </b-form-group>
      </b-row>

      <b-row>
        <b-col cols="12" class="mt-3 text-right">
          <small v-if="disableSaving()" class="mx-3">Debe introducir una <strong>Fecha de Parte</strong> y un <strong>Pelotari</strong> para poder guardar los partes</small>
          <b-button variant="danger" @click="onClickSave" :disabled="disableSaving()">Guardar</b-button>
        </b-col>
      </b-row>
    </b-card>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  export default {
    props: ['onSave'],
    data() {
      return {
      }
    },
    computed: mapState({
      _edit: 'edit',
      _header: 'parte',
      _lesion: 'parte_lesion',
      _partes_aux: 'partes_aux',
    }),
    created: function() {
      console.log("Parte Lesion created");
    },
    methods: {
      ...mapActions([
        'updateLesionParte',
        'updateLesionLesionDesc',
        'updateLesionPartesCuerpo',
        'updateLesionPartesCuerpoTxt',
        'updateLesionMedico',
        'updateLesionMedicoTxt',
        'updateLesionCausante',
        'updateLesionCausanteTxt',
        'updateLesionGradoLesion',
        'updateLesionTipoAtencion',
        'updateLesionDescripcion',
        'updateLesionObservaciones',
      ]),
      disableSaving() {
        return (null == this._header.fecha_parte || null == this._header.pelotari_id);
      },
      onClickSave(evt) {
        evt.preventDefault();
        this.onSave();
      },
    }

  }
</script>

<style focused>
  .header .form-control {
    padding:0.135rem 0.75rem;
  }
  .no-background {
    background:transparent;
  }
  .no-border {
    border:none;
  }
  .parte-body {
    background-color:white;
    /* border:1px solid #efefef; */
    box-shadow:0px 5px 14px 0px #d8d8d8;
  }
  .parte-header {
    background-color:slategray;
    border:none;
    color:white;
    font-weight:bold;
  }
</style>
