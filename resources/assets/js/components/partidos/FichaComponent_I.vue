<template>

  <div class="modal-intendente-partido">

    <b-form @submit="onSubmit">

      <b-row class="form-toolbar text-center text-white py-2">
        <div class="col-sm-3 marcador rojo float-left text-left position-relative">
          <b-form-select
            v-model="tanteo.puntos_rojo"
            :disabled="(isPrensa ? true : false)"
            :options="tantos" />
          <div class="pelotaris position-absolute">
            <p v-if="partido.pelotari_1" class="mb-0 text-uppercase font-weight-bold">{{ partido.pelotari_1.alias }}</p>
            <p v-if="partido.pelotari_2" class="mb-0 text-uppercase font-weight-bold">{{ partido.pelotari_2.alias }}</p>
          </div>
        </div>
        <div class="col-sm-6 title font-weight-bold" style="font-size:20px;">
          <p class="mb-0">{{ this.partido.orden }}º Partido</p>
          <p v-if="null !== partido.campeonato_name" class="text-uppercase mb-0"><small>Cpto.{{ this.partido.campeonato_name }} - {{ this.partido.fase }}</small></p>
          <p v-else class="text-uppercase mb-0"><small>{{ this.partido.tipo_partido_name }}</small></p>
        </div>
        <div class="col-sm-3 marcador azul float-right text-right position-relative">
          <b-form-select
            v-model="tanteo.puntos_azul"
            :disabled="(isPrensa ? true : false)"
            :options="tantos" />
          <div class="pelotaris position-absolute">
            <p v-if="partido.pelotari_3" class="mb-0 text-uppercase font-weight-bold">{{ partido.pelotari_3.alias }}</p>
            <p v-if="partido.pelotari_4" class="mb-0 text-uppercase font-weight-bold">{{ partido.pelotari_4.alias }}</p>
          </div>
        </div>
      </b-row>

      <div class="form-body">

        <b-card no-body class="mb-3">
          <b-tabs pills card>
            <b-tab title="Tanteo" active>
              <tab-tanteo :partido="partido" :tanteo="tanteo" :is-prensa="isPrensa"></tab-tanteo>
            </b-tab>
            <b-tab title="Marcadores">
              <tab-marcadores :partido="partido" :marcadores="marcadores" :is-prensa="isPrensa"></tab-marcadores>
            </b-tab>
            <b-tab title="Anotaciones">
              <tab-anotaciones :partido="partido" :anotaciones="anotaciones" :is-prensa="isPrensa"></tab-anotaciones>
            </b-tab>
          </b-tabs>
        </b-card>

        <b-row class="m-0">
          <b-button v-if="!isPrensa" variant="danger" type="submit">Guardar</b-button>
          <b-button variant="default" @click="onClickCancelar()" class="ml-2">Cancelar</b-button>
        </b-row>

      </div>

    </b-form>

  </div>

</template>

<script>
  import { mapState } from 'vuex';
  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [APIGetters, Utils],
    props: ['partido', 'modalId', 'isPrensa'],
    data () {
      return {
        tanteo: {
          pelotari_1_id: this.partido.pelotari_1.id,
          pelotari_2_id: (this.partido.pelotari_2 ? this.partido.pelotari_2.id : 0),
          pelotari_3_id: this.partido.pelotari_3.id,
          pelotari_4_id: (this.partido.pelotari_4 ? this.partido.pelotari_4.id : 0),
          puntos_rojo: this.partido.puntos_rojo,
          puntos_azul: this.partido.puntos_azul,
          pelotazos: this.partido.pelotazos,
          duracion: this.partido.duracion,
          tantos: []
        },
        marcadores: {
          rojo: [],
          azul: [],
        },
        anotaciones: {
          afluencia: '',
          fronton: '',
          incidencias: '',
          comentarios: '',
        },
        tantos: [
          { value: 22, text: '22' },
          { value: 21, text: '21' },
          { value: 20, text: '20' },
          { value: 19, text: '19' },
          { value: 18, text: '18' },
          { value: 17, text: '17' },
          { value: 16, text: '16' },
          { value: 15, text: '15' },
          { value: 14, text: '14' },
          { value: 13, text: '13' },
          { value: 12, text: '12' },
          { value: 11, text: '11' },
          { value: 10, text: '10' },
          { value: 9, text: '9' },
          { value: 8, text: '8' },
          { value: 7, text: '7' },
          { value: 6, text: '6' },
          { value: 5, text: '5' },
          { value: 4, text: '4' },
          { value: 3, text: '3' },
          { value: 2, text: '2' },
          { value: 1, text: '1' },
          { value: 0, text: '0' },
        ],
      }
    },
    created: function () {
      console.log("FestivalNuevoPartidoComponent created");

      var tipo_tantos = [
        'saque',
        'dos_paredes',
        'dejada',
        'aire',
        'bote',
        'cortadas',
        'escapadas',
        'atras',
        'falta_saque',
        'errores',
        'total_tantos',
        'total_fallos',
      ];

      var self = this;

      // Tanteo
      tipo_tantos.forEach( function(tipo) {
        var tantos = _.filter(self.partido.tanteos, { tanteo_desc: tipo });
        if( self.partido.is_partido_parejas ) {
          self.tanteo.tantos.push({
            name: tipo,
            pelotari_1: (tantos[0] ? tantos[0].tanteo : 0),
            pelotari_2: (tantos[1] ? tantos[1].tanteo : 0),
            pelotari_3: (tantos[2] ? tantos[2].tanteo : 0),
            pelotari_4: (tantos[3] ? tantos[3].tanteo : 0),
          });
        } else {
          self.tanteo.tantos.push({
            name: tipo,
            pelotari_1: (tantos[0] ? tantos[0].tanteo : 0),
            pelotari_3: (tantos[1] ? tantos[1].tanteo : 0),
          });
        }
      });

      // Marcadores
      this.partido.marcadores.forEach( function(marcador) {
        self.marcadores.rojo.push(marcador.marcador_rojo);
        self.marcadores.azul.push(marcador.marcador_azul);
      });

      // Anotaciones
      this.anotaciones.afluencia = this.partido.obs_publico;
      this.anotaciones.fronton = this.partido.obs_fronton;
      this.anotaciones.incidencias = this.partido.obs_incidencias;
      this.anotaciones.comentarios = this.partido.obs_comentarios;
    },
    computed: mapState({
      _header: 'header',
    }),
    methods: {
      onFocusInput (field) {
        field.select();
      },
      closeModal () {
        this.$root.$emit('bv::hide::modal', this.modalId);
      },
      onSubmit (evt) {
        evt.preventDefault();
        jQuery('.modal-intendente-partido').addClass('preloader');
        var data = {
          id: this.partido.id,
          tanteo: this.tanteo,
          marcadores: this.marcadores,
          anotaciones: this.anotaciones,
        }

        this.$store.dispatch('updatePartidoCelebrado', data)
          .then((response) => {
            this.showSnackbar("Partido actualizado");
            jQuery('.modal-intendente-partido').removeClass('preloader');
            this.$emit('update-partido', response);
            this.closeModal();
          })
          .catch((error) => {
            console.log(error);
            this.showSnackbar("Se ha producido un ERROR");
            jQuery('.modal-intendente-partido').removeClass('preloader');
            this.closeModal();
          });
      },
      onClickCancelar() {
        this.closeModal();
      }
    }
  }

  Vue.component('tab-tanteo', {
    props: ['partido', 'tanteo', 'isPrensa'],
    data: function() {
      return {
        fields: [],
        tantos: [
          { value: 0, text: '0' },
          { value: 1, text: '1' },
          { value: 2, text: '2' },
          { value: 3, text: '3' },
          { value: 4, text: '4' },
          { value: 5, text: '5' },
          { value: 6, text: '6' },
          { value: 7, text: '7' },
          { value: 8, text: '8' },
          { value: 9, text: '9' },
          { value: 10, text: '10' },
          { value: 11, text: '11' },
          { value: 12, text: '12' },
          { value: 13, text: '13' },
          { value: 14, text: '14' },
          { value: 15, text: '15' },
          { value: 16, text: '16' },
          { value: 17, text: '17' },
          { value: 18, text: '18' },
          { value: 19, text: '19' },
          { value: 20, text: '20' },
          { value: 21, text: '21' },
          { value: 22, text: '22' },
        ]
      }
    },
    created: function() {
      if( this.partido.is_partido_parejas ) {
        this.fields = {
          name: {
            key: "name",
            label: "name",
            class: "w-20",
          },
          pelotari_1: {
            key: this.partido.pelotari_1.alias.toUpperCase(),
            label: this.partido.pelotari_1.alias.toUpperCase(),
            class: "w-20 rojo",
          },
          pelotari_2: {
            key: this.partido.pelotari_2.alias.toUpperCase(),
            label: this.partido.pelotari_2.alias.toUpperCase(),
            class: "w-20 rojo",
          },
          pelotari_3: {
            key: this.partido.pelotari_3.alias.toUpperCase(),
            label: this.partido.pelotari_3.alias.toUpperCase(),
            class: "w-20 azul",
          },
          pelotari_4: {
            key: this.partido.pelotari_4.alias.toUpperCase(),
            label: this.partido.pelotari_4.alias.toUpperCase(),
            class: "w-20 azul",
          },
        };
      } else {
        this.fields = {
          name: {
            key: "name",
            label: "name",
            class: "w-50",
          },
          pelotari_1: {
            key: this.partido.pelotari_1.alias.toUpperCase(),
            label: this.partido.pelotari_1.alias.toUpperCase(),
            class: "w-25 rojo",
          },
          pelotari_3: {
            key: this.partido.pelotari_3.alias.toUpperCase(),
            label: this.partido.pelotari_3.alias.toUpperCase(),
            class: "w-25 azul",
          },
        };
      }
    },
    methods: {
      onFocusInput (field) {
        field.select();
      },
    },
    template: `
      <div>
        <b-row>
          <b-col sm="4">
            <label for="pelotazos" class="pt-1 mb-0 font-weight-bold float-right">Total pelotazos:</label>
          </b-col>
          <b-col sm="2">
            <b-form-input id="pelotazos"
              class="text-right"
              type="number"
              @focus.native="onFocusInput($event.target)"
              v-model="tanteo.pelotazos"
              step="1" min="0" max="2000"
              :disabled="(isPrensa ? true : false)" />
          </b-col>
          <b-col sm="4">
            <label for="duracion" class="pt-1 mb-0 font-weight-bold float-right">Duración partido:</label>
          </b-col>
          <b-col sm="2">
            <b-form-input id="duracion"
              class="text-right"
              type="number"
              @focus.native="onFocusInput($event.target)"
              v-model="tanteo.duracion"
              step="1" min="0" max="120"
              :disabled="(isPrensa ? true : false)" />
          </b-col>
        </b-row>

        <b-row class="mt-3">
          <table class="w-100">
            <thead>
              <tr class="font-weight-bold text-center">
                <td :class="fields.name.class">&nbsp;</td>
                <td :class="fields.pelotari_1.class">{{ this.fields.pelotari_1.label.replace("_", " ").toUpperCase() }}</td>
                <td v-if="fields.pelotari_2" :class="fields.pelotari_2.class">{{ this.fields.pelotari_2.label.replace("_", " ").toUpperCase() }}</td>
                <td :class="fields.pelotari_3.class">{{ this.fields.pelotari_3.label.replace("_", " ").toUpperCase() }}</td>
                <td v-if="fields.pelotari_4" :class="fields.pelotari_4.class">{{ this.fields.pelotari_4.label.replace("_", " ").toUpperCase() }}</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in this.tanteo.tantos" class="text-center">
                  <td class="text-left text-uppercase font-weight-bold">{{ item.name.replace("_", " ").toUpperCase() }}</td>
                  <td>
                    <b-form-select class="rojo"
                      v-model="item.pelotari_1"
                      :options="tantos"
                      :disabled="(isPrensa ? true : false)" />
                  </td>
                  <td v-if="partido.is_partido_parejas">
                    <b-form-select class="rojo"
                      v-model="item.pelotari_2"
                      :options="tantos"
                      :disabled="(isPrensa ? true : false)" />
                  </td>
                  <td>
                    <b-form-select class="azul"
                      v-model="item.pelotari_3"
                      :options="tantos"
                      :disabled="(isPrensa ? true : false)" />
                  </td>
                  <td v-if="partido.is_partido_parejas">
                    <b-form-select class="azul"
                      v-model="item.pelotari_4"
                      :options="tantos"
                      :disabled="(isPrensa ? true : false)" />
                  </td>
              </tr>
            </tbody>
          </table>
        </b-row>
      </div>
    `
  });

  Vue.component('tab-marcadores', {
    props: ['partido', 'marcadores', 'isPrensa'],
    data: function() {
      return {
        tantos: [
          { value: 0, text: '0' },
          { value: 1, text: '1' },
          { value: 2, text: '2' },
          { value: 3, text: '3' },
          { value: 4, text: '4' },
          { value: 5, text: '5' },
          { value: 6, text: '6' },
          { value: 7, text: '7' },
          { value: 8, text: '8' },
          { value: 9, text: '9' },
          { value: 10, text: '10' },
          { value: 11, text: '11' },
          { value: 12, text: '12' },
          { value: 13, text: '13' },
          { value: 14, text: '14' },
          { value: 15, text: '15' },
          { value: 16, text: '16' },
          { value: 17, text: '17' },
          { value: 18, text: '18' },
          { value: 19, text: '19' },
          { value: 20, text: '20' },
          { value: 21, text: '21' },
          { value: 22, text: '22' },
        ]
      }
    },
    methods: {
      onFocusInput (field) {
        field.select();
      },
    },
    template: `
      <b-row>
        <div class="d-inline-flex" style="margin:0 auto;">
          <table class="w-20 mr-3 ml-2">
            <thead>
              <tr>
                <td>&nbsp;</td>
                <td class="rojo" style="border:none;border-right:2px solid;">&nbsp;</td>
                <td class="azul" style="border:none;border-left:2px solid;">&nbsp;</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="i in 11">
                <td class="text-center font-weight-bold">{{ i }}:&nbsp;</td>
                <td><b-form-select class="rojo" v-model="marcadores.rojo[i - 1]" :options="tantos" :disabled="(isPrensa ? true : false)" /></td>
                <td><b-form-select class="azul" v-model="marcadores.azul[i - 1]" :options="tantos" :disabled="(isPrensa ? true : false)" /></td>
              </tr>
            </tbody>
          </table>
          <table class="w-20 mr-3">
            <thead>
              <tr>
                <td>&nbsp;</td>
                <td class="rojo" style="border:none;border-right:2px solid;">&nbsp;</td>
                <td class="azul" style="border:none;border-left:2px solid;">&nbsp;</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="i in 11">
                <td class="text-center font-weight-bold">{{ i + 11 }}:&nbsp;</td>
                <td><b-form-select class="rojo" v-model="marcadores.rojo[i + 10]" :options="tantos" :disabled="(isPrensa ? true : false)" /></td>
                <td><b-form-select class="azul" v-model="marcadores.azul[i + 10]" :options="tantos" :disabled="(isPrensa ? true : false)" /></td>
              </tr>
            </tbody>
          </table>
          <table class="w-20 mr-3">
            <thead>
              <tr>
                <td>&nbsp;</td>
                <td class="rojo" style="border:none;border-right:2px solid;">&nbsp;</td>
                <td class="azul" style="border:none;border-left:2px solid;">&nbsp;</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="i in 11">
                <td class="text-center font-weight-bold">{{ i + 22 }}:&nbsp;</td>
                <td><b-form-select class="rojo" v-model="marcadores.rojo[i + 21]" :options="tantos" :disabled="(isPrensa ? true : false)" /></td>
                <td><b-form-select class="azul" v-model="marcadores.azul[i + 21]" :options="tantos" :disabled="(isPrensa ? true : false)" /></td>
              </tr>
            </tbody>
          </table>
          <table class="w-20">
            <thead>
              <tr>
                <td>&nbsp;</td>
                <td class="rojo" style="border:none;border-right:2px solid;">&nbsp;</td>
                <td class="azul" style="border:none;border-left:2px solid;">&nbsp;</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="i in 10">
                <td class="text-center font-weight-bold">{{ i + 33 }}:&nbsp;</td>
                <td><b-form-select class="rojo" v-model="marcadores.rojo[i + 32]" :options="tantos" :disabled="(isPrensa ? true : false)" /></td>
                <td><b-form-select class="azul" v-model="marcadores.azul[i + 32]" :options="tantos" :disabled="(isPrensa ? true : false)" /></td>
              </tr>
            </tbody>
          </table>
        </div>
      </b-row>
    `
  });

  Vue.component('tab-anotaciones', {
    props: ['partido', 'anotaciones', 'isPrensa'],
    data: function() {
      return {

      }
    },
    template: `
      <b-row>
        <b-form-group label="Afluencia público"
                      label-for="afluencia"
                      class="col-sm-12 font-weight-bold">
          <b-form-textarea id="afluencia"
                           v-model="anotaciones.afluencia"
                           :rows="3"
                           :max-rows="6"
                           :disabled="(isPrensa ? true : false)">
          </b-form-textarea>
        </b-form-group>
        <b-form-group label="Condiciones frontón"
                      label-for="fronton"
                      class="col-sm-12 font-weight-bold">
          <b-form-textarea id="fronton"
                           v-model="anotaciones.fronton"
                           :rows="3"
                           :max-rows="6"
                           :disabled="(isPrensa ? true : false)">
          </b-form-textarea>
        </b-form-group>
        <b-form-group label="Alguna incidencia"
                      label-for="incidencias"
                      class="col-sm-12 font-weight-bold">
          <b-form-textarea id="incidencias"
                           v-model="anotaciones.incidencias"
                           :rows="3"
                           :max-rows="6"
                           :disabled="(isPrensa ? true : false)">
          </b-form-textarea>
        </b-form-group>
        <b-form-group label="Otros comentarios"
                      label-for="comentarios"
                      class="col-sm-12 font-weight-bold">
          <b-form-textarea id="comentarios"
                           v-model="anotaciones.comentarios"
                           :rows="3"
                           :max-rows="6"
                           :disabled="(isPrensa ? true : false)">
          </b-form-textarea>
        </b-form-group>
      </b-row>
    `
  });

</script>

<style>
  .tabs {
    border:none;
    margin-bottom:0;
  }
  .modal-title {
    font-weight: bold;
    text-align:center;
    width:100%;
  }
  .modal-body {
    padding:0;
  }
  .modal-intendente-partido .form-toolbar {
    background-color:slategray;
    margin:0;
    margin-top:-1px;
  }
  .modal-intendente-partido .form-toolbar .marcador input,
  .modal-intendente-partido .form-toolbar .marcador select {
    color:white;
    display:inline;
    font-size:20px;
    font-weight:bold;
    margin-top:.2rem;
    max-width:50px;
    padding:0;
    text-align:center;
  }
  .modal-intendente-partido .form-toolbar .marcador select {
    background:none;
    padding-bottom:5px;
    padding-left:10px;
  }
  .modal-intendente-partido .form-toolbar .marcador option {
    background:white;
    color:black;
    font-size:14px;
  }
  .modal-intendente-partido .form-toolbar .marcador.rojo input,
  .modal-intendente-partido .form-toolbar .marcador.rojo select {
    background-color:red;
    float:left;
  }
  .modal-intendente-partido .form-toolbar .marcador.azul input,
  .modal-intendente-partido .form-toolbar .marcador.azul select {
    background-color:blue;
    float:right;
  }
  .modal-intendente-partido .form-toolbar p {
    line-height:1;
  }
  .modal-intendente-partido .form-toolbar .marcador .pelotaris {
    font-size:1.15rem;
    top:50%;
    width:100%;

    -webkit-transform:translateY(-50%);
    -moz-transform:translateY(-50%);
    -ms-transform:translateY(-50%);
    -o-transform:translateY(-50%);
    transform:translateY(-50%);
  }
  .modal-intendente-partido .form-toolbar .marcador.rojo .pelotaris {
    left:75px;
  }
  .modal-intendente-partido .form-toolbar .marcador.azul .pelotaris {
    right:75px;
  }
  .modal-intendente-partido .form-body {
    padding:1rem;
  }
  .modal-intendente-partido .card-header {
    background-color:transparent;
    padding:0;
  }
  .modal-intendente-partido .card-header .card-header-pills {
    margin:0;
  }
  .modal-intendente-partido table .w-20 {
    width:20%!important;
  }
  .modal-intendente-partido table td.rojo,
  .modal-intendente-partido table td.azul {
    border-color:white;
    color:white;
    border-left: 10px solid;
    border-right: 10px solid;
    border-top:2px solid white;
    border-bottom:2px solid white;
  }
  .modal-intendente-partido table td.rojo {
    background-color:red;
  }
  .modal-intendente-partido table td.azul {
    background-color:blue;
  }
  .modal-intendente-partido table th {
    text-align:center;
  }
  .modal-intendente-partido input[type="number"],
  .modal-intendente-partido select {
    margin:0 auto;
    max-width:50px;
    padding-bottom:3px;
    padding-top:3px;
    text-align:center!important;

    padding-left:0;
    padding-right:0;
  }
  .modal-intendente-partido select {
    background:none;
    padding-left:15px;
    width:100px;
  }
  .modal-intendente-partido select option {
    background-color:white;
    color:black;
  }
  .modal-intendente-partido input[type="number"].rojo,
  .modal-intendente-partido select.rojo {
    border-color:rgba(255,0,0,.25);
    color:rgba(255,0,0,.7);
    font-weight:bold;
  }
  .modal-intendente-partido input[type="number"].azul,
  .modal-intendente-partido select.azul {
    border-color:rgba(0,0,255,.25);
    color:rgba(0,0,255,.7);
    font-weight:bold;
  }
</style>
