<template>
  <div class="container-fluid">
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
        <b-btn class="bg-danger mb-0" title="Crear Pelotari">
          <router-link to="/rrhh/pelotari/new" class="text-white">Nuevo Pelotari</router-link>
        </b-btn>
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
          <b-button size="sm" variant="danger" @click.stop="onClickDelete(row.item.id, row.item.alias)" title="Eliminar">
            <span class="icon voyager-trash"></span>
          </b-button>
          <b-button size="sm" variant="primary" @click.stop="edit(row.item.id)" title="Editar">
            <span class="icon voyager-edit"></span>
          </b-button>
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
                <b-col sm="2" class="text-sm-right"><b>DNI:</b></b-col>
                <b-col sm="4">{{ row.item.DNI }}</b-col>
              </b-row>
              <b-row class="mb-2">
                <b-col sm="2" class="text-sm-right"><b>Dirección:</b></b-col>
                <b-col sm="10">{{ row.item.direccion }} - {{ row.item.cod_postal }} {{ row.item.municipio }} ({{ row.item.provincia }})</b-col>
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
        <b-form-group horizontal label="NºLíneas" class="mb-0">
          <b-form-select :options="pageOptions" v-model="perPage" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-modal ref="modalDelete" title="BORRAR Pelotari" hide-footer>
      <div class="modal-body">
        <p>Se va a borrar la ficha completa de <strong id="deleteAlias"></strong>. ¿Desea continuar?</p>
      </div>
      <div class="modal-footer">
        <b-btn variant="danger" @click="remove">Borrar</b-btn>
        <b-btn @click="hideModalDelete">Cancelar</b-btn>
      </div>
    </b-modal>

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
            { key: 'posicion', label: 'Posición', sortable: true },
            { key: 'provincia', label: 'Provincia', sortable: true },
            { key: 'email', label: 'Correo', sortable: true },
            { key: 'telefono', label: 'Teléfono', sortable: false },
            { key: 'actions', label: 'Acciones', sortable: false },
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
        },
        edit (id) {
          this.$router.push('/rrhh/pelotari/' + id + '/edit/');
        },
        remove () {
          let uri = '/www/pelotaris/' + this.deleteId;
          this.axios.delete(uri)
            .then((response) => {
              this.deleteId = null;
              this.$refs.modalDelete.hide();
              this.fetchPelotaris();
              showSnackbar("Pelotari BORRADO");
            })
            .catch((error) => {
              console.log("[remove] error: " + error);
              this.deleteId = null;
              this.$refs.modalDelete.hide();
              showSnackbar("ERROR al borrar");
            });
        },
        onClickDelete (id, name) {
          this.deleteId = id;
          jQuery('#deleteAlias').html(name);
          this.$refs.modalDelete.show();
        },
        hideModalDelete() {
          this.deleteId = null;
          this.$refs.modalDelete.hide();
        }
      }
  }
</script>
