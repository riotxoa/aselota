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
        home: '/prensa',
        title: 'Gestión de Eventos de Prensa',
      }
    }
  }
</script>
