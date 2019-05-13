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
          <b-form-group horizontal label="Filtro" class="mb-0">
            <b-input-group>
              <b-form-input v-model="filter" placeholder="Texto de búsqueda" />
              <b-input-group-append>
                <b-btn :disabled="!filter" @click="filter = ''" title="Limpiar filtro">Limpiar</b-btn>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col class="col-sm-6 text-right my-1 mb-3">
          <router-link to="/cuadro/imprimir" class="text-white"><b-btn variant="outline-link" class="mb-0" title="Imprimir datos">Imprimir</b-btn></router-link>
          <router-link to="/cuadro/exportar" class="text-white"><b-btn variant="danger" class="mb-0" title="Exportar datos">Exportar</b-btn></router-link>
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
                  <b-col sm="2" class="text-sm-right"><b>Nombre:</b></b-col>
                  <b-col sm="4">{{ row.item.nombre }} {{ row.item.apellidos }}</b-col>
                  <b-col sm="2" class="text-sm-right"><b>F.Nacimiento:</b></b-col>
                  <b-col sm="4">{{ row.item.fecha_nac }}</b-col>
                </b-row>
                <b-row class="mb-2">
                  <b-col sm="2" class="text-sm-right"><b>DNI:</b></b-col>
                  <b-col sm="4">{{ row.item.DNI }}</b-col>
                  <b-col sm="2" class="text-sm-right"><b>NºSS:</b></b-col>
                  <b-col sm="4">{{ row.item.num_ss }}</b-col>
                </b-row>
                <b-row class="mb-2">
                  <b-col sm="2" class="text-sm-right"><b>Dirección:</b></b-col>
                  <b-col sm="10">{{ row.item.direccion }} - {{ row.item.cod_postal }} {{ row.item.municipio }} ({{ row.item.provincia }})</b-col>
                </b-row>
                <b-row class="mb-2">
                  <b-col sm="2" class="text-sm-right"><b>E-Mail:</b></b-col>
                  <b-col sm="4">{{ row.item.email }}</b-col>
                  <b-col sm="2" class="text-sm-right"><b>Tel.Fijo:</b></b-col>
                  <b-col sm="4">{{ row.item.telefono }}</b-col>
                </b-row>
                <b-row class="mb-2">
                  <b-col sm="2" class="text-sm-right"><b>Tel.Móvil:</b></b-col>
                  <b-col sm="4">{{ row.item.telefono_2 }}</b-col>
                  <b-col sm="2" class="text-sm-right"><b>Tel.Alternativo:</b></b-col>
                  <b-col sm="4">{{ row.item.telefono_3 }}</b-col>
                </b-row>
                <b-row class="mb-2">
                  <b-col sm="2" class="text-sm-right"><b>NºHijos:</b></b-col>
                  <b-col sm="4">{{ row.item.num_hijos }}</b-col>
                  <b-col sm="2" class="text-sm-right"><b>IBAN:</b></b-col>
                  <b-col sm="4">{{ row.item.iban }}</b-col>
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
            { key: '1', label: 'Retribuciones', sortable: true },
            { key: '2', label: 'Ratio disponibilidad', sortable: true },
            { key: '3', label: 'Bajas deportivas', sortable: true },
            { key: '4', label: 'Bajas médicas', sortable: true },
            { key: '5', label: 'No asistencia', sortable: true },
            { key: 'actions', label: 'Acciones', sortable: false, class: "text-center" },
          ],
          items: [],
          defaultPhoto: '/storage/avatars/default/default.jpg',
          totalRows: 0,
          perPage: 10,
          currentPage: 1,
          pageOptions: [ 10, 25, 50 ],
          filter: null,
          deleteId: null,
        }
      },
      created() {
        this.fetchPelotaris();
      },
      methods: {
        fetchPelotaris() {
          let uri = '/www/pelotaris';
          this.axios.get(uri).then((response) => {
            var stringified = JSON.stringify(response.data);
            this.items = JSON.parse(stringified);
            this.totalRows = this.items.length;
          });
        },
        onFiltered (filteredItems) {
          // Trigger pagination to update the number of buttons/pages due to filtering
          this.totalRows = filteredItems.length;
          this.currentPage = 1;
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
