<template>
  <b-row>
    <b-col cols="12" class="mb-4">
      <PelotariHeader :pelotari="pelotari" />
    </b-col>
    <b-col cols="12">
      <b-card no-body>
        <b-tabs card pills nav-wrapper-class="py-1" v-model="tabIndex">
          <b-tab title="Parte" active>
            <ParteFisiologia />
          </b-tab>
          <b-tab title="Adjuntos">
            <Adjuntos tipo="fisiologia" :informes="informes" />
          </b-tab>
        </b-tabs>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  import Adjuntos from '../adjuntos/Adjuntos';
  import ParteFisiologia from './ParteFisiologia';
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
      parte: state => state.med2.p_fisiologia,
      informes: state => state.med2.informes_p_fis
    }),
    created() {
      this.$root.$on('reset-index', () => {
        this.tabIndex = this.index
      })
    },
    methods: {
      ...mapActions({
        setInformes: 'med2/setInformesByParteFis',
        uploadInforme: 'med2/uploadInformeParteFis',
      }),
    },
    components: {
      Adjuntos,
      ParteFisiologia,
      PelotariHeader
    }
  }
</script>
