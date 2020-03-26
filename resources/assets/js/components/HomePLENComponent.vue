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
      switch( this.$route.params.userRole ) {
        case 'plen_gestor':
          this.$route.push({ name: 'PLEN_GESTOR' });
          break;
        default: // 'plen_entrenador'
          this.$route.push({ name: 'PLEN_ENTRENADOR' });
          break;
      }
      console.log("[CREATED] 1. this.userRole: " + this.$route.params.userRole);
    }
  }
</script>
