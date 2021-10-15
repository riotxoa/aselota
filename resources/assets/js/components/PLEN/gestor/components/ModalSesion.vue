<template>
  <b-modal ref="editSesion"
           id="editSesion"
           title="Editar Sesion"
           size="lg"
           @ok="saveSesion"
           @cancel="cancelEditItem"
           :ok-disabled="disabled"
           ok-title="Guardar"
           ok-variant="danger"
           cancel-title="Cancelar"
           cancel-variant="secondary"
           @show="onShowModal">
    <FormSesion />
  </b-modal>
</template>

<script>
  import { mapState } from 'vuex';
  import FormSesion from './FormSesion.vue';

  export default {
    data() {
      return {
        sesion_backup: null,
        disabled: false
      }
    },
    computed: mapState({
      sesion: state => state.plen.sesion
    }),
    created() {
      this.resetSesionBackup();
      this.$root.$on('disable-modal-sesion-save-button', this.toggleSaveButton);
    },
    methods: {
      cancelEditItem() {
        this.restoreSesionBackup();
        this.$root.$emit('cancelEditSesion');
      },
      makeSesionBackup() {
        this.sesion_backup.id = this.sesion.id;
        this.sesion_backup.microciclo_id = this.sesion.microciclo_id;
        this.sesion_backup.fecha = this.sesion.fecha;
        this.sesion_backup.hora = this.sesion.hora;
        this.sesion_backup.fronton_id = this.sesion.fronton_id;
        this.sesion.pelotaris.map( (val, key) => {
          this.sesion_backup.pelotaris[key] = val;
        });
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
        this.sesion.id = this.sesion_backup.id;
        this.sesion.microciclo_id = this.sesion_backup.microciclo_id;
        this.sesion.fecha = this.sesion_backup.fecha;
        this.sesion.hora = this.sesion_backup.hora;
        this.sesion.fronton_id = this.sesion_backup.fronton_id;
        this.sesion.pelotaris = [];
        this.sesion_backup.pelotaris.map( (val, key) => {
          this.sesion.pelotaris[key] = val;
        })
      },
      saveSesion() {
        this.$root.$emit('saveEditSesion');
      },
      toggleSaveButton(disabled) {
        this.disabled = disabled ? true : false;
      }
    },
    components: {
      FormSesion,
    }
  }
</script>
