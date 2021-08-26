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
  import { mapState } from 'vuex';
  import FormMicrociclo from './FormMicrociclo.vue';

  export default {
    data() {
      return {
        microciclo_backup: null,
        disabled: false
      }
    },
    computed: mapState({
      microciclo: state => state.plen.microciclo
    }),
    created() {
      this.resetMicrocicloBackup();
      this.$root.$on('disable-modal-microciclo-save-button', this.toggleSaveButton);
    },
    methods: {
      cancelEditItem() {
        this.restoreMicrocicloBackup();
        this.$root.$emit('cancelEditMicrociclo');
      },
      makeMicrocicloBackup() {
        this.microciclo_backup.id = this.microciclo.id;
        this.microciclo_backup.mesociclo_id = this.microciclo.mesociclo_id;
        this.microciclo_backup.tipo_microciclo_id = this.microciclo.tipo_microciclo_id;
        this.microciclo_backup.fecha_ini = this.microciclo.fecha_ini;
        this.microciclo_backup.fecha_fin = this.microciclo.fecha_fin;
        this.microciclo_backup.volumen = this.microciclo.volumen;
        this.microciclo_backup.intensidad = this.microciclo.intensidad;
        this.microciclo_backup.description = this.microciclo.description;
        this.microciclo_backup.objetivos = this.microciclo.objetivos;
      },
      onShowModal() {
        this.makeMicrocicloBackup();
      },
      resetMicrocicloBackup() {
        this.microciclo_backup = {
          id: null,
          mesociclo_id: null,
          tipo_microciclo_id: null,
          fecha_ini: null,
          fecha_fin: null,
          volumen: null,
          intensidad: null,
          description: '',
          objetivos: ''
        };
      },
      restoreMicrocicloBackup() {
        this.microciclo.id = this.microciclo_backup.id;
        this.microciclo.mesociclo_id = this.microciclo_backup.mesociclo_id;
        this.microciclo.tipo_microciclo_id = this.microciclo_backup.tipo_microciclo_id;
        this.microciclo.fecha_ini = this.microciclo_backup.fecha_ini;
        this.microciclo.fecha_fin = this.microciclo_backup.fecha_fin;
        this.microciclo.volumen = this.microciclo_backup.volumen;
        this.microciclo.intensidad = this.microciclo_backup.intensidad;
        this.microciclo.description = this.microciclo_backup.description;
        this.microciclo.objetivos = this.microciclo_backup.objetivos;
        this.resetMicrocicloBackup();
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
