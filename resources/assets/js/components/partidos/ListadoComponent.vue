<template>
  <div v-if="partidos" class="list-partidos-festival">
    <ul class="mt-3 px-3">
      <li v-for="partido in partidos">
        <partido :partido="partido" v-on:delete-partido="removePartido($event)"></partido>
      </li>
    </ul>
    <delete-modal object-name="Partido" :remove-item="deletePartidoFromDB"></delete-modal>
  </div>
</template>

<script>
  Vue.component('partido', require('./ListadoItemComponent.vue'));
  Vue.component('delete-modal', require('../modals/ModalDeleteComponent.vue'));

  import Utils from '../utils/utils.js';

  export default {
    mixins: [Utils],
    props: ['festivalId', 'partidos'],
    data() {
      console.log("data");
      return {
        deleteID: null,
      }
    },
    created: function () {
      console.log("ListadoComponent created");
    },
    methods: {
      removePartido(id) {
        this.deleteID = id;
        var msg = "Se va a borrar un Partido.";
        jQuery('#deleteMsg').html(msg);
        this.$root.$emit('bv::show::modal','modalDelete');
      },
      deletePartidoFromDB() {
        let id = this.deleteID;
        let uri = '/www/partidos/' + id;

        this.axios.delete(uri)
          .then((response) => {
            this.$emit('delete-partido', id);
            this.$root.$emit('bv::hide::modal','modalDelete')
            this.showSnackbar("Partido BORRADO");
          })
          .catch((error) => {
            console.log("[remove] error: " + error);
            this.$root.$emit('bv::hide::modal','modalDelete')
            this.showSnackbar("ERROR al borrar");
          });
      }
    }
  }
</script>

<style>
  ul {
    list-style-type:none;
  }
</style>
