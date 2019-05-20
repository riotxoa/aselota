<template>
  <div>
    <div class="main-header pelotaris">
      <div class="toolbar mb-0 py-1">
        <div class="container">
          <b-row>
            <div class="col-sm-3">&nbsp;</div>
            <h4 class="col-sm-6 text-white font-weight-bold">MÓDULO DE RRHH</h4>
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
          <b-tab title="Profesionales" title-item-class="text-uppercase font-weight-bold">
            <listado-pelotaris-profesional />
          </b-tab>
          <b-tab title="Promesas" title-item-class="text-uppercase font-weight-bold">
            <listado-pelotaris-promesa />
          </b-tab>
        </b-tabs>
      </b-card>
    </div>
  </div>
</template>

<script>
  var store_pelotaris = {
    tabIndex: 0,
  }

  export default {
    data () {
      return {
        home: '/rrhh',
        title: 'Gestión de RRHH',
        tabIndex: store_pelotaris.tabIndex
      }
    },
    updated() {
      store_pelotaris.tabIndex = this.tabIndex;
    },
  }
</script>

<style>
  .pelotaris.main-header {
    margin-bottom:2rem;
    margin-top:-1.45rem;
  }
  .pelotaris.main-header .toolbar {
    background-color:slategray;
    padding:10px 0;
    text-align:center;
  }
  .pelotaris.main-header h4 {
    line-height:1.75;
    margin:0 auto;
  }
  .main-header:not(.pelotaris) {
    display:none;
  }
</style>
