<template>
  <div>
    <add-new-partido-form></add-new-partido-form>
    <list-partidos></list-partidos>
  </div>
</template>

<script>
  Vue.component('add-new-partido-form', require('./FestivalNuevoPartidoComponent.vue'));
  Vue.component('list-partidos', require('./ListadoComponent.vue'));

  export default {
    data () {
      return {

      }
    },
    created: function () {
      console.log("FestivalListadoPartidosComponent created");
    },
    methods: {
    }
  }
</script>
