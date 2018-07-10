<template>
  <div>
    <festival-header :form-title="formTitle" :festival-id="id" :edit="edit" v-on:toggle-edit="edit = !edit; id = $event"></festival-header>
    <festival-body v-if="edit" :festival-id="id" :edit="edit"></festival-body>
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
        id: null,
        edit: null,
      }
    },
    created: function() {
      console.log("FichaComponent created");
      this.edit = !this.isNewFestival;
    },
    methods: {
    }
  }
</script>
