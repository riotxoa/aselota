<template>
  <b-modal id="modal-notificacion-med2"
           ref="modal-notificacion-med2"
           title="Notificación"
           cancel-title="Cancelar"
           ok-title="Guardar"
           scrollable="true"
           size="lg"
           hide-footer>
    <FormNotificacion />
  </b-modal>
</template>

<script>
  import FormNotificacion from './FormNotificacion';

  export default {
    components: {
      FormNotificacion
    }
  }
</script>
