<template>
  <div id="preloader">
    <festival-header :form-title="formTitle" :is-gerente="isGerente" :is-prensa="isPrensa"></festival-header>
    <festival-body v-if="_edit && isGerente"></festival-body>
    <!-- <list-partidos-i v-else></list-partidos-i> -->
    <list-partidos-i v-if="_edit && !isGerente" :is-prensa="isPrensa"></list-partidos-i>
  </div>
</template>

<script>
  import { store } from '../store/store';
  import { mapState } from 'vuex';

  Vue.component('festival-header', require('./FestivalHeaderComponent.vue'));
  Vue.component('festival-body', require('./FestivalBodyComponent.vue'));
  Vue.component('list-partidos-i', require('../partidos/ListadoComponent_I.vue'));
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
    props: ['formTitle', 'isNewFestival', 'isGerente', 'isPrensa'],
    data () {
      return {
      }
    },
    created: function() {
      console.log("FichaComponent created");

      this.$store.commit('SET_EDIT', !this.isNewFestival);
      if(!this.isNewFestival) {
        this.$store.commit('SET_HEADER_ID', this.$route.params.id);
      } else {
        store.dispatch('resetFestival');
      }
    },
    computed: mapState({
      _edit: 'edit',
    }),
    methods: {
      updateHeader(h) {
        this.header = h;
      }
    }
  }
</script>
