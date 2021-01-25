<template>
  <div>
    <b-row>
      <b-col cols="12" class="mb-3">
        <BotonNewNotificacion />
      </b-col>
      <b-col cols="12">
        <ListadoNotificaciones :pelotari="pelotari.id" />
      </b-col>
    </b-row>
    <ModalNotificacion />
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import BotonNewNotificacion from './BotonNewNotificacion';
  import ModalNotificacion from './ModalNotificacion';
  import ListadoNotificaciones from './ListadoNotificaciones';

  export default {
    computed: mapGetters({
      pelotari: 'med2/pelotari',
    }),
    components: {
      BotonNewNotificacion,
      ListadoNotificaciones,
      ModalNotificacion
    }
  }
</script>
