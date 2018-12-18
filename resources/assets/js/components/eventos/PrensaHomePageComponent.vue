<template>
  <div>
    <div class="main-header prensa">
      <div class="toolbar mb-0 py-1">
        <div class="container">
          <b-row>
            <div class="col-sm-3">&nbsp;</div>
            <h4 class="col-sm-6 text-white font-weight-bold">MÓDULO DE PRENSA</h4>
            <div class="col-sm-3 text-right home-icon">
              <b-button v-if="this.$route.params.userRole === 'admin'" size="sm" variant="outline" href="/" class="mt-1"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Admin Menú</b-button>
            </div>
          </b-row>
        </div>
      </div>
    </div>

    <div class="container">
      <b-card no-body>
        <b-tabs pills card v-model="tabIndex">
          <b-tab title="Eventos de Prensa" title-item-class="text-uppercase font-weight-bold">
            <listado-eventos/>
          </b-tab>
          <b-tab title="Festivales" title-item-class="text-uppercase font-weight-bold">
            <listado-festivales is-gerente=0 is-prensa=1 />
          </b-tab>
        </b-tabs>
      </b-card>
    </div>
  </div>
</template>

<script>
  var store_prensa = {
    tabIndex: 0,
  }

  export default {
    data () {
      return {
        tabIndex: store_prensa.tabIndex
      }
    },
    updated() {
      store_prensa.tabIndex = this.tabIndex;
    },
  }
</script>

<style>
  .prensa.main-header {
    margin-bottom:2rem;
    margin-top:-1.45rem;
  }
  .prensa.main-header .toolbar {
    background-color:slategray;
    padding:10px 0;
    text-align:center;
  }
  .prensa.main-header h4 {
    line-height:1.75;
    margin:0 auto;
  }
  .main-header:not(.prensa) {
    display:none;
  }
</style>
