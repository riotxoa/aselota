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
        home: '/rrhh',
        title: 'Gestión de RRHH',
      }
    }
  }
</script>
