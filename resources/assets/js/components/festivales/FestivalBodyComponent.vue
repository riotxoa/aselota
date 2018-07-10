<template>
  <div class="body">
    <b-tabs pills card class="container">
      <b-tab title="Partidos">
        <h6>Partidos {{ this.festivalId }}</h6>
      </b-tab>
      <b-tab title="Costes">
        <h6>Costes {{ this.festivalId }}</h6>
      </b-tab>
      <b-tab title="Facturación">
        <h6>Facturación {{ this.festivalId }}</h6>
      </b-tab>
    </b-tabs>
  </div>
</template>

<script>
  export default {
    props: ['festivalId'],
    data () {
      return {

      }
    },
    created: function () {
      console.log("FestivalBodyComponent created");
    },
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
  .card-header .nav-item {
    background-color:#ffffff;
    font-weight:bold;
    text-align:center;
    /* width:calc(100% / 3); */
  }
  .card-header .nav-item {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
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
</style>
