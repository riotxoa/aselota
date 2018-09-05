<template>
  <div v-if="_partidos" class="list-partidos-festival container px-0">
    <ul class="mt-3 px-3">
      <li v-for="partido in _partidos">
        <partido-i :partido="partido"></partido-i>
      </li>
    </ul>
  </div>
</template>

<script>
  Vue.component('partido-i', require('./ListadoItemComponent_I.vue'));

  import Utils from '../utils/utils.js';
  import { mapState } from 'vuex';

  export default {
    mixins: [Utils],
    data() {
      return {
      }
    },
    created: function () {
      console.log("ListadoComponent created");
    },
    computed: {...mapState({
      _partidos: 'partidos',
    })},
    methods: {

    }
  }
</script>

<style>
  ul {
    list-style-type:none;
  }
</style>
