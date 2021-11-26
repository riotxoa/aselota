<template>
  <div class="sesion-designer-wrap">
    <header class="bg-primary font-weight-bold header py-2 text-center text-uppercase text-white">
      <b-row  align-v="center">
        <b-col cols="10" offset="1">
          <h5 class="m-0">Diseñador de Sesiones de Entrenamientos</h5>
        </b-col>
        <b-col cols="1" class="text-right">
          <b-button class="text-white-50" variant="link" v-on:click="onClickCancel">X</b-button>
        </b-col>
      </b-row>
    </header>
    <div class="body p-relative w-100">
      <b-row class="mt-3 mx-4">
        <b-col cols="12">
          <strong class="d-inline-block" style="width:100px;">Fecha:</strong>&nbsp;{{ getFechaES(sesion.fecha) }}
          <strong class="d-inline-block text-right" style="width:100px;">Hora:</strong>&nbsp;{{ (sesion.hora ? sesion.hora.substr(0,5) : '-') }}
        </b-col>
        <b-col cols="12">
          <strong class="d-inline-block" style="width:100px;">Frontón:</strong>&nbsp;{{ getFrontonName(sesion.fronton_id) }}
        </b-col>
      </b-row>
      <b-row class="mt-3 pb-3 px-5">
        <b-col cols="3" class="bg-light draggable">
          <SDDraggable title="Pelotaris" :list="listPelotaris" :group="{ name: 'items', type: 'pelotari', pull: true, put: false}" />
        </b-col>
        <b-col cols="4" class="bg-light draggable">
          <SDDraggable title="Ejercicios" :list="listEjercicios" :group="{ name: 'items', type: 'ejercicio', pull: 'clone', put: false}" />
        </b-col>
        <b-col cols="5" class="bg-light draggable">
          <h6 class="font-weight-bold mb-4 text-center text-uppercase">Sesión</h6>
          <SDDraggable v-model="listSesion" :group="{ name: 'items', type: 'sesion', pull: true, put: true}" class="sesion" />
        </b-col>
      </b-row>
    </div>
    <footer class="border-top footer pt-3 px-5">
      <b-row>
        <b-col cols="12" class="text-right">
          <b-button v-on:click="onClickCancel" variant="secondary">Cancelar</b-button>
          <b-button v-on:click="onClickSave" variant="danger" class="ml-2">Asignar</b-button>
        </b-col>
      </b-row>
    </footer>
  </div>
</template>

<script>
  import { mapState } from 'vuex';

  import functions from '../../../common/functions';

  import SDDraggable from './SesionDesignerDraggable';

  export default {
    props: [ 'pelotaris', 'frontones' ],
    mixins: [ functions ],
    data() {
      return {
        filterPelotaris: '',
        listEjercicios: [
          { id: 1, name: "Ejercicio 1", type: "ejercicio" },
          { id: 2, name: "Ejercicio 2", type: "ejercicio" },
          { id: 3, name: "Ejercicio 3", type: "ejercicio" },
          { id: 4, name: "Ejercicio 4", type: "ejercicio" }
        ],
        listPelotaris: [
          { id: 1, name: "John", type: "pelotari", elements: [], collapsed: false },
          { id: 2, name: "Joao", type: "pelotari", elements: [], collapsed: false },
          { id: 3, name: "Jean", type: "pelotari", elements: [], collapsed: false },
          { id: 4, name: "Gerard", type: "pelotari", elements: [], collapsed: false }
        ],
        workingList: [],
      }
    },
    computed: {
      listSesion: {
        get() {
          return this.workingList;
        },
        set(value) {
          this.workingList = value;
        }
      },
      ...mapState({
        ejercicios: state => state.plen.ejercicios,
        fases: state => state.plen.fases,
        sesion: state => state.plen.sesion,
      }),
    },
    created() {
      this.loadSesion();

      this.listPelotaris = [];

      _.sortBy(this.pelotaris, 'alias');

      this.pelotaris.map( (val) => {
        const indexPelotari = _.findIndex( this.listSesion, { id: val.id, type: 'pelotari' } );
        if( indexPelotari < 0 ) {
          this.listPelotaris.push({
            type: 'pelotari',
            id: val.id,
            name: val.alias,
            elements: [],
            collapsed: false
          })
        }
      } );

      this.listEjercicios = [];
      this.ejercicios.map( (val) => {
        this.listEjercicios.push({
          type: 'ejercicio',
          id: val.id,
          tipo_ejercicio_id: val.tipo_ejercicio_id,
          subtipo_ejercicio_id: val.subtipo_ejercicio_id,
          name: val.name
        })
      });

      // this.$root.$on('sd-restore-pelotari', this.restorePelotari);
    },
    methods: {
      getFrontonName(id) {
        if( !id ) {
          return '-';
        }
        const fronton = _.find(this.frontones, { 'value': id });
        return (fronton ? fronton.text : '-');
      },
      initializeFases() {
        let fases = [];

        _.orderBy(this.fases, ['order', 'asc']);

        this.fases.map( (fase) => {
          fases.push({
            id: fase.id,
            name: fase.desc,
            elements: [],
            type: 'fase',
            collapsed: false
          });
        });

        return fases;
      },
      loadSesion() {
        let itemEjercicio = {};
        let itemFase = {};
        let itemPelotari = {};
        let list = [];

        this.sesion.pelotaris.map( (pelotari, indexPelotari) => {
          itemPelotari = {
            id: pelotari.id,
            name: pelotari.alias,
            elements: this.initializeFases(),
            type: 'pelotari',
            collapsed: true
          };

          list.push(itemPelotari);

          pelotari.ejercicios.map( (ejercicio) => {
            let indexFase = _.findIndex(list[indexPelotari].elements, { id: ejercicio.fase_sesion_id, type: 'fase' });

            itemEjercicio = {
              id: ejercicio.ejercicio_id,
              name: ejercicio.name,
              tipo_ejercicio_id: ejercicio.tipo_ejercicio_id,
              subtipo_ejercicio_id: ejercicio.subtipo_ejercicio_id,
              order: ejercicio.order,
              type: 'ejercicio'
            }
            list[indexPelotari].elements[indexFase].elements.push(itemEjercicio);
          })
        } );

        this.listSesion = list;
      },
      onClickCancel() {
        this.$root.$emit('hideFormSesionDesigner');
      },
      onClickSave() {
        let index_ejercicios = null;
        let index_pelotaris = null;
        let item = {};
        let new_ejercicio = null;
        let new_fase = null;
        let new_pelotari = null;
        let order = null;

        this.listSesion.map( (pelotari) => {
          index_pelotaris = _.findIndex(this.sesion.pelotaris, { id: pelotari.id });
          new_pelotari = _.find(this.pelotaris, { id: pelotari.id });
          order = 10;

          if( index_pelotaris < 0 ) {
            new_pelotari.new = true;
            this.sesion.pelotaris.push(new_pelotari);
            index_pelotaris = this.sesion.pelotaris.length - 1;
          } else {
            new_pelotari.new = false;
          }

          new_pelotari.ejercicios = [];

          pelotari.elements.map( (fase) => {
            new_fase = _.find(this.fases, { id: fase.id });

            fase.elements.map( (ejercicio, index) => {
              new_ejercicio = _.find(this.ejercicios, { id: ejercicio.id });

              item = {
                tipo_ejercicio_id: new_ejercicio.tipo_ejercicio_id,
                subtipo_ejercicio_id: new_ejercicio.subtipo_ejercicio_id,
                ejercicio_id: new_ejercicio.id,
                name: new_ejercicio.name,
                order: order,
                fase_sesion_id: fase.id,
                fase_desc: (new_fase ? new_fase.desc : ''),
                intensidad: 0,
                volumen: 0
              }

              order += 10;
              // this.sesion.pelotaris[index_pelotaris].ejercicios.push(item);
              // this.sesion.pelotaris[index_pelotaris].ejercicios = _.orderBy(this.sesion.pelotaris[index_pelotaris].ejercicios, ['order', 'asc']);
              new_pelotari.ejercicios.push(item);
              new_pelotari.ejercicios = _.orderBy(new_pelotari.ejercicios, ['order', 'asc']);
            } );
          } );

          this.sesion.pelotaris[index_pelotaris] = new_pelotari;
        } );
        this.$root.$emit('hideFormSesionDesigner');
      },
      // restorePelotari(pelotari_id) {
      //   const pelotari = _.find(this.pelotaris, { id: pelotari_id });
      //   console.log('[restorePelotari][' + pelotari_id + '] pelotari: ' + JSON.stringify(pelotari))
      //   this.listPelotaris.push({
      //     type: 'pelotari',
      //     id: pelotari.id,
      //     name: pelotari.alias,
      //     elements: []
      //   });
      //   this.listPelotaris = _.sortBy(this.listPelotaris, 'name');
      //   console.log('[restorePelotari][' + pelotari_id + '] this.listPelotaris:', this.listPelotaris);
      // },
    },
    components: {
      SDDraggable
    }
  }
</script>

<style scoped>
  .sesion-designer-wrap .body {
    background-color:white;
    height:calc(100% - 6.5rem);
    overflow-x:hidden;
    overflow-y:auto;
  }
  .sesion-designer-wrap .border {
    border-radius: 0;
    border-style: dotted!important;
    border-width: 2px!important;
  }
  .sesion-designer-wrap .draggable {
    border:5px solid white;
    min-height:400px;
    padding:1rem 1.5rem;
  }
  .sesion-designer-wrap .sesion {
    min-height:250px;
  }
</style>
