<template>
  <div class="w-100">
      <router-view></router-view>
      <div id="snackbar"></div>
  </div>
</template>

<script>
  export default {
    props: ['userRole'],
    data () {
      return {
        home: '/PLEN',
        title: 'PLANIFICACIÓN DE ENTRENAMIENTOS',
      }
    },
    created() {

    }
  }
</script>
