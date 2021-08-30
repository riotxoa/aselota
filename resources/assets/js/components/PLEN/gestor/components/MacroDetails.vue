<template>
  <div>
    <div :id="visualization_id"></div>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  import { Timeline } from 'vis-timeline/standalone';
  import moment from 'moment';

  export default {
    props: ['item'],
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
        visualization_id: "visualization_",
        time_groups: [
          { id: 0, content: 'Macrociclo' , style: 'height:15px' },
          { id: 1, content: 'Mesociclos' , style: 'height:15px' },
          { id: 2, content: 'Microciclos', style: 'height:15px' },
        ],
        time_items: [],
        time_options: {
          orientation: 'top', //Add the timeline to the top
          itemsAlwaysDraggable: true, // Dont have to click for moving entries around
          clickToUse: true,
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
          onAdd: this.onItemAdd,
          onMoving: this.onItemMoving,
          onRemove: this.onItemRemove,
          onUpdate: this.onItemUpdate,
          showWeekScale: false,
          timeAxis: {
            scale: 'week',
          },
          tooltipOnItemUpdateTime: {
            template: function (item) {
              return "Inicio: " + moment(item.start).format('DD-MM-YYYY') + "<br>Fin: " + moment(item.end).format('DD-MM-YYYY');
            },
          },
          type: 'range',
          start: this.item.fecha_ini,
          end: this.item.fecha_fin,
        },
        timeline: null,
      }
    },
    computed: mapState({
      macrociclo: state => state.plen.macrociclo,
      mesociclo:  state => state.plen.mesociclo,
      microciclo: state => state.plen.microciclo
    }),
    created() {
      this.visualization_id = "visualization_" + this.item.id;
    },
    mounted() {
      this.setMacrociclo(this.item);

      this.time_items = [
        {
          id: `plen_group_0_${this.item.id}`,
          plen_id: this.item.id,
          content: this.item.description,
          start: this.item.fecha_ini,
          end: this.item.fecha_fin,
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
    },
    methods: {
      ...mapActions({
        deleteMesociclo: 'plen/deleteMesociclo',
        deleteMicrociclo: 'plen/deleteMicrociclo',
        saveMesociclo: 'plen/saveMesociclo',
        saveMicrociclo: 'plen/saveMicrociclo',
        setMacrociclo: 'plen/setMacrociclo',
        setMesociclo: 'plen/setMesociclo',
        setMicrociclo: 'plen/setMicrociclo',
        updateMesociclo: 'plen/updateMesociclo',
        updateMicrociclo: 'plen/updateMicrociclo',
      }),
      addMesociclo(item, callback) {
        const start = moment(item.start).startOf('month').add(1, 'hours').format('YYYY-MM-DD HH:mm:ss');

        if( this.findSuitableMesociclo(start) ) {
          alert("No puede crear 2 mesociclos que cubran la misma fecha");
          return;
        }

        const macrociclo = this.findSuitableMacrociclo(start);

        if( macrociclo ) {
          const end = moment(item.start).endOf('month').subtract(1, 'hours').format('YYYY-MM-DD HH:mm:ss');

          item.group = 1;
          item.new = true;
          item.start = ( moment(item.start).isBefore(macrociclo.start) ? macrociclo.start : start );
          item.end = ( moment(end).isAfter(macrociclo.end) ? macrociclo.end : end );
          item.mesociclo = {
            id: item.id,
            macrociclo_id: macrociclo.plen_id,
            tipo_mesociclo_id: null,
            fecha_ini: moment(item.start).format("YYYY-MM-DD"),
            fecha_fin: moment(item.end).format("YYYY-MM-DD"),
            description: "Nuevo Mesociclo",
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
        const start = moment(item.start).startOf('week').add(1, 'hours').format('YYYY-MM-DD HH:mm:ss');

        if( this.findSuitableMicrociclo(start) ) {
          alert("No puede crear 2 microcilos que cubran la misma fecha");
          return;
        }

        const mesociclo = this.findSuitableMesociclo(start);

        if( mesociclo ) {
          const end = moment(item.start).endOf('week').subtract(1, 'hours').format('YYYY-MM-DD HH:mm:ss');

          item.group = 2;
          item.new = true;
          item.start = ( moment(item.start).isBefore(mesociclo.start) ? mesociclo.start : start );
          item.end = ( moment(end).isAfter(mesociclo.end) ? mesociclo.end : end );
          item.microciclo = {
            id: item.id,
            mesociclo_id: mesociclo.plen_id,
            tipo_microciclo_id: null,
            fecha_ini: moment(item.start).format("YYYY-MM-DD"),
            fecha_fin: moment(item.end).format("YYYY-MM-DD"),
            volumen: null,
            intensidad: null,
            description: "Nuevo Microciclo",
            objetivos: ""
          }
          item.content = this.getMicrocicloContent( item );
          item.className = 'font-weight-bold microciclo';

          this.saveMicrociclo(item.microciclo).then( (res) => {
            item.id = `plen_group_2_${res.id}`;
            item.plen_id = res.id;
            item.microciclo.id = res.id;
            this.time_items.push(item);
            callback(item);
          });
        } else {
          alert("Debe seleccionar una fecha dentro de un Mesociclo");
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
      getMesocicloContent( item ) {
        return "<p>" + item.mesociclo.description + "</p><p><small>" + moment(item.start).format('DD-MM-YYYY') + " - " + moment(item.end).format('DD-MM-YYYY') + "</small></p>";
      },
      getMicrocicloContent( item ) {
        return "<p>" + item.microciclo.description + "</p><p><small>" + moment(item.start).format('DD-MM-YYYY') + " - " + moment(item.end).format('DD-MM-YYYY') + "</small></p>";
      },
      loadMesociclos() {
        this.item.mesociclos.map( (val, key) => {
          let mesociclo = {};

          mesociclo.group = 1;
          mesociclo.new = false;
          mesociclo.id = `plen_group_${mesociclo.group}_${val.id}`;
          mesociclo.plen_id = val.id;
          mesociclo.start = val.fecha_ini;
          mesociclo.end = val.fecha_fin;
          mesociclo.mesociclo = {
            id: val.id,
            macrociclo_id: val.macrociclo_id,
            tipo_mesociclo_id: val.tipo_mesociclo_id,
            fecha_ini: val.fecha_ini,
            fecha_fin: val.fecha_fin,
            description: val.description,
            objetivos: val.objetivos,
            microciclos: [],
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
          microciclo.start = val.fecha_ini;
          microciclo.end = val.fecha_fin;
          microciclo.microciclo = {
            id: val.id,
            mesociclo_id: val.mesociclo_id,
            tipo_microciclo_id: val.tipo_microciclo_id,
            fecha_ini: val.fecha_ini,
            fecha_fin: val.fecha_fin,
            volumen: val.volumen,
            intensidad: val.intensidad,
            description: val.description,
            objetivos: val.objetivos
          }
          microciclo.content = this.getMicrocicloContent(microciclo);
          microciclo.className = 'font-weight-bold microciclo';
          this.time_items.push(microciclo);
        });
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
        }
      },
      onItemMoving(item, callback) {
        switch( item.group ) {
          case 1:
            let mesociclo_1 = this.findMesocicloById(item.plen_id);

            const macrociclo_1 = this.findMacrocicloById(item.mesociclo.macrociclo_id);
            const duration_1 = moment(mesociclo_1.end) - moment(mesociclo_1.start);

            if( moment(item.start).isBetween(moment(macrociclo_1.start), moment(macrociclo_1.end), undefined, '[]') &&
                moment(item.end).isBetween(moment(macrociclo_1.start), moment(macrociclo_1.end), undefined, '[]') ) {
              mesociclo_1.start = item.start;
              mesociclo_1.end = item.end;
              item.content = this.getMesocicloContent(item);
            } else if( moment(item.start).isBefore(moment(macrociclo_1.start)) ) {
              item.start = macrociclo_1.start;
              item.end = moment(item.start).add(duration_1).format('YYYY-MM-DD HH:mm:ss');
              item.content = this.getMesocicloContent(item);
            } else if( moment(item.end).isAfter(moment(macrociclo_1.end)) ) {
              item.end = macrociclo_1.end;
              item.start = moment(item.end).subtract(duration_1).format('YYYY-MM-DD HH:mm:ss');
              item.content = this.getMesocicloContent(item);
            }
            item.mesociclo.fecha_ini = moment(item.start).format('YYYY-MM-DD');
            item.mesociclo.fecha_fin = moment(item.end).format('YYYY-MM-DD');
            callback(item);
            this.updateMesociclo(item.mesociclo);
            break;
          case 2:
            let microciclo_2 = this.findMicrocicloById(item.plen_id);

            const mesociclo_2 = this.findMesocicloById(item.microciclo.mesociclo_id);
            const duration_2 = moment(microciclo_2.end) - moment(microciclo_2.start);

            if( moment(item.start).isBetween(moment(mesociclo_2.start), moment(mesociclo_2.end), undefined, '[]') &&
                moment(item.end).isBetween(moment(mesociclo_2.start), moment(mesociclo_2.end), undefined, '[]') ) {
              microciclo_2.start = item.start;
              microciclo_2.end = item.end;
              item.content = this.getMicrocicloContent( item );
            } else if( moment(item.start).isBefore(moment(mesociclo_2.start)) ) {
              item.start = mesociclo_2.start;
              item.end = moment(item.start).add(duration_2).format('YYYY-MM-DD HH:mm:ss');
              item.content = this.getMicrocicloContent( item );
            } else if( moment(item.end).isAfter(moment(mesociclo_2.end)) ) {
              item.end = mesociclo_2.end;
              item.start = moment(item.end).subtract(duration_2).format('YYYY-MM-DD HH:mm:ss');
              item.content = this.getMicrocicloContent( item );
            }
            item.microciclo.fecha_ini = moment(item.start).format('YYYY-MM-DD');
            item.microciclo.fecha_fin = moment(item.end).format('YYYY-MM-DD');
            callback(item);
            this.updateMicrociclo(item.microciclo);
            break;
        }
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
            const mesociclo = _.find(this.macrociclo.mesociclos, { plen_id: item.microciclo.mesociclo_id });
            this.setMesociclo(mesociclo);
            this.setMicrociclo(item.microciclo);
            this.setEditMicrociclo( item, callback );
            this.$root.$emit("bv::show::modal", "editMicrociclo");
            break;
        }
      },
      removeItem(item, callback) {
        let deleteFunction;

        switch( item.group ) {
          case 1:
            deleteFunction = this.deleteMesociclo;
            break;
          case 2:
            deleteFunction = this.deleteMicrociclo;
            break;
        }
        deleteFunction(item.plen_id).then( () => {
          _.remove(this.time_items, { plen_id: item.id, group: item.group })
          callback(item);
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
      saveEditMesociclo() {
        const index = _.findIndex(this.time_items, { id: this.editMesociclo.item.id, group: 1 });
        this.time_items[index].mesociclo = this.mesociclo;
        this.time_items[index].start = moment(this.mesociclo.fecha_ini).format('YYYY-MM-DD');
        this.time_items[index].end = moment(this.mesociclo.fecha_fin).format('YYYY-MM-DD');
        this.time_items[index].content = this.getMesocicloContent(this.time_items[index]);

        this.editMesociclo.callback(this.time_items[index]);

        this.updateMesociclo(this.mesociclo);
        this.resetEditMesociclo();
      },
      saveEditMicrociclo() {
        const index = _.findIndex(this.time_items, { id: this.editMicrociclo.item.id, group: 2 });
        this.time_items[index].microciclo = this.microciclo;
        this.time_items[index].start = moment(this.microciclo.fecha_ini).format('YYYY-MM-DD');
        this.time_items[index].end = moment(this.microciclo.fecha_fin).format('YYYY-MM-DD');
        this.time_items[index].content = this.getMicrocicloContent(this.time_items[index]);

        this.editMicrociclo.callback(this.time_items[index]);

        this.updateMicrociclo(this.microciclo);
        this.resetEditMicrociclo();
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
      }
    }
  }
</script>

<style>
  .macrociclo {
    background-color:#c82333;
    color:#ffffff;
  }
  .mesociclo {
    background-color:#007bff;
    color:#ffffff;
  }
  .microciclo {
    background-color:slategray;
    color:#ffffff;
  }
  .vis-item-content p {
    line-height:1;
    margin:0;
  }
</style>
