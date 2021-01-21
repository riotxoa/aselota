<template>
  <div v-if="show">
    <MainHeader />
    <div class="container">
      <div style="min-height:625px;" class="container py-4">
        <PelotariHeader :pelotari="pelotari" />

        <b-card no-body class="my-3">
          <b-tabs card pills>

            <b-col cols="3" offset="9" class="position-absolute fixed-top">
              <b-row class="pt-3 mt-1">
                <label class="col-10 text-right">Promesa</label>
                <b-form-checkbox id="check_promesa"
                              class="col-2 text-right pr-0"
                              style="margin-right:0px !important;"
                              v-model="pelotari.promesa"
                              value="1"
                              unchecked-value="0"
                              disabled >
                </b-form-checkbox>
              </b-row>
            </b-col>

            <b-tab title="Accidente">
              <b-row>
                <b-col cols="12">
                  <PartesAccidente :partes="pelotari.accidente" :alias="pelotari.alias" :show-alias="false" />
                </b-col>
              </b-row>
            </b-tab>
            <b-tab title="Enfermedad">
              <b-row>
                <b-col cols="12">
                  <PartesEnfermedad :partes="pelotari.enfermedad" :alias="pelotari.alias" :show-alias="false" />
                </b-col>
              </b-row>
            </b-tab>
            <b-tab title="Prevención">
              <b-row>
                <b-col cols="12">
                  <PartesPrevencion :partes="pelotari.prevencion" :alias="pelotari.alias" :show-alias="false" />
                </b-col>
              </b-row>
            </b-tab>
            <b-tab title="Fisiología">
              <b-row>
                <b-col cols="12">
                  <PartesFisiologia :partes="pelotari.fisiologia" :alias="pelotari.alias" :show-alias="false" />
                </b-col>
              </b-row>
            </b-tab>
            <b-tab title="Notificaciones">
              <b-row>
                <b-col cols="12">
                  <Notificaciones />
                </b-col>
              </b-row>
            </b-tab>
          </b-tabs>
        </b-card>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import _ from 'lodash';

  import MainHeader from '../components/MainHeader';
  import Notificaciones from '../notificaciones/Notificaciones';
  import PartesAccidente from '../partes/PartesAccidente';
  import PartesEnfermedad from '../partes/PartesEnfermedad';
  import PartesFisiologia from '../partes/PartesFisiologia';
  import PartesPrevencion from '../partes/PartesPrevencion';
  import PelotariHeader from './PelotariHeader';

  export default {
    data() {
      return {
        pelotari: null,
        show: false,
      }
    },
    computed: {
      ...mapGetters({
        p_profesionales: 'med2/pelotaris',
        p_promesas: 'med2/promesas',
      })
    },
    created() {
      const pelotaris = this.p_profesionales.concat(this.p_promesas);
      this.pelotari = _.find(pelotaris, (o) => {
        return o.id == this.$route.params.id
      });

      this.setPelotari(this.pelotari).then( () => {
        this.show = true;
      })
    },
    methods: {
      ...mapActions({
        setPelotari: 'med2/setPelotari'
      })
    },
    components: {
      MainHeader,
      Notificaciones,
      PartesAccidente,
      PartesEnfermedad,
      PartesFisiologia,
      PartesPrevencion,
      PelotariHeader
    }
  }
</script>
