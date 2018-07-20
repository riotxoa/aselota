<template>
  <div class="listado-item mb-4">

    <b-row class="m-0 mb-2 position-relative">
      <div class="block partido col-sm-3 col-lg-2 text-center font-weight-bold">
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
      <div class="toolbar col-sm-3 col-lg-2 text-right position-absolute">
        <b-button size="sm" variant="outline-danger" @click.stop="onClickDelete(partido)" title="Eliminar Partido">
          <span class="icon voyager-trash"></span>
        </b-button>
        <b-button size="sm" variant="outline-primary" @click.stop="onClickEdit(partido)" title="Editar Partido">
          <span class="icon voyager-edit"></span>
        </b-button>
      </div>
    </b-row>

    <b-row class="title-wrap my-1">
      <div class="title col-4 p-0 font-weight-bold ml-md-3"><div class="tanteo rojo d-inline-block">22</div><div class="d-inline-block px-3">Pelotaris</div></div>
      <div class="title coste rojo col-2 p-0 text-center font-weight-bold">Coste</div>
      <div class="title col-4 p-0 font-weight-bold ml-md-1 ml-lg-3"><div class="tanteo azul d-inline-block">20</div><div class="d-inline-block px-3 font-weight-bold">Pelotaris</div></div>
      <div class="title coste azul col-2 p-0 text-center font-weight-bold">Coste</div>
    </b-row>

    <b-row class="mb-1">

      <div class="equipo rojo col-4 p-0 ml-md-3">
        <div class="pelotari-foto d-none d-sm-inline-block">
          <img v-if="data.pelotari_1" :src="data.pelotari_1.foto" />
        </div>
        <div class="pelotari-name d-inline-block text-center text-uppercase px-3">
          <span v-if="data.pelotari_1">{{ this.data.pelotari_1.alias }}</span>
        </div>
      </div>
      <div class="col-2 coste rojo text-center">
        <span v-if="data.pelotari_1">100.00{{ this.data.pelotari_1.coste }}&nbsp;&euro;</span>
      </div>

      <div class="equipo azul col-4 p-0 ml-md-1 ml-lg-3">
        <div class="pelotari-foto d-none d-sm-inline-block">
          <img v-if="data.pelotari_3" :src="data.pelotari_3.foto" />
        </div>
        <div class="pelotari-name d-inline-block text-center text-uppercase px-3">
          <span v-if="data.pelotari_3">{{ this.data.pelotari_3.alias }}</span>
        </div>
      </div>
      <div class="col-2 coste azul text-center">
        <span v-if="data.pelotari_3">100.00{{ this.data.pelotari_3.coste }}&nbsp;&euro;</span>
      </div>

    </b-row>

    <b-row v-if="data.is_partido_parejas">

      <div class="equipo rojo col-4 p-0 ml-md-3">
        <div class="pelotari-foto d-none d-sm-inline-block">
          <img v-if="data.pelotari_2" :src="data.pelotari_2.foto" />
        </div>
        <div class="pelotari-name d-inline-block text-center text-uppercase px-3">
          <span v-if="data.pelotari_2">{{ this.data.pelotari_2.alias }}</span>
        </div>
      </div>
      <div class="col-2 coste rojo text-center">
        <span v-if="data.pelotari_2">100.00{{ this.data.pelotari_2.coste }}&nbsp;&euro;</span>
      </div>

      <div class="equipo azul col-4 p-0 ml-md-1 ml-lg-3">
        <div class="pelotari-foto d-none d-sm-inline-block">
          <img v-if="data.pelotari_4" :src="data.pelotari_4.foto" />
        </div>
        <div class="pelotari-name d-inline-block text-center text-uppercase px-3">
          <span v-if="data.pelotari_4">{{ this.data.pelotari_4.alias }}</span>
        </div>
      </div>
      <div class="col-2 coste azul text-center">
        <span v-if="data.pelotari_4">100.00{{ this.data.pelotari_4.coste }}&nbsp;&euro;</span>
      </div>

    </b-row>

    <b-modal class="modalEditPartido" :id="modalID" :ref="modalID" size="lg" hideFooter lazy>
      <form-partido :edit="true" :festival-header="festivalHeader" :partido="partido" :modal-id="modalID" v-on:update-partido="updatePartido($event)"></form-partido>
    </b-modal>

  </div>
</template>

<script>
  Vue.component('form-partido', require('../partidos/FichaComponent.vue'));

  export default {
    props: ['festivalHeader', 'partido'],
    data () {
      return {
        data: null,
        modalID: '',
      }
    },
    created: function () {
      console.log("ListadoItemComponent created");
      this.data = this.partido;
      this.modalID = "modalEditPartido-" + this.data.id;
    },
    methods: {
      onClickEdit(p) {
        this.$root.$emit('bv::show::modal', this.modalID);
      },
      onClickDelete(p) {
        this.$emit('delete-partido', p.id);
      },
      updatePartido(p) {
        this.data.orden = p.orden;
        this.data.estelar = p.estelar;
        if( p.campeonato_id !== this.data.campeonato_id ) {
          this.data.campeonato_id = p.campeonato_id;
          this.data.campeonato_name = p.campeonato_name;
        }
        if( p.tipo_partido_id !== this.data.tipo_partido_id ) {
          this.data.tipo_partido_id = p.tipo_partido_id;
          this.data.tipo_partido_name = p.tipo_partido_name;
        }
        this.data.fase = p.fase;
        this.data.pelotari_1 = p.pelotari_1;
        this.data.pelotari_2 = p.pelotari_2;
        this.data.pelotari_3 = p.pelotari_3;
        this.data.pelotari_4 = p.pelotari_4;
      }
    }
  }
</script>

<style>
  .voyager-star-two {
    bottom: -2px;
    color:darkorange;
    left:15px;
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
  .modalEditPartido .modal-dialog {
    margin-top:10%;
    max-width:1080px;
    width:85%;
  }
</style>
