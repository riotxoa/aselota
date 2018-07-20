<template>
  <div>
    <add-new-partido-form :festival-header="festivalHeader" v-on:new-partido="fetchPartidos"></add-new-partido-form>
    <list-partidos :festival-header="festivalHeader" :partidos="partidos" v-on:delete-partido="deletePartido($event)"></list-partidos>
  </div>
</template>

<script>
  Vue.component('add-new-partido-form', require('./FestivalNuevoPartidoComponent.vue'));
  Vue.component('list-partidos', require('../partidos/ListadoComponent.vue'));
  export default {
    props: ['festivalHeader'],
    data () {
      return {
        partidos: null,
      }
    },
    created: function () {
      console.log("FestivalListadoPartidosComponent created");
      this.fetchPartidos();
    },
    methods: {
      fetchPartidos() {
        let uri = '/www/partidos';
        let data = {
          festival_id: this.festivalHeader.id
        };
        this.axios.get(uri, {
            params: {
              festival_id: this.festivalHeader.id
            }
        })
        .then((response) => {
          var stringified = JSON.stringify(response.data);
          this.partidos = JSON.parse(stringified);
        });
      },
      deletePartido(id) {
        this.partidos = _.remove(this.partidos, function(n){
          return n.id !== id;
        });
      }
    }
  }
</script>

<style>

</style>
