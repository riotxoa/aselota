<template>
  <b-btn size="sm" @click="onClick" ref="btnNewPPrevencion">Nuevo Parte Prevención</b-btn>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      pelotari: 'med2/pelotari',
    }),
    methods: {
      ...mapActions({
        resetPPrevencion: 'med2/resetPPrevencion',
        setPPrevencion_key: 'med2/setPPrevencion_key',
        setPPrevencion_key: 'med2/setPPrevencion_key',
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
      iniPPrevencion() {
        const today = this.formatDate( new Date() );

        this.resetPPrevencion();
        this.setPPrevencion_key({ key: 'pelotari_id', val: this.pelotari.id });
        this.setPPrevencion_key({ key: 'fecha_asistencia', val: today });
      },
      onClick() {
        this.iniPPrevencion();
        this.$root.$emit('bv::show::modal', 'modal-parte-prevencion', '#btnNewPPrevencion')
      }
    }
  }
</script>
