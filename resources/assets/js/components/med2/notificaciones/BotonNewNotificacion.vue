<template>
  <b-btn size="sm" @click="onClick" ref="btnNewNotificacionMed2">Nueva Notificación</b-btn>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      pelotari: 'med2/pelotari',
    }),
    methods: {
      ...mapActions({
        resetNotificacion: 'med2/resetNotificacion',
        setNotificacion_key: 'med2/setNotificacion_key',
      }),
      formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) {
          month = '0' + month;
        }
        if (day.length < 2) {
          day = '0' + day;
        }

        return [year, month, day].join('-');
      },
      iniNotificacion() {
        const today = this.formatDate( new Date() );

        this.resetNotificacion();
        this.setNotificacion_key({ key: 'pelotari_id', val: this.pelotari.id });
      },
      onClick() {
        this.iniNotificacion();
        this.$root.$emit('bv::show::modal', 'modal-notificacion-med2', '#btnNewNotificacionMed2');
      }
    }
  }
</script>
