<template>
  <div>
    <div class="main-header">
      <div class="toolbar mb-0 py-1">
        <div class="container">
          <b-row>
            <div class="col-sm-3">&nbsp;</div>
            <h4 class="col-sm-6 text-white font-weight-bold">GESTIÓN DE ENTRENAMIENTOS</h4>
            <div class="col-sm-3 text-right home-icon">
              <b-button v-if="this.$route.params.userRole === 'admin'" size="sm" variant="outline" href="/" class="mt-1"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Admin Menú</b-button>
            </div>
          </b-row>
        </div>
      </div>
    </div>

    <div class="container">
      <b-row>

        <b-col class="col-sm-8 float-left my-1 mb-3">
          <!-- <filtro-festivales></filtro-festivales> -->
        </b-col>

        <b-col class="col-sm-4 text-right my-1 mb-3">
          <router-link to="/entrenador/entrenamiento/new" class="text-white"><b-btn variant="danger" class="mb-0" title="Crear Entrenamiento">Nuevo Entrenamiento</b-btn></router-link>
        </b-col>

      </b-row>
    </div>
  </div>
</template>

<script>
  import { store } from '../store/store';

  export default {
      data () {
        return {
        }
      },
      created() {
        var myDate = new Date();
        var month = ('0' + (myDate.getMonth() + 1)).slice(-2);
        var date = ('0' + myDate.getDate()).slice(-2);
        var year = myDate.getFullYear();
        var formattedDate = year + '/' + month + '/' + date;

        store.dispatch('loadPelotaris', formattedDate);
        store.dispatch('loadProvincias');
        store.dispatch('loadMunicipios');
        store.dispatch('loadEntrActitudes');
        store.dispatch('loadEntrContenidos');
        store.dispatch('loadEntrFrontones');
        store.dispatch('loadEntrEvoluciones');
        store.dispatch('loadEntrAprovechamientos');
        store.dispatch('loadEntrenamientos');
      },
      methods: {
      }
  }
</script>

<style>
  .main-header {
    margin-bottom:2rem;
    margin-top:-1.45rem;
  }
  .main-header .toolbar {
    background-color:slategray;
    padding:10px 0;
    text-align:center;
  }
  .main-header h4 {
    line-height:1.75;
    margin:0 auto;
  }
</style>
