<template>
  <b-row id="currentItemsList" class="bg-white border p-3">
    <!-- Selector de tipología de items -->
    <b-col cols="12" class="d-none d-sm-block text-center">
      <b-button-group>
        <b-button class="btn-tipology px-md-1 px-lg-2" variant="outline-primary" size="sm" @click.stop="onClickTipologyMacrociclos" :pressed="activeTipology['macrociclos']">Macrociclos</b-button>
        <b-button class="btn-tipology px-md-1 px-lg-2" variant="outline-primary" size="sm" @click.stop="onClickTipologyMesociclos" :pressed="activeTipology['mesociclos']">Mesociclos</b-button>
        <b-button class="btn-tipology px-md-1 px-lg-2" variant="outline-primary" size="sm" @click.stop="onClickTipologyMicrociclos" :pressed="activeTipology['microciclos']">Microciclos</b-button>
        <b-button class="btn-tipology px-md-1 px-lg-2" variant="outline-primary" size="sm" @click.stop="onClickTipologySesiones" :pressed="activeTipology['sesiones']">Sesiones</b-button>
      </b-button-group>
    </b-col>
    <b-col cols="12" class="d-block d-sm-none">
      <label label-for="selectTipology" class="mb-0"><small class="font-weight-bold">Tipo de ciclo:</small></label>
      <b-form-select v-model="selected_tipology" :options="options_tipology" id="selectTipology" class="mb-1" size="sm" @input="onClickTipologySelect"></b-form-select>
    </b-col>
    <!-- /Selector de tipología de items -->

    <!-- Selector de periodo -->
    <b-col cols="12" class="d-none d-sm-block my-2 text-center">
      <b-button-group>
        <b-button class="btn-period px-1" variant="link" size="sm" @click.stop="onClickPeriodToday" :pressed="activePeriod['today']">Hoy</b-button>
        <b-button class="btn-period px-1" variant="link" size="sm" @click.stop="onClickPeriodThisWeek" :pressed="activePeriod['week']">Esta semana</b-button>
        <b-button class="btn-period px-1" variant="link" size="sm" @click.stop="onClickPeriodThisMonth" :pressed="activePeriod['month']">Este mes</b-button>
        <b-button class="btn-period px-1" variant="link" size="sm" @click.stop="onClickPeriodThisYear" :pressed="activePeriod['year']">Este año</b-button>
      </b-button-group>
    </b-col>
    <b-col cols="12" class="d-block d-sm-none">
      <label label-for="selectPeriod" class="mb-0"><small class="font-weight-bold">Periodo:</small></label>
      <b-form-select v-model="selected_period" :options="options_period" id="selectPeriod" class="mb-4" size="sm" @input="onClickTipologyPeriod"></b-form-select>
    </b-col>
    <!-- /Selector de periodo -->

    <!-- Tabla con los elementos activos en el periodo seleccionado -->
    <b-col cols="12">
      <b-card class="tree-wrap p-0" body-class="bg-light pb-0 px-0 pt-2">
        <div v-if="loading" class="py-1 text-center w-100">
          <div class="spinner-border text-secondary" role="status"></div>
        </div>
        <div v-else>
          <v-treeview
            v-if="tree.data.length"
            v-model="tree.data"
            :treeTypes="tree.types"
            :openAll="tree.openAll"
          >
          </v-treeview>
          <p v-else class="text-center text-danger py-3"><small>No hay <span class="font-weight-bold text-capitalize">{{ selected_tipology }}</span> para <span>{{ getPeriodDesc(selected_period) }}</span>.</small></p>
        </div>
      </b-card>
    </b-col>
    <!-- /Tabla con los elementos activos en el periodo seleccionado -->

  </b-row>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import VTreeview from "v-treeview";
  import moment from 'moment';
  import functions from '../functions';

  export default {
    mixins: [ functions ],
    data() {
      return {
        activePeriod: {
          'today': true,
          'week': false,
          'month': false,
          'year': false
        },
        activeTipology: {
          'macrociclos': true,
          'mesociclos': false,
          'microciclos': false,
          'sesiones': false
        },
        ejercicios: [],
        loading: true,
        macrociclos: [],
        mesociclos: [],
        microciclos: [],
        sesiones: [],
        options_period: [
          { value: 'today', text: 'Hoy' },
          { value: 'week', text: 'Esta semana' },
          { value: 'month', text: 'Este mes' },
          { value: 'year', text: 'Este año' }
        ],
        options_tipology: [
          { value: 'macrociclos', text: 'Macrociclos'},
          { value: 'mesociclos', text: 'Mesociclos'},
          { value: 'microciclos', text: 'Microciclos'},
          { value: 'sesiones', text: 'Sesiones'}
        ],
        pelotaris: [],
        selected_period: 'today',
        selected_tipology: 'macrociclos',
        tree: {
          // https://hyounoo.github.io/v-treeview/
          openAll: false,
          types: [],
          data: [],
        }
      }
    },
    created() {
      const date = Date.now();
      this.loading = true;
      this.getActiveItemsToday(date).then( (items) => {
        this.setActiveItems(items);
        this.onClickPeriodToday();
        this.onClickTipologyMacrociclos();
        this.loading = false;
      });
    },
    methods: {
      ...mapActions({
        getActiveItemsThisMonth: 'plen/getActiveItemsThisMonth',
        getActiveItemsThisWeek: 'plen/getActiveItemsThisWeek',
        getActiveItemsThisYear: 'plen/getActiveItemsThisYear',
        getActiveItemsToday: 'plen/getActiveItemsByDate',
      }),
      deactivateAllPeriods() {
        const keys = Object.keys(this.activePeriod);
        keys.map( (val) => {
          this.activePeriod[val] = false;
        });
      },
      deactivateAllTipologies() {
        const keys = Object.keys(this.activeTipology);
        keys.map( (val) => {
          this.activeTipology[val] = false;
        });
      },
      getPeriodDesc(period) {
        let desc = '';

        switch (period) {
          case 'today':
            desc = 'hoy';
            break;
          case 'week':
            desc = 'esta semana';
            break;
          case 'month':
            desc = 'este mes';
            break;
          case 'year':
            desc = 'este año';
            break;
        }

        return desc;
      },
      loadTreeDataMacrociclos() {
        this.loadTreeTypesForMacrociclos();
        this.tree.data = [];
        this.macrociclos.map( (val) => {
          this.loadTreeDataMacrociclo(val);
        })
      },
      loadTreeDataMacrociclo(macrociclo) {
        const data = {
          id: 'macrociclo_id_' + macrociclo.id,
          text: 'MACROCICLO: ' + macrociclo.description,
          type: '00_MACROCICLO',
          count: 0,
          children: this.loadTreeDataMacrocicloChildren(macrociclo)
        }
        this.tree.data.push(data);
      },
      loadTreeDataMacrocicloChildren(macrociclo) {
        let children = [];

        children.push({
          id: 'macrociclo_fechas_' + macrociclo.id,
          text: this.getRangoFechas(macrociclo.fecha_ini, macrociclo.fecha_fin, 'long'),
          type: 'Fecha',
          count: 0,
          children: []
        });

        children.push({
          id: 'macrociclo_objetivos_' + macrociclo.id,
          text: macrociclo.objetivos,
          type: 'Objetivos',
          count: 0,
          children: []
        });

        const mesociclos = _.filter(this.mesociclos, { macrociclo_id: macrociclo.id });
        mesociclos.map( (val) => {
          children.push(this.loadTreeDataMesociclo(val));
        })

        return children;
      },
      loadTreeDataMesociclos() {
        this.loadTreeTypesForMesociclos();
        this.tree.data = [];
        this.mesociclos.map( (val) => {
          this.tree.data.push(this.loadTreeDataMesociclo(val));
        })
      },
      loadTreeDataMesociclo(mesociclo) {
        const data = {
          id: 'mesociclo_id_' + mesociclo.id,
          text: 'MESOCICLO: ' + this.getRangoFechas(mesociclo.fecha_ini, mesociclo.fecha_fin, 'short'),
          type: '01_MESOCICLO',
          count: 0,
          children: this.loadTreeDataMesocicloChildren(mesociclo)
        }
        return data;
      },
      loadTreeDataMesocicloChildren(mesociclo) {
        let children = [];

        // children.push({
        //   id: 'mesociclo_fechas_' + mesociclo.id,
        //   text: this.getRangoFechas(mesociclo.fecha_ini, mesociclo.fecha_fin, 'long'),
        //   type: 'Fecha',
        //   count: 0,
        //   children: []
        // });

        if( mesociclo.tipo_mesociclo ) {
          children.push({
            id: 'mesociclo_tipo_' + mesociclo.id,
            text: mesociclo.tipo_mesociclo,
            type: 'Tipo',
            count: 0,
            children: []
          });
        }

        if( mesociclo.description && mesociclo.description.length ) {
          children.push({
            id: 'mesociclo_descripcion_' + mesociclo.id,
            text: 'Descripción',
            type: 'Descripcion',
            count: 0,
            children: [{
              id: 'mesociclo_descripcion_txt_' + mesociclo.id,
              text: mesociclo.description,
              type: 'Basic',
              count: 0,
              children: []
            }]
          });
        }

        if( mesociclo.objetivos && mesociclo.objetivos.length ) {
          children.push({
            id: 'mesociclo_objetivos_' + mesociclo.id,
            text: 'Objetivos',
            type: 'Objetivos',
            count: 0,
            children: [{
              id: 'mesociclo_objetivos_txt_' + mesociclo.id,
              text: mesociclo.objetivos,
              type: 'Basic',
              count: 0,
              children: []
            }]
          });
        }

        const microciclos = _.filter(this.microciclos, { mesociclo_id: mesociclo.id });
        microciclos.map( (val) => {
          children.push(this.loadTreeDataMicrociclo(val));
        })

        return children;
      },
      loadTreeDataMicrociclos() {
        this.loadTreeTypesForMicrociclos();
        this.tree.data = [];
        this.microciclos.map( (val) => {
          this.tree.data.push(this.loadTreeDataMicrociclo(val));
        })
      },
      loadTreeDataMicrociclo(microciclo) {
        const data = {
          id: 'microciclo_id_' + microciclo.id,
          text: 'MICROCICLO: ' + this.getRangoFechas(microciclo.fecha_ini, microciclo.fecha_fin, 'short'),
          type: '02_MICROCICLO',
          count: 0,
          children: this.loadTreeDataMicrocicloChildren(microciclo)
        }
        return data;
      },
      loadTreeDataMicrocicloChildren(microciclo) {
        let children = [];

        // children.push({
        //   id: 'microciclo_fechas_' + microciclo.id,
        //   text: this.getRangoFechas(microciclo.fecha_ini, microciclo.fecha_fin, 'long'),
        //   type: 'Fecha',
        //   count: 0,
        //   children: []
        // });

        if( microciclo.tipo_microciclo ) {
          children.push({
            id: 'microciclo_tipo_' + microciclo.id,
            text: microciclo.tipo_microciclo,
            type: 'Tipo',
            count: 0,
            children: []
          });
        }

        if( microciclo.description && microciclo.description.length ) {
          children.push({
            id: 'microciclo_descripcion_' + microciclo.id,
            text: 'Descripción',
            type: 'Descripcion',
            count: 0,
            children: [{
              id: 'microciclo_descripcion_txt_' + microciclo.id,
              text: microciclo.description,
              type: 'Basic',
              count: 0,
              children: []
            }]
          });
        }

        if( microciclo.objetivos && microciclo.objetivos.length ) {
          children.push({
            id: 'microciclo_objetivos_' + microciclo.id,
            text: 'Objetivos',
            type: 'Objetivos',
            count: 0,
            children: [{
              id: 'microciclo_objetivos_txt_' + microciclo.id,
              text: microciclo.objetivos,
              type: 'Basic',
              count: 0,
              children: []
            }]
          });
        }

        if( microciclo.volumen ) {
          children.push({
            id: 'microciclo_volumen_' + microciclo.id,
            text: 'Vol.: ' + microciclo.volumen,
            type: 'Volumen',
            count: 0,
            children: []
          });
        }

        if( microciclo.intensidad ) {
          children.push({
            id: 'microciclo_intensidad_' + microciclo.id,
            text: 'Int.: ' + microciclo.intensidad,
            type: 'Intensidad',
            count: 0,
            children: []
          });
        }

        const sesiones = _.filter(this.sesiones, { microciclo_id: microciclo.id });
        sesiones.map( (val) => {
          children.push(this.loadTreeDataSesion(val));
        })

        return children;
      },
      loadTreeDataSesiones() {
        this.loadTreeTypesForSesiones();
        this.tree.data = [];
        this.sesiones.map( (val) => {
          this.tree.data.push(this.loadTreeDataSesion(val));
        })
      },
      loadTreeDataSesion(sesion) {
        const data = {
          id: 'sesion_id_' + sesion.id,
          text: 'SESIÓN: ' + this.getFechaES(sesion.fecha, 'short'),
          type: '03_SESION',
          count: 0,
          children: this.loadTreeDataSesionChildren(sesion)
        }
        return data;
      },
      loadTreeDataSesionChildren(sesion) {
        let children = [];

        // children.push({
        //   id: 'sesion_fecha_' + sesion.id,
        //   text: this.getFechaES(sesion.fecha),
        //   type: 'Fecha',
        //   count: 0,
        //   children: []
        // });

        if( sesion.hora ) {
          children.push({
            id: 'sesion_hora_' + sesion.id,
            text: sesion.hora.substr(0, 5),
            type: 'Hora',
            count: 0,
            children: []
          });
        }

        if( sesion.fronton_id ) {
          children.push({
            id: 'sesion_fronton_' + sesion.id,
            text: sesion.fronton,
            type: 'Fronton',
            count: 0,
            children: []
          });
        }

        const pelotaris = _.filter(this.pelotaris, { sesion_id: sesion.id });
        if( pelotaris.length) {
          var pelotaris_children = [];

          pelotaris.map( (val) => {
            pelotaris_children.push(this.loadTreeDataPelotari(val));
          });

          children.push({
            id: 'pelotaris_sesion_id_' + sesion.id,
            text: 'PELOTARIS',
            type: '04_PELOTARI',
            count: 0,
            children: pelotaris_children
          });
        }

        return children;
      },
      loadTreeDataPelotari(pelotari) {
        const data = {
          id: 'pelotari_alias_' + pelotari.id,
          text: pelotari.alias,
          type: 'Alias',
          count: 0,
          children: []
        };

        const ejercicios = _.filter(this.ejercicios, { sesion_pelotari_id: pelotari.id });

        ejercicios.map( (val) => {
          data.children.push({
            id: 'pelotari_ejercicio_' + val.id,
            text: val.name,
            type: '05_EJERCICIO',
            count: 0,
            children: []
          });
        })

        return data;
      },
      loadTreeTypesForMacrociclos() {
        this.tree.types = [
            {
              type: "#",
              max_children: 999,
              max_depth: 4,
              valid_children: [
                "00_MACROCICLO"
              ]
            },
            {
              type: "00_MACROCICLO",
              icon: "fas fa-arrows-alt-h",
              valid_children: ["Fecha", "Objetivos", "01_MESOCICLO"]
            },
            {
              type: "01_MESOCICLO",
              icon: "fas fa-exchange-alt",
              valid_children: ["Fecha", "Tipo", "Descripcion", "Objetivos", "02_MICROCICLO"]
            },
            {
              type: "02_MICROCICLO",
              icon: "fa fa-arrows-alt",
              valid_children: ["Fecha", "Tipo", "Descripcion", "Objetivos", "Volumen", "Intensidad", "03_SESION"]
            },
            {
              type: "03_SESION",
              icon: "fas fa-stopwatch",
              valid_children: ["Fecha", "Hora", "Fronton", "04_PELOTARI"]
            },
            {
              type: "04_PELOTARI",
              icon: "fa fa-users",
              valid_children: ["Alias", "05_EJERCICIO"]
            },
            {
              type: "05_EJERCICIO",
              icon: "fa fa-chevron-right",
              valid_children: ["Fase", "Descripcion", "Volumen", "Intensidad"]
            },
            {
              type: "Alias",
              icon: "fa fa-user-circle",
              valid_children: []
            },
            {
              type: "Basic",
              // icon: "far fa-circle",
              valid_children: []
            },
            {
              type: "Descripcion",
              icon: "far fa-file-alt",
              valid_children: []
            },
            {
              type: "Fecha",
              icon: "fa fa-calendar",
              valid_children: []
            },
            {
              type: "Fronton",
              icon: "fas fa-map-marker-alt",
              valid_children: []
            },
            {
              type: "Hora",
              icon: "far fa-clock",
              valid_children: []
            },
            {
              type: "Intensidad",
              icon: "fas fa-tachometer-alt",
              valid_children: []
            },
            {
              type: "Objetivos",
              icon: "fa fa-bullseye",
              valid_children: ["Basic"]
            },
            {
              type: "Tipo",
              icon: "fas fa-tag",
              valid_children: []
            },
            {
              type: "Volumen",
              icon: "fas fa-sort-amount-up",
              valid_children: []
            }
        ]
      },
      loadTreeTypesForMesociclos() {
        this.tree.types = [
            {
              type: "#",
              max_children: 999,
              max_depth: 4,
              valid_children: [
                "01_MESOCICLO"
              ]
            },
            {
              type: "01_MESOCICLO",
              icon: "fas fa-exchange-alt",
              valid_children: ["Fecha", "Tipo", "Descripcion", "Objetivos", "02_MICROCICLO"]
            },
            {
              type: "02_MICROCICLO",
              icon: "fa fa-arrows-alt",
              valid_children: ["Fecha", "Tipo", "Descripcion", "Objetivos", "Volumen", "Intensidad", "03_SESION"]
            },
            {
              type: "03_SESION",
              icon: "fas fa-stopwatch",
              valid_children: ["Fecha", "Hora", "Fronton", "04_PELOTARI"]
            },
            {
              type: "04_PELOTARI",
              icon: "fa fa-users",
              valid_children: ["Alias", "05_EJERCICIO"]
            },
            {
              type: "05_EJERCICIO",
              icon: "fa fa-chevron-right",
              valid_children: ["Fase", "Descripcion", "Volumen", "Intensidad"]
            },
            {
              type: "Alias",
              icon: "fa fa-user-circle",
              valid_children: []
            },
            {
              type: "Basic",
              // icon: "far fa-circle",
              valid_children: []
            },
            {
              type: "Descripcion",
              icon: "far fa-file-alt",
              valid_children: []
            },
            {
              type: "Fecha",
              icon: "fa fa-calendar",
              valid_children: []
            },
            {
              type: "Fronton",
              icon: "fas fa-map-marker-alt",
              valid_children: []
            },
            {
              type: "Hora",
              icon: "far fa-clock",
              valid_children: []
            },
            {
              type: "Intensidad",
              icon: "fas fa-tachometer-alt",
              valid_children: []
            },
            {
              type: "Objetivos",
              icon: "fa fa-bullseye",
              valid_children: ["Basic"]
            },
            {
              type: "Tipo",
              icon: "fas fa-tag",
              valid_children: []
            },
            {
              type: "Volumen",
              icon: "fas fa-sort-amount-up",
              valid_children: []
            }
        ]
      },
      loadTreeTypesForMicrociclos() {
        this.tree.types = [
            {
              type: "#",
              max_children: 999,
              max_depth: 4,
              valid_children: [
                "02_MICROCICLO"
              ]
            },
            {
              type: "02_MICROCICLO",
              icon: "fa fa-arrows-alt",
              valid_children: ["Fecha", "Tipo", "Descripcion", "Objetivos", "Volumen", "Intensidad", "03_SESION"]
            },
            {
              type: "03_SESION",
              icon: "fas fa-stopwatch",
              valid_children: ["Fecha", "Hora", "Fronton", "04_PELOTARI"]
            },
            {
              type: "04_PELOTARI",
              icon: "fa fa-users",
              valid_children: ["Alias", "05_EJERCICIO"]
            },
            {
              type: "05_EJERCICIO",
              icon: "fa fa-chevron-right",
              valid_children: ["Fase", "Descripcion", "Volumen", "Intensidad"]
            },
            {
              type: "Alias",
              icon: "fa fa-user-circle",
              valid_children: []
            },
            {
              type: "Basic",
              // icon: "far fa-circle",
              valid_children: []
            },
            {
              type: "Descripcion",
              icon: "far fa-file-alt",
              valid_children: []
            },
            {
              type: "Fecha",
              icon: "fa fa-calendar",
              valid_children: []
            },
            {
              type: "Fronton",
              icon: "fas fa-map-marker-alt",
              valid_children: []
            },
            {
              type: "Hora",
              icon: "far fa-clock",
              valid_children: []
            },
            {
              type: "Intensidad",
              icon: "fas fa-tachometer-alt",
              valid_children: []
            },
            {
              type: "Objetivos",
              icon: "fa fa-bullseye",
              valid_children: ["Basic"]
            },
            {
              type: "Tipo",
              icon: "fas fa-tag",
              valid_children: []
            },
            {
              type: "Volumen",
              icon: "fas fa-sort-amount-up",
              valid_children: []
            }
        ]
      },
      loadTreeTypesForSesiones() {
        this.tree.types = [
            {
              type: "#",
              max_children: 999,
              max_depth: 4,
              valid_children: [
                "03_SESION"
              ]
            },
            {
              type: "03_SESION",
              icon: "fas fa-stopwatch",
              valid_children: ["Fecha", "Hora", "Fronton", "04_PELOTARI"]
            },
            {
              type: "04_PELOTARI",
              icon: "fa fa-users",
              valid_children: ["Alias", "05_EJERCICIO"]
            },
            {
              type: "05_EJERCICIO",
              icon: "fa fa-chevron-right",
              valid_children: ["Fase", "Descripcion", "Volumen", "Intensidad"]
            },
            {
              type: "Alias",
              icon: "fa fa-user-circle",
              valid_children: []
            },
            {
              type: "Basic",
              // icon: "far fa-circle",
              valid_children: []
            },
            {
              type: "Descripcion",
              icon: "far fa-file-alt",
              valid_children: []
            },
            {
              type: "Fecha",
              icon: "fa fa-calendar",
              valid_children: []
            },
            {
              type: "Fronton",
              icon: "fas fa-map-marker-alt",
              valid_children: []
            },
            {
              type: "Hora",
              icon: "far fa-clock",
              valid_children: []
            },
            {
              type: "Intensidad",
              icon: "fas fa-tachometer-alt",
              valid_children: []
            },
            {
              type: "Volumen",
              icon: "fas fa-sort-amount-up",
              valid_children: []
            }
        ]
      },
      onClickTipologyPeriod(period) {
        switch (period) {
          case 'today':
            this.onClickPeriodToday();
            break;
          case 'week':
            this.onClickPeriodThisWeek();
            break;
          case 'month':
            this.onClickPeriodThisMonth();
            break;
          case 'year':
            this.onClickPeriodThisYear();
            break;
        }
      },
      onClickPeriodThisMonth() {
        if (!this.activeTipology['month']) {
          this.loading = true;
          this.getActiveItemsThisMonth(Date.now()).then( (items) => {
            this.setActiveItems(items);
            this.setActivePeriod('month');
            this.onClickTipologySelect(this.selected_tipology);
            this.loading = false;
          });
        }
      },
      onClickPeriodThisWeek() {
        if (!this.activeTipology['week']) {
          this.loading = true;
          this.getActiveItemsThisWeek(Date.now()).then( (items) => {
            this.setActiveItems(items);
            this.setActivePeriod('week');
            this.onClickTipologySelect(this.selected_tipology);
            this.loading = false;
          });
        }
      },
      onClickPeriodThisYear() {
        if (!this.activeTipology['year']) {
          this.loading = true;
          this.getActiveItemsThisYear(Date.now()).then( (items) => {

            // Mostramos solo datos del año en curso, aunque de la API recibimos datos de los años anterior y posterior
            const currentYear = new Date().getFullYear();
            items.sesiones = _.filter(items.sesiones, (sesion) => {
              return parseInt(sesion.fecha.substr(0,4)) === currentYear;
            });
            items.microciclos = _.filter(items.microciclos, (microciclo) => {
              return parseInt(microciclo.fecha_ini.substr(0,4)) === currentYear;
            });
            items.mesociclos = _.filter(items.mesociclos, (mesociclo) => {
              return parseInt(mesociclo.fecha_ini.substr(0,4)) === currentYear;
            });
            items.macrociclos = _.filter(items.macrociclos, (macrociclo) => {
              return parseInt(macrociclo.fecha_ini.substr(0,4)) === currentYear;
            });

            this.setActiveItems(items);
            this.setActivePeriod('year');
            this.onClickTipologySelect(this.selected_tipology);
            this.loading = false;
          });
        }
      },
      onClickPeriodToday() {
        if (!this.activeTipology['today']) {
          this.loading = true;
          this.getActiveItemsToday(Date.now()).then( (items) => {
            this.setActiveItems(items);
            this.setActivePeriod('today');
            this.onClickTipologySelect(this.selected_tipology);
            this.loading = false;
          });
        }
      },
      onClickTipologySelect(tipology) {
        switch (tipology) {
          case 'macrociclos':
            this.onClickTipologyMacrociclos();
            break;
          case 'mesociclos':
            this.onClickTipologyMesociclos();
            break;
          case 'microciclos':
            this.onClickTipologyMicrociclos();
            break;
          case 'sesiones':
            this.onClickTipologySesiones();
            break;
        }
      },
      onClickTipologyMacrociclos() {
        this.setActiveTipology('macrociclos');
        this.loadTreeDataMacrociclos();
      },
      onClickTipologyMesociclos() {
        this.setActiveTipology('mesociclos');
        this.loadTreeDataMesociclos();
      },
      onClickTipologyMicrociclos() {
        this.setActiveTipology('microciclos');
        this.loadTreeDataMicrociclos();
      },
      onClickTipologySesiones() {
        this.setActiveTipology('sesiones');
        this.loadTreeDataSesiones();
      },
      setActiveItems(items) {
        this.macrociclos = items.macrociclos;
        this.mesociclos = items.mesociclos;
        this.microciclos = items.microciclos;
        this.sesiones = items.sesiones;
        this.pelotaris = items.pelotaris;
        this.ejercicios = items.ejercicios;
      },
      setActivePeriod(period) {
        this.deactivateAllPeriods();
        this.selected_period = period;
        this.activePeriod[period] = true;
      },
      setActiveTipology(tipology) {
        this.deactivateAllTipologies();
        this.selected_tipology = tipology;
        this.activeTipology[tipology] = true;
      },
    },
    components: {
      VTreeview
    }
  }
</script>

<style>
  #currentItemsList .btn-tipology:not(.active) {
    border-color:gray;
    color:gray;
  }
  #currentItemsList .btn-tipology:active,
  #currentItemsList .btn-tipology:focus,
  #currentItemsList .btn-tipology:hover {
    color:white;
  }
  #currentItemsList .btn-period:focus,
  #currentItemsList .btn-tipology:focus {
    -webkit-box-shadow: none;
    box-shadow: none;
  }
  #currentItemsList .btn-period:active,
  #currentItemsList .btn-period:focus,
  #currentItemsList .btn-period:hover {
    font-weight:100;
    text-decoration:none;
  }
  #currentItemsList .btn-period:not(:first-child)::before {
    content: "|";
    font-weight:normal!important;
    padding-right: 0.5rem;
  }
  #currentItemsList .btn-period.active {
    font-weight:bold;
  }
  #currentItemsList .btn-period:not(.active) {
    color:gray;
  }
  #currentItemsList .tree-wrap ul {
    font-size:12px;
    padding-left:0;
  }
  #currentItemsList .tree-wrap ul label {
    padding-left:0.25rem;
    vertical-align:text-top;
  }
  #currentItemsList .tree-wrap ul label[for*="descripcion_txt"],
  #currentItemsList .tree-wrap ul label[for*="objetivos_txt"] {
    width:80%;
  }
  #currentItemsList label[for^="macrociclo_id_"],
  #currentItemsList label[for^="mesociclo_id_"],
  #currentItemsList label[for^="microciclo_id_"],
  #currentItemsList label[for^="sesion_id_"],
  #currentItemsList label[for^="pelotaris_sesion_id_"] {
    font-weight:bold;
  }
</style>
