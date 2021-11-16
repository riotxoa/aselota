<template>
  <b-modal ref="editMesociclo"
           id="editMesociclo"
           title="Editar Mesociclo"
           size="lg"
           @ok="saveMesociclo"
           @cancel="cancelEditItem"
           :ok-disabled="disabled || readonly"
           ok-title="Guardar"
           ok-variant="danger"
           cancel-title="Cancelar"
           cancel-variant="secondary"
           @show="onShowModal">
    <FormMesociclo :readonly="readonly" />
  </b-modal>
</template>

<script>
  import FormMesociclo from './FormMesociclo';
  import { mesociclo } from '../../common/functions.js';

  export default {
    mixins: [ mesociclo ],
    data() {
      return {
        disabled: false,
        readonly: false,
      }
    },
    created() {
      this.resetMesocicloBackup();
      this.$root.$on('disable-modal-mesociclo-save-button', this.toggleSaveButton);

      this.$root.$on('disable-modal-mesociclo-readonly', this.disableReadOnly );
      this.$root.$on('enable-modal-mesociclo-readonly', this.enableReadOnly );
    },
    methods: {
      cancelEditItem() {
        this.restoreMesocicloBackup();
        this.$root.$emit('cancelEditMesociclo');
      },
      disableReadOnly() {
        this.readonly = false;
      },
      enableReadOnly() {
        this.readonly = true;
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
