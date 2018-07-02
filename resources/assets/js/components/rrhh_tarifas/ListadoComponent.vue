<template>
  <div id="rrhh-tarifas">
    <b-card no-body>
      <b-tabs pills card vertical>
        <b-tab :title="`${tab.name}`" v-for="tab in campeonatos">
          <tarifas-campeonato :pelotari-id="pelotariId" :pelotari-alias="pelotariAlias" :campeonato-id="`${tab.id}`" :campeonato-name="`${tab.name}`"></tarifas-campeonato>
        </b-tab>
      </b-tabs>
    </b-card>
  </div>
</template>

<script>
  Vue.component('tarifas-campeonato', require('./TarifasCampeonatoComponent.vue'));
  export default {
    props: ['pelotariId', 'pelotariAlias'],
    data () {
      return {
        campeonatos: [],
        tabs: [],
      }
    },
    created() {
      this.fetchCampeonatos();
    },
    methods: {
      fetchCampeonatos() {
        let uri = '/www/campeonatos';
        this.axios.get(uri)
        .then((response) => {
          var stringified = JSON.stringify(response.data);
          this.campeonatos = JSON.parse(stringified);
          this.campeonatos.map( (val,key) => {
            this.tabs.push(val.name);
          });
        });
      }
    }
  }
</script>

<style>
  #rrhh-tarifas .card {
    border:none;
  }
  #rrhh-tarifas .nav-pills.card-header {
    background-color:transparent;
  }
  #rrhh-tarifas .nav-pills .nav-link {
    margin-bottom:.25rem;
  }
  #rrhh-tarifas .nav-pills .nav-link.active,
  #rrhh-tarifas .nav-pills .show > .nav-link {
    background-color:#d82a1f;
  }
  #rrhh-tarifas .tabs .card-header {
    padding-left:0;
  }
  #rrhh-tarifas .tab-pane.card-body {
    padding:0;
  }
</style>
