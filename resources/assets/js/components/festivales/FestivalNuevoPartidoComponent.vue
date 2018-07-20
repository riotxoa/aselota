<template>
  <div id="nuevoPartido">
    <b-card no-body class="mb-1">
      <b-card-header header-tag="header" class="p-0" role="tab">
        <b-btn block href="#" v-b-toggle.accordionPartido variant="primary">Nuevo Partido</b-btn>
      </b-card-header>
      <b-collapse id="accordionPartido" accordion="accordion-partido" role="tabpanel">
        <b-card-body>
          <form-partido :festival-id="festivalId" :edit="false"  v-on:new-partido="() => this.$emit('new-partido')"></form-partido>
        </b-card-body>
      </b-collapse>
    </b-card>
  </div>
</template>

<script>
  Vue.component('form-partido', require('../partidos/FichaComponent.vue'));

  export default {
    props: ['festivalId'],
    data () {
      return {
      }
    },
    created: function () {
      console.log("FestivalNuevoPartidoComponent created");
    },
    methods: {
      addPartido(p) {
        console.log("[addPartido] p: " + JSON.stringify(p));
      }
    }
  }
</script>

<style>
  #nuevoPartido .card {
    border:none;
    margin-left:1rem;
  }
  #nuevoPartido .card-header a {
    float:left;
    max-width:10rem;
  }
  #accordionPartido {
    border:1px solid #007bff;
    margin-right:1.5rem;
  }
</style>
