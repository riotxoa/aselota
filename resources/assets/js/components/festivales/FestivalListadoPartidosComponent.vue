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
        festival_fecha: null,
        partidos: null,
      }
    },
    created: function () {
      console.log("FestivalListadoPartidosComponent created");
      this.fetchPartidos();
    },
    updated: function () {
      if(!this.festival_fecha && this.festivalHeader.fecha) {
        this.festival_fecha = this.festivalHeader.fecha;
        this.fetchPartidos();
      }
    },
    methods: {
      fetchPartidos() {
        if(!this.festivalHeader.fecha)
          return;

        let uri = '/www/partidos';

        this.axios.get(uri, {
            params: {
              festival_id: this.festivalHeader.id,
              festival_fecha: this.festivalHeader.fecha,
              festival_kaka: 'kakakulo'
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
