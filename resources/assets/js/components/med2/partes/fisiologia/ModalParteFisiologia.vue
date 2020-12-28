<template>
  <b-modal id="modal-parte-fisiologia"
           ref="modal-parte-fisiologia"
           title="Parte de Fisiología"
           cancel-title="Cancelar"
           ok-title="Guardar"
           scrollable="true"
           size="lg"
           @ok="onClickSaveParteFisiologia"
           @show="onShowParteFisiologia">
    <FormParteFisiologia v-if="show" :index="tabIndex" />
  </b-modal>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Utils from '../../../utils/utils.js';

  import FormParteFisiologia from './FormParteFisiologia';

  export default {
    mixins: [Utils],
    data() {
      return {
        show: true,
        tabIndex: 0
      }
    },
    computed: mapGetters({
        p_fisiologia: 'med2/p_fisiologia',
        pelotari: 'med2/pelotari'
    }),
    created() {
      this.$root.$on('edit-parte-fisiologia', (id) => {
        this.getInformesByParteId(id);
      });
    },
    methods: {
      ...mapActions({
        getInformesPFis: 'med2/getInformesPFis',
        saveParteFisiologia: 'med2/saveParteFisiologia',
        setPelotari: 'med2/setPelotari',
        updateParteFisiologia: 'med2/updateParteFisiologia',
      }),
      getInformesByParteId(id) {
        this.getInformesPFis(id).then( (res) => {
          this.show = true;
        })
      },
      onClickSaveParteFisiologia() {
        const data = {
          pelotari_id: this.pelotari.id,
          p_fisiologia: this.p_fisiologia
        }

        if( this.p_fisiologia.id )  {
          this.updateParteFisiologia(data).then( (res) => {
            this.setPelotari(res);
            this.showSnackbar("Parte de Fisiología actualizado");
          });
        } else {
          this.saveParteFisiologia(data).then( (res) => {
            this.setPelotari(res);
            this.showSnackbar("Nuevo Parte de Fisiología guardado");
          });
        }
      },
      onShowParteFisiologia() {
        this.tabIndex = 0
        this.$root.$emit('reset-index')
      }
    },
    components: {
      FormParteFisiologia
    }
  }
</script>
