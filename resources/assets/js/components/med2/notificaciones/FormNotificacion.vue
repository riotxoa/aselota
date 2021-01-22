<template>
  <b-row>
    <b-col cols="12" class="mb-4">
      <PelotariHeader :pelotari="pelotari" />
    </b-col>
    <b-col cols="12">
      <b-form @submit="onSubmit" @reset="onCancel">
        <b-card>
          <b-form-group
            label="Destinatarios"
            label-size="lg"
            label-class="font-weight-bold pt-0"
            class="mb-0"
          >
            <b-form-checkbox-group
              id="destinatarios"
              v-model="notificacion.destinatarios"
              :options="options"
              name="destinatarios"
               />
          </b-form-group>
        </b-card>
        <b-card class="mt-3">
          <b-form-group
            label="Disponibilidad del Pelotari"
            label-size="lg"
            label-class="font-weight-bold pt-0"
            class="mb-0"
          >
            <b-row>
              <b-col cols="12" md="4">
                <b-form-checkbox
                  id="disponible"
                  v-model="notificacion.disponible"
                  name="disponible"
                  :value="0"
                  :unchecked-value="1"
                >
                  NO disponible
                </b-form-checkbox>
              </b-col>
              <b-col v-if="!notificacion.disponible" cols="6" md="4">
                <label for="date_from" class="d-inline-block font-weight-bold">Desde:</label>
                <b-form-input
                  id="date_from"
                  class="d-inline-block"
                  name="date_from"
                  v-model="notificacion.date_from"
                  type="date"
                />
              </b-col>
              <b-col v-if="!notificacion.disponible" cols="6" md="4">
                <label for="date_to" class="d-inline-block font-weight-bold">Hasta:</label>
                <b-form-input
                  id="date_to"
                  class="d-inline-block"
                  name="date_to"
                  v-model="notificacion.date_to"
                  type="date"
                />
              </b-col>
            </b-row>
          </b-form-group>
        </b-card>
        <b-card class="mt-3">
          <b-form-group
            label="Texto de la notificación"
            label-size="lg"
            label-class="font-weight-bold pt-0"
            class="mb-0"
          >
            <b-form-textarea
              id="texto"
              v-model="notificacion.texto"
              name="texto"
              rows="3"
              max-rows="6"
            />
          </b-form-group>
        </b-card>
        <hr>
        <div class="float-right">
          <b-button type="reset" variant="secondary">Cancelar</b-button>
          <b-button type="submit" variant="primary">Enviar</b-button>
        </div>
      </b-form>
    </b-col>
  </b-row>
</template>

<script>
  import { mapActions, mapGetters, mapState } from 'vuex';

  import Utils from '../../utils/utils.js';
  import PelotariHeader from '../partes/PartePelotariHeader';

  export default {
    mixins: [Utils],
    data() {
      return {
        options: [
          { text: 'Recursos Humanos', value: 'rrhh' },
          { text: 'Gerentes de Festivales', value: 'gerente' },
          { text: 'Entrenadores', value: 'entrenador' },
          { text: 'Prensa', value: 'prensa' }
        ],
      }
    },
    computed: {
      ...mapState({
        pelotari: state => state.med2.pelotari,
      }),
      ...mapGetters({
        notificacion: 'med2/notificacion'
      })
    },
    methods: {
      ...mapActions({
        resetNotificacion: 'med2/resetNotificacion',
        saveNotificacion: 'med2/saveNotificacion',
        setPelotari: 'med2/setPelotari'
      }),
      checkFields() {
        if( !this.notificacion.destinatarios.length ) {
          this.showSnackbar("SELECCIONE UN DESTINATARIO");
          return false;
        } else if ( true == this.notificacion.disponible && !this.notificacion.texto.length ) {
          this.showSnackbar("INTRODUZCA UN TEXTO EN LA NOTIFICACIÓN");
          return false;
        } else if ( false == this.notificacion.disponible && (!this.notificacion.date_from || !this.notificacion.date_to) ) {
          this.showSnackbar("INTRODUZCA UN RANGO DE FECHAS");
          return false;
        }
        return true;
      },
      onCancel(event) {
        event.preventDefault();
        this.resetNotificacion();
        this.$root.$emit('bv::hide::modal','modal-notificacion-med2');
      },
      onSubmit(event) {
        event.preventDefault();

        if ( this.checkFields() ) {
          if( confirm("Va a enviar una notificación. ¿Está seguro?") ) {
            this.saveNotificacion(this.notificacion).then( () => {
              this.resetNotificacion();
              this.showSnackbar("Nueva Notificación guardada");
              this.$root.$emit('bv::hide::modal','modal-notificacion-med2');
            });
          }
        }
      }
    },
    components: {
      PelotariHeader
    }
  }
</script>
