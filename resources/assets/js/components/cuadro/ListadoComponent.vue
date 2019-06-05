<template>
  <div>
    <div class="main-header">
      <div class="toolbar mb-0 py-1">
        <div class="container">
          <b-row>
            <div class="col-sm-3">&nbsp;</div>
            <h4 class="col-sm-6 text-white font-weight-bold">CUADRO DE MANDO</h4>
            <div class="col-sm-3 text-right home-icon">
              <b-button v-if="this.$route.params.userRole === 'admin'" size="sm" variant="outline" href="/" class="mt-1"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Admin Menú</b-button>
            </div>
          </b-row>
        </div>
      </div>
    </div>

    <div class="container">
      <b-row>

        <b-col class="col-sm-6 float-left my-1 mb-3">
          <b-form-group horizontal label="Fechas" class="mb-0">
            <b-input-group class="col-sm-6 float-left">
              <b-form-input @change="actualizaFechaIni()" :min="fecha_min" :max="fecha_max" v-model="fecha_ini" type="date" placeholder="Fecha inicio" />
            </b-input-group>
            <b-input-group class="col-sm-6 float-left">
              <b-form-input @change="actualizaFechaFin()" :min="fecha_min" :max="fecha_max" v-model="fecha_fin" type="date" placeholder="Fecha fin" />
            </b-input-group>
          </b-form-group>
          <br>
          <b-form-group horizontal label="Filtro" class="mb-0">
            <b-input-group class="col-sm-12 float-left">
              <b-form-input v-model="filter" placeholder="Texto de búsqueda" />
              <b-input-group-append>
                <b-btn :disabled="!filter" @click="filter = ''" title="Limpiar filtro">Limpiar</b-btn>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col class="col-sm-6 text-right my-1 mb-3">
          <b-btn @click="imprimirDatos()" variant="outline-link" class="mb-0" title="Imprimir datos">Imprimir</b-btn>
          <b-btn @click="exportarDatos()" variant="danger" class="mb-0" title="Exportar datos">Exportar</b-btn>
        </b-col>

      </b-row>
      
      <b-table striped hover small responsive
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :per-page="perPage"
        :current-page="currentPage"
        :items="items"
        :fields="fields"
        :filter="filter"
        @filtered="onFiltered">

        <template slot="actions" slot-scope="row">
          <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
          <b-button-group>
            <b-button size="sm" variant="secondary" @click.stop="row.toggleDetails" title="Mostrar/Ocultar Detalle">
              <span class="icon" v-bind:class="{ 'voyager-x': row.detailsShowing, 'voyager-eye': !row.detailsShowing }"></span>
            </b-button>
          </b-button-group>
        </template>

        <template slot="row-details" slot-scope="row">
          <b-card>
            <b-row>
              <b-col sm="1">
                <img :src="(row.item.foto ? row.item.foto : defaultPhoto)" class="img-responsive" style="width:100%;">
              </b-col>
              <b-col sm="11">
                <b-row class="mb-2">
                  <b-col sm="4" class="text-sm-right"><b>Dietas mensuales:</b></b-col>
                  <b-col sm="2">{{ row.item.retribucion_meses }}</b-col>
                  <b-col sm="4" class="text-sm-right"><b>Derechos de imagen:</b></b-col>
                  <b-col sm="2">{{ row.item.retribucion_d_imagen }}</b-col>
                </b-row>
                <b-row class="mb-2">
                  <b-col sm="4" class="text-sm-right"><b>Partidos jugados:</b></b-col>
                  <b-col sm="2">{{ row.item.partidos_jugados }} ({{ row.item.partidos_ganados }} ganados)</b-col>
                  <b-col sm="4" class="text-sm-right"><b>Dieta partidos:</b></b-col>
                  <b-col sm="2">{{ row.item.retribucion_dieta_partido }}</b-col>
                </b-row>
                <b-row class="mb-2">
                  <b-col sm="4" class="text-sm-right"><b>Primas partidos:</b></b-col>
                  <b-col sm="2">{{ row.item.retribucion_prima_partido }}</b-col>
                  <b-col sm="4" class="text-sm-right"><b>Primas estelar:</b></b-col>
                  <b-col sm="2">{{ row.item.retribucion_prima_estelar }}</b-col>
                </b-row>
                <b-row class="mb-2">
                  <b-col sm="4" class="text-sm-right"><b>Primas Manomanista:</b></b-col>
                  <b-col sm="2">{{ row.item.retribucion_prima_manomanista }}</b-col>
                  <b-col sm="4" class="text-sm-right"><b>Primas Manomanista PROMO:</b></b-col>
                  <b-col sm="2">{{ row.item.retribucion_prima_manomanista_promo }}</b-col>
                </b-row>
                <b-row class="mb-2">
                  <b-col sm="4" class="text-sm-right"><b>Total entrenamientos:</b></b-col>
                  <b-col sm="4">{{ row.item.num_entrenamientos }} ({{ row.item.no_asiste }} sin asistencia)</b-col>
                </b-row>
              </b-col>
            </b-row>
          </b-card>
        </template>

      </b-table>

      <b-row>
        <b-col md="5" class="my-1">
          <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
        </b-col>
        <b-col md="3" class="my-1 text-right">
          <b-form-group horizontal class="mb-0" label="Total: ">
            <b-form-input readonly plaintext v-model="totalRows"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4" class="my-1">
          <b-form-group horizontal label="Mostrar" class="mb-0">
            <b-form-select :options="pageOptions" v-model="perPage" />
          </b-form-group>
        </b-col>
      </b-row>

    </div>
  </div>
</template>

<script>
  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';
  import { store } from '../store/store';
  import { mapState } from 'vuex';

  function formatDate(date) {
    var day = date.getDate();
    var month = date.getMonth()+1;
    var year = date.getFullYear();
    if(day<10){
      day = "0" + day;
    }
    if(month<10){
      month = "0" + month;
    }
    return year + '-' + month + '-' + day;
  }

  const showSnackbar = (msg) => {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";
    x.innerHTML = msg;

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  }
  export default {
      data () {
        return {
          sortBy: 'age',
          sortDesc: false,
          fields: [
            { key: 'alias', label: 'Apodo', sortable: true },
            { key: 'retribucion', label: 'Retribuciones', sortable: false },
            { key: 'ratio_disponibilidad', label: 'Ratio disponibilidad', sortable: true },
            { key: '3', label: 'Bajas deportivas', sortable: true },
            { key: '4', label: 'Bajas médicas', sortable: true },
            { key: 'no_asiste', label: 'No asistencia', sortable: true },
            { key: 'actions', label: 'Acciones', sortable: false, class: "text-center" },
          ],
          items: [],
          defaultPhoto: '/storage/avatars/default/default.jpg',
          totalRows: 0,
          perPage: 10,
          currentPage: 1,
          pageOptions: [ 10, 25, 50 ],
          filter: null,
          fecha_min: "1900-01-01",
          fecha_max: formatDate(new Date()),
          fecha_ini: new Date().getFullYear() + "-01-01",
          fecha_fin: formatDate(new Date()),
          deleteId: null,
        }
      },
      created() {
        this.fetchPelotaris();
      },
      methods: {
        fetchPelotaris() {
          let uri = '/www/pelotaris-cuadro';
          
          this.axios.get(uri, {
            params: {
              fecha_ini: this.fecha_ini,
              fecha_fin: this.fecha_fin,
            }
          }).then((response) => {
            var stringified = JSON.stringify(response.data);
            this.items = JSON.parse(stringified);
            console.log(this.items);
            this.totalRows = this.items.length;
            
          });
        },
        onFiltered (filteredItems) {
          // Trigger pagination to update the number of buttons/pages due to filtering
          this.totalRows = filteredItems.length;
          this.currentPage = 1;
        },
        actualizaFechaIni(){
          this.fetchPelotaris();
        },
        actualizaFechaFin(){
          this.fetchPelotaris();
        },
        imprimirDatos (){

        },
        exportarDatos (){
          var redirectWindow = window.open('/exportar-cuadro-mando?fecha_ini=' + this.fecha_ini + '&'+'fecha_fin=' + this.fecha_fin, '_blank');
          redirectWindow.location;
          /*
          let uri = '/exportar-cuadro-mando';
          
          this.axios.get(uri, {
            params: {
              fecha_ini: ,
              fecha_fin: this.fecha_fin,
            }
          }).then((response) => {
            alert("hola");
            console.log(response);

          });*/
        }
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
