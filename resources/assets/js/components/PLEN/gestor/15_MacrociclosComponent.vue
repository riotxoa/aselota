<template>
  <div>
    <p class="module-title py-2 text-center">Macrociclos</p>
    <b-container fluid class="p-xl-3">
      <b-button :disabled="edit_index > 0" @click="addNewItem" variant="danger" class="float-right mb-3">Nuevo Macrociclo</b-button>
      <b-table v-if="show" outlined small striped hover :items="items" :fields="fields" class="plen-table" :current-page="currentPage" :per-page="perPage">
        <template slot="order" slot-scope="row">
            <input v-if="edit_index && ( edit_index == row.index + 1 )"
                   id="orderInput"
                   class="px-2 w-100"
                   v-on:keyup.enter="focusOnFechaIni"
                   type="number"
                   size="sm"
                   v-model="item_new.order">
            </input>
            <div v-else>{{ row.item.order }}</div>
        </template>
        <template slot="fecha_ini" slot-scope="row">
          <input v-if="edit_index && ( edit_index == row.index + 1 )"
                 id="fechaIniInput"
                 class="px-2 w-100"
                 v-on:keyup.enter="focusOnFechaFin"
                 type="date"
                 size="sm"
                 v-model="item_new.fecha_ini">
          </input>
          <div v-else>
            <div v-if="row.item.fecha_ini">{{ formatDateES(row.item.fecha_ini) }}</div>
            <div v-else>&nbsp;</div>
          </div>
        </template>
        <template slot="fecha_fin" slot-scope="row">
          <input v-if="edit_index && ( edit_index == row.index + 1 )"
                 id="fechaFinInput"
                 class="px-2 w-100"
                 v-on:keyup.enter="focusOnDesc"
                 type="date"
                 size="sm"
                 v-model="item_new.fecha_fin">
          </input>
          <div v-else>
            <div v-if="row.item.fecha_fin">{{ formatDateES(row.item.fecha_fin) }}</div>
            <div v-else>&nbsp;</div>
          </div>
        </template>
        <template slot="description" slot-scope="row">
              <input v-if="edit_index && ( edit_index == row.index + 1 )"
                            id="descInput"
                            class="px-2 w-100"
                            v-on:keyup.enter="focusOnObjetivos"
                            type="text"
                            size="sm"
                            v-model="item_new.description">
              </input>
              <div v-else>{{ row.item.description }}</div>
        </template>
        <template slot="objetivos" slot-scope="row">
          <input v-if="edit_index && ( edit_index == row.index + 1 )"
                 id="objetivosInput"
                 class="px-2 w-100"
                 v-on:keyup.enter="onClickSave(row.index)"
                 type="text"
                 size="sm"
                 v-model="item_new.objetivos">
          </input>
          <div v-else>
            {{ row.item.objetivos }}
          </div>
        </template>
        <template slot="actions" slot-scope="row" v-if="items_count || edit_index">
              <div v-if="edit_index && ( edit_index == row.index + 1 )">
                <b-button size="sm" title="Guardar" variant="success" @click="onClickSave(row.index)"><i class="far fa-save"></i></b-button>
                <b-button size="sm" title="Cancelar" variant="secondary" @click="onClickCancel(row.index)"><i class="fas fa-times"></i></b-button>
              </div>
              <div v-if="!edit_index">
                <b-button size="sm" title="Editar" variant="primary" @click="onClickEdit(row.index)"><i class="fas fa-edit"></i></b-button>
                <b-button size="sm" title="Mostrar/Ocultar Detalle" variant="secondary" @click="onClick(row)"> <!-- @click.stop="row.toggleDetails"> -->
                  <span class="icon" v-bind:class="{ 'voyager-x': row.detailsShowing, 'voyager-eye': !row.detailsShowing }"></span>
                </b-button>
                <b-button size="sm" title="Borrar" variant="danger" @click="onClickRemove(row.index)"><i class="fas fa-trash-alt"></i></b-button>
              </div>
        </template>

        <!-- Detalles del item -->
        <template slot="row-details" slot-scope="row">
           <MacroDetails v-if="items[row.index]._showDetails" :item="items[row.index]" />
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
            :total-rows="items.length"
            :per-page="perPage"
          ></b-pagination>
        </b-col>
      </b-row>
    </b-container>
    <b-modal ref="confirm-delete-modal"
             title="Borrar Macrociclo"
             @ok="deleteItem"
             @cancel="cancelDeleteItem"
             ok-title="Borrar"
             ok-variant="danger"
             cancel-title="Cancelar"
             cancel-variant="secondary">
      <p>Va a borrar el siguiente Macrociclo:</p>
      <ul>
        <li><strong>ID</strong>: {{ item_delete.id }}</li>
        <li><strong>Fecha inicio:</strong>: {{ formatDateES(item_delete.fecha_ini) }}</li>
        <li><strong>Fecha fin:</strong>: {{ formatDateES(item_delete.fecha_fin) }}</li>
        <li><strong>Descripción</strong>: {{ item_delete.description }}</li>
      </ul>
      <p>¿Desea continuar?</p>
    </b-modal>
    <ModalMesociclo />
    <ModalMicrociclo />
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import Utils from '../../utils/utils.js';
  import $ from 'jquery';
  import _ from 'lodash';

  import MacroDetails from './components/MacroDetails';
  import ModalMesociclo from './components/ModalMesociclo';
  import ModalMicrociclo from './components/ModalMicrociclo';

  // Drag the slider Totally useless vue range slider: https://vuejsexamples.com/drag-the-slider-totally-useless-vue-range-slider/
  // Horizontal calendar <<- OSO ITXURA ONA ->> https://medium.com/@casperbottelet/building-a-horizontal-calendar-with-vuejs-and-vis-js-part-1-3-2322b7e7ff

  export default {
    mixins: [ Utils ],
    data() {
      return {
        add_new: false,
        currentPage: 1,
        del_index: null,
        edit_index: 0,
        fields: [
          // { key: 'order', label: 'Orden', tdClass: 'w-auto', sortable: true },
          { key: 'fecha_ini', label: 'F.Inicio', tdClass: 'w-auto', sortable: true },
          { key: 'fecha_fin', label: 'F.Fin', tdClass: 'w-auto', sortable: true },
          { key: 'description', label: 'Descripción', tdClass: 'w-auto', sortable: true },
          { key: 'objetivos', label: 'Objetivos', tdClass: 'col-objetivos w-auto', sortable: true },
          { key: 'actions', label: 'Acciones', tdClass: 'w-auto', sortable: false },
        ],
        item_backup: {
          id: null,
          order: null,
          fecha_ini: null,
          fecha_fin: null,
          description: '',
          objetivos: '',
        },
        item_delete: {
          id: null,
          order: null,
          fecha_ini: null,
          fecha_fin: null,
          description: '',
          objetivos: '',
        },
        item_new: {
          id: null,
          order: null,
          fecha_ini: null,
          fecha_fin: null,
          description: '',
          objetivos: '',
          _showDetails: false,
        },
        items: [],
        items_count: 0,
        perPage: 10,
        perPageOptions: [
          { value: 10, text: "10" },
          { value: 20, text: "20" },
          { value: 50, text: "50" },
          { value: 100, text: "100" },
        ],
        show: false,
      }
    },
    computed: mapState({
      macrociclos: state => state.plen.macrociclos
    }),
    created() {
      this.getMacrociclos().then( (res) => {
        if( res.length ) {
          this.items = res;
          this.items_count = res.length;
        } else {
          this.items = [];
          this.items.push(this.item_new);
          this.items_count = 0;
        }
        this.show = true;
      });
    },
    updated() {
      this.focusOnOrder();
    },
    methods: {
      ...mapActions({
        deleteMacrociclo: 'plen/deleteMacrociclo',
        getMacrociclos: 'plen/getMacrociclos',
        getMesociclos: 'plen/getMesociclos',
        saveMacrociclo: 'plen/saveMacrociclo',
        showSnackBar: 'plen/showSnackBar',
        updateMacrociclo: 'plen/updateMacrociclo',
      }),
      addNewItem() {
        this.add_new = true;
        if( this.items_count > 0 ) {
          this.items_count = this.items.splice(0, 0, this.item_new).length;
          this.edit_index = this.items_count + 1;
        } else {
          this.edit_index = 1;
        }
      },
      cancelDeleteItem() {
        this.resetItemDelete();
      },
      checkNumberOfItems() {
        this.resetItemNew();
        this.items_count = this.items.length;
        if( !this.items_count ) {
          this.items.push(this.item_new);
        }
      },
      deleteItem() {
        this.deleteMacrociclo(this.item_delete.id)
          .then( () => {
            this.items.splice(this.del_index, 1);
            this.resetItemDelete();
            this.checkNumberOfItems();
            this.showSnackBar("MACROCICLO eliminado");
          })
          .catch( (err) => {
            alert("[deleteItem] Error al borrar: " + JSON.stringify(err.response.data));
          });
      },
      focusOnDesc() {
        $('#descInput').focus();
      },
      focusOnFechaIni() {
        $('#fechaIniInput').focus();
      },
      focusOnFechaFin() {
        $('#fechaFinInput').focus();
      },
      focusOnObjetivos() {
        $('#objetivosInput').focus();
      },
      focusOnOrder() {
        $('#orderInput').focus();
      },
      onClick(row) {
        this.getMesociclos(this.items[row.index].id).then( (meso) => {
          this.items[row.index].mesociclos = meso;
          this.items[row.index]._showDetails = !this.items[row.index]._showDetails;
        });
      },
      onClickCancel(index) {
        if( true == this.add_new ) {
          this.items.shift();
          this.resetItemNew();
          this.items_count = this.items.length;
          this.checkNumberOfItems();
          this.add_new = false;
        } else {
          this.items[index].order = this.item_backup.order;
          this.items[index].fecha_ini  = this.item_backup.fecha_ini;
          this.items[index].fecha_fin  = this.item_backup.fecha_fin;
          this.items[index].description  = this.item_backup.description;
          this.items[index].objetivos  = this.item_backup.objetivos;
          this.resetItemBackup();
        }
        this.edit_index = 0;
      },
      onClickEdit(index) {
        this.item_backup.id = this.items[index].id;
        this.item_backup.order = this.items[index].order;
        this.item_backup.fecha_ini  = this.items[index].fecha_ini;
        this.item_backup.fecha_fin  = this.items[index].fecha_fin;
        this.item_backup.description  = this.items[index].description;
        this.item_backup.objetivos  = this.items[index].objetivos;
        this.item_new = this.item_backup;
        this.edit_index = index + 1;
        this.focusOnOrder();
      },
      onClickRemove(index) {
        this.del_index = index;
        this.item_delete.id = this.items[index].id;
        this.item_delete.order = this.items[index].order;
        this.item_delete.fecha_ini = this.items[index].fecha_ini;
        this.item_delete.fecha_fin = this.items[index].fecha_fin;
        this.item_delete.description = this.items[index].description;
        this.item_delete.objetivos = this.items[index].objetivos;
        this.$refs['confirm-delete-modal'].show();
      },
      onClickSave(index) {
        if( true == this.add_new ) {
          this.saveItem(index);
        } else {
          this.updateItem(index);
        }
      },
      resetItemBackup() {
        this.item_backup = {
          id: null,
          order: null,
          fecha_ini: null,
          fecha_fin: null,
          description: '',
          objetivos: '',
        };
      },
      resetItemDelete() {
        this.item_delete = {
          id: null,
          order: null,
          fecha_ini: null,
          fecha_fin: null,
          description: '',
          objetivos: '',
        };
        this.del_index = null;
      },
      resetItemNew() {
        this.item_new = {
          id: null,
          order: null,
          fecha_ini: null,
          fecha_fin: null,
          description: '',
          objetivos: '',
          _showDetails: false,
        };
        this.items_count = this.items.length;
      },
      saveItem(index) {
        this.saveMacrociclo(this.item_new)
          .then( (item) => {
            this.resetItemNew();
            this.add_new = false;
            this.edit_index = 0;
            this.items[index].id = item.id;
            this.showSnackBar("Nuevo MACROCICLO guardado");
          })
          .catch( () => {
            alert("saveItem AKATSA");
          });
      },
      updateItem(index) {
        this.updateMacrociclo(this.item_new)
        .then( (item) => {
          this.resetItemNew();
          this.items[index].id = item.id;
          this.items[index].order = item.order;
          this.items[index].fecha_ini = item.fecha_ini;
          this.items[index].fecha_fin = item.fecha_fin;
          this.items[index].description = item.description;
          this.items[index].objetivos = item.objetivos;
          this.edit_index = 0;
          this.showSnackBar("MACROCICLO actualizado");
        })
        .catch( () => {
          alert("updateItem AKATSA");
        });
      }
    },
    components: {
      MacroDetails,
      ModalMesociclo,
      ModalMicrociclo
    }
  }
</script>

<style focused>
  .col-objetivos {
    max-width:225px;
  }
  .plen-table {
    background-color:#ffffff;
  }
</style>
