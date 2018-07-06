<template>
  <div class="container">
      <router-view></router-view>
      <div id="snackbar"></div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        home: '/gerente',
        title: 'Gestión de Festivales',
      }
    }
  }
</script>
