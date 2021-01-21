<template>
  <b-modal id="modal-notificacion-med2"
           ref="modal-notificacion-med2"
           title="Notificación"
           cancel-title="Cancelar"
           ok-title="Guardar"
           scrollable="true"
           size="lg"
           hide-footer
           @ok="onClickSaveNotificacion"
           @show="onShowNotificacion">
    <FormNotificacion />
  </b-modal>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';

  import Utils from '../../utils/utils.js';

  import FormNotificacion from './FormNotificacion';

  export default {
    mixins: [Utils],
    data() {
      return {
        show: true,
        tabIndex: 0
      }
    },
    computed: mapGetters({
        notificacion: 'med2/notificacion',
        pelotari: 'med2/pelotari'
    }),
    methods: {
      ...mapActions({
        saveNotificacion: 'med2/saveNotificacion',
        setPelotari: 'med2/setPelotari'
      }),
      checkNotificacion() {
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
      onClickSaveNotificacion() {
        if ( this.checkNotificacion() ) {
          this.saveNotificacion(this.notificacion).then( (res) => {
            this.setPelotari(res);
            this.showSnackbar("Nueva Notificación guardada");
          });
        }
      },
      onShowNotificacion() {
        this.tabIndex = 0
        this.$root.$emit('reset-index')
      }
    },
    components: {
      FormNotificacion
    }
  }
</script>
