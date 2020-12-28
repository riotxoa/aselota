<template>
  <div>
    <b-row>
      <b-col cols="12">
        <TablaInformes :tipo="tipo" :informes="informes" :delInforme="delInforme" :setInformes="setInformes" :updateInforme="updateInforme" />
      </b-col>
    </b-row>
    <b-row class="mt-3">
      <b-col cols="12">
        <SubirInforme :tipo="tipo" :setInformes="setInformes" :uploadInforme="uploadInforme" />
      </b-col>
    </b-row>
  </div>
</template>

<script>
  import { mapActions } from 'vuex';
  import SubirInforme from './UploadInforme.vue';
  import TablaInformes from './TablaInformes.vue';

  export default {
    props: ['informes', 'tipo'],
    data() {
      return {
        setInformes: null,
        updloadInforme: null,
      }
    },
    created() {
      switch( this.tipo ) {
        case "accidente":
          this.isAccidente = true;
          this.delInforme = this.delInformeAcc;
          this.setInformes = this.setInformesAcc;
          this.updateInforme = this.updateInformeAcc;
          this.uploadInforme = this.uploadInformeAcc;
          break;
        case "enfermedad":
          this.isEnfermedad = true;
          this.delInforme = this.delInformeEnf;
          this.setInformes = this.setInformesEnf;
          this.updateInforme = this.updateInformeEnf;
          this.uploadInforme = this.uploadInformeEnf;
          break;
        case "fisiologia":
          this.isFisiologia = true;
          this.delInforme = this.delInformeFis;
          this.setInformes = this.setInformesFis;
          this.updateInforme = this.updateInformeFis;
          this.uploadInforme = this.uploadInformeFis;
          break;
        case "prevencion":
          this.isPrevencion = true;
          this.delInforme = this.delInformePre;
          this.setInformes = this.setInformesPre;
          this.updateInforme = this.updateInformePre;
          this.uploadInforme = this.uploadInformePre;
          break;
      }
    },
    methods: {
      ...mapActions({
        delInformeAcc: 'med2/deleteInformeParteAcc',
        delInformeEnf: 'med2/deleteInformeParteEnf',
        delInformeFis: 'med2/deleteInformeParteFis',
        delInformePre: 'med2/deleteInformePartePre',

        setInformesAcc: 'med2/setInformesByParteAcc',
        setInformesEnf: 'med2/setInformesByParteEnf',
        setInformesFis: 'med2/setInformesByParteFis',
        setInformesPre: 'med2/setInformesByPartePre',

        updateInformeAcc: 'med2/updateInformeParteAcc',
        updateInformeEnf: 'med2/updateInformeParteEnf',
        updateInformeFis: 'med2/updateInformeParteFis',
        updateInformePre: 'med2/updateInformePartePre',

        uploadInformeAcc: 'med2/uploadInformeParteAcc',
        uploadInformeEnf: 'med2/uploadInformeParteEnf',
        uploadInformeFis: 'med2/uploadInformeParteFis',
        uploadInformePre: 'med2/uploadInformePartePre',
      })
    },
    components: {
      SubirInforme,
      TablaInformes,
    }
  }
</script>
