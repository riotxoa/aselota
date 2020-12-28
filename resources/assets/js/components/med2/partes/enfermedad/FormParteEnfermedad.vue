<template>
  <b-row>
    <b-col cols="12" class="mb-4">
      <PelotariHeader :pelotari="pelotari" />
    </b-col>
    <b-col cols="12">
      <b-card no-body>
        <b-tabs card pills nav-wrapper-class="py-1" v-model="tabIndex">
          <b-tab title="Parte" active>
            <ParteEnfermedad />
          </b-tab>
          <b-tab title="Adjuntos">
            <Adjuntos tipo="enfermedad" :informes="informes" />
          </b-tab>
        </b-tabs>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  import Adjuntos from '../adjuntos/Adjuntos';
  import ParteEnfermedad from './ParteEnfermedad';
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
      parte: state => state.med2.p_enfermedad,
      informes: state => state.med2.informes_p_enf
    }),
    created() {
      this.$root.$on('reset-index', () => {
        this.tabIndex = this.index
      })
    },
    methods: {
      ...mapActions({
        setInformes: 'med2/setInformesByParteEnf',
        uploadInforme: 'med2/uploadInformeParteEnf',
      }),
    },
    components: {
      Adjuntos,
      ParteEnfermedad,
      PelotariHeader
    }
  }
</script>
