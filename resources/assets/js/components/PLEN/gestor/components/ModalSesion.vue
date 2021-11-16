<template>
  <b-modal ref="editSesion"
           id="editSesion"
           title="Editar Sesion"
           size="lg"
           scrollable="true"
           @ok="saveSesion"
           @cancel="cancelEditItem"
           :ok-disabled="ok_disabled || readonly"
           ok-title="Guardar"
           ok-variant="danger"
           :cancel-disabled="cancel_disabled"
           cancel-title="Cancelar"
           cancel-variant="secondary"
           @show="onShowModal"
           :no-close-on-backdrop="true"
           :no-close-on-esc="true">
    <FormSesion :readonly="readonly"/>
  </b-modal>
</template>

<script>
  import FormSesion from './FormSesion.vue';
  import { sesion } from '../../common/functions.js';

  export default {
    mixins: [ sesion ],
    data() {
      return {
        cancel_disabled: false,
        ok_disabled: false,
        readonly: false,
      }
    },
    created() {
      this.resetSesionBackup();
      this.$root.$on('disable-modal-sesion-save-button', this.toggleSaveButton);
      this.$root.$on('disable-modal-sesion-footer-buttons', this.toggleFooterButtons);

      this.$root.$on('disable-modal-sesion-readonly', this.disableReadOnly );
      this.$root.$on('enable-modal-sesion-readonly', this.enableReadOnly );
    },
    methods: {
      cancelEditItem() {
        this.restoreSesionBackup();
        this.$root.$emit('cancelEditSesion');
      },
      disableReadOnly() {
        this.readonly = false;
      },
      enableReadOnly() {
        this.readonly = true;
      },
      onShowModal() {
        this.makeSesionBackup();
      },
      saveSesion() {
        this.$root.$emit('saveEditSesion');
      },
      toggleFooterButtons(disabled) {
        this.cancel_disabled = disabled ? true : false;
        this.ok_disabled = this.cancel_disabled;
      },
      toggleSaveButton(disabled) {
        this.cancel_disabled = false;
        this.ok_disabled = disabled ? true : false;
      }
    },
    components: {
      FormSesion,
    }
  }
</script>

<style>
  @media all and (max-width:991px) {
    #editSesion .modal-dialog {
      max-width:95vw!important;
    }
  }
</style>
