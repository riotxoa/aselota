<template>
  <b-row id="calendarView" class="bg-white border p-3">
    <b-col cols="12" class="text-center">
      <h4 class="border-bottom font-weight-bold pb-3 text-uppercase text-info">Calendario de planificación</h4>
    </b-col>
    <b-col cols="12" class="filter-wrap mt-3 mx-0 px-0 text-center">
      <b-button-group size="sm" class="d-block d-sm-inline-flex">
        <b-button
          class="btn-tipology d-block d-sm-inline-flex mx-auto"
          variant="outline-primary"
          :pressed.sync="showMacrociclos"
          @click="onClickShowMacrociclos">
          Macrociclos
        </b-button>
        <b-button
          class="btn-tipology d-block d-sm-inline-flex mx-auto"
          variant="outline-primary"
          :pressed.sync="showMesociclos"
          @click="onClickShowMesociclos">
          Mesociclos
        </b-button>
        <b-button
          class="btn-tipology d-block d-sm-inline-flex mx-auto"
          variant="outline-primary"
          :pressed.sync="showMicrociclos"
          @click="onClickShowMicrociclos">
          Microciclos
        </b-button>
        <b-button
          class="btn-tipology d-block d-sm-inline-flex mx-auto"
          variant="outline-primary"
          :pressed.sync="showSesiones"
          @click="onClickShowSesiones">
          Sesiones
        </b-button>
      </b-button-group>
    </b-col>
    <b-col cols="12" class="mx-0 my-2 px-0 text-center">
      <b-button-group class="d-block d-sm-inline-flex">
        <b-button
          v-for="(option, index) in options_macrociclo"
          v-bind:key="option.value"
          :active="option.active"
          @click="onClickShowMacrocicloItems(index)"
          :pressed="option.active"
          class="btn-macrociclo d-block d-sm-inline-flex mx-auto px-1"
          variant="link"
          size="sm"
        >
          {{ option.text }}
        </b-button>
      </b-button-group>
    </b-col>
    <b-col v-if="!loading" cols="12" class="calendar-wrap">
      <FullCalendar :options="calendarOptions" />
    </b-col>
    <b-col v-else cols="12" class="py-1 text-center w-100">
      <div class="spinner-border text-secondary" role="status"></div>
    </b-col>
    <ModalSesion />
    <ModalMicrociclo />
    <ModalMesociclo />
  </b-row>
</template>

<script>
  import { mapActions } from 'vuex';

  import '@fullcalendar/core/vdom';
  import FullCalendar from '@fullcalendar/vue';
  import dayGridPlugin from '@fullcalendar/daygrid';
  import esLocale from '@fullcalendar/core/locales/es';

  import ModalSesion from '../../gestor/components/ModalSesion';
  import ModalMicrociclo from '../../gestor/components/ModalMicrociclo';
  import ModalMesociclo from '../../gestor/components/ModalMesociclo';

  import functions from '../functions.js';
  import { sesion, microciclo, mesociclo } from '../functions.js';

  export default {
    mixins: [ functions, sesion, microciclo, mesociclo ],
    data() {
      return {
        allEvents: [],
        calendarOptions: {
          eventOrder: '_order_',
          eventTimeFormat: { // like '14:30'
            hour: 'numeric',
            minute: '2-digit',
            meridiem: false
          },
          eventClick: this.eventClick,
          eventDidMount: this.eventDidMount,
          events: [],
          firstDay: 1,
          headerToolbar: {
            start: 'title',
            center: 'dayGridMonth,dayGridWeek,dayGridDay',
            end: 'today prev,next'
          },
          height: 'auto',
          initialView: 'dayGridMonth',
          locale: esLocale,
          plugins: [ dayGridPlugin ],
        },
        items: {},
        loading: true,
        options_macrociclo: [],
        selected_macrociclo: null,
        showMacrociclos: false,
        showMesociclos: false,
        showMicrociclos: false,
        showSesiones: true
      }
    },
    created() {
      this.loadEvents(Date.now());
    },
    methods: {
      ...mapActions({
        getActiveItemsThisYear: 'plen/getActiveItemsThisYear',
        setMacrociclo: 'plen/setMacrociclo',
        setMesociclo: 'plen/setMesociclo',
        setMicrociclo: 'plen/setMicrociclo',
        setSesion: 'plen/setSesion',
      }),
      eventClick( info ) {
        if( info.event.classNames.includes('sesion') ) {
          this.showModalSesion(info.event.id);
        } else if( info.event.classNames.includes('microciclo') ) {
          this.showModalMicrociclo(info.event.id);
        } else if( info.event.classNames.includes('mesociclo') ) {
          this.showModalMesociclo(info.event.id);
        } else {
          // Do nothing
        }
      },
      eventDidMount(info) {
        const cicloClasses = info.el.className;
        let tooltip = '';

        if( cicloClasses.includes("sesion") ) {
          tooltip = this.getTooltipSesion(info.event.id);
        } else if( cicloClasses.includes("microciclo") ) {
          tooltip = this.getTooltipMicrociclo(info.event.id);
        } else if( cicloClasses.includes("mesociclo") ) {
          tooltip = this.getTooltipMesociclo(info.event.id);
        } else if( cicloClasses.includes("macrociclo") ) {
          tooltip = this.getTooltipMacrociclo(info.event.id);
        }

        if( tooltip.length ) {
          jQuery(info.el).attr('title', tooltip);
        }
      },
      getSelectedCiclos() {
        let ciclos = [];

        if( this.showMacrociclos ) {
          ciclos.push('macrociclo');
        }
        if( this.showMesociclos ) {
          ciclos.push('mesociclo');
        }
        if( this.showMicrociclos ) {
          ciclos.push('microciclo');
        }
        if( this.showSesiones ) {
          ciclos.push('sesion');
        }

        return ciclos;
      },
      getSelectedGroupOfMacrociclos() {
        let group_macrociclos = [];

        this.options_macrociclo.map( (val) => {
          if( val.active ) {
            group_macrociclos.push('group_macrociclo_' + val.value);
          }
        });

        return group_macrociclos;
      },
      getTooltipMacrociclo(event_id) {
        const macrociclo_id = parseInt(event_id.replace("macrociclo_", ""));
        const macrociclo = _.find(this.items.macrociclos, { id: macrociclo_id });

        const tooltip = 'FECHAS: ' + this.getRangoFechas(macrociclo.fecha_ini, macrociclo.fecha_fin) + '\n\n' +
                        (macrociclo.description ? 'DESCRIPCIÓN: ' + macrociclo.description + '\n\n' : '') +
                        (macrociclo.objetivos ? 'OBJETIVOS: ' + macrociclo.objetivos : '');

        return tooltip;
      },
      getTooltipMesociclo(event_id) {
        const mesociclo_id = parseInt(event_id.replace("mesociclo_", ""));
        const mesociclo = _.find(this.items.mesociclos, { id: mesociclo_id });

        const tooltip = 'FECHAS: ' + this.getRangoFechas(mesociclo.fecha_ini, mesociclo.fecha_fin) + '\n' +
                        'TIPO: ' + (mesociclo.tipo_mesociclo ? mesociclo.tipo_mesociclo : '---') + '\n\n' +
                        (mesociclo.description ? 'DESCRIPCIÓN: ' + mesociclo.description + '\n\n' : '') +
                        (mesociclo.objetivos ? 'OBJETIVOS: ' + mesociclo.objetivos : '');

        return tooltip;
      },
      getTooltipMicrociclo(event_id) {
        const microciclo_id = parseInt(event_id.replace("microciclo_", ""));
        const microciclo = _.find(this.items.microciclos, { id: microciclo_id });

        const tooltip = 'FECHAS: ' + this.getRangoFechas(microciclo.fecha_ini, microciclo.fecha_fin) + '\n' +
                        'TIPO: ' + (microciclo.tipo_microciclo ? microciclo.tipo_microciclo : '---') + '\n' +
                        'VOLUMEN: ' + (microciclo.volumen ? microciclo.volumen : '---') + '   -   INTENSIDAD: ' + (microciclo.intensidad ? microciclo.intensidad : '---') + '\n\n' +
                        (microciclo.description ? 'DESCRIPCIÓN: ' + microciclo.description + '\n\n' : '') +
                        (microciclo.objetivos ? 'OBJETIVOS: ' + microciclo.objetivos : '');

        return tooltip;
      },
      getTooltipSesion(event_id) {
        const sesion_id = parseInt(event_id.replace("sesion_", ""));
        const sesion = _.find(this.items.sesiones, { id: sesion_id });
        const pelotaris = _.filter(this.items.pelotaris, { sesion_id: sesion_id });

        let tooltip = 'FECHA: ' + this.getFechaES(sesion.fecha) + ' ' + (sesion.hora ? sesion.hora.substr(0, 5) : '') + '\n' +
                    (sesion.fronton ? 'FRONTÓN: ' + sesion.fronton + '\n\n' : '\n');
        if( pelotaris.length ) {
          tooltip += 'Pelotaris convocados:\n\n';
          pelotaris.map( (pelotari) => {
            tooltip += '- ' + pelotari.alias + '\n';
          })
        } else {
          tooltip += 'No hay pelotaris convocados';
        }

        return tooltip;
      },
      loadEvents(date) {
        let order = 0;

        this.loading = true;

        this.getActiveItemsThisYear(date).then( (items) => {
          this.items = items;

          // MACROCICLOS
          items.macrociclos.map( (macrociclo, key_macrociclo) => {
            const group_id = 'group_macrociclo_' + macrociclo.id;

            order++;

            this.options_macrociclo.push({
              value: macrociclo.id,
              text: 'Macrociclo: ' + macrociclo.description,
              active: true
            });

            const e_macrociclo = {
              id: 'macrociclo_' + macrociclo.id,
              group_id: group_id,
              title: '[MACRO] ' + macrociclo.description,
              start: macrociclo.fecha_ini,
              end: macrociclo.fecha_fin + 'T23:59:59',
              color: '#c82333',
              className: 'macrociclo',
              _order_: order,
              display: ( this.showMacrociclos ? 'auto' : 'none')
            };
            this.calendarOptions.events.push(e_macrociclo);

            // MESOCICLOS
            const mesociclos = _.filter(items.mesociclos, { macrociclo_id: macrociclo.id });
            mesociclos.map( (mesociclo, key_mesociclo) => {
              order++;

              const e_mesociclo = {
                id: 'mesociclo_' + mesociclo.id,
                group_id: group_id,
                title: '[MESO] ' + (mesociclo.tipo_mesociclo ? mesociclo.tipo_mesociclo + ': ' : '') + this.getRangoFechas(mesociclo.fecha_ini, mesociclo.fecha_fin, 'short'),
                start: mesociclo.fecha_ini,
                end: mesociclo.fecha_fin + 'T23:59:59',
                color: mesociclo.color,
                className: 'mesociclo',
                _order_: order,
                display: ( this.showMesociclos ? 'auto' : 'none')
              };
              this.calendarOptions.events.push(e_mesociclo);

              // MICROCICLOS
              const microciclos = _.filter(items.microciclos, { mesociclo_id: mesociclo.id });
              microciclos.map( (microciclo, key_microciclo) => {
                order++;

                const e_microciclo = {
                  id: 'microciclo_' + microciclo.id,
                  group_id: group_id,
                  title: '[MICRO] ' + (microciclo.tipo_microciclo ? microciclo.tipo_microciclo + ': ' : '') + this.getRangoFechas(microciclo.fecha_ini, microciclo.fecha_fin, 'short'),
                  start: microciclo.fecha_ini,
                  end: microciclo.fecha_fin + 'T23:59:59',
                  color: mesociclo.color,
                  className: 'microciclo',
                  _order_: order,
                  display: ( this.showMicrociclos ? 'auto' : 'none')
                };
                this.calendarOptions.events.push(e_microciclo);

                // SESIONES
                const sesiones = _.filter(items.sesiones, { microciclo_id: microciclo.id });
                sesiones.map( (sesion, key_sesion) => {
                  order++;

                  const e_sesion = {
                    id: 'sesion_' + sesion.id,
                    group_id: group_id,
                    title: (macrociclo.description ? '[' + macrociclo.description.trim() + ']\n' : '') + (sesion.fronton ? sesion.fronton.trim() : ''),
                    start: sesion.fecha + (sesion.hora ? 'T' + sesion.hora : ''),
                    color: '#666666',
                    className: 'sesion',
                    _order_: 999999999999, // order,
                    display: ( this.showSesiones ? 'auto' : 'none')
                  };
                  this.calendarOptions.events.push(e_sesion);
                });
              });
            })
          })
          this.sortCalendarEvents();
          this.loading = false;
        });
      },
      onClickShowMacrocicloItems(index) {
        const ciclos = this.getSelectedCiclos();
        let macrociclo = this.options_macrociclo[index];

        macrociclo.active = !macrociclo.active;

        const items = _.filter(this.calendarOptions.events, (item) => {
          return item.group_id === 'group_macrociclo_' + macrociclo.value && ciclos.indexOf(item.className) > -1
        });

        items.map( (val) => {
          val.display = (macrociclo.active ? 'auto' : 'none');
        });
      },
      onClickShowMacrociclos() {
        this.toggleCiclo(this.showMacrociclos, 'macrociclo');
      },
      onClickShowMesociclos() {
        this.toggleCiclo(this.showMesociclos, 'mesociclo');
      },
      onClickShowMicrociclos() {
        this.toggleCiclo(this.showMicrociclos, 'microciclo');
      },
      onClickShowSesiones() {
        this.toggleCiclo(this.showSesiones, 'sesion');
      },
      showModalMesociclo(id) {
        const mesociclo_id = parseInt(id.replace('mesociclo_', ''));
        const mesociclo = _.find(this.items.mesociclos, { id: mesociclo_id });
        const macrociclo = _.find(this.items.macrociclos, { id: mesociclo.macrociclo_id });

        this.setMacrociclo( macrociclo );
        this.setMesociclo( mesociclo );
        this.setEditMesociclo( mesociclo );

        this.$root.$emit("bv::show::modal", "editMesociclo");
        this.$root.$emit("enable-modal-mesociclo-readonly");
      },
      showModalMicrociclo(id) {
        const microciclo_id = parseInt(id.replace('microciclo_', ''));
        const microciclo = _.find(this.items.microciclos, { id: microciclo_id });
        const mesociclo = _.find(this.items.mesociclos, { id: microciclo.mesociclo_id });
        const macrociclo = _.find(this.items.macrociclos, { id: mesociclo.macrociclo_id });

        this.setMacrociclo( macrociclo );
        this.setMesociclo( mesociclo );
        this.setMicrociclo(  microciclo );
        this.setEditMicrociclo( microciclo );

        this.$root.$emit("bv::show::modal", "editMicrociclo");
        this.$root.$emit("enable-modal-microciclo-readonly");
      },
      showModalSesion(id) {
        const sesion_id = parseInt(id.replace('sesion_', ''));
        let pelotaris = _.filter(this.items.pelotaris, { sesion_id: sesion_id });
        let sesion = _.find(this.items.sesiones, { id: sesion_id });
        const microciclo = _.find(this.items.microciclos, { id: sesion.microciclo_id });
        const mesociclo = _.find(this.items.mesociclos, { id: microciclo.mesociclo_id });
        const macrociclo = _.find(this.items.macrociclos, { id: mesociclo.macrociclo_id });

        pelotaris.map( (pelotari) => {
          const ejercicios = _.filter(this.items.ejercicios, { sesion_pelotari_id: pelotari.id });
          pelotari.ejercicios = ejercicios;
        })
        sesion.pelotaris = pelotaris;

        this.setMacrociclo( macrociclo );
        this.setMesociclo( mesociclo );
        this.setMicrociclo( microciclo );
        this.setSesion( sesion );
        this.setEditSesion( sesion );

        this.$root.$emit("bv::show::modal", "editSesion");
        this.$root.$emit("enable-modal-sesion-readonly");
      },
      sortCalendarEvents() {
        let sorted = JSON.parse(JSON.stringify(this.calendarOptions.events));
        sorted = _.sortBy(sorted, '_order_');
        this.calendarOptions.events = JSON.parse(JSON.stringify(sorted));
      },
      toggleCiclo(show, ciclo, id = null) {
        const selectedMacrociclos = this.getSelectedGroupOfMacrociclos();
        const filter = ( id ? { id: ciclo + '_' + id } : { className: ciclo } );
        const items = _.filter(this.calendarOptions.events, filter);

        items.map( (val) => {
          val.display = ( show && selectedMacrociclos.indexOf(val.group_id) > -1 ? 'auto' : 'none' );
        });
      }
    },
    components: {
      FullCalendar,
      ModalMesociclo,
      ModalMicrociclo,
      ModalSesion
    }
  }
</script>

<style>
  #calendarView .btn-tipology,
  #calendarView .btn-macrociclo {
    margin-bottom:0.15rem;
    text-align:center;
    width:100%;
  }
  #calendarView .btn-tipology:not(.active) {
    border-color:gray;
    color:gray;
  }
  #calendarView .btn-tipology:not(.active):hover {
    background:transparent;
    border-color:#007bff;
    color:#007bff;
  }
  #calendarView .btn-tipology:focus {
    -webkit-box-shadow: none;
    box-shadow: none;
  }
  #calendarView .btn-macrociclo:not(:last-child) {
    border-bottom:1px solid lightgray;
  }
  #calendarView .btn-macrociclo:focus {
    -webkit-box-shadow: none;
    box-shadow: none;
  }
  #calendarView .btn-macrociclo:active,
  #calendarView .btn-macrociclo:focus,
  #calendarView .btn-macrociclo:hover {
    font-weight:100;
    text-decoration:none;
  }
  #calendarView .btn-macrociclo.active {
    font-weight:bold;
  }
  #calendarView .btn-macrociclo:not(.active) {
    color:gray;
  }

  #calendarView .fc-scrollgrid {
    width:auto;
  }
  #calendarView .fc-col-header {
    background-color:#efefef;
    text-transform: uppercase;
  }
  #calendarView .fc-col-header a {
    color:darkslategray;
    cursor:default;
    font-size:12px;
  }
  #calendarView .fc-scrollgrid-sync-table a.fc-daygrid-day-number {
    color:royalblue;
    cursor:default;
    font-size:12px;
    font-weight: bold;
    padding-bottom:0;
    padding-top:0;
  }
  #calendarView .fc-col-header a:hover,
  #calendarView .fc-scrollgrid-sync-table a.fc-daygrid-day-number:hover {
    text-decoration:none;
  }

  .calendar-wrap .fc-toolbar-title {
    font-size:14px;
    font-weight:bold;
    max-width:100px;
    text-transform: uppercase;
  }
  .calendar-wrap .fc-button {
    padding: 2px 10px;
  }
  .calendar-wrap .macrociclo,
  .calendar-wrap .mesociclo,
  .calendar-wrap .microciclo,
  .calendar-wrap .sesion {
    border:none;
    border-radius:0;
    font-size:12px;
    font-weight:bold;
    line-height:1;
    margin-left:0!important;
    margin-right:0!important;
    text-align:center;
  }
  .calendar-wrap .macrociclo {
    cursor:default;
  }
  .calendar-wrap .macrociclo:focus {
    box-shadow:none;
    outline-color:transparent;
  }
  .calendar-wrap .macrociclo:focus:after {
    content:none;
    background-color:rgb(200, 35, 51);
  }
  .calendar-wrap .mesociclo,
  .calendar-wrap .microciclo,
  .calendar-wrap .sesion {
    cursor:pointer;
  }
  .calendar-wrap .microciclo {
    /* margin-bottom:10px; */
  }
  .calendar-wrap .microciclo .fc-event-title {
    color:darkslategray;
  }
  .calendar-wrap .microciclo::before {
    background-color: rgba(255,255,255,0.5);
    content: " ";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    top: 0;
  }
  .calendar-wrap .macrociclo .fc-event-time,
  .calendar-wrap .mesociclo .fc-event-time,
  .calendar-wrap .microciclo .fc-event-time {
    display:none;
  }
  .calendar-wrap .sesion {
    align-items:start;
    background-color:#efefef!important;
    border-color:lightblue!important;
    border-bottom:1px solid lightblue;
    border-top:1px solid lightblue;
    display:block;
    margin-top:5px;
    text-align:left;
  }
  .calendar-wrap .sesion:hover {
    background-color:#dfdfdf;
  }
  .calendar-wrap .sesion .fc-daygrid-event-dot {
    border-color:red!important;
    display:inline-block;
    margin-top:2px;
  }
  .calendar-wrap .sesion .fc-event-time,
  .calendar-wrap .sesion .fc-event-title {
    color:darkslategray;
  }
  .calendar-wrap .sesion .fc-event-time {
    border-bottom:1px solid lightgray;
    display:inline-block;
    font-weight:bold;
    margin-bottom:2.5px;
    padding-bottom:3.5px;
    width:calc(100% - 20px);
  }
  .calendar-wrap .sesion .fc-event-title {
    display: block;
    font-weight:normal;
    padding-left:15px;
    white-space:pre-wrap;
  }

  @media all and (min-width:575px) {
    #calendarView .btn-tipology,
    #calendarView .btn-macrociclo {
      margin-left:-1px!important;
      width:unset;
    }
    #calendarView .btn-macrociclo:not(:last-child) {
      border:none;
    }
    #calendarView .btn-macrociclo:not(:first-child)::before {
      content: "|";
      font-weight:normal!important;
      padding-right: 0.5rem;
    }
  }
</style>
