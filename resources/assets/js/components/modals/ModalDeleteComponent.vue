<template>
  <b-modal id="modalDelete" ref="modalDelete" :title="title" data-id="" hide-footer>
    <div class="modal-body">
      <p id="deleteMsg"></p>
      <p>¿Desea continuar?</p>
    </div>
    <div class="modal-footer">
      <b-btn variant="danger" @click="removeItem">Borrar</b-btn>
      <b-btn @click="hideModalDelete">Cancelar</b-btn>
    </div>
  </b-modal>
</template>

<script>
  export default {
    props: ['objectName', 'removeItem'],
    data () {
      return {
        title: '',
      }
    },
    created () {
      console.log("ModalDeleteComponent created");
      this.title = "BORRAR " + this.objectName;
      // this.removeItem();
    },
    methods: {
      hideModalDelete() {
        this.$refs.modalDelete.hide();
      }
    }
  }
</script>
