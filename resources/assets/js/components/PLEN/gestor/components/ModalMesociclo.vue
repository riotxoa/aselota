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
  import { mapState } from 'vuex';
  import FormMesociclo from './FormMesociclo';

  export default {
    data() {
      return {
        mesociclo_backup: null,
        disabled: false
      }
    },
    computed: mapState({
      mesociclo: state => state.plen.mesociclo
    }),
    created() {
      this.resetMesocicloBackup();
      this.$root.$on('disable-modal-mesociclo-save-button', this.toggleSaveButton);
    },
    methods: {
      cancelEditItem() {
        this.restoreMesocicloBackup();
        this.$root.$emit('cancelEditMesociclo');
      },
      makeMesocicloBackup() {
        this.mesociclo_backup.id = this.mesociclo.id;
        this.mesociclo_backup.macrociclo_id = this.mesociclo.macrociclo_id;
        this.mesociclo_backup.tipo_mesociclo_id = this.mesociclo.tipo_mesociclo_id;
        this.mesociclo_backup.fecha_ini = this.mesociclo.fecha_ini;
        this.mesociclo_backup.fecha_fin = this.mesociclo.fecha_fin;
        this.mesociclo_backup.description = this.mesociclo.description;
        this.mesociclo_backup.objetivos = this.mesociclo.objetivos;
        this.mesociclo_backup.microciclos = this.mesociclo.microciclos;
      },
      onShowModal() {
        this.makeMesocicloBackup();
      },
      resetMesocicloBackup() {
        this.mesociclo_backup = {
          id: null,
          macrociclo_id: null,
          tipo_mesociclo_id: null,
          fecha_ini: null,
          fecha_fin: null,
          description: '',
          objetivos: '',
          microciclos: []
        };
      },
      restoreMesocicloBackup() {
        this.mesociclo.id = this.mesociclo_backup.id;
        this.mesociclo.macrociclo_id = this.mesociclo_backup.macrociclo_id;
        this.mesociclo.tipo_mesociclo_id = this.mesociclo_backup.tipo_mesociclo_id;
        this.mesociclo.fecha_ini = this.mesociclo_backup.fecha_ini;
        this.mesociclo.fecha_fin = this.mesociclo_backup.fecha_fin;
        this.mesociclo.description = this.mesociclo_backup.description;
        this.mesociclo.objetivos = this.mesociclo_backup.objetivos;
        this.mesociclo.microciclos = this.mesociclo_backup.microciclos;
        this.resetMesocicloBackup();
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
