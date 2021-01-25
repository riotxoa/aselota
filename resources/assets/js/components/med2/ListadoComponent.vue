<template>
  <div v-if="show">
    <MainHeader />

    <div class="container">
      <b-card no-body>
        <b-tabs v-model="tabIndex" pills card>
          <b-tab title="Profesionales" :title-link-class="linkClass(0)">
            <listado-partes-pelotaris :promesas="false" />
          </b-tab>
          <b-tab title="Promesas" :title-link-class="linkClass(1)">
            <listado-partes-pelotaris :promesas="true" />
          </b-tab>
          <b-tab title="Accidente" :title-link-class="linkClass(2)">
            <listado-partes-accidente :partes="partes_acc" :show-alias="true" />
          </b-tab>
          <b-tab title="Enfermedad" :title-link-class="linkClass(3)">
            <listado-partes-enfermedad :partes="partes_enf" :show-alias="true" />
          </b-tab>
          <b-tab title="Fisiología" :title-link-class="linkClass(4)">
            <listado-partes-fisiologia :partes="partes_fis" :show-alias="true" />
          </b-tab>
          <b-tab title="Prevención" :title-link-class="linkClass(5)">
            <listado-partes-prevencion :partes="partes_pre" :show-alias="true" />
          </b-tab>
          <b-tab title="Notificaciones" :title-link-class="linkClass(6)">
            <listado-notificaciones :pelotari="false" />
          </b-tab>
        </b-tabs>
      </b-card>
    </div>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  import ListadoPelotarisComponent from './pelotaris/ListadoPelotarisComponent';
  import ListadoAccidenteComponent from './partes/PartesAccidente';
  import ListadoEnfermedadComponent from './partes/PartesEnfermedad';
  import ListadoFisiologiaComponent from './partes/PartesFisiologia';
  import ListadoPrevencionComponent from './partes/PartesPrevencion';
  import ListadoNotificacionesComponent from './notificaciones/ListadoNotificaciones';

  import MainHeader from './components/MainHeader';

  export default {
    data() {
      return {
        show: false,
        tabIndex: 0
      }
    },
    computed: mapState({
      partes_acc: state => state.med2.partes_acc,
      partes_enf: state => state.med2.partes_enf,
      partes_fis: state => state.med2.partes_fis,
      partes_pre: state => state.med2.partes_pre,
    }),
    created() {
      this.getMed2Aux().then( () => {
        this.getAllPAccidente().then( (acc) => {
          this.getAllPEnfermedad().then( (enf) => {
            this.getAllPFisiologia().then( (fis) => {
              this.getAllPPrevencion().then( (pre) => {
                this.show = true;
              } );
            } );
          } );
        } );
      } );
    },
    methods: {
      ...mapActions({
        getMed2Aux: 'med2/getMed2Aux',
        getAllPAccidente: 'med2/getAllPAccidente',
        getAllPEnfermedad: 'med2/getAllPEnfermedad',
        getAllPFisiologia: 'med2/getAllPFisiologia',
        getAllPPrevencion: 'med2/getAllPPrevencion',
      }),
      linkClass(idx) {
        var classes = 'text-uppercase font-weight-bold';

        if (this.tabIndex !== idx) {
          switch( idx ) {
            case 0:
            case 1:
              classes += ' text-primary';
              break;
            case 2:
            case 3:
            case 4:
            case 5:
              classes += ' text-success';
              break;
            case 6:
              classes += ' text-secondary';
              break;
          }
        } else {
          switch( idx ) {
            case 0:
            case 1:
              classes += ' bg-primary';
              break;
            case 2:
            case 3:
            case 4:
            case 5:
              classes += ' bg-success';
              break;
            case 6:
              classes += ' bg-secondary';
              break;
          }
        }
        return classes;
      }
    },
    components: {
      'listado-partes-pelotaris': ListadoPelotarisComponent,
      'listado-partes-accidente': ListadoAccidenteComponent,
      'listado-partes-enfermedad': ListadoEnfermedadComponent,
      'listado-partes-fisiologia': ListadoFisiologiaComponent,
      'listado-partes-prevencion': ListadoPrevencionComponent,
      'listado-notificaciones': ListadoNotificacionesComponent,
      'MainHeader': MainHeader
    }
  }
</script>
