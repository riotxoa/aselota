<template>
  <div id="preloader">
    <festival-header :form-title="formTitle" :festival-id="header.id" :edit="edit" v-on:toggle-edit="edit = !edit" v-on:update-header="updateHeader($event)"></festival-header>
    <festival-body v-if="edit" :festival-header="header" :edit="edit"></festival-body>
  </div>
</template>

<script>
  Vue.component('festival-header', require('./FestivalHeaderComponent.vue'));
  Vue.component('festival-body', require('./FestivalBodyComponent.vue'));
  const showSnackbar = (msg) => {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";
    x.innerHTML = msg;

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  }
  export default {
    props: ['formTitle', 'isNewFestival'],
    data () {
      return {
        header: {
          id: null,
          fecha: '',
          hora: '',
          fronton_id: null,
          television: 0,
          television_txt: '',
          estado_id: 1,
        },
        edit: null,
      }
    },
    created: function() {
      console.log("FichaComponent created");
      this.edit = !this.isNewFestival;
      this.header.id = this.$route.params.id;
    },
    methods: {
      updateHeader(h) {
        this.header = h;
      }
    }
  }
</script>

<style>

</style>
