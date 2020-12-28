<template>
  <b-row>
    <b-col cols="12" class="mb-4">
      <PelotariHeader :pelotari="pelotari" />
    </b-col>
    <b-col cols="12">
      <b-card no-body>
        <b-tabs card pills nav-wrapper-class="py-1" v-model="tabIndex">
          <b-tab title="Parte" active>
            <PartePrevencion />
          </b-tab>
          <b-tab title="Adjuntos">
            <Adjuntos tipo="prevencion" :informes="informes" />
          </b-tab>
        </b-tabs>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  import Adjuntos from '../adjuntos/Adjuntos';
  import PartePrevencion from './PartePrevencion';
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
      parte: state => state.med2.p_prevencion,
      informes: state => state.med2.informes_p_pre
    }),
    created() {
      this.$root.$on('reset-index', () => {
        this.tabIndex = this.index
      })
    },
    methods: {
      ...mapActions({
        setInformes: 'med2/setInformesByPartePre',
        uploadInforme: 'med2/uploadInformePartePre',
      }),
    },
    components: {
      Adjuntos,
      PartePrevencion,
      PelotariHeader
    }
  }
</script>
