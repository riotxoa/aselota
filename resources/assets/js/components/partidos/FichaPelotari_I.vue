<template>
  <div class="ficha-pelotari-i">
    <div class="header">
      {{ this._header.fecha }} - Frontón {{ this._header.fronton }} - {{ this._header.hora }}
    </div>
    <div class="body">

      <b-row>

        <div class="equipo gray col-sm-4 p-0 ml-md-3">
          <div class="pelotari-foto d-none d-sm-inline-block">
            <img :src="pelotari.foto" />
          </div>
          <div class="pelotari-name d-inline-block text-center text-uppercase px-3">
            <span>{{ this.pelotari.alias }}</span>
          </div>
        </div>

        <div class="block partido col-sm-2 text-center font-weight-bold ml-3">
          <span v-if="partido.estelar"><i class="voyager-star-two d-inline-block position-absolute"></i></span>{{ this.partido.orden }}º Partido
        </div>
        <div v-if="partido.campeonato_name" class="block campeonato col-sm-2 text-center font-weight-bold">
          Cpto. {{ this.partido.campeonato_name }}
        </div>
        <div v-if="!partido.campeonato_name" class="block light tipo col-sm-2 text-center font-weight-bold">
          {{ this.partido.tipo_partido_name }}
        </div>
        <div v-if="partido.fase" class="block fase col-sm-2 text-center font-weight-bold text-capitalize">
          {{ this.partido.fase }}
        </div>

      </b-row>

      <form @submit="onSubmit">

        <b-row class="mt-3">
          <div class="col-sm-4 p-0 pt-1 ml-md-3">
            <b-form-radio-group buttons
                                button-variant="outline-primary"
                                v-model="pelotari.asiste"
                                :options="asiste_options"
                                name="radiosBtnDefault" />

          </div>
        </b-row>

        <b-row v-if="!pelotari.asiste">

          <div class="col-sm-4 px-0 pt-3 pb-0 ml-md-3">
            <b-form-group label="Sustituto">
              <b-form-select :options="pelotaris"
                             v-model="pelotari.sustituto_id"
                             :disabled="(isPrensa ? true : false)">
              </b-form-select>
            </b-form-group>
          </div>

          <div class="col-sm-7 px-0 pt-3 pb-0 ml-md-3">
            <b-form-group label="Motivo sustitución">
              <b-form-input v-model="pelotari.sustituto_txt"
                            placeholder="Motivo de la sustitución"
                            :disabled="(isPrensa ? true : false)">
              </b-form-input>
            </b-form-group>
          </div>

        </b-row>

        <b-row class="mt-3">
          <b-form-group label="Observaciones"
                        class="col-12">
            <b-form-textarea  v-model="pelotari.observaciones"
                              placeholder="Observaciones"
                              rows="3"
                              max-rows="6"
                              :disabled="(isPrensa ? true : false)">
            </b-form-textarea>
          </b-form-group>
        </b-row>

        <b-row>
          <b-button v-if="!isPrensa" variant="danger" type="submit" style="margin-left:15px;">Guardar</b-button>
          <b-button variant="default" @click="onClickCancelar()" class="ml-2">Cancelar</b-button>
        </b-row>

      </form>

    </div>
  </div>
</template>

<script>
  import { mapState } from 'vuex';
  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [APIGetters, Utils],
    props: ['partido', 'pelotari', 'modalId', 'isPrensa'],
    data () {
      return {
        asiste_options: [
          { text: "Asiste", value: 1 },
          { text: "No asiste", value: 0 },
        ]
      }
    },
    created() {
      this.getPelotaris(this._header.fecha);
      if( this.isPrensa ) {
        this.asiste_options = [
          { text: "Asiste", value: 1, disabled: (this.pelotari.asiste ? false : true) },
          { text: "No asiste", value: 0, disabled: (this.pelotari.asiste ? true : false) },
        ];
      }
    },
    computed: mapState({
      _header: 'header',
    }),
    methods: {
      onSubmit(evt) {
        evt.preventDefault();

        var data = {
          id: this.pelotari.partido_pelotari_id,
          asiste: this.pelotari.asiste,
          sustituto_id: (this.pelotari.asiste ? null : this.pelotari.sustituto_id),
          sustituto_txt: (this.pelotari.asiste ? null : this.pelotari.sustituto_txt),
          observaciones: this.pelotari.observaciones
        }

        this.$store.dispatch('updatePartidoPelotari', data)
          .then((response) => {
            this.showSnackbar("Pelotari actualizado");
            response.sustituto_alias = _.find(this.pelotaris, { value: this.pelotari.sustituto_id }).text;
            this.$emit('update-pelotari', response);
            this.closeModal();
          })
          .catch((error) => {
            console.log(error);
            this.showSnackbar("Se ha producido un ERROR");
            this.closeModal();
          });
      },
      closeModal () {
        this.$root.$emit('bv::hide::modal', this.modalId);
      },
      onClickCancelar() {
        this.closeModal();
      }
    }
  }
</script>

<style>
  .ficha-pelotari-i .modal-header {
    padding:.5rem 1rem;
  }
  .ficha-pelotari-i .modal-title {
    font-weight:bold;
    text-align:center;
  }
  .ficha-pelotari-i .modal-body {
    padding:0;
  }
  .ficha-pelotari-i .header {
    background-color:slategray;
    border:none;
    color:white;
    font-weight:bold;
    margin:0;
    margin-top:-1px;
    padding:.5rem 1rem;
    text-align:center;
    text-transform:uppercase;
  }
  .ficha-pelotari-i .body {
    padding:1.5rem;
  }
  .ficha-pelotari-i .block.partido,
  .ficha-pelotari-i .block.tipo,
  .ficha-pelotari-i .block.campeonato,
  .ficha-pelotari-i .block.fase {
    padding:.5rem 0;
  }
  .ficha-pelotari-i .voyager-star-two {
    bottom:7.5px;
    left:7.5px;
  }
</style>
