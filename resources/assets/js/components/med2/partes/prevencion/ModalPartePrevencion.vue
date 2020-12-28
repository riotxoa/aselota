<template>
  <b-modal id="modal-parte-prevencion"
           ref="modal-parte-prevencion"
           title="Parte de Prevención"
           cancel-title="Cancelar"
           ok-title="Guardar"
           scrollable="true"
           size="lg"
           @ok="onClickSavePartePrevencion"
           @show="onShowPartePrevencion">
    <FormPartePrevencion v-if="show" :index="tabIndex" />
  </b-modal>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Utils from '../../../utils/utils.js';

  import FormPartePrevencion from './FormPartePrevencion';

  export default {
    mixins: [Utils],
    data() {
      return {
        show: true,
        tabIndex: 0
      }
    },
    computed: mapGetters({
        p_prevencion: 'med2/p_prevencion',
        pelotari: 'med2/pelotari'
    }),
    created() {
      this.$root.$on('edit-parte-prevencion', (id) => {
        this.getInformesByParteId(id);
      });
    },
    methods: {
      ...mapActions({
        getInformesPPre: 'med2/getInformesPPre',
        savePartePrevencion: 'med2/savePartePrevencion',
        setPelotari: 'med2/setPelotari',
        updatePartePrevencion: 'med2/updatePartePrevencion',
      }),
      getInformesByParteId(id) {
        this.getInformesPPre(id).then( (res) => {
          this.show = true;
        })
      },
      onClickSavePartePrevencion() {
        const data = {
          pelotari_id: this.pelotari.id,
          p_prevencion: this.p_prevencion
        }

        if( this.p_prevencion.id )  {
          this.updatePartePrevencion(data).then( (res) => {
            this.setPelotari(res);
            this.showSnackbar("Parte de Prevención actualizado");
          });
        } else {
          this.savePartePrevencion(data).then( (res) => {
            this.setPelotari(res);
            this.showSnackbar("Nuevo Parte de Prevención guardado");
          });
        }
      },
      onShowPartePrevencion() {
        this.tabIndex = 0
        this.$root.$emit('reset-index')
      }
    },
    components: {
      FormPartePrevencion
    }
  }
</script>
