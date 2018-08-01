<template>
  <div class="body">
    <b-card no-body>
      <b-tabs pills card class="container">
        <b-tab title="Partidos" class="px-0 py-4">
          <festival-partidos></festival-partidos>
        </b-tab>
        <b-tab title="Costes">
          <festival-costes></festival-costes>
        </b-tab>
        <b-tab title="Facturación">
          <h6>Facturación {{ this._header.id }}</h6>
        </b-tab>
      </b-tabs>
    </b-card>
  </div>
</template>

<script>
  import { mapState } from 'vuex';

  Vue.component('festival-partidos', require('../partidos/FestivalListadoPartidosComponent.vue'));
  Vue.component('festival-costes', require('../costes/FestivalFichaCostesComponent.vue'));

  export default {
    data () {
      return {
      }
    },
    created: function () {
      console.log("FestivalBodyComponent created");
    },
    computed: mapState({
      _header: 'header',
    }),
    methods: {

    }
  }
</script>

<style>
  .card-header {
    background:transparent;
    border:none;
    padding:0;
  }
  .card-header .nav-pills .nav-link.active,
  .card-header .nav-pills .show > .nav-link {
    background-color:#4fb749;
  }
  .card-header .nav-item {
    background-color:#daf0da;
    font-weight:bold;
    text-align:center;

    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .card-header .nav-item a {
    color:#4fb749;
  }
  .card-header .nav-item:first-child {
    border-right:2.5px solid #f5f8fa;
  }
  .card-header .nav-item:last-child {
    border-left:2.5px solid #f5f8fa;
  }
  .card-header .nav-item:not(:first-child):not(:last-child) {
    border-left:1.25px solid #f5f8fa;
    border-right:1.25px solid #f5f8fa;
  }
  .tabs {
    border-left: 1px solid #daf0da;
    padding: 0 .55rem;
    border-right: 1px solid #daf0da;
    border-bottom: 1px solid #daf0da;
    margin-bottom: 3rem;
  }
</style>
