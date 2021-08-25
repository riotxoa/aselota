<template>
  <b-modal ref="editMicrociclo"
           id="editMicrociclo"
           title="Editar Microciclo"
           size="lg"
           @ok="saveMicrociclo"
           @cancel="cancelEditItem"
           ok-title="Guardar"
           ok-variant="danger"
           cancel-title="Cancelar"
           cancel-variant="secondary">
    <FormMicrociclo />
  </b-modal>
</template>

<script>
  import FormMicrociclo from './FormMicrociclo.vue';

  export default {
    methods: {
      cancelEditItem() {
        this.$root.$emit('cancelEditMicrociclo')
      },
      saveMicrociclo() {
        this.$root.$emit('saveEditMicrociclo')
      },
    },
    components: {
      FormMicrociclo,
    }
  }
</script>
