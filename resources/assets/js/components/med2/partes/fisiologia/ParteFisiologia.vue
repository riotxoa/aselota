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
        <b-form-group label="Fecha síntomas:"
                      label-for="fecha_sintomas"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_sintomas"
                        type="date"
                        v-model="parte.fecha_sintomas"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="PE Fecha:"
                      label-for="fecha_pe"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_pe"
                        type="date"
                        v-model="parte.fecha_pe"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="8">
        <b-form-group label="Observaciones PE:"
                      label-for="observaciones_pe"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-textarea id="observaciones_pe"
                        v-model="parte.observaciones"
                        maxlength="3500"
                        placeholder="Observaciones"
                        rows="3"
                        max-rows="6"
                        size="sm">
          </b-form-textarea>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Test de campo:"
                      label-for="test"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="test"
                        type="text"
                        v-model="parte.test"
                        placeholder="Test de campo"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="8">
        <b-form-group label="Observaciones Test:"
                      label-for="observaciones_test"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-textarea id="observaciones_test"
                        v-model="parte.observaciones_test"
                        maxlength="3500"
                        placeholder="Observaciones Test"
                        rows="3"
                        max-rows="6"
                        size="sm">
          </b-form-textarea>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="4">
        <b-form-group label="Composición corporal:"
                      label-for="composicion"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="composicion"
                        type="text"
                        v-model="parte.composicion"
                        placeholder="Composición corporal"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="8">
        <b-form-group label="Observaciones Composición:"
                      label-for="observaciones_comp"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-textarea id="observaciones_comp"
                        v-model="parte.observaciones_comp"
                        maxlength="3500"
                        placeholder="Observaciones Composición"
                        rows="3"
                        max-rows="6"
                        size="sm">
          </b-form-textarea>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12">
        <b-form-group label="Suplementación:"
                      label-for="suplementacion"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="suplementacion"
                        type="text"
                        v-model="parte.suplementacion"
                        placeholder="Suplementación"
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
        show: false
      }
    },
    computed: mapGetters({
      parte: 'med2/p_fisiologia',
    }),
    created() {
      this.show = true;
    },
  }
</script>
