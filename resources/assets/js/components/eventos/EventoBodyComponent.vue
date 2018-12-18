<template>
  <div class="container py-4">
    <!-- Convocar pelotaris -->
    <b-row>
      <b-form-group label="Convocar pelotari:"
                    label-for="pelotariInput"
                    horizontal
                    :label-cols="5"
                    class="col-sm-5 font-weight-bold">
        <b-form-select id="pelotariInput"
                       :options="_pelotaris"
                       v-model="pelotari_id"
                       :change="onChangePelotari()">
        </b-form-select>
      </b-form-group>
      <b-button
        variant="primary"
        style="max-height: 2.25rem;"
        title="Añadir a la convocatoria"
        @click.stop="onConvocarPelotari()"
        :disabled="!pelotariSelected">
        Convocar
      </b-button>
    </b-row>

    <!-- Pelotaris convocados -->
    <b-row>
      <div v-for="pelotari in _evento.pelotaris" class="col-sm-4">
        <evento-pelotaris :pelotari="pelotari"></evento-pelotaris>
      </div>
    </b-row>

  </div>
</template>

<script>
  import { store } from '../store/store';
  import { mapState } from 'vuex';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [Utils],
    data() {
      return {
        pelotari_id: null,
        pelotariSelected: false,
      }
    },
    computed: mapState({
      _evento: 'evento',
      _pelotaris: 'pelotaris',
    }),
    methods: {
      onConvocarPelotari() {
        if( _.findIndex(this._evento.pelotaris, { "id": this.pelotari_id }) > -1 ) {
          this.showSnackbar("El Pelotari seleccionado ya se encuentra convocado al Evento");
          return;
        } else {
          var pelotari = _.find(this._pelotaris, { value: this.pelotari_id });
          pelotari.asiste = 1;
          pelotari.motivo = '';
          store.dispatch('addPelotariToEvento', pelotari).then( (response) => {
            this.pelotari_id = null;
            this.pelotariSelected = false;
            this.showSnackbar("Pelotari añadido al Evento de Prensa");
          });
        }
      },
      onChangePelotari() {
        this.pelotariSelected = (null != this.pelotari_id);
      }
    }
  }
</script>

<style>
</style>
