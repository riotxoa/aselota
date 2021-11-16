<template>
  <b-modal ref="editMicrociclo"
           id="editMicrociclo"
           title="Editar Microciclo"
           size="lg"
           @ok="saveMicrociclo"
           @cancel="cancelEditItem"
           :ok-disabled="disabled"
           ok-title="Guardar"
           ok-variant="danger"
           cancel-title="Cancelar"
           cancel-variant="secondary"
           @show="onShowModal">
    <FormMicrociclo />
  </b-modal>
</template>

<script>
  import FormMicrociclo from './FormMicrociclo.vue';
  import { microciclo } from '../../common/functions.js';

  export default {
    mixins: [ microciclo ],
    data() {
      return {
        disabled: false
      }
    },
    created() {
      this.resetMicrocicloBackup();
      this.$root.$on('disable-modal-microciclo-save-button', this.toggleSaveButton);
    },
    methods: {
      cancelEditItem() {
        this.restoreMicrocicloBackup();
        this.$root.$emit('cancelEditMicrociclo');
      },
      onShowModal() {
        this.makeMicrocicloBackup();
      },
      saveMicrociclo() {
        this.$root.$emit('saveEditMicrociclo');
      },
      toggleSaveButton(disabled) {
        this.disabled = disabled ? true : false;
      }
    },
    components: {
      FormMicrociclo,
    }
  }
</script>
