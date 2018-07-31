<template>
  <div v-if="_partidos" class="list-partidos-festival">
    <ul class="mt-3 px-3">
      <li v-for="partido in _partidos">
        <partido :partido="partido" v-on:delete-partido="showDeleteModal($event)"></partido>
      </li>
    </ul>
    <delete-modal object-name="Partido" :remove-item="deletePartido"></delete-modal>
  </div>
</template>

<script>
  Vue.component('partido', require('./ListadoItemComponent.vue'));
  Vue.component('delete-modal', require('../modals/ModalDeleteComponent.vue'));

  import Utils from '../utils/utils.js';
  import { mapState } from 'vuex';

  export default {
    mixins: [Utils],
    data() {
      return {
        deleteID: null,
      }
    },
    created: function () {
      console.log("ListadoComponent created");
    },
    computed: {...mapState({
      _partidos: 'partidos',
    })},
    methods: {
      showDeleteModal(id) {
        this.deleteID = id;
        var msg = "Se va a borrar un Partido.";
        jQuery('#deleteMsg').html(msg);
        this.$root.$emit('bv::show::modal','modalDelete');
      },
      deletePartido() {

        this.$store.dispatch('deletePartido', this.deleteID)
          .then( (response) => {
            this.$root.$emit('bv::hide::modal','modalDelete')
            this.showSnackbar("Partido BORRADO");
          })
          .catch( (error) => {
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
