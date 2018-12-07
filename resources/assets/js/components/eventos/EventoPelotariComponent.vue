<template>
  <b-card-group v-if="this._evento.pelotaris.length" deck class="my-3">
    <div v-for="pelotari in this._evento.pelotaris" class="col-sm-4 p-0">
      <b-card class="w-80 p-0 mb-4"
        border-variant="primary"
        :header="pelotari.alias"
        header-bg-variant="primary"
        header-text-variant="white font-weight-bold">
        <b-row>
          <b-col class="col-sm-3">
            <img :src="pelotari.foto"/>
          </b-col>
          <b-col class="col-sm-9">
            <b-form-group label="Asiste:"
                          label-for="asisteInput"
                          class="ml-3 font-weight-bold">
              <b-form-select id="asisteInput"
                             :options="{ 1: 'Sí', 0: 'No'}"
                             v-model="pelotari.asiste"
                             class="col-sm-6">
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col class="col-12">
            <b-form-group label="Observaciones:"
                          label-for="motivoInput"
                          class="mt-3 font-weight-bold">
              <b-form-textarea id="motivoInput"
                                v-model="pelotari.motivo"
                                :rows="4"
                                :max-rows="4">
              </b-form-textarea>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col class="col-sm-6 text-left">
            <b-button size="sm" variant="outline-danger" @click.stop="onClickDelete(pelotari.id)" title="Eliminar" class="float-left">
              Borrar
            </b-button>
          </b-col>
          <b-col class="col-sm-6 text-right">
            <b-button size="sm" variant="primary" @click.stop="onClickSave(pelotari)" title="Guardar" class="float-right">
              Guardar
            </b-button>
          </b-col>
        </b-row>
      </b-card>
    </div>
  </b-card-group>
</template>

<script>
  import Utils from '../utils/utils.js';
  import { store } from '../store/store';
  import { mapState } from 'vuex';

  export default {
    mixins: [Utils],
    data() {
      return {
      }
    },
    created: function () {
      console.log("EventoPelotariComponent created");
    },
    computed: mapState({
      _evento: 'evento',
    }),
    methods: {
      onClickSave(pelotari) {
        store.dispatch('updatePelotariFromEvento', pelotari).then( () => {
          this.showSnackbar("Pelotari actualizado");
        });
      },
      onClickDelete(id) {
        store.dispatch('deletePelotariFromEvento', id).then( () => {
          this.showSnackbar("Pelotari eliminado");
        });
      }
    }
  }
</script>
