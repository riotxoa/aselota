<template>
  <b-btn size="sm" @click="onClick" ref="btnNewPAccidente">Nuevo Parte Accidente</b-btn>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';

  export default {
    computed: mapGetters({
      pelotari: 'med2/pelotari',
    }),
    methods: {
      ...mapActions({
        resetPAccidente: 'med2/resetPAccidente',
        resetPDelta: 'med2/resetPDelta',
        setPAccidente_key: 'med2/setPAccidente_key',
        setPAccidente_key: 'med2/setPAccidente_key',
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
      iniPAccidente() {
        const today = this.formatDate( new Date() );

        this.resetPAccidente();
        this.setPAccidente_key({ key: 'pelotari_id', val: this.pelotari.id });
        this.setPAccidente_key({ key: 'fecha_parte', val: today });
      },
      iniPDelta() {
        this.resetPDelta();
      },
      onClick() {
        this.iniPAccidente();
        this.iniPDelta();
        this.$root.$emit('bv::show::modal', 'modal-parte-accidente', '#btnNewPAccidente')
      }
    }
  }
</script>
