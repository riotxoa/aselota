<template>
  <div class="w-100">
      <router-view></router-view>
      <div id="snackbar"></div>
  </div>
</template>

<script>/* #MODIF4 */
  export default {
    data () {
      return {
        home: '/informes',
        title: 'Informes',
      }
    }
  }
</script>
