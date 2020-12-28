<template>
  <b-btn size="sm" @click="onClick" ref="btnNewPFisiologia">Nuevo Parte Fisiologia</b-btn>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      pelotari: 'med2/pelotari',
    }),
    methods: {
      ...mapActions({
        resetPFisiologia: 'med2/resetPFisiologia',
        setPFisiologia_key: 'med2/setPFisiologia_key',
        setPFisiologia_key: 'med2/setPFisiologia_key',
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
      iniPFisiologia() {
        const today = this.formatDate( new Date() );

        this.resetPFisiologia();
        this.setPFisiologia_key({ key: 'pelotari_id', val: this.pelotari.id });
        this.setPFisiologia_key({ key: 'fecha_asistencia', val: today });
      },
      onClick() {
        this.iniPFisiologia();
        this.$root.$emit('bv::show::modal', 'modal-parte-fisiologia', '#btnNewPFisiologia')
      }
    }
  }
</script>
