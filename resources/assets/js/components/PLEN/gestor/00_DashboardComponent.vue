<template>
  <div>
    <h1>KAIXO MUNDUA!! Gestor</h1>
  </div>
</template>

<script>
export default {
  props: ['userRole'],
  data () {
    return {

    }
  },
  created() {
    console.log("[CREATED] 2. this.userRole: " + this.$route.params.userRole);
  }
}
</script>

<style focused>
</style>
