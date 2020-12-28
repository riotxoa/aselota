<template>
  <b-modal id="modal-parte-enfermedad"
           ref="modal-parte-enfermedad"
           title="Parte de Enfermedad"
           cancel-title="Cancelar"
           ok-title="Guardar"
           scrollable="true"
           size="lg"
           @ok="onClickSaveParteEnfermedad"
           @show="onShowParteEnfermedad">
    <FormParteEnfermedad v-if="show" :index="tabIndex" />
  </b-modal>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Utils from '../../../utils/utils.js';

  import FormParteEnfermedad from './FormParteEnfermedad';

  export default {
    mixins: [Utils],
    data() {
      return {
        show: true,
        tabIndex: 0
      }
    },
    computed: mapGetters({
        p_enfermedad: 'med2/p_enfermedad',
        pelotari: 'med2/pelotari'
    }),
    created() {
      this.$root.$on('edit-parte-enfermedad', (id) => {
        this.getInformesByParteId(id);
      });
    },
    methods: {
      ...mapActions({
        getInformesPEnf: 'med2/getInformesPEnf',
        saveParteEnfermedad: 'med2/saveParteEnfermedad',
        setPelotari: 'med2/setPelotari',
        updateParteEnfermedad: 'med2/updateParteEnfermedad',
      }),
      getInformesByParteId(id) {
        this.getInformesPEnf(id).then( (res) => {
          this.show = true;
        })
      },
      onClickSaveParteEnfermedad() {
        const data = {
          pelotari_id: this.pelotari.id,
          p_enfermedad: this.p_enfermedad
        }

        if( this.p_enfermedad.id )  {
          this.updateParteEnfermedad(data).then( (res) => {
            this.setPelotari(res);
            this.showSnackbar("Parte de Enfermedad actualizado");
          });
        } else {
          this.saveParteEnfermedad(data).then( (res) => {
            this.setPelotari(res);
            this.showSnackbar("Nuevo Parte de Enfermedad guardado");
          });
        }
      },
      onShowParteEnfermedad() {
        this.tabIndex = 0
        this.$root.$emit('reset-index')
      }
    },
    components: {
      FormParteEnfermedad
    }
  }
</script>
