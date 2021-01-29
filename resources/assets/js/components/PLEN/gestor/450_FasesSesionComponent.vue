<template>
  <div>
    <p class="module-title py-2 text-center">Fases Sesión</p>
    <b-container class="p-xl-3">
      <b-button :disabled="edit_index > 0" @click="addNewItem" variant="danger" class="float-right mb-3">Nueva Fase de Sesión</b-button>
      <b-table v-if="show" outlined small striped hover :items="items" :fields="fields" class="plen-table" :current-page="currentPage" :per-page="perPage">
        <template slot="order" slot-scope="row">
          <input v-if="edit_index && ( edit_index == row.index + 1 )"
                 id="orderInput"
                 class="px-2 w-100"
                 v-on:keyup.enter="focusOnDesc"
                 type="number"
                 size="sm"
                 v-model="item_new.order">
          </input>
          <div v-else>{{ row.item.order }}</div>
        </template>
        <template slot="desc" slot-scope="row">
          <input v-if="edit_index && ( edit_index == row.index + 1 )"
                        id="descInput"
                        class="px-2 w-100"
                        v-on:keyup.enter="onClickSave(row.index)"
                        type="text"
                        size="sm"
                        v-model="item_new.desc">
          </input>
          <div v-else>{{ row.item.desc }}</div>
        </template>
        <template slot="actions" slot-scope="row" v-if="items_count || edit_index">
          <div v-if="edit_index && ( edit_index == row.index + 1 )">
            <b-button size="sm" title="Guardar" variant="success" @click="onClickSave(row.index)"><i class="far fa-save"></i></b-button>
            <b-button size="sm" title="Cancelar" variant="secondary" @click="onClickCancel(row.index)"><i class="fas fa-times"></i></b-button>
          </div>
          <div v-if="!edit_index">
            <b-button size="sm" title="Editar" variant="primary" @click="onClickEdit(row.index)"><i class="fas fa-edit"></i></b-button>
            <b-button size="sm" title="Borrar" variant="danger" @click="onClickRemove(row.index)"><i class="fas fa-trash-alt"></i></b-button>
          </div>
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
             title="Borrar Fase de Sesión"
             @ok="deleteItem"
             @cancel="cancelDeleteItem"
             ok-title="Borrar"
             ok-variant="danger"
             cancel-title="Cancelar"
             cancel-variant="secondary">
      <p>Va a borrar la siguiente Fase de Sesión:</p>
      <ul>
        <li><strong>ID</strong>: {{ item_delete.id }}</li>
        <li><strong>Orden</strong>: {{ item_delete.order }}</li>
        <li><strong>Descripción</strong>: {{ item_delete.desc }}</li>
      </ul>
      <p>¿Desea continuar?</p>
    </b-modal>
  </div>
</template>

<script>
  import { mapActions } from 'vuex';
  import $ from 'jquery';

  export default {
    data() {
      return {
        add_new: false,
        currentPage: 1,
        del_index: null,
        edit_index: 0,
        fields: [
          { key: 'order', label: 'Orden', sortable: true },
          { key: 'desc', label: 'Descripción', sortable: true },
          { key: 'actions', label: 'Acciones', sortable: false },
        ],
        item_backup: {
          id: null,
          order:null,
          desc:'',
        },
        item_delete: {
          id: null,
          order: null,
          desc: '',
        },
        item_new: {
          id: null,
          order:null,
          desc: '',
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
    created() {
      this.getFases().then( (fases) => {
        if( fases.length ) {
          this.items = fases;
          this.items_count = fases.length;
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
        deleteFase: 'plen/deleteFase',
        getFases: 'plen/getFases',
        saveFase: 'plen/saveFase',
        showSnackBar: 'plen/showSnackBar',
        updateFase: 'plen/updateFase',
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
        this.deleteFase(this.item_delete.id)
          .then( () => {
            this.items.splice(this.del_index, 1);
            this.resetItemDelete();
            this.checkNumberOfItems();
            this.showSnackBar("FASE eliminada");
          })
          .catch( (err) => {
            alert("[deleteItem] Error al borrar: " + JSON.stringify(err));
          });
      },
      focusOnDesc() {
        $('#descInput').focus();
      },
      focusOnOrder() {
        $('#orderInput').focus();
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
          this.items[index].desc  = this.item_backup.desc;
          this.resetItemBackup();
        }
        this.edit_index = 0;
      },
      onClickEdit(index) {
        this.item_backup.id = this.items[index].id;
        this.item_backup.order = this.items[index].order;
        this.item_backup.desc  = this.items[index].desc;
        this.item_new = this.item_backup;
        this.edit_index = index + 1;
        this.focusOnOrder();
      },
      onClickRemove(index) {
        this.del_index = index;
        this.item_delete.id = this.items[index].id;
        this.item_delete.order = this.items[index].order;
        this.item_delete.desc = this.items[index].desc;
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
          desc: '',
        };
      },
      resetItemDelete() {
        this.item_delete = {
          id: null,
          order: null,
          desc: '',
        };
        this.del_index = null;
      },
      resetItemNew() {
        this.item_new = {
          id: null,
          order: null,
          desc: '',
        };
        this.items_count = this.items.length;
      },
      saveItem(index) {
        this.saveFase(this.item_new)
          .then( (item) => {
            this.resetItemNew();
            this.add_new = false;
            this.edit_index = 0;
            this.items[index].id = item.id;
            this.showSnackBar("Nueva FASE guardada");
          })
          .catch( () => {
            alert("saveItem AKATSA");
          });
      },
      updateItem(index) {
        this.updateFase(this.item_new)
        .then( (item) => {
          this.resetItemNew();
          this.items[index].id = item.id;
          this.items[index].order = item.order;
          this.items[index].desc = item.desc;
          this.edit_index = 0;
          this.showSnackBar("FASE actualizada");
        })
        .catch( () => {
          alert("updateItem AKATSA");
        });
      }
    }
  }
</script>

<style focused>
  .plen-table {
    background-color:#ffffff;
  }
</style>
