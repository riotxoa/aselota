<template>
  <div id="preloader">
    <evento-header :form-title="formTitle"></evento-header>
    <evento-body v-if="_edit"></evento-body>
  </div>
</template>

<script>
  import { store } from '../store/store';
  import { mapState } from 'vuex';

  Vue.component('evento-header', require('./EventoHeaderComponent.vue'));
  Vue.component('evento-body', require('./EventoBodyComponent.vue'));
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
    props: ['formTitle', 'isNewEvento'],
    data () {
      return {
      }
    },
    created: function() {
      console.log("FichaComponent created");

      this.$store.commit('SET_EVENTO_EDIT', !this.isNewEvento);
      if(!this.isNewEvento) {
        this.$store.commit('SET_EVENTO_ID', this.$route.params.id);
      } else {
        store.dispatch('resetEvento');
      }
    },
    computed: mapState({
      _edit: 'edit_evento',
    }),
    methods: {

    }
  }
</script>
