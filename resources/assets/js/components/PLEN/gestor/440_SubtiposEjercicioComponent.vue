<template>
  <div>
    <p class="module-title py-2 text-center">Subtipos de Ejercicio</p>
    <b-container class="p-xl-3">
      <b-button :disabled="edit_index > 0" @click="addNewItem" variant="danger" class="float-right mb-3">Nuevo Subtipo de Ejercicio</b-button>
      <b-table v-if="show" outlined small striped hover :items="items" :fields="fields" class="plen-table" :current-page="currentPage" :per-page="perPage">
        <template slot="tipo_name" slot-scope="row">
          <b-form-select v-if="edit_index && ( edit_index == row.index + 1 )"
                         id="tipoInput"
                         v-model="item_new.plen_tipos_ejercicio_id"
                         :options="tipos"
                         v-on:keyup.enter="focusOnOrder"
                         v-on:input="onInputTipoEjercicio"
                         size="sm">
          </b-form-select>
          <div v-else>{{ row.item.tipo_name }}</div>
        </template>
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
      <b-pagination
        class="float-right"
        v-model="currentPage"
        :total-rows="items.length"
        :per-page="perPage"
      ></b-pagination>
    </b-container>
    <b-modal ref="confirm-delete-modal"
             title="Borrar Subtipo de Ejercicio"
             @ok="deleteItem"
             @cancel="cancelDeleteItem"
             ok-title="Borrar"
             ok-variant="danger"
             cancel-title="Cancelar"
             cancel-variant="secondary">
      <p>Va a borrar el siguiente Subtipo de Ejercicio:</p>
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
          { key: 'tipo_name', lable: 'Tipo de Ejercicio', sortable: true },
          { key: 'order', label: 'Orden', sortable: true },
          { key: 'desc', label: 'Descripción', sortable: true },
          { key: 'actions', label: 'Acciones', sortable: false },
        ],
        item_backup: {
          id: null,
          plen_tipos_ejercicio_id: null,
          tipo_name: '',
          order:null,
          desc:'',
        },
        item_delete: {
          id: null,
          plen_tipos_ejercicio_id: null,
          tipo_name: '',
          order: null,
          desc: '',
        },
        item_new: {
          id: null,
          plen_tipos_ejercicio_id: null,
          tipo_name: '',
          order:null,
          desc: '',
        },
        items: [],
        items_count: 0,
        perPage: 10,
        tipos: [
          { value: null, text: 'Seleccionar Tipo de Ejercicio' }
        ],
        show: false,
      }
    },
    created() {
      this.getTipos().then( (tipos) => {
        tipos.map( (tipo) => {
          const new_tipo = {
            value: tipo.id,
            text: tipo.desc
          }
          this.tipos.push(new_tipo);
        } );

        this.getSubtipos().then( (subtipos) => {
          if( subtipos.length ) {
            this.items = subtipos;
            this.items_count = subtipos.length;
          } else {
            this.items = [];
            this.items.push(this.item_new);
            this.items_count = 0;
          }
          this.show = true;
        });
      });
    },
    updated() {
      this.focusOnTipo();
    },
    methods: {
      ...mapActions({
        deleteSubtipo: 'plen/deleteSubtipoEjercicio',
        getSubtipos: 'plen/getSubtiposEjercicio',
        getTipos: 'plen/getTiposEjercicio',
        saveSubtipo: 'plen/saveSubtipoEjercicio',
        showSnackBar: 'plen/showSnackBar',
        updateSubtipo: 'plen/updateSubtipoEjercicio',
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
        this.deleteSubtipo(this.item_delete.id)
          .then( () => {
            this.items.splice(this.del_index, 1);
            this.resetItemDelete();
            this.checkNumberOfItems();
            this.showSnackBar("SUBTIPO eliminado");
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
      focusOnTipo() {
        $('#tipoInput').focus();
      },
      onInputTipoEjercicio(val) {
        this.item_new.tipo_name = _.find(this.tipos, { 'value': val }).text;
      },
      onClickCancel(index) {
        if( true == this.add_new ) {
          this.items.shift();
          this.resetItemNew();
          this.items_count = this.items.length;
          this.checkNumberOfItems();
          this.add_new = false;
        } else {
          this.items[index].plen_tipos_ejercicio_id = this.item_backup.plen_tipos_ejercicio_id;
          this.items[index].tipo_name = this.item_backup.tipo_name;
          this.items[index].order = this.item_backup.order;
          this.items[index].desc  = this.item_backup.desc;
          this.resetItemBackup();
        }
        this.edit_index = 0;
      },
      onClickEdit(index) {
        this.item_backup.id = this.items[index].id;
        this.item_backup.plen_tipos_ejercicio_id = this.items[index].plen_tipos_ejercicio_id;
        this.item_backup.tipo_name = this.items[index].tipo_name;
        this.item_backup.order = this.items[index].order;
        this.item_backup.desc  = this.items[index].desc;
        this.item_new = this.item_backup;
        this.edit_index = index + 1;
        this.focusOnTipo();
      },
      onClickRemove(index) {
        this.del_index = index;
        this.item_delete.id = this.items[index].id;
        this.item_delete.plen_tipos_ejercicio_id = this.items[index].plen_tipos_ejercicio_id;
        this.item_delete.tipo_name = this.items[index].tipo_name;
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
          plen_tipos_ejercicio_id: null,
          tipo_name: '',
          order: null,
          desc: '',
        };
      },
      resetItemDelete() {
        this.item_delete = {
          id: null,
          plen_tipos_ejercicio_id: null,
          tipo_name: '',
          order: null,
          desc: '',
        };
        this.del_index = null;
      },
      resetItemNew() {
        this.item_new = {
          id: null,
          plen_tipos_ejercicio_id: null,
          tipo_name: '',
          order: null,
          desc: '',
        };
        this.items_count = this.items.length;
      },
      saveItem(index) {
        this.saveSubtipo(this.item_new)
          .then( (item) => {
            this.resetItemNew();
            this.add_new = false;
            this.edit_index = 0;
            this.items[index].id = item.id;
            this.showSnackBar("Nuevo SUBTIPO guardado");
          })
          .catch( () => {
            alert("saveItem AKATSA");
          });
      },
      updateItem(index) {
        this.updateSubtipo(this.item_new)
        .then( (item) => {
          this.resetItemNew();
          this.items[index].id = item.id;
          this.items[index].plen_tipos_ejercicio_id = item.plen_tipos_ejercicio_id;
          this.items[index].tipo_name = item.tipo_name;
          this.items[index].order = item.order;
          this.items[index].desc = item.desc;
          this.edit_index = 0;
          this.showSnackBar("SUBTIPO actualizado");
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
