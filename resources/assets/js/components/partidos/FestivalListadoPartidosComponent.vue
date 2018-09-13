<template>
  <div>
    <add-new-partido-form v-if="_header.estado_id !== 3"></add-new-partido-form>
    <list-partidos :is-gerente="true"></list-partidos>
  </div>
</template>

<script>
  import { mapState } from 'vuex';
  
  Vue.component('add-new-partido-form', require('./FestivalNuevoPartidoComponent.vue'));
  Vue.component('list-partidos', require('./ListadoComponent_I.vue'));

  export default {
    data () {
      return {

      }
    },
    created: function () {
      console.log("FestivalListadoPartidosComponent created");
    },
    computed: mapState({
      _header: 'header',
    }),
    methods: {
    }
  }
</script>
