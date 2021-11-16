<template>
  <b-modal ref="editMesociclo"
           id="editMesociclo"
           title="Editar Mesociclo"
           size="lg"
           @ok="saveMesociclo"
           @cancel="cancelEditItem"
           :ok-disabled="disabled"
           ok-title="Guardar"
           ok-variant="danger"
           cancel-title="Cancelar"
           cancel-variant="secondary"
           @show="onShowModal">
    <FormMesociclo />
  </b-modal>
</template>

<script>
  import FormMesociclo from './FormMesociclo';
  import { mesociclo } from '../../common/functions.js';

  export default {
    mixins: [ mesociclo ],
    data() {
      return {
        disabled: false
      }
    },
    created() {
      this.resetMesocicloBackup();
      this.$root.$on('disable-modal-mesociclo-save-button', this.toggleSaveButton);
    },
    methods: {
      cancelEditItem() {
        this.restoreMesocicloBackup();
        this.$root.$emit('cancelEditMesociclo');
      },
      onShowModal() {
        this.makeMesocicloBackup();
      },
      saveMesociclo() {
        this.$root.$emit('saveEditMesociclo');
      },
      toggleSaveButton(disabled) {
        this.disabled = disabled ? true : false;
      }
    },
    components: {
      FormMesociclo,
    }
  }
</script>
