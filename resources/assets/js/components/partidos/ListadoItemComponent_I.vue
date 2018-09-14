<template>
  <div class="listado-item mb-4">

    <b-row class="m-0 mb-2 position-relative">
      <div class="block partido brmn-pointer col-sm-3 col-lg-2 text-center font-weight-bold brmn-clickable brmn-gray" @click.stop="onClickEditPartido(partido)">
        <span v-if="data.estelar"><i class="voyager-star-two d-inline-block position-absolute"></i></span>{{ this.data.orden }}º Partido
      </div>
      <div v-if="data.campeonato_name" class="block campeonato col-sm-3 col-lg-2 text-center font-weight-bold">
        Cpto. {{ this.data.campeonato_name }}
      </div>
      <div v-if="!data.campeonato_name" class="block light tipo col-sm-3 col-lg-2 text-center font-weight-bold">
        {{ this.data.tipo_partido_name }}
      </div>
      <div v-if="data.fase" class="block fase col-sm-3 col-lg-2 text-center font-weight-bold text-capitalize">
        {{ this.data.fase }}
      </div>
      <div v-if="isGerente && _header.estado_id !== 3" class="toolbar col-sm-3 col-lg-2 text-right position-absolute pr-0">
        <b-button size="sm" variant="outline-danger" @click.stop="onClickDelete(partido)" title="Eliminar Partido">
          <span class="icon voyager-trash"></span>
        </b-button>
        <b-button size="sm" variant="outline-primary" @click.stop="onClickEditPartido(partido)" title="Editar Partido">
          <span class="icon voyager-edit"></span>
        </b-button>
      </div>
    </b-row>

    <b-row class="title-wrap my-1">
      <div class="title col-4 p-0 font-weight-bold ml-md-3"><div class="tanteo rojo d-inline-block">{{ ( partido.puntos_rojo ? partido.puntos_rojo : "-" ) }}</div><div class="d-inline-block px-3">Pelotaris</div></div>
      <div v-if="isGerente" class="title coste rojo col-2 pt-1 pb-0 text-center font-weight-bold">Coste</div>
      <div v-else class="title coste rojo col-2 pt-1 pb-0 text-center font-weight-bold">Asistencia</div>
      <div class="title col-4 p-0 font-weight-bold ml-md-1 ml-lg-3"><div class="tanteo azul d-inline-block">{{ ( partido.puntos_azul ? partido.puntos_azul : "-" ) }}</div><div class="d-inline-block px-3 font-weight-bold">Pelotaris</div></div>
      <div v-if="isGerente" class="title coste azul col-2 pt-1 pb-0 text-center font-weight-bold">Coste</div>
      <div v-else class="title coste azul col-2 pt-1 pb-0 text-center font-weight-bold">Asistencia</div>
    </b-row>

    <b-row class="mb-1">

      <div :class="['equipo rojo col-4 p-0 ml-md-3', { 'brmn-pointer': data.pelotari_1.asegarce }]" @click.stop="onClickEditPelotari(data.pelotari_1)">
        <div :class="['pelotari-foto d-none d-sm-inline-block', { 'brmn-img-tachado': !data.pelotari_1.asiste }]">
          <img v-if="data.pelotari_1 && 1 == data.pelotari_1.asegarce" :src="data.pelotari_1.foto" />
          <img v-if="data.pelotari_1 && 0 == data.pelotari_1.asegarce" :src=" '/storage/' + data.pelotari_1.foto" class="grayscale" />
        </div>
        <div class="pelotari-name d-inline-block text-left text-uppercase px-3">
          <div v-if="data.pelotari_1" :class="{ ['brmn-clickable brmn-red']: data.pelotari_1.asegarce }">
            <span :class="{ 'brmn-tachado': !data.pelotari_1.asiste }">{{ this.data.pelotari_1.alias }}</span>
            <span v-if="!data.pelotari_1.asiste"><br/>{{ this.data.pelotari_1.sustituto_alias }}</span>
          </div>
        </div>
      </div>
      <div class="col-2 coste rojo text-center p-0">
        <div v-if="isGerente"> <!-- Gerente -->
          <span v-if="data.pelotari_1 && 1 == data.pelotari_1.asegarce" :class="['d-block', { 'pt-1 brmn-tachado': !data.pelotari_1.asiste }, { 'pt-2': data.pelotari_1.asiste }]">{{ this.data.pelotari_1.coste.toFixed(2) }}&nbsp;&euro;</span>
          <span v-if="!data.pelotari_1.asiste">{{ this.data.pelotari_1.sustituto_coste.toFixed(2) }}&nbsp;&euro;</span>
        </div>
        <div v-else> <!-- Intendente -->
          <span v-if="data.pelotari_1 && 1 == data.pelotari_1.asegarce" @click.stop="onClickEditPelotari(data.pelotari_1)">
            <span v-if="data.pelotari_1.asiste" class="icon voyager-check d-inline-block"></span>
            <span v-if="!data.pelotari_1.asiste" class="icon voyager-x d-inline-block"></span>
          </span>
        </div>
        <span v-if="data.pelotari_1 && 0 == data.pelotari_1.asegarce" class="fas fa-ban" style="top:12.5px"></span>
      </div>

      <div :class="['equipo azul col-4 p-0 ml-md-1 ml-lg-3', { 'brmn-pointer': data.pelotari_3.asegarce }]" @click.stop="onClickEditPelotari(data.pelotari_3)">
        <div :class="['pelotari-foto d-none d-sm-inline-block', { 'brmn-img-tachado': !data.pelotari_3.asiste }]">
          <img v-if="data.pelotari_3 && 1 == data.pelotari_3.asegarce" :src="data.pelotari_3.foto" />
          <img v-if="data.pelotari_3 && 0 == data.pelotari_3.asegarce" :src=" '/storage/' + data.pelotari_3.foto" class="grayscale" />
        </div>
        <div class="pelotari-name d-inline-block text-left text-uppercase px-3">
          <div v-if="data.pelotari_3" :class="{ 'brmn-clickable brmn-blue': data.pelotari_3.asegarce }">
            <span :class="{ 'brmn-tachado': !data.pelotari_3.asiste }">{{ this.data.pelotari_3.alias }}</span>
            <span v-if="!data.pelotari_3.asiste"><br/>{{ this.data.pelotari_3.sustituto_alias }}</span>
          </div>
        </div>
      </div>
      <div class="col-2 coste azul text-center p-0">
        <div v-if="isGerente"> <!-- Gerente -->
          <span v-if="data.pelotari_3 && 1 == data.pelotari_3.asegarce" :class="['d-block', { 'pt-1 brmn-tachado': !data.pelotari_3.asiste }, { 'pt-2': data.pelotari_3.asiste }]">{{ this.data.pelotari_3.coste.toFixed(2) }}&nbsp;&euro;</span>
          <span v-if="!data.pelotari_3.asiste">{{ this.data.pelotari_3.sustituto_coste.toFixed(2) }}&nbsp;&euro;</span>
        </div>
        <div v-else> <!-- Intendente -->
          <span v-if="data.pelotari_3 && 1 == data.pelotari_3.asegarce" @click.stop="onClickEditPelotari(data.pelotari_3)">
            <span v-if="data.pelotari_3.asiste" class="icon voyager-check d-inline-block"></span>
            <span v-if="!data.pelotari_3.asiste" class="icon voyager-x d-inline-block"></span>
          </span>
        </div>
        <span v-if="data.pelotari_3 && 0 == data.pelotari_3.asegarce" class="fas fa-ban" style="top:12.5px"></span>
      </div>

    </b-row>

    <b-row v-if="data.is_partido_parejas">

      <div :class="['equipo rojo col-4 p-0 ml-md-3', { 'brmn-pointer': data.pelotari_2.asegarce }]" @click.stop="onClickEditPelotari(data.pelotari_2)">
        <div :class="['pelotari-foto d-none d-sm-inline-block', { 'brmn-img-tachado': !data.pelotari_2.asiste }]">
          <img v-if="data.pelotari_2 && 1 == data.pelotari_2.asegarce" :src="data.pelotari_2.foto" />
          <img v-if="data.pelotari_2 && 0 == data.pelotari_2.asegarce" :src=" '/storage/' + data.pelotari_2.foto" class="grayscale" />
        </div>
        <div class="pelotari-name d-inline-block text-left text-uppercase px-3">
          <div v-if="data.pelotari_2" :class="{ 'brmn-clickable brmn-red': data.pelotari_2.asegarce}">
            <span :class="{ 'brmn-tachado': !data.pelotari_2.asiste }">{{ this.data.pelotari_2.alias }}</span>
            <span v-if="!data.pelotari_2.asiste"><br/>{{ this.data.pelotari_2.sustituto_alias }}</span>
          </div>
        </div>
      </div>
      <div class="col-2 coste rojo text-center p-0">
        <div v-if="isGerente"> <!-- Gerente -->
          <span v-if="data.pelotari_2 && 1 == data.pelotari_2.asegarce"  :class="['d-block', { 'pt-1 brmn-tachado': !data.pelotari_2.asiste }, { 'pt-2': data.pelotari_2.asiste }]">{{ this.data.pelotari_2.coste.toFixed(2) }}&nbsp;&euro;</span>
          <span v-if="!data.pelotari_2.asiste">{{ this.data.pelotari_2.sustituto_coste.toFixed(2) }}&nbsp;&euro;</span>
        </div>
        <div v-else> <!-- Intendente -->
          <span v-if="data.pelotari_2 && 1 == data.pelotari_2.asegarce" @click.stop="onClickEditPelotari(data.pelotari_2)">
            <span v-if="data.pelotari_2.asiste" class="icon voyager-check d-inline-block"></span>
            <span v-if="!data.pelotari_2.asiste" class="icon voyager-x d-inline-block"></span>
          </span>
        </div>
        <span v-if="data.pelotari_2 && 0 == data.pelotari_2.asegarce" class="fas fa-ban" style="top:12.5px"></span>
      </div>

      <div :class="['equipo azul col-4 p-0 ml-md-1 ml-lg-3', { 'brmn-pointer': data.pelotari_4.asegarce }]" @click.stop="onClickEditPelotari(data.pelotari_4)">
        <div :class="['pelotari-foto d-none d-sm-inline-block', { 'brmn-img-tachado': !data.pelotari_4.asiste }]">
          <img v-if="data.pelotari_4 && 1 == data.pelotari_4.asegarce" :src="data.pelotari_4.foto" />
          <img v-if="data.pelotari_4 && 0 == data.pelotari_4.asegarce" :src=" '/storage/' + data.pelotari_4.foto" class="grayscale" />
        </div>
        <div class="pelotari-name d-inline-block text-left text-uppercase px-3">
          <div v-if="data.pelotari_4" :class="{ 'brmn-clickable brmn-blue': data.pelotari_4.asegarce}">
            <span :class="{ 'brmn-tachado': !data.pelotari_4.asiste }">{{ this.data.pelotari_4.alias }}</span>
            <span v-if="!data.pelotari_4.asiste"><br/>{{ this.data.pelotari_4.sustituto_alias }}</span>
          </div>
        </div>
      </div>
      <div class="col-2 coste azul text-center p-0">
        <div v-if="isGerente"> <!-- Gerente -->
          <span v-if="data.pelotari_4 && 1 == data.pelotari_4.asegarce"  :class="['d-block', { 'pt-1 brmn-tachado': !data.pelotari_4.asiste }, { 'pt-2': data.pelotari_4.asiste }]">{{ this.data.pelotari_4.coste.toFixed(2) }}&nbsp;&euro;</span>
          <span v-if="!data.pelotari_4.asiste">{{ this.data.pelotari_4.sustituto_coste.toFixed(2) }}&nbsp;&euro;</span>
        </div>
        <div v-else> <!-- Intendente -->
          <span v-if="data.pelotari_4 && 1 == data.pelotari_4.asegarce" @click.stop="onClickEditPelotari(data.pelotari_4)">
            <span v-if="data.pelotari_4.asiste" class="icon voyager-check d-inline-block"></span>
            <span v-if="!data.pelotari_4.asiste" class="icon voyager-x d-inline-block"></span>
          </span>
        </div>
        <span v-if="data.pelotari_4 && 0 == data.pelotari_4.asegarce" class="fas fa-ban" style="top:12.5px"></span>
      </div>

    </b-row>

    <b-modal class="modalEditPartido modalEditPartidoCelebrado" :id="modalPartidoC_ID" :title="modalPartidoTitle" size="lg" hideFooter lazy>
      <form-partido-i :partido="partido" :modal-id="modalPartidoC_ID" v-on:update-partido="updatePartidoCelebrado($event)"></form-partido-i>
    </b-modal>

    <b-modal class="modalEditPelotari" :id="modalPelotariID" title="Asistencia Pelotari" size="lg" hideFooter lazy>
      <form-pelotari-i :partido="partido" :pelotari="pelotari" :modal-id="modalPelotariID" v-on:update-pelotari="updatePelotari($event)"></form-pelotari-i>
    </b-modal>

    <b-modal class="modalEditPartido modalEditPartidoPendiente" :id="modalPartidoP_ID" size="lg" hideFooter lazy>
      <form-partido :edit="true" :festival-header="_header" :partido="partido" :modal-id="modalPartidoP_ID" v-on:update-partido="updatePartidoPendiente($event)"></form-partido>
    </b-modal>

  </div>
</template>

<script>
  import { mapState } from 'vuex';

  Vue.component('form-partido-i', require('./FichaComponent_I.vue'));
  Vue.component('form-partido', require('./FichaComponent.vue'));
  Vue.component('form-pelotari-i', require('./FichaPelotari_I.vue'));

  export default {
    props: ['partido', 'isGerente'],
    data () {
      return {
        data: null,
        modalPartidoTitle: '',
        modalPartidoC_ID: '',
        modalPartidoP_ID: '',
        modalPelotariID: '',
        pelotari: null,
      }
    },
    created: function () {
      console.log("ListadoItemComponent created");

      this.data = this.partido;

      var fecha_fest = new Date(this._header.fecha).toLocaleDateString('en-GB');

      this.modalPartidoTitle = "Festival " + this._header.fronton + " - " + fecha_fest;
      this.modalPartidoC_ID = "modalEditPartidoC-" + this.data.id;
      this.modalPartidoP_ID = "modalEditPartidoP-" + this.data.id;
      this.modalPelotariID = "modalEditPelotarisPartido-" + this.data.id;
    },
    computed: mapState({
      _header: 'header',
    }),
    updated: function () {
      this.data = this.partido;
    },
    methods: {
      onClickDelete(p) {
        this.$emit('delete-partido', p.id);
      },
      onClickEditPartido(p) {
        if( this._header.estado_id === 3 ) {
          this.onClickEditPartidoCelebrado(p);
        } else {
          this.onClickEditPartidoPendiente(p);
        }
      },
      onClickEditPartidoCelebrado(p) {
        this.$root.$emit('bv::show::modal', this.modalPartidoC_ID);
      },
      onClickEditPartidoPendiente(p) {
        this.$root.$emit('bv::show::modal', this.modalPartidoP_ID);
      },
      updatePartidoCelebrado(p) {
        this.partido.duracion = p.duracion;
        this.partido.pelotazos = p.pelotazos;
        this.partido.obs_publico = p.obs_publico;
        this.partido.obs_fronton = p.obs_fronton;
        this.partido.obs_incidencias = p.obs_incidencias;
        this.partido.obs_comentarios = p.obs_comentarios;
        this.partido.puntos_rojo = p.puntos_rojo;
        this.partido.puntos_azul = p.puntos_azul;
        this.partido.tanteos = p.tanteos;
        this.partido.marcadores = p.marcadores;
      },
      updatePartidoPendiente(p) {
        this.partido.orden = p.orden;
        this.partido.estelar = p.estelar;
        if( p.campeonato_id !== this.partido.campeonato_id ) {
          this.partido.campeonato_id = p.campeonato_id;
          this.partido.campeonato_name = p.campeonato_name;
        }
        if( p.tipo_partido_id !== this.partido.tipo_partido_id ) {
          this.partido.tipo_partido_id = p.tipo_partido_id;
          this.partido.tipo_partido_name = p.tipo_partido_name;
        }
        this.partido.fase = p.fase;
        this.partido.pelotari_1 = p.pelotari_1;
        this.partido.pelotari_2 = p.pelotari_2;
        this.partido.pelotari_3 = p.pelotari_3;
        this.partido.pelotari_4 = p.pelotari_4;
        this.partido.is_partido_parejas = p.is_partido_parejas;
      },
      onClickEditPelotari(p) {
        if(p.asegarce) {
          this.pelotari = p;
          this.$root.$emit('bv::show::modal', this.modalPelotariID);
        }
      },
      updatePelotari(p) {
        this.pelotari.asiste = p.asiste;
        this.pelotari.is_sustituto = p.is_sustituto;
        this.pelotari.sustituto_id = p.sustituto_id;
        this.pelotari.sustituto_alias = p.sustituto_alias;
        this.pelotari.sustituto_txt = p.sustituto_txt;
        this.pelotari.observaciones = p.observaciones;
      },
    }
  }
</script>

<style>
  .fa-hand-point-up {
    bottom:5px;
    color:lightgray;
    left:15px;
  }
  .voyager-star-two {
    bottom: -2px;
    color:darkorange;
    left:0;
    margin-left:.5rem;
  }
  .toolbar {
    bottom:0;
    right:-5px;
  }
  .block {
    background-color:gray;
    border:1px solid gray;
    color:white;
    margin-right:.25rem;
  }
  .block.light {
    background-color:#c0c0c0;
    border-color:#c0c0c0;
  }
  .block.partido {
    background-color:white;
    border-color:gray;
    color:#666;
  }
  .title-wrap .title:last-child {
    margin-right:-30px;
  }
  .equipo {
    max-height:42px;
  }
  .title-wrap .title:first-child,
  .title-wrap .title:nth-child(2),
  .equipo.rojo {
    background-color:#fbe7eb;
    border:1px solid #fbe7eb;
  }
  .title-wrap .title:nth-child(3),
  .title-wrap .title:last-child,
  .equipo.azul {
    background-color:#d4deee;
    border:1px solid #d4deee;
  }
  .equipo.gray {
    background-color:lightgray;
    border:1px solid lightgray;
  }
  .title-wrap .title.coste,
  .equipo .coste,
  .coste.col-2 {
    background-color:transparent;
    border:1px solid gray;
    margin:0 3px;
  }
  .coste.col-2.rojo {
    border-color:#fbe7eb;
  }
  .coste.col-2.azul {
    border-color:#d4deee;
  }
  .coste.col-2 {
    flex:0 0 13.75%;
    max-width:13.75%;
    padding:.5rem 0;
  }
  .tanteo{
    color:white;
    text-align:center;
    width:40px;
  }
  .tanteo.rojo {
    background-color:#d92a1f;
    border:1px solid #d92a1f;
  }
  .tanteo.azul {
    background-color:#0a4ea1;
    border:1px solid #0a4ea1;
  }
  .pelotari-foto {
    background-color:white;
    display:inline-block;
    height:40px;
    text-align:center;
    width:40px;
  }
  .pelotari-foto img {
    height:100%;
    width:auto;
  }
  .pelotari-name {
    line-height: 1;
    left:40px;
    position:absolute;
    top:50%;
    width:calc(100% - 40px);

    -webkit-transform:translateY(-50%);
    -moz-transform:translateY(-50%);
    -ms-transform:translateY(-50%);
    -o-transform:translateY(-50%);
    transform:translateY(-50%);
  }
  .coste {
    line-height:1;
  }
  .modalEditPartido .modal-dialog {
    margin-top:10%;
    max-width:1080px;
    width:85%;
  }
  .listado-item .toolbar {
    right:0;
  }
  .listado-item .coste .icon.voyager-edit {
    font-size:20px;
    top:5px;
  }
  .listado-item img.grayscale {
    -webkit-filter:grayscale(1) opacity(.85);
    filter:grayscale(1) opacity(.85);
  }
  .listado-item .voyager-check,
  .listado-item .voyager-x,
  .listado-item .fa-ban {
    border-radius: 50%;
    color: white;
    font-size: 20px;
    height:30px;
    line-height: 0;
    position: relative;
    top: 5px!important;
    padding-top: 15px;
    padding-left: 5px;
    padding-right: 5px;
    width:30px;
  }
  .listado-item .voyager-check::before,
  .listado-item .voyager-x::before,
  .listado-item .fa-ban::before {
    line-height:0;
  }
  .listado-item .fa-ban {
    background:lightgray;
  }
  .listado-item .voyager-check {
    background:#4fb749;
  }
  .listado-item .voyager-x {
    background:#dd3545;
  }

  .brmn-pointer {
    cursor:pointer;
    -webkit-transition:all .25s ease-in-out;
    -moz-transition:all .25s ease-in-out;
    -ms-transition:all .25s ease-in-out;
    -o-transition:all .25s ease-in-out;
    transition:all .25s ease-in-out;
  }
  .brmn-pointer:active,
  .brmn-pointer:focus,
  .brmn-pointer:hover {
    filter:alpha(opacity=75%);
    opacity:.75;
  }
  .brmn-clickable::before {
    content:"\f0a6";
    font-family:"Font Awesome 5 Free";
    font-weight:400;
    position:absolute;
    right:10px;
  }
  .brmn-clickable.brmn-gray::before {
    color:gray;
    filter:alpha(opacity=50%);
    opacity:.5;
  }
  .brmn-clickable.brmn-red::before {
    color:#dd3545;
    filter:alpha(opacity=50%);
    opacity:.5;
  }
  .brmn-clickable.brmn-blue::before {
    color:#0a4ea1;
    filter:alpha(opacity=50%);
    opacity:.5;
  }

  .brmn-tachado {
    text-decoration:line-through!important;
  }
  .brmn-img-tachado::after {
    background-color:rgba(250,250,250,.35);
    color:#dd3545;
    content:"\f05e";
    font-family:"Font Awesome 5 Free";
    font-size:25px;
    font-weight:900;
    height:40px;
    left:0;
    position:absolute;
    top:0;
    width:40px;
  }
</style>
