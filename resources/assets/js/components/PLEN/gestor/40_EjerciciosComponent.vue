<template>
  <div>
    <p class="module-title py-2 text-center">Ejercicios</p>
    <b-container class="p-xl-3">
      <b-button @click="addNewItem" variant="danger" class="float-right mb-3">Nuevo Ejercicio</b-button>
      <b-table v-if="show" outlined small striped hover :items="ejercicios" :fields="fields" class="plen-table" :current-page="currentPage" :per-page="perPage">

        <template slot="plen_tipos_ejercicio_id" slot-scope="row">
          {{ row.item.tipo_name }}
        </template>

        <template slot="plen_subtipos_ejercicio_id" slot-scope="row">
          {{ row.item.subtipo_name }}
        </template>

        <!-- Botonera de acciones -->
        <template slot="actions" slot-scope="row" v-if="ejercicios[0].id">
          <b-button size="sm" title="Editar" variant="primary" @click="onClickEdit(row.item)"><i class="fas fa-edit"></i></b-button>
          <b-button size="sm" title="Mostrar/Ocultar Detalle" variant="secondary" @click.stop="row.toggleDetails">
            <span class="icon" v-bind:class="{ 'voyager-x': row.detailsShowing, 'voyager-eye': !row.detailsShowing }"></span>
          </b-button>
          <b-button size="sm" title="Borrar" variant="danger" @click="onClickRemove(row.item)"><i class="fas fa-trash-alt"></i></b-button>
        </template>

        <!-- Detalles del item -->
        <template slot="row-details" slot-scope="row">
          <b-row>
            <b-col cols="12" md="6">
              <b-row class="pl-md-5">
                <b-col cols="3"><strong>ID:</strong></b-col>
                <b-col cols="3" class="border mt-2">{{ row.item.id }}</b-col>
                <b-col cols="3"><strong>Orden:</strong></b-col>
                <b-col cols="3" class="border mt-2">{{ row.item.order }}</b-col>
                <b-col cols="3"><strong>Tipo:</strong></b-col>
                <b-col cols="3" class="border mt-2">{{ row.item.tipo_name }}</b-col>
                <b-col cols="3"><strong>Suptipo:</strong></b-col>
                <b-col cols="3" class="border mt-2">{{ row.item.subtipo_name }}</b-col>
                <b-col cols="3"><strong>Nombre:</strong></b-col>
                <b-col cols="9" class="border mt-2">{{ row.item.name }}</b-col>
                <b-col cols="3"><strong>Descripción:</strong></b-col>
                <b-col cols="9" class="border mt-2">{{ row.item.desc }}</b-col>
                <b-col cols="3"><strong>Material:</strong></b-col>
                <b-col cols="9" class="border mt-2">{{ row.item.material }}</b-col>
              </b-row>
            </b-col>
            <b-col cols="12" md="6">
              <b-row class="pr-md-5">
                <b-col cols="6">
                  <div class="background border cursor-pointer" :style="getImage(row.item)" @click="toggler = !toggler"></div>
                  <FsLightbox
                    :toggler="toggler"
                    :sources="[
                      row.item.imagen
                    ]"
                  />
                </b-col>
                <b-col cols="6" v-html="getVideoFrame(row.item.video)" class="border"></b-col>
              </b-row>
            </b-col>
          </b-row>
        </template>

      </b-table>
      <b-row>
        <b-col cols="1">
          <b-form-select v-model="perPage"
                         :options="perPageOptions">
          </b-form-select>
        </b-col>
        <b-col cols="11">
          <b-pagination
            class="float-right"
            v-model="currentPage"
            :total-rows="ejercicios.length"
            :per-page="perPage"
          ></b-pagination>
        </b-col>
      </b-row>
    </b-container>
    <b-modal ref="confirmDeleteModal"
             title="Borrar Ejercicio"
             @ok="deleteItem"
             @cancel="cancelDeleteItem"
             ok-title="Borrar"
             ok-variant="danger"
             cancel-title="Cancelar"
             cancel-variant="secondary">
      <p>Va a borrar el siguiente Ejercicio:</p>
      <ul>
        <li><strong>ID</strong>: {{ item_delete.id }}</li>
        <li><strong>Orden</strong>: {{ item_delete.order }}</li>
        <li><strong>Tipo</strong>: {{ item_delete.tipo_name }}</li>
        <li><strong>Subtipo</strong>: {{ item_delete.subtipo_name }}</li>
        <li><strong>Nombre:</strong>: {{ item_delete.name }}</li>
        <li><strong>Descripción</strong>: {{ item_delete.desc }}</li>
      </ul>
      <p>¿Desea continuar?</p>
    </b-modal>
    <b-modal v-if="show"
             id="editEjercicioModal"
             ref="editEjercicioModal"
             size="lg"
             title="Editar Ejercicio"
             :hide-footer="true">
      <FormEjercicio v-if="edit || add_new" :new="add_new" v-on:cancel="onCancelFormEjercicio()" />
    </b-modal>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import Utils from '../../utils/utils.js';
  import FsLightbox from "fslightbox-vue";
  import FormEjercicio from './components/FormEjercicio';

  export default {
    mixins: [Utils],
    data() {
      return {
        add_new: false,
        currentPage: 1,
        del_index: null,
        edit: false,
        fields: [
          { key: 'id', label: 'ID', sortable: true },
          { key: 'order', label: 'Orden', sortable: true },
          { key: 'name', label: 'Nombre', sortable: true },
          { key: 'plen_tipos_ejercicio_id', label: 'Tipo', sortable: true },
          { key: 'plen_subtipos_ejercicio_id', label: 'Subtipo', sortable: true },
          { key: 'actions', label: 'Acciones', sortable: false },
        ],
        item_backup: {
          id: null,
          order: null,
          name: null,
          desc: null,
          tipo_ejercicio_id: null,
          subtipo_ejercicio_id: null,
          imagen: null,
          video: null,
          material: null,
        },
        item_delete: {
          id: null,
          order: null,
          name: null,
          desc: null,
          tipo_ejercicio_id: null,
          subtipo_ejercicio_id: null,
          imagen: null,
          video: null,
          material: null,
        },
        item_new: {
          id: null,
          order: null,
          name: null,
          desc: null,
          tipo_ejercicio_id: null,
          subtipo_ejercicio_id: null,
          imagen: null,
          video: null,
          material: null,
        },
        perPage: 10,
        perPageOptions: [
          { value: 10, text: "10" },
          { value: 20, text: "20" },
          { value: 50, text: "50" },
          { value: 100, text: "100" },
        ],
        show: false,
        toggler: false
      }
    },
    computed: mapState({
      ejercicio: state => state.plen.ejercicio,
      ejercicios: state => state.plen.ejercicios,
    }),
    created() {
      this.getSubtiposEjercicio().then( () => {
        this.getTiposEjercicio().then( () => {
          this.getEjercicios().then( () => {
            if( !this.ejercicios.length ) {
              this.addEjercicio(this.item_new);
            }
            this.show = true;
          });
        })
      });
    },
    methods: {
      ...mapActions({
        addEjercicio: 'plen/addEjercicio',
        deleteEjercicio: 'plen/deleteEjercicio',
        getEjercicios: 'plen/getEjercicios',
        getSubtiposEjercicio: 'plen/getSubtiposEjercicio',
        getTiposEjercicio: 'plen/getTiposEjercicio',
        resetEjercicio: 'plen/resetEjercicio',
        saveEjercicio: 'plen/saveEjercicio',
        setEjercicio: 'plen/setEjercicio',
        showSnackBar: 'plen/showSnackBar',
        updateEjercicio: 'plen/updateEjercicio',
      }),
      addNewItem() {
        this.resetEjercicio();
        this.add_new = true;
        this.$refs.editEjercicioModal.show();
      },
      cancelDeleteItem() {
        this.resetDeleteItem();
      },
      deleteItem() {
        this.deleteEjercicio(this.item_delete.id)
          .then( () => {
            this.resetDeleteItem();
            this.showSnackbar("Ejercicio BORRADO");
          })
          .catch( (err) => {
            console.log("[ERROR][deleteEjercicio] err: " + JSON.stringify(err));
          })
      },
      getImage(item) {
        return "background-image: url(" + item.imagen + ")";
      },
      onCancelFormEjercicio() {
        this.recoverBackupItem();
      },
      onClickEdit(val) {
        this.saveBackupItem(val);
        this.setEjercicio(val);
        this.edit = true;
        this.add_new = false;
        this.$refs.editEjercicioModal.show();
      },
      onClickRemove(val) {
        this.saveDeleteItem(val);
        this.$refs.confirmDeleteModal.show();
      },
      recoverBackupItem() {
        const index = _.findIndex(this.ejercicios, { id: this.item_backup.id });

        this.ejercicios[index].id = this.item_backup.id;
        this.ejercicios[index].order = this.item_backup.order;
        this.ejercicios[index].name = this.item_backup.name;
        this.ejercicios[index].desc = this.item_backup.desc;
        this.ejercicios[index].tipo_ejercicio_id = this.item_backup.tipo_ejercicio_id;
        this.ejercicios[index].subtipo_ejercicio_id = this.item_backup.subtipo_ejercicio_id;
        this.ejercicios[index].imagen = this.item_backup.imagen;
        this.ejercicios[index].video = this.item_backup.video;
        this.ejercicios[index].material = this.item_backup.material;

        this.resetBackupItem();
      },
      resetBackupItem() {
        this.item_backup = {
          id: null,
          order: null,
          name: null,
          desc: null,
          tipo_ejercicio_id: null,
          subtipo_ejercicio_id: null,
          imagen: null,
          video: null,
          material: null,
        }
      },
      resetDeleteItem() {
        this.item_delete = {
          id: null,
          order: null,
          name: null,
          desc: null,
          tipo_ejercicio_id: null,
          subtipo_ejercicio_id: null,
          imagen: null,
          video: null,
          material: null,
        }
      },
      saveBackupItem(item) {
        this.item_backup.id = item.id;
        this.item_backup.order = item.order;
        this.item_backup.name = item.name;
        this.item_backup.desc = item.desc;
        this.item_backup.tipo_ejercicio_id = item.tipo_ejercicio_id;
        this.item_backup.subtipo_ejercicio_id = item.subtipo_ejercicio_id;
        this.item_backup.imagen = item.imagen;
        this.item_backup.video = item.video;
        this.item_backup.material = item.material;
      },
      saveDeleteItem(item) {
        this.item_delete.id = item.id;
        this.item_delete.order = item.order;
        this.item_delete.name = item.name;
        this.item_delete.desc = item.desc;
        this.item_delete.tipo_ejercicio_id = item.tipo_ejercicio_id;
        this.item_delete.subtipo_ejercicio_id = item.subtipo_ejercicio_id;
        this.item_delete.imagen = item.imagen;
        this.item_delete.video = item.video;
        this.item_delete.material = item.material;
      },
    },
    components: {
      FormEjercicio,
      FsLightbox,
    }
  }
</script>

<style scoped>
  .background {
    background-color:transparent;
    background-position:center;
    background-repeat:no-repeat;
    background-size:contain;
    height:250px;
  }
  tr:hover .border {
    border-color:#b1b8bf!important;
  }
</style>
