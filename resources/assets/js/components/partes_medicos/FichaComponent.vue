<template>
  <div v-if="show" id="preloader">
    <parte-header :form-title="formTitle" :on-save="onSaveParte"></parte-header>
    <parte-delta :on-save="onSaveParte"></parte-delta>
    <parte-lesion :on-save="onSaveParte"></parte-lesion>
  </div>
</template>

<script>
  import { store } from '../store/store';
  import { mapActions, mapState } from 'vuex';
  import Utils from '../utils/utils.js';

  Vue.component('parte-header', require('./ParteHeader.vue'));
  Vue.component('parte-delta', require('./ParteDelta.vue'));
  Vue.component('parte-lesion', require('./ParteLesion.vue'));

  export default {
    props: ['formTitle', 'isNewParte'],
    mixins: [ Utils ],
    data () {
      return {
        show: false,
      }
    },
    computed: {
      _header: {
        get() {
          return store.getters.parte;
        },
        set (value) {
          store.commit('SET_PARTE_HEADER', value);
        }
      },
      _parte_delta: {
        get() {
          return store.getters.parte_delta;
        },
        set (value) {
          store.commit('SET_PARTE_DELTA', value);
        }
      },
      _parte_lesion: {
        get() {
          return store.getters.parte_lesion;
        },
        set (value) {
          store.commit('SET_PARTE_LESION', value);
        }
      },
      ...mapState({
        _edit: 'parte_edit',
        _partes_aux: 'partes_aux',
      }),
    },
    created: function() {
      console.log("FichaComponent created");

      store.dispatch('getInfoAuxPartesMedicos').then( () => {
        this.$store.commit('SET_PARTE_EDIT', !this.isNewParte);
        if(!this.isNewParte) {
          let uri = '/www/partes/' + this.$route.params.id + '/get';
          this.axios.get(uri)
            .then((response) => {
              this._header = response.data.parte;
              this._parte_lesion = response.data.parte_lesion;
              this._parte_delta = response.data.parte_delta;
              this.$store.commit('SET_PARTE_HEADER_ID', this.$route.params.id);
              this.show = true;
            })
            .catch((error) => {
              console.log("[remove] error: " + error);
              this.showSnackbar("ERROR al editar");
              this.show = true;
            });

        } else {
          store.dispatch('resetParte');
          this.show = true;
        }
      });
    },
    methods: {
      ...mapActions([
        'saveParte',
        'updateParte',
      ]),
      onSaveParte () {

        var data = {
          header: this._header,
          parte_lesion: this._parte_lesion,
          parte_delta: this._parte_delta,
        }

        if( this._edit || this._header.id ) {
            this.updateParte( data )
              .then( () =>{
                this.showSnackbar("Parte actualizado");
              })
              .catch( (error) => {
                console.log(error);
                this.showSnackbar("Se ha producido un ERROR");
              });
        } else {
          this.saveParte( data )
            .then( (response) => {
              this._header.id = response.data;
              this._parte_lesion.med_parte_id = response.data;
              this._parte_delta.med_parte_id = response.data;
              store.commit('SET_PARTE_EDIT', 1);
              this.showSnackbar("Parte Médico creado");
            })
            .catch( (error) => {
              console.log(error);
              this.showSnackbar("Se ha producido un ERROR");
            });
        }
      },
    }
  }
</script>
