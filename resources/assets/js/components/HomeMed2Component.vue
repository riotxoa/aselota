<template>
  <div class="w-100">
      <router-view></router-view>
      <div id="snackbar"></div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        home: '/modulo-medico',
        title: 'Gestión de Partes Médicos',
      }
    }
  }
</script>
