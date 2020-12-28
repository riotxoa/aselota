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
    </b-row>

    <b-row>
      <b-col cols="12">
        <b-form-group label="Historia clínica:"
                      label-for="historia"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="historia"
                        type="text"
                        v-model="parte.historia"
                        placeholder="Historia clínica"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="8">
        <b-form-group label="Reconocimiento médico:"
                      label-for="reconocimiento"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-textarea id="reconocimiento"
                        v-model="parte.reconocimiento"
                        maxlength="3500"
                        placeholder="Reconocimiento médico"
                        rows="3"
                        max-rows="6"
                        size="sm">
          </b-form-textarea>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha reconocimiento:"
                      label-for="fecha_rec"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_rec"
                        type="date"
                        v-model="parte.fecha_rec"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="8">
        <b-form-group label="Analíticas:"
                      label-for="analiticas"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-textarea id="analiticas"
                        v-model="parte.analiticas"
                        maxlength="3500"
                        placeholder="Analíticas"
                        rows="3"
                        max-rows="6"
                        size="sm">
          </b-form-textarea>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha analíticas:"
                      label-for="fecha_ana"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_ana"
                        type="date"
                        v-model="parte.fecha_ana"
                        placeholder="dd/mm/yyyy"
                        size="sm">
          </b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" md="8">
        <b-form-group label="Pruebas complementarias:"
                      label-for="pruebas"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-textarea id="pruebas"
                        v-model="parte.pruebas"
                        maxlength="3500"
                        placeholder="Pruebas complementarias"
                        rows="3"
                        max-rows="6"
                        size="sm">
          </b-form-textarea>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="4">
        <b-form-group label="Fecha pruebas:"
                      label-for="fecha_pru"
                      label-class="font-weight-bold"
                      label-size="sm"
                      class="mb-md-2">
          <b-form-input id="fecha_pru"
                        type="date"
                        v-model="parte.fecha_pru"
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
        show: false
      }
    },
    computed: mapGetters({
      parte: 'med2/p_prevencion',
    }),
    created() {
      this.show = true;
    },
  }
</script>
