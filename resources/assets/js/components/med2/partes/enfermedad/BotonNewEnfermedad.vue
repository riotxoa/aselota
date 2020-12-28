<template>
  <b-btn size="sm" @click="onClick" ref="btnNewPEnfermedad">Nuevo Parte Enfermedad</b-btn>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      pelotari: 'med2/pelotari',
    }),
    methods: {
      ...mapActions({
        resetPEnfermedad: 'med2/resetPEnfermedad',
        setPEnfermedad_key: 'med2/setPEnfermedad_key',
        setPEnfermedad_key: 'med2/setPEnfermedad_key',
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
      iniPEnfermedad() {
        const today = this.formatDate( new Date() );

        this.resetPEnfermedad();
        this.setPEnfermedad_key({ key: 'pelotari_id', val: this.pelotari.id });
        this.setPEnfermedad_key({ key: 'fecha_asistencia', val: today });
      },
      onClick() {
        this.iniPEnfermedad();
        this.$root.$emit('bv::show::modal', 'modal-parte-enfermedad', '#btnNewPEnfermedad')
      }
    }
  }
</script>
