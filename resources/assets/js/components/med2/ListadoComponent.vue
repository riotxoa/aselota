<template>
  <div v-if="show">
    <MainHeader />

    <div class="container">
      <b-card no-body>
        <b-tabs pills card>
          <b-tab title="Pelotaris" title-item-class="text-uppercase font-weight-bold">
            <b-tabs card>
              <b-tab title="Profesionales" title-item-class="text-uppercase font-weight-bold">
                <listado-partes-pelotaris :promesas="false" />
              </b-tab>
              <b-tab title="Promesas" title-item-class="text-uppercase font-weight-bold">
                <listado-partes-pelotaris :promesas="true" />
              </b-tab>
            </b-tabs>
          </b-tab>
          <b-tab title="Asistencia Accidente" title-item-class="text-uppercase font-weight-bold">
            Asistencia Accidente
          </b-tab>
          <b-tab title="Asistencia Enfermedad" title-item-class="text-uppercase font-weight-bold">
            Asistencia Enfermedad
          </b-tab>
          <b-tab title="Fisiología" title-item-class="text-uppercase font-weight-bold">
            Fisiología
          </b-tab>
          <b-tab title="Prevención" title-item-class="text-uppercase font-weight-bold">
            Prevención
          </b-tab>
        </b-tabs>
      </b-card>
    </div>
  </div>
</template>

<script>
  import { mapActions } from 'vuex';

  import ListadoPelotarisComponent from './pelotaris/ListadoPelotarisComponent';
  import MainHeader from './components/MainHeader';

  export default {
    data() {
      return {
        show: false
      }
    },
    created() {
      this.getMed2Aux().then( () => {
        this.show = true;
      } );
    },
    methods: {
      ...mapActions({
        getMed2Aux: 'med2/getMed2Aux'
      })
    },
    components: {
      'listado-partes-pelotaris': ListadoPelotarisComponent,
      'MainHeader': MainHeader
    }
  }
</script>
