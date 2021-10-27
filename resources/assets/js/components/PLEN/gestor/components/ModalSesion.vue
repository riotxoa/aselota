<template>
  <b-modal ref="editSesion"
           id="editSesion"
           title="Editar Sesion"
           size="lg"
           @ok="saveSesion"
           @cancel="cancelEditItem"
           :ok-disabled="ok_disabled"
           ok-title="Guardar"
           ok-variant="danger"
           :cancel-disabled="cancel_disabled"
           cancel-title="Cancelar"
           cancel-variant="secondary"
           @show="onShowModal"
           :no-close-on-backdrop="true"
           :no-close-on-esc="true">
    <FormSesion />
  </b-modal>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import FormSesion from './FormSesion.vue';

  export default {
    data() {
      return {
        cancel_disabled: false,
        ok_disabled: false,
        sesion_backup: null
      }
    },
    computed: mapState({
      sesion: state => state.plen.sesion
    }),
    created() {
      this.resetSesionBackup();
      this.$root.$on('disable-modal-sesion-save-button', this.toggleSaveButton);
      this.$root.$on('disable-modal-sesion-footer-buttons', this.toggleFooterButtons);
    },
    methods: {
      ...mapActions({
        setSesion: 'plen/setSesion'
      }),
      cancelEditItem() {
        this.restoreSesionBackup();
        this.$root.$emit('cancelEditSesion');
      },
      makeSesionBackup() {
        this.resetSesionBackup();
        this.sesion_backup = JSON.parse(JSON.stringify(this.sesion));
      },
      onShowModal() {
        this.makeSesionBackup();
      },
      resetSesionBackup() {
        this.sesion_backup = {
          id: null,
          microciclo_id: null,
          fecha: null,
          hora: null,
          fronton_id: null,
          pelotaris: [],
        };
      },
      restoreSesionBackup() {
        const sesion_backup = JSON.parse(JSON.stringify(this.sesion_backup));
        this.sesion.id = sesion_backup.id;
        this.sesion.microciclo_id = sesion_backup.microciclo_id;
        this.sesion.fecha = sesion_backup.fecha;
        this.sesion.hora = sesion_backup.hora;
        this.sesion.fronton_id = sesion_backup.fronton_id;
        this.sesion.pelotaris = [];
        sesion_backup.pelotaris.map( (val, key) => {
          const index = this.sesion.pelotaris.length;
          this.sesion.pelotaris.push(val);
          let ejercicios = [];
          sesion_backup.pelotaris[key].ejercicios.map( (v, k) => {
            ejercicios.push(v);
          });
          this.sesion.pelotaris[index].ejercicios = ejercicios;
        });
        this.setSesion(this.sesion);
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
