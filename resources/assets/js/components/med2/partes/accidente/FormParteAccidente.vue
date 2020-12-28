<template>
  <b-row>
    <b-col cols="12" class="mb-4">
      <PelotariHeader :pelotari="pelotari" />
    </b-col>
    <b-col cols="12">
      <b-card no-body>
        <b-tabs card pills nav-wrapper-class="py-1" v-model="tabIndex">
          <b-tab title="Parte" active>
            <ParteAccidente />
          </b-tab>
          <b-tab title="Delta" :title-item-class="!(true == parte.is_baja || 1 == parte.is_baja) ? 'd-none' : 'd-inline-block'" :disabled="!(true == parte.is_baja || 1 == parte.is_baja)">
            <ParteDelta />
          </b-tab>
          <b-tab title="Adjuntos">
            <Adjuntos tipo="accidente" :informes="informes" />
          </b-tab>
        </b-tabs>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
  import { mapState } from 'vuex';

  import Adjuntos from '../adjuntos/Adjuntos';
  import ParteAccidente from './ParteAccidente';
  import ParteDelta from '../delta/ParteDelta';
  import PelotariHeader from '../PartePelotariHeader';

  export default {
    props: ['index'],
    data() {
      return {
        tabIndex: this.index
      }
    },
    computed: mapState({
      pelotari: state => state.med2.pelotari,
      parte: state => state.med2.p_accidente,
      informes: state => state.med2.informes_p_acc
    }),
    created() {
      this.$root.$on('reset-index', () => {
        this.tabIndex = this.index
      })
    },
    components: {
      Adjuntos,
      ParteAccidente,
      ParteDelta,
      PelotariHeader
    }
  }
</script>
