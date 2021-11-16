<template>
  <b-modal ref="editMicrociclo"
           id="editMicrociclo"
           title="Editar Microciclo"
           size="lg"
           @ok="saveMicrociclo"
           @cancel="cancelEditItem"
           :ok-disabled="disabled || readonly"
           ok-title="Guardar"
           ok-variant="danger"
           cancel-title="Cancelar"
           cancel-variant="secondary"
           @show="onShowModal">
    <FormMicrociclo :readonly="readonly" />
  </b-modal>
</template>

<script>
  import FormMicrociclo from './FormMicrociclo.vue';
  import { microciclo } from '../../common/functions.js';

  export default {
    mixins: [ microciclo ],
    data() {
      return {
        disabled: false,
        readonly: false,
      }
    },
    created() {
      this.resetMicrocicloBackup();
      this.$root.$on('disable-modal-microciclo-save-button', this.toggleSaveButton);

      this.$root.$on('disable-modal-microciclo-readonly', this.disableReadOnly );
      this.$root.$on('enable-modal-microciclo-readonly', this.enableReadOnly );
    },
    methods: {
      cancelEditItem() {
        this.restoreMicrocicloBackup();
        this.$root.$emit('cancelEditMicrociclo');
      },
      disableReadOnly() {
        this.readonly = false;
      },
      enableReadOnly() {
        this.readonly = true;
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
