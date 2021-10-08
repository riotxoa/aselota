<template>
  <div class="py-5">
    <div :id="visualization_id"></div>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import APIGetters from '../../../utils/getters.js';
  import { Timeline } from 'vis-timeline/standalone';
  import moment from 'moment';

  export default {
    props: ['item'],
    mixins: [ APIGetters ],
    data() {
      return {
        editMesociclo: {
          item: null,
          callback: null
        },
        editMicrociclo: {
          item: null,
          callback: null
        },
        editSesion: {
          item: null,
          callback: null
        },
        visualization_id: "visualization_",
        time_groups: [
          { id: 0, content: 'Macrociclo' , style: 'height:15px' },
          { id: 1, content: 'Mesociclos' , style: 'height:15px' },
          { id: 2, content: 'Microciclos', style: 'height:15px' },
          { id: 3, content: 'Sesiones'   , style: 'height:15px' },
        ],
        time_items: [],
        time_options: {
          orientation: 'top', //Add the timeline to the top
          itemsAlwaysDraggable: false, // Dont have to click for moving entries around
          clickToUse: false,
          editable: {
            add: true,
            remove: true,
            updateTime: true,
          },
          format: {
            minorLabels: {
              week:       '',
            },
            majorLabels: {
              week:       'MMMM',
            }
          },
          locale: 'es',
          margin: {
            axis: 0,
            item: {
              horizontal: 0,
              vertical: 0
            }
          },
          onAdd: this.onItemAdd,
          onMove: this.onItemMove,
          onMoving: this.onItemMoving,
          onRemove: this.onItemRemove,
          onUpdate: this.onItemUpdate,
          showWeekScale: false,
          showMinorLabels: false,
          timeAxis: {
            scale: 'week',
            step: 1,
          },
          tooltipOnItemUpdateTime: {
            template: function (item) {
              return "Inicio: " + moment(item.start).format('DD-MM-YYYY') + "<br>Fin: " + moment(item.end).format('DD-MM-YYYY');
            },
          },
          type: 'range',
          start: moment(this.item.fecha_ini).subtract(2, 'weeks').startOf('week'),
          end: moment(this.item.fecha_fin).add(2, 'weeks').endOf('week')
        },
        timeline: null,
        tiposMesociclo: [],
        tiposMicrociclo: [],
        show: false,
      }
    },
    watch: {
      show: function( newShow, oldShow ) {
        if( newShow ) {
          this.loadTimeline();
        }
      }
    },
    computed: mapState({
      macrociclo: state => state.plen.macrociclo,
      mesociclo:  state => state.plen.mesociclo,
      microciclo: state => state.plen.microciclo,
      sesion:     state => state.plen.sesion,
    }),
    created() {
      this.getFrontones();
      this.visualization_id = "visualization_" + this.item.id;
      this.getTiposMesociclo().then( (tMeso) => {
        this.getTiposMicrociclo().then( (tMicro) => {
          this.setTiposMicrociclo(tMicro);
          this.setTiposMesociclo(tMeso);
          this.show = true;
        })
      })
    },
    methods: {
      ...mapActions({
        deleteMesociclo: 'plen/deleteMesociclo',
        deleteMicrociclo: 'plen/deleteMicrociclo',
        deleteSesion: 'plen/deleteSesion',
        getTiposMesociclo: 'plen/getTiposMesociclo',
        getTiposMicrociclo: 'plen/getTiposMicrociclo',
        saveMesociclo: 'plen/saveMesociclo',
        saveMicrociclo: 'plen/saveMicrociclo',
        saveSesion: 'plen/saveSesion',
        setMacrociclo: 'plen/setMacrociclo',
        setMesociclo: 'plen/setMesociclo',
        setMicrociclo: 'plen/setMicrociclo',
        setSesion: 'plen/setSesion',
        updateMesociclo: 'plen/updateMesociclo',
        updateMicrociclo: 'plen/updateMicrociclo',
        updateSesion: 'plen/updateSesion',
      }),
      addItemToMicrocicloArrayInMesociclo(item) {
        const meso_index = _.findIndex(this.time_items, { group: 1, plen_id: item.mesociclo_id });
        this.time_items[meso_index].mesociclo.microciclos.push(item.microciclo);
        this.timeline.setItems(this.time_items);
      },
      addItemToSesionArrayInMicrociclo(item) {
        const micro_index = _.findIndex(this.time_items, { group: 2, plen_id: item.microciclo_id });
        this.time_items[micro_index].microciclo.sesiones.push(item.sesion);
        this.timeline.setItems(this.time_items);
      },
      addMesociclo(item, callback) {
        const macrociclo = this.findSuitableMacrociclo(item.start);

        if( macrociclo ) {
          let start = null
          let end = null

          start = moment(item.start).startOf('month').startOf('week');

          if( moment(start).isBefore(macrociclo.start) ) {
            start = moment(macrociclo.start).startOf('day');
          }

          // if( this.findSuitableMesociclo(start) ) {
          //   alert("No puede crear 2 mesociclos que cubran la misma fecha");
          //   return;
          // }

          end = moment(start).day(8).endOf('month').day('Sunday').endOf('day');

          if( 6 === moment(end).endOf('month').weekday() ) {
            end = moment(end).endOf('month')
          }
          if( moment(end).endOf('month').format('YYYY-MM-DD') == moment(macrociclo.end).endOf('day').format('YYYY-MM-DD') ) {
            end = moment(macrociclo.end).endOf('day')
          }

          item.group = 1;
          item.new = true;
          item.start = ( moment(item.start).isBefore(macrociclo.start) ? macrociclo.start : start );
          item.end = ( moment(end).isAfter(macrociclo.end) ? macrociclo.end : end );
          item.macrociclo_id = macrociclo.plen_id;
          item.mesociclo = {
            id: item.id,
            macrociclo_id: macrociclo.plen_id,
            tipo_mesociclo_id: null,
            fecha_ini: moment(item.start).format("YYYY-MM-DD"),
            fecha_fin: moment(item.end).format("YYYY-MM-DD"),
            description: "",
            objetivos: "",
            microciclos: [],
          }
          item.content = this.getMesocicloContent(item);
          item.className = 'font-weight-bold mesociclo';
          this.saveMesociclo(item.mesociclo).then( (res) => {
            item.id = `plen_group_1_${res.id}`;
            item.plen_id = res.id;
            item.mesociclo.id = res.id;
            this.time_items.push(item);
            callback(item);
          })
        } else {
          alert("Debe seleccionar una fecha dentro del Macrociclo");
        }
      },
      addMicrociclo(item, callback) {
        const mesociclo = this.findSuitableMesociclo(item.start);

        if( mesociclo ) {
          let start = null
          let end = null

          start = moment(item.start).startOf('week');

          if( moment(start).isBefore(mesociclo.start) ) {
            start = moment(mesociclo.start).startOf('day');
          }

          // if( this.findSuitableMicrociclo(start) ) {
          //   alert("No puede crear 2 microcilos que cubran la misma fecha");
          //   return;
          // }

          end = moment(item.start).endOf('week');

          item.group = 2;
          item.new = true;
          item.start = ( moment(item.start).isBefore(mesociclo.start) ? mesociclo.start : start );
          item.end = ( moment(end).isAfter(mesociclo.end) ? mesociclo.end : end );
          item.mesociclo_id = mesociclo.plen_id;
          item.microciclo = {
            id: item.id,
            mesociclo_id: mesociclo.plen_id,
            tipo_microciclo_id: null,
            fecha_ini: moment(item.start).format("YYYY-MM-DD"),
            fecha_fin: moment(item.end).format("YYYY-MM-DD"),
            volumen: null,
            intensidad: null,
            description: "",
            objetivos: "",
            sesiones: []
          }
          item.content = this.getMicrocicloContent( item );
          item.className = 'font-weight-bold microciclo';

          this.saveMicrociclo(item.microciclo).then( (res) => {
            item.id = `plen_group_2_${res.id}`;
            item.plen_id = res.id;
            item.microciclo.id = res.id;
            this.addItemToMicrocicloArrayInMesociclo(item);
            this.time_items.push(item);
            callback(item);
          });
        } else {
          alert("Debe seleccionar una fecha dentro de un Mesociclo");
        }
      },
      addSesion(item, callback) {
        const microciclo = this.findSuitableMicrociclo(item.start);

        if( microciclo ) {
          item.group = 3;
          item.new = true;
          item.start = microciclo.start;
          item.end = microciclo.end;
          item.microciclo_id = microciclo.plen_id;
          item.sesion = {
            id: item.id,
            microciclo_id: microciclo.plen_id,
            fecha: moment(item.start).format("YYYY-MM-DD"),
            hora: null,
            fronton_id: null
          }
          item.content = this.getSesionContent( item );
          item.className = 'font-weight-bold sesion';

          this.saveSesion(item.sesion).then( (res) => {
            item.id = `plen_group_3_${res.id}`;
            item.plen_id = res.id;
            item.sesion.id = res.id;
            this.addItemToSesionArrayInMicrociclo(item);
            this.time_items.push(item);
            callback(item);
          });
        } else {
          alert("Debe seleccionar una fecha dentro de un Microciclo");
        }
      },
      cancelEditMesociclo() {
        this.setMesociclo(this.editMesociclo.item);
        this.resetEditMesociclo();
      },
      cancelEditMicrociclo() {
        this.setMicrociclo(this.editMicrociclo.item);
        this.resetEditMicrociclo();
      },
      cancelEditSesion() {
        this.setSesion(this.editSesion.item);
        this.resetEditSesion();
      },
      checkDateAfterLimit(end, limit) {
        var isAfterLimit = false;

        if( moment(end).endOf('day') > moment(limit).endOf('day') ) {
          isAfterLimit = true;
        }

        return isAfterLimit;
      },
      checkDateBeforeLimit(start, limit) {
        var isBeforeLimit = false;

        if( moment(start).startOf('day') < moment(limit).startOf('day') ) {
          isBeforeLimit = true;
        }

        return isBeforeLimit
      },
      dragMicrociclos(item) {
        const mesociclo = this.findMesocicloById(item.plen_id);
        const new_duration = Math.round(moment.duration(moment(item.end) - moment(item.start)).asDays());
        const old_duration = Math.round(moment.duration(moment(mesociclo.end) - moment(mesociclo.start)).asDays());

        if( item.mesociclo.microciclos.length && new_duration === old_duration ) {
          let gap = 0;
          let index = 0;
          let microciclos = [];
          let new_fecha_ini = null;
          let new_fecha_fin = null;
          let old_fecha_ini = null;
          let old_fecha_fin = null;

          gap = Math.round(moment.duration(moment(item.start) - moment(mesociclo.start)).asDays());

          microciclos = _.filter(this.time_items, { group: 2, mesociclo_id: item.plen_id })

          microciclos.map( (val, key) => {
            old_fecha_ini = moment(item.mesociclo.microciclos[key].fecha_ini);
            old_fecha_fin = moment(item.mesociclo.microciclos[key].fecha_fin);
            new_fecha_ini = moment(old_fecha_ini).add(gap, 'days');
            new_fecha_fin = moment(old_fecha_fin).add(gap, 'days');

            index = _.findIndex(this.time_items, { group: 2, plen_id: val.plen_id });

            item.mesociclo.microciclos[key].fecha_ini = new_fecha_ini.format('YYYY-MM-DD');
            item.mesociclo.microciclos[key].fecha_fin = new_fecha_fin.format('YYYY-MM-DD');

            this.time_items[index].start = new_fecha_ini.startOf('day').format('YYYY-MM-DD HH:mm:ss');
            this.time_items[index].end = new_fecha_fin.endOf('day').format('YYYY-MM-DD HH:mm:ss');
            this.time_items[index].content = this.getMicrocicloContent(this.time_items[index]);

            this.dragSesiones(this.time_items[index], gap);

            this.updateMicrociclo(item.mesociclo.microciclos[key]);
          });
        }
      },
      dragSesiones(item, gap) {
        const duration = Math.round(moment.duration(moment(item.end) - moment(item.start)).asDays());
        const microciclo = this.findMicrocicloById(item.plen_id);

        if( !gap ) {
          gap = Math.round(moment.duration(moment(item.start) - moment(microciclo.start)).asDays());
        }

        if( item.microciclo.sesiones.length ) {
          let index = 0;
          let sesiones = [];
          let new_fecha = null;
          let old_fecha = null;

          sesiones = _.filter(this.time_items, { group: 3, microciclo_id: item.plen_id })

          sesiones.map( (val, key) => {
            old_fecha = moment(item.microciclo.sesiones[key].fecha);
            new_fecha = moment(old_fecha).add(gap, 'days');

            index = _.findIndex(this.time_items, { group: 3, id: val.id });

            item.microciclo.sesiones[key].fecha = new_fecha.format('YYYY-MM-DD');


            this.time_items[index].start = moment(this.time_items[index].start).add(gap, 'days'); // microciclo.start;
            this.time_items[index].end = moment(this.time_items[index].start).add(duration, 'days'); // microciclo.end;
            this.time_items[index].sesion = item.microciclo.sesiones[key];
            this.time_items[index].content = this.getSesionContent(this.time_items[index]);

            this.updateSesion(item.microciclo.sesiones[key]);
          });
        }
      },
      findMacrocicloById(id) {
        return _.find(this.time_items, (o) => {
          if( o.group === 0 && o.plen_id === id ) {
            return o;
          }
        });
      },
      findMesocicloById(id) {
        return _.find(this.time_items, (o) => {
          if( o.group === 1 && o.plen_id === id ) {
            return o;
          }
        });
      },
      findMicrocicloById(id) {
        return _.find(this.time_items, (o) => {
          if( o.group === 2 && o.plen_id === id ) {
            return o;
          }
        });
      },
      findSesionById(id) {
        return _.find(this.time_items, (o) => {
          if( o.group === 3 && o.plen_id === id ) {
            return o;
          }
        });
      },
      findSuitableItem(start, group) {
        return _.find(this.time_items, (e) => {
          if( e.group === group ) {
            if( moment(start).isBetween(e.start, e.end, undefined, '[]') ) {
              return e;
            }
          }
        });
      },
      findSuitableMacrociclo(start) {
        return this.findSuitableItem(start, 0);
      },
      findSuitableMesociclo(start) {
        return this.findSuitableItem(start, 1);
      },
      findSuitableMicrociclo(start) {
        return this.findSuitableItem(start, 2);
      },
      findSuitableSesion(start) {
        return this.findSuitableItem(start, 3);
      },
      getMesocicloContent( item ) {
        let description = "";
        let fecha_fin = moment(item.end).format('DD/MM');
        let fecha_ini = moment(item.start).format('DD/MM');

        if( item.mesociclo.tipo_mesociclo_id ) {
          description = this.tiposMesociclo[item.mesociclo.tipo_mesociclo_id];
        } else if( item.mesociclo.description && item.mesociclo.description.length ) {
          description = item.mesociclo.description;
        } else {
          description = "Mesociclo";
        }

        return "<p>" + description + "</p><p><small>" + fecha_ini + " - " + fecha_fin + "</small></p>";
      },
      getMicrocicloContent( item ) {
        let description = '';
        let fecha_fin = moment(item.end).format('DD');
        let fecha_ini = moment(item.start).format('DD');
        let intensidad = (item.microciclo.intensidad ? item.microciclo.intensidad : '-');
        let volumen = (item.microciclo.volumen ? item.microciclo.volumen : '-');
        let html = '';
        let html_int = this.getMicrocicloHTMLIntensidad( intensidad );
        let html_vol = this.getMicrocicloHTMLVolumen( volumen );

        if( item.microciclo.tipo_microciclo_id ) {
          description = this.tiposMicrociclo[item.microciclo.tipo_microciclo_id][0];
        } else if ( item.microciclo.description && item.microciclo.description.length ) {
          description = item.microciclo.description;
        } else {
          description = '-';
        }

        html = ' \
          <p>' + description + '</p> \
          <p><small>' + fecha_ini + '-' + fecha_fin + '</small></p> \
          <hr> \
          <article><header><small>Vol: ' + volumen + '</small></header>' + html_vol + '</article> \
          <article><header><small>Int: ' + intensidad + '</small></header>' + html_int + '</article>';

        return html;
      },
      getMicrocicloHTMLIntensidad( intensidad ) {
        return this.getMicrocicloHTMLRows( intensidad );
      },
      getMicrocicloHTMLRows( value ) {
        let html = '';
        let i = 5;

        for( i = 5; i >= 1; i-- ) {
          html += '<p>' + ( i == value ? '<span>&nbsp;</span>' : '&nbsp;' ) + '</p>';
        }

        return html;
      },
      getMicrocicloHTMLVolumen( volumen ) {
        return this.getMicrocicloHTMLRows( volumen );
      },
      getSesionContent( item ) {
        const fronton = _.find(this.frontones, { value: item.sesion.fronton_id });
        let fecha = moment(item.sesion.fecha).locale('es').format('D MMM');
        let hora = (item.sesion.hora ? item.sesion.hora.substr(0, 5) : '&nbsp;');
        let frontonText = (item.sesion.fronton_id ? fronton.text : '&nbsp;');

        return "<p><small><strong>" + fecha.toUpperCase() + "</strong></small></p><p><small>" + hora + "</small></p><p><small>" + frontonText + "</small></p>";
      },
      loadMesociclos() {
        this.item.mesociclos.map( (val, key) => {
          let mesociclo = {};

          mesociclo.group = 1;
          mesociclo.new = false;
          mesociclo.id = `plen_group_${mesociclo.group}_${val.id}`;
          mesociclo.plen_id = val.id;
          mesociclo.start = moment(val.fecha_ini).startOf('day').format('YYYY-MM-DD HH:mm:ss');
          mesociclo.end = moment(val.fecha_fin).endOf('day').format('YYYY-MM-DD HH:mm:ss');
          mesociclo.macrociclo_id = val.macrociclo_id;
          mesociclo.mesociclo = {
            id: val.id,
            macrociclo_id: val.macrociclo_id,
            tipo_mesociclo_id: val.tipo_mesociclo_id,
            fecha_ini: val.fecha_ini, //moment(val.fecha_ini),
            fecha_fin: val.fecha_fin, //moment(val.fecha_fin),
            description: val.description,
            objetivos: val.objetivos,
            microciclos: val.microciclos,
          }
          mesociclo.content = this.getMesocicloContent(mesociclo);
          mesociclo.className = 'font-weight-bold mesociclo';
          this.time_items.push(mesociclo);

          this.loadMicrociclos(val.microciclos);
        });
      },
      loadMicrociclos(microciclos) {
        microciclos.map( (val, key) => {
          let microciclo = {};

          microciclo.group = 2;
          microciclo.new = false;
          microciclo.id = `plen_group_${microciclo.group}_${val.id}`;
          microciclo.plen_id = val.id;
          microciclo.start = moment(val.fecha_ini).startOf('day').format('YYYY-MM-DD HH:mm:ss');
          microciclo.end = moment(val.fecha_fin).endOf('day').format('YYYY-MM-DD HH:mm:ss');
          microciclo.mesociclo_id = val.mesociclo_id;
          microciclo.microciclo = {
            id: val.id,
            mesociclo_id: val.mesociclo_id,
            tipo_microciclo_id: val.tipo_microciclo_id,
            fecha_ini: val.fecha_ini,
            fecha_fin: val.fecha_fin,
            volumen: val.volumen,
            intensidad: val.intensidad,
            description: val.description,
            objetivos: val.objetivos,
            sesiones: val.sesiones,
          }
          microciclo.content = this.getMicrocicloContent(microciclo);
          microciclo.className = 'font-weight-bold microciclo';
          this.time_items.push(microciclo);

          this.loadSesiones(microciclo);
        });
      },
      loadSesiones(microciclo) {
        microciclo.microciclo.sesiones.map( (val, key) => {
          let sesion = {};

          sesion.group = 3;
          sesion.new = false;
          sesion.id = `plen_group_${sesion.group}_${val.id}`;
          sesion.plen_id = val.id;
          sesion.start = microciclo.start;
          sesion.end = microciclo.end;
          sesion.microciclo_id = val.microciclo_id;
          sesion.sesion = {
            id: val.id,
            microciclo_id: val.microciclo_id,
            fecha: val.fecha,
            hora: val.hora,
            fronton_id: val.fronton_id,
            ejercicios: val.ejercicios,
          }
          sesion.content = this.getSesionContent(sesion);
          sesion.className = 'font-weight-bold sesion';
          this.time_items.push(sesion);
        } );
      },
      loadTimeline() {
        this.setMacrociclo(this.item);

        this.time_items = [
          {
            id: `plen_group_0_${this.item.id}`,
            plen_id: this.item.id,
            content: this.item.description,
            start: moment(this.item.fecha_ini).startOf('day'),
            end: moment(this.item.fecha_fin).endOf('day'),
            group: 0,
            selectable: false,
            editable: false,
            className: 'font-weight-bold macrociclo text-center text-uppercase'
          },
        ];

        this.loadMesociclos();

        var container = document.getElementById(this.visualization_id);

        this.timeline = new Timeline(container);

        this.timeline.setOptions(this.time_options);
        this.timeline.setGroups(this.time_groups);
        this.timeline.setItems(this.time_items);

        this.$root.$on('saveEditMesociclo', this.saveEditMesociclo);
        this.$root.$on('cancelEditMesociclo', this.cancelEditMesociclo);

        this.$root.$on('saveEditMicrociclo', this.saveEditMicrociclo);
        this.$root.$on('cancelEditMicrociclo', this.cancelEditMicrociclo);

        this.$root.$on('saveEditSesion', this.saveEditSesion);
        this.$root.$on('cancelEditSesion', this.cancelEditSesion);
      },
      onItemAdd(item, callback) {
        moment.locale('es');
        switch( item.group ) {
          case 1:
            this.addMesociclo(item, callback);
            break;
          case 2:
            this.addMicrociclo(item, callback);
            break;
          case 3:
            this.addSesion(item, callback);
            break;
          default:
            callback(item);
            break;
        }
      },
      onItemMove(item, callback) {
        switch( item.group ) {
          case 1:
            this.dragMicrociclos(item);
            this.updateMesociclo(item.mesociclo);
            break;
          case 2:
            this.dragSesiones(item, 0);
            this.updateMicrocicloArrayInMesociclo(item);
            this.updateMicrociclo(item.microciclo);
            break;
          case 3:
            break;
          default:
            callback(item);
            break;
        }
        this.updateTimelineItem(item);
        callback(item);
      },
      onItemMoving(item, callback) {
        switch( item.group ) {
          case 1:
            this.onMesocicloMoving(item, callback);
            break;
          case 2:
            this.onMicrocicloMoving(item, callback);
            break;
          case 3:
            break;
          default:
            callback(item);
            break;
        }
      },
      onMesocicloMoving(item, callback) {
        // Corrección para que la fecha final siempre coincida con domingo
        item.end = moment(item.end).day('Sunday').endOf('day');
        if( this.checkDateBeforeLimit(item.end, item.start) ) {
          item.end = moment(item.start).add(6, 'days').endOf('day');
        }

        const macrociclo = this.findMacrocicloById(item.mesociclo.macrociclo_id);
        const mesociclo = this.findMesocicloById(item.plen_id);
        const new_duration = Math.round(moment.duration(moment(item.end) - moment(item.start)).asDays());
        const old_duration = Math.round(moment.duration(moment(mesociclo.end) - moment(mesociclo.start)).asDays());
        let gap = 0;
        let max_end = null;
        let min_start = null;

        if( new_duration === old_duration ) {
          if( this.checkDateBeforeLimit(item.start, macrociclo.start) ) {
            gap = Math.round(moment.duration(moment(mesociclo.start) - moment(macrociclo.start)).asDays());
            item.start = moment(mesociclo.start).subtract(gap, 'days').startOf('day').format('YYYY-MM-DD HH:mm:ss');
            item.end   = moment(mesociclo.end).subtract(gap, 'days').endOf('day').format('YYYY-MM-DD HH:mm:ss');
          };
          if( this.checkDateAfterLimit(item.end, macrociclo.end) ) {
            gap = Math.round(moment.duration(moment(macrociclo.end) - moment(mesociclo.end)).asDays());
            item.start = moment(mesociclo.start).add(gap, 'days').startOf('day').format('YYYY-MM-DD HH:mm:ss');
            item.end   = moment(mesociclo.end).add(gap, 'days').endOf('day').format('YYYY-MM-DD HH:mm:ss');
          };
        } else {
          min_start = item.start;
          max_end = item.end;

          if( item.mesociclo.microciclos.length ) {
            var micro = null;

            micro = _.minBy(item.mesociclo.microciclos, (val) => {
              return moment(val.fecha_ini);
            });
            min_start = micro.fecha_ini;

            micro = _.maxBy(item.mesociclo.microciclos, (val) => {
              return moment(val.fecha_fin);
            });
            max_end = micro.fecha_fin
          }

          if( this.checkDateBeforeLimit(item.start, macrociclo.start) ) {
            item.start = moment(macrociclo.start).startOf('day').format('YYYY-MM-DD HH:mm:ss');
          } else if( this.checkDateBeforeLimit(min_start, item.start) ) {
            item.start = moment(min_start).startOf('day').format('YYYY-MM-DD HH:mm:ss');
          }

          if( this.checkDateAfterLimit(item.end, macrociclo.end) ) {
            item.end = moment(macrociclo.end).endOf('day').format('YYYY-MM-DD HH:mm:ss');
          } else if( this.checkDateAfterLimit(max_end, item.end) ) {
            item.end = moment(max_end).endOf('day').format('YYYY-MM-DD HH:mm:ss');
          }
        }

        item.content = this.getMesocicloContent(item);
        item.mesociclo.fecha_ini = moment(item.start).format('YYYY-MM-DD');
        item.mesociclo.fecha_fin = moment(item.end).format('YYYY-MM-DD');

        callback(item);
      },
      onMicrocicloMoving(item, callback) {
        // Corrección para que la fecha final siempre coincida con domingo
        item.end = moment(item.end).day("Sunday").endOf('day');
        if( this.checkDateBeforeLimit(item.end, item.start) ) {
          item.end = moment(item.start).add(6, 'days').endOf('day');
        }

        const mesociclo = this.findMesocicloById(item.microciclo.mesociclo_id);
        const microciclo = this.findMicrocicloById(item.plen_id);
        const new_duration = Math.round(moment.duration(moment(item.end) - moment(item.start)).asDays());
        const old_duration = Math.round(moment.duration(moment(microciclo.end) - moment(microciclo.start)).asDays());
        let gap = 0;

        if( new_duration === old_duration ) {
          if( this.checkDateBeforeLimit(item.start, mesociclo.start) ) {
            gap = Math.round(moment.duration(moment(microciclo.start) - moment(mesociclo.start)).asDays());
            item.start = moment(microciclo.start).subtract(gap, 'days').startOf('day').format('YYYY-MM-DD HH:mm:ss');
            item.end   = moment(microciclo.end).subtract(gap, 'days').endOf('day').format('YYYY-MM-DD HH:mm:ss');
          };
          if( this.checkDateAfterLimit(item.end, mesociclo.end) ) {
            gap = Math.round(moment.duration(moment(mesociclo.end) - moment(microciclo.end)).asDays());
            item.start = moment(microciclo.start).add(gap, 'days').startOf('day').format('YYYY-MM-DD HH:mm:ss');
            item.end   = moment(microciclo.end).add(gap, 'days').endOf('day').format('YYYY-MM-DD HH:mm:ss');
          };
        } else {
          if( this.checkDateBeforeLimit(item.start, mesociclo.start) ) {
            item.start = moment(mesociclo.start).startOf('day').format('YYYY-MM-DD HH:mm:ss');
          }
          if( this.checkDateAfterLimit(item.end, mesociclo.end) ) {
            item.end = moment(mesociclo.end).endOf('day').format('YYYY-MM-DD HH:mm:ss');
          }
        }

        item.content = this.getMicrocicloContent(item);
        item.microciclo.fecha_ini = moment(item.start).format('YYYY-MM-DD');
        item.microciclo.fecha_fin = moment(item.end).format('YYYY-MM-DD');

        callback(item);
      },
      onItemRemove(item, callback) {
        let event_id;

        switch( item.group ) {
          case 1:
            event_id = 'delete-mesociclo';
            break;
          case 2:
            event_id = 'delete-microciclo';
            break;
          case 3:
            event_id = 'delete-sesion';
            break;
          default:
            callback(item);
            break;
        }

        this.$root.$emit(event_id, () => this.removeItem(item, callback), item);
      },
      onItemUpdate(item, callback) {
        switch( item.group ) {
          case 1:
            this.setMesociclo(item.mesociclo);
            this.setEditMesociclo( item, callback );
            this.$root.$emit("bv::show::modal", "editMesociclo");
            break;
          case 2:
            mesociclo = _.find(this.macrociclo.mesociclos, { id: item.microciclo.mesociclo_id });
            this.setMesociclo(mesociclo);
            this.setMicrociclo(item.microciclo);
            this.setEditMicrociclo( item, callback );
            this.$root.$emit("bv::show::modal", "editMicrociclo");
            break;
          case 3:
            let microciclo = _.find(this.time_items, { group: 2, plen_id: item.microciclo_id });
            let mesociclo = _.find(this.time_items, { group: 1, plen_id: microciclo.mesociclo_id });
            this.setMesociclo(mesociclo.mesociclo);
            this.setMicrociclo(microciclo.microciclo);
            this.setSesion(item.sesion);
            this.setEditSesion( item, callback );
            this.$root.$emit("bv::show::modal", "editSesion");
            break;
          default:
            callback(item);
            break;
        }
      },
      removeItem(item, callback) {
        switch( item.group ) {
          case 1:
            this.removeMesociclo(item, callback);
            break;
          case 2:
            this.removeMicrociclo(item, callback);
            break;
          case 3:
            this.removeSesion(item, callback);
            break;
        }
      },
      removeMesociclo(item, callback) {
        this.deleteMesociclo(item.plen_id).then( () => {
          // Sesiones
          item.mesociclo.microciclos.map( (val) => {
            val.sesiones.map( (o) => {
              _.remove(this.time_items, { microciclo_id: o.microciclo_id, group: 3 });
            })
          });
          // Microciclos
          _.remove(this.time_items, { mesociclo_id: item.plen_id, group: 2 });
          // Mesociclo
          _.remove(this.time_items, { plen_id: item.plen_id, group: 1 });
          this.timeline.setItems(this.time_items);
        });
      },
      removeMicrociclo(item, callback) {
        this.deleteMicrociclo(item.plen_id).then( () => {
          // Eliminar microciclo del array dentro del mesociclo
          const mesoIndex = _.findIndex(this.time_items, { plen_id: item.mesociclo_id, group: 1 });
          _.remove(this.time_items[mesoIndex].mesociclo.microciclos, { id: item.plen_id });
          // Eliminar Sesiones
          _.remove(this.time_items, { microciclo_id: item.plen_id, group: 3 });
          // Eliminar microciclo del timeline
          _.remove(this.time_items, { plen_id: item.plen_id, group: 2});
          // Actualizar timeline
          this.timeline.setItems(this.time_items);
        });
      },
      removeSesion(item, callback) {
        this.deleteSesion(item.plen_id).then( () => {
          // Eliminar sesión del array dentro del microciclo
          const microIndex = _.findIndex(this.time_items, { plen_id: item.microciclo_id, group: 2 });
          _.remove(this.time_items[microIndex].microciclo.sesiones, { id: item.plen_id });
          // Eliminar sesión del timeline
          _.remove(this.time_items, { plen_id: item.plen_id, group: 3});
          // Actualizar timeline
          this.timeline.setItems(this.time_items);
        });
      },
      resetEditMesociclo() {
        this.editMesociclo = {
          item: null,
          callback: null
        }
      },
      resetEditMicrociclo() {
        this.editMicrociclo = {
          item: null,
          callback: null
        }
      },
      resetEditSesion() {
        this.editSesion = {
          item: null,
          callback: null
        }
      },
      saveEditMesociclo() {
        const index = _.findIndex(this.time_items, { id: this.editMesociclo.item.id, group: 1 });
        this.time_items[index].mesociclo = this.mesociclo;
        this.time_items[index].start = moment(this.mesociclo.fecha_ini).startOf('day'); // .format('YYYY-MM-DD');
        this.time_items[index].end = moment(this.mesociclo.fecha_fin).endOf('day'); // .format('YYYY-MM-DD');
        this.time_items[index].content = this.getMesocicloContent(this.time_items[index]);

        this.editMesociclo.callback(this.time_items[index]);

        this.updateMesociclo(this.mesociclo);
        this.resetEditMesociclo();
      },
      saveEditMicrociclo() {
        const index = _.findIndex(this.time_items, { id: this.editMicrociclo.item.id, group: 2 });
        this.time_items[index].microciclo = this.microciclo;
        this.time_items[index].start = moment(this.microciclo.fecha_ini).startOf('day'); // .format('YYYY-MM-DD');
        this.time_items[index].end = moment(this.microciclo.fecha_fin).endOf('day'); // .format('YYYY-MM-DD');
        this.time_items[index].content = this.getMicrocicloContent(this.time_items[index]);

        this.editMicrociclo.callback(this.time_items[index]);

        this.updateMicrociclo(this.microciclo);
        this.resetEditMicrociclo();
      },
      saveEditSesion() {
        const index = _.findIndex(this.time_items, { id: this.editSesion.item.id, group: 3 });
        this.time_items[index].sesion = this.sesion;
        this.time_items[index].content = this.getSesionContent(this.time_items[index]);

        this.editSesion.callback(this.time_items[index]);

        this.updateSesion(this.sesion);
        this.resetEditSesion();
      },
      setEditMesociclo( item, callback ) {
        this.editMesociclo = {
          item: item,
          callback: callback
        }
      },
      setEditMicrociclo( item, callback ) {
        this.editMicrociclo = {
          item: item,
          callback: callback
        }
      },
      setEditSesion( item, callback ) {
        this.editSesion = {
          item: item,
          callback: callback
        }
      },
      setTiposMesociclo(arr) {
        arr.map( (val) => {
          this.tiposMesociclo[val.id] = val.desc;
        });
      },
      setTiposMicrociclo(arr) {
        arr.map( (val) => {
          this.tiposMicrociclo[val.id] = val.desc;
        });
      },
      updateMicrocicloArrayInMesociclo(item) {
        const meso_index = _.findIndex(this.time_items, { group: 1, plen_id: item.mesociclo_id });
        const micro_index = _.findIndex(this.time_items[meso_index].mesociclo.microciclos, { id: item.plen_id });

        this.time_items[meso_index].mesociclo.microciclos[micro_index] = item.microciclo;
      },
      updateTimelineItem(item) {
        const index =_.findIndex(this.time_items, { group: item.group, plen_id: item.plen_id });

        this.time_items[index] = item;
        this.timeline.setItems(this.time_items);
      },
    }
  }
</script>

<style>
  .macrociclo,
  .mesociclo,
  .microciclo,
  .sesion {
    color:#ffffff;
    text-align:center;
    width:100%;
  }

  .vis-group .macrociclo {
    background-color:#c82333;
  }

  .vis-group .mesociclo:nth-child(2n) {
    background-color:#007bff;
  }
  .vis-group .mesociclo:nth-child(2n+1) {
    background-color:#2b8400;
  }
  .vis-group .mesociclo.vis-selected {
    background-color:#ff8400;
  }

  .vis-group .microciclo {
    background-color:goldenrod;
    color:darkslategray;
  }
  .vis-group .microciclo.vis-selected {
    background-color:greenyellow;
  }
  .vis-group .microciclo .vis-item-content,
  .vis-group .sesion .vis-item-content {
    display:block;
    padding-bottom:0;
  }
  .vis-group .microciclo .vis-item-content hr {
    margin-bottom:0;
    margin-top:0.25rem;
  }
  .vis-group .microciclo .vis-item-content article:last-of-type {
    margin-bottom:0.25rem;
  }
  .vis-group .microciclo .vis-item-content article header small {
    font-weight:bold;
  }
  .vis-group .microciclo .vis-item-content article p {
    background:white;
    border:1px solid #e0e0e0;
    line-height:0.3;
  }
  .vis-group .microciclo.vis-selected .vis-item-content article p {
    background:rgba(255,255,255,0.75);
  }
  .vis-group .microciclo .vis-item-content article p:not(:first-child) {
    margin-bottom:-1px;
  }
  .vis-group .microciclo .vis-item-content article p span {
    display:block;
  }
  .vis-group .microciclo .vis-item-content article:first-of-type p span {
    background-color:red;
  }
  .vis-group .microciclo .vis-item-content article:last-of-type p span {
    background-color:green;
  }

  .vis-group .sesion {
    background-color:lightgray;
    color:darkslategray;
  }

  .vis-item.vis-range .vis-item-content {
    padding-left:0;
    padding-right:0;
  }

  .vis-item-content p {
    line-height:1;
    margin:0;
  }

  .vis-time-axis .vis-text {
    font-weight:bold;
    text-transform:uppercase;
  }
</style>
