<template>
  <b-modal ref="editMesociclo"
           id="editMesociclo"
           title="Editar Mesociclo"
           size="lg"
           @ok="saveMesociclo"
           @cancel="cancelEditItem"
           ok-title="Guardar"
           ok-variant="danger"
           cancel-title="Cancelar"
           cancel-variant="secondary">
    <FormMesociclo />
  </b-modal>
</template>

<script>
  import FormMesociclo from './FormMesociclo';

  export default {
    methods: {
      cancelEditItem() {
        this.$root.$emit('cancelEditMesociclo')
      },
      saveMesociclo() {
        this.$root.$emit('saveEditMesociclo')
      },
    },
    components: {
      FormMesociclo,
    }
  }
</script>
