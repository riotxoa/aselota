<template>
  <div>
    <BotonNewNotificacion />
    <ModalNotificacion />
    <ListadoNotificaciones />
  </div>
</template>

<script>
  import BotonNewNotificacion from './BotonNewNotificacion';
  import ModalNotificacion from './ModalNotificacion';
  import ListadoNotificaciones from './ListadoNotificaciones';

  export default {
    components: {
      BotonNewNotificacion,
      ModalNotificacion
    }
  }
</script>
