<template>
  <b-modal id="modal-parte-accidente"
           ref="modal-parte-accidente"
           title="Parte de Accidente"
           cancel-title="Cancelar"
           ok-title="Guardar"
           scrollable="true"
           size="lg"
           @ok="onClickSaveParteAccidente"
           @show="onShowParteAccidente">
    <FormParteAccidente v-if="show" :index="tabIndex" />
  </b-modal>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Utils from '../../../utils/utils.js';

  import FormParteAccidente from './FormParteAccidente';

  export default {
    mixins: [Utils],
    data() {
      return {
        show: true,
        tabIndex: 0
      }
    },
    computed: mapGetters({
        p_accidente: 'med2/p_accidente',
        p_delta: 'med2/p_delta',
        pelotari: 'med2/pelotari'
    }),
    created() {
      this.$root.$on('edit-parte-accidente', (id) => {
        this.getInformesByParteId(id);
      });
    },
    methods: {
      ...mapActions({
        getInformesPAcc: 'med2/getInformesPAcc',
        saveParteAccidente: 'med2/saveParteAccidente',
        setPelotari: 'med2/setPelotari',
        updateParteAccidente: 'med2/updateParteAccidente',
      }),
      getInformesByParteId(id) {
        this.getInformesPAcc(id).then( (res) => {
          this.show = true;
        })
      },
      onClickSaveParteAccidente() {
        const data = {
          pelotari_id: this.pelotari.id,
          p_accidente: this.p_accidente,
          p_delta: this.p_delta
        }

        if( this.p_accidente.id )  {
          this.updateParteAccidente(data).then( (res) => {
            this.setPelotari(res);
            this.showSnackbar("Parte de Accidente actualizado");
          });
        } else {
          this.saveParteAccidente(data).then( (res) => {
            this.setPelotari(res);
            this.showSnackbar("Nuevo Parte de Accidente guardado");
          });
        }
      },
      onShowParteAccidente() {
        this.tabIndex = 0
        this.$root.$emit('reset-index')
      }
    },
    components: {
      FormParteAccidente
    }
  }
</script>
