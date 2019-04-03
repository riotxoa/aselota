<template>
  <div class="calendar-wrap">
    <div class="main-header">
      <div class="toolbar mb-0 py-1">
        <div class="container">
          <b-row>
            <div class="col-sm-3"></div>
            <h4 class="col-sm-6 text-uppercase text-white font-weight-bold">Calendario de Pelotaris</h4>
            <div class="col-sm-3 text-right"><b-button @click="goBack" variant="default">Volver</b-button></div>
          </b-row>
        </div>
      </div>
    </div>

    <b-row class="mt-3 px-3">
      <div class="col-sm-2"><b-button class="month-nav prev" size="sm" variant="primary" @click="onClickPrev"><i class="icon voyager-double-left"></i>&nbsp;{{ this.months[this.prev_month] }}&nbsp;{{ this.prev_year }}</b-button></div>
      <div class="text-center text-uppercase font-weight-bold col-sm-8">{{ this.months[this.curr_month] }}&nbsp;{{ this.curr_year }}</div>
      <div class="col-sm-2"><b-button class="month-nav next" size="sm" variant="primary" @click="onClickNext">{{ this.months[this.next_month] }}&nbsp;{{ this.next_year }}&nbsp;<i class="icon voyager-double-right"></i></b-button></div>
    </b-row>
    <b-row class="mt-4 px-3">
      <div class="col-2 pr-0" style="margin-right:-1px;">
        <table class="table table-striped">
          <thead class="thead-dark">
            <tr>
              <th scope="col" style="min-width:10rem;">Pelotari</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="pelotari in pelotaris">
              <th scope="row">
                {{ pelotari.alias }}<br/>
                <small>Jugados: <strong>{{ pelotari.partidos_jugados}}</strong> - Garantía: <strong>{{ pelotari.garantia }}</strong></small><br/>
                <small>Inicio periodo: <strong>{{ formatDateShort(pelotari.fecha_contrato) }}</strong></small>
              </th>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-10 pl-0" style="overflow:auto;">
        <table class="table table-striped">
          <thead class="thead-dark">
            <tr>
              <th scope="col" v-for="day in days[curr_month]" style="border-left:1px solid #666;min-width:2rem;">{{ day }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="pelotari in pelotaris">
              <td v-for="day in days[curr_month]" v-html="showAgenda(pelotari, day)" @click="tralara(pelotari, day)"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </b-row>
  </div>
</template>

<script>
  import { mapState } from 'vuex';
  import { store } from '../store/store';
  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [APIGetters],
    data () {
      return {
        months: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic.'],
        days: [],
        prev_month: '',
        curr_month: '',
        next_month: '',
        prev_year: '',
        curr_year: '',
        next_year: '',
        today: '',
        agenda: null,
        prev_agenda: null,
        curr_agenda: null,
        next_agenda: null,
        pelotaris: null,
      };
    },
    computed: mapState({
      _calendario: 'calendario',
    }),
    created() {
      console.log("CalendarMainViewComponent created");

      this.today = new Date();

      if( this._calendario && this._calendario.length ) {
        this.curr_month = this._calendario[0].month - 1;
        this.curr_year = this._calendario[0].year;

      } else {
        this.curr_month = this.today.getMonth();
        this.curr_year = this.today.getFullYear();
      }

      this.prev_month = this.getPrevMonth(this.curr_month);
      this.next_month = this.getNextMonth(this.curr_month);

      this.prev_year = this.getPrevYear(this.curr_year);
      this.next_year = this.getNextYear(this.next_year);

      this.days = [31, (this.curr_year % 4 ? 28 : 29), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

      store.dispatch('loadCalendario', this.curr_month + 1);
      this.getPelotarisMonth(this.curr_year, this.curr_month);
    },
    updated() {
        jQuery('[data-toggle="tooltip"]').tooltip();
    },
    methods: {
      formatDateShort(date) {
        var short = new Date(date);
        var month = '';

        month = this.months[short.getMonth()];

        return short.getDate() + " " + month;
      },
      showAgenda(pelotari, day) {
        var date = this.curr_year + "-" + (this.curr_month+1).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
        var curr_agenda = _.filter(this._calendario, function(o) {
          return (o.fecha && o.fecha.indexOf(date) !== -1);
        });
        var match = _.find(curr_agenda, { alias: pelotari.alias, year: this.curr_year, month: this.curr_month+1, day: day });

        if ( match ) {
          var tooltip = "<div style=\"text-align:left\"> \
                            <strong>Fecha: </strong> " + match.fecha + " - " + match.hora + "<br> \
                            <strong>Nº Partido:</strong> " + match.orden + " - <strong>Estelar:</strong> " + (match.estelar ? "Sí" : "No") + "<br> \
                            <strong>Modalidad:</strong> " + match.tipo_partido_name + "<br> \
                            <strong>Frontón:</strong> " + match.fronton_name + " (" + match.municipio_name + ")<br> \
                            <strong>Asiste:</strong> " + (match.asiste ? 'Sí' : 'No') + "<br> \
                         ";
          if( match.campeonato_id ) {
            var fase = (match.fase ? "&nbsp;(<span style=\"text-transform:capitalize;\">" + match.fase.replace("_", "&nbsp;") + "</span>)" : "");
            tooltip += "<hr class=\"my-1\" style=\"border-color:gray;\"><strong>Campeonato:</strong> " + match.campeonato_name + fase;
          }

          tooltip += "</div>";

          return "<div class='" + (match.asiste ? 'asiste' : 'no-asiste') + "' data-toggle='tooltip' data-placement='left' title='" + tooltip + "' data-html='true' style='cursor:pointer;'>" + match.municipio_name + "</div>";
        } else {
          return "<span>&nbsp;</span>";
        }
      },
      tralara(pelotari, day) {
        var date = this.curr_year + "-" + (this.curr_month+1).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
        var curr_agenda = _.filter(this._calendario, function(o) {
          return (o.fecha && o.fecha.indexOf(date) !== -1);
        });
        var match = _.find(curr_agenda, { alias: pelotari.alias, year: this.curr_year, month: this.curr_month+1, day: day });

        if ( match ) {
          jQuery('[data-toggle="tooltip"]').tooltip('hide');
          this.$router.push('/gerente/festival/' + match.festival_id + '/edit/');
        }
      },
      getPrevMonth(month) {
        return ( month > 0 ? month - 1 : 11);
      },
      getNextMonth(month) {
        return ( month < 11 ? month + 1 : 0);
      },
      getPrevYear(month) {
        return (month > 0 ? this.curr_year : this.curr_year - 1);
      },
      getNextYear(month) {
        return (month < 11 ? this.curr_year : this.curr_year + 1);
      },
      onClickPrev() {
        this.curr_month = this.getPrevMonth(this.curr_month);
        this.prev_month = this.getPrevMonth(this.curr_month);
        this.next_month = this.getNextMonth(this.curr_month);

        this.curr_year = this.prev_year;
        this.prev_year = this.getPrevYear(this.curr_month);
        this.next_year = this.getNextYear(this.curr_month);

        store.dispatch('loadCalendario', this.curr_month + 1);
        this.getPelotarisMonth(this.curr_year, this.curr_month);
      },
      onClickNext() {
        this.curr_month = this.getNextMonth(this.curr_month);
        this.prev_month = this.getPrevMonth(this.curr_month);
        this.next_month = this.getNextMonth(this.curr_month);

        this.curr_year = this.next_year;
        this.prev_year = this.getPrevYear(this.curr_month);
        this.next_year = this.getNextYear(this.curr_month);

        store.dispatch('loadCalendario', this.curr_month + 1);
        this.getPelotarisMonth(this.curr_year, this.curr_month);
      },
      goBack() {
        window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/');
      },
    }
  }
</script>

<style>
  .calendar-wrap .month-nav {
    border-radius:0;
    font-weight:bold;
    text-transform:uppercase;
    width:100%;
  }

  .calendar-wrap table {
    border:1px solid black;
    padding:.5rem;
  }
  .calendar-wrap thead {
    font-weight:bold;
  }
  .calendar-wrap thead td,
  .calendar-wrap thead th {
    font-size:.8rem;
    padding:.25rem 1rem;
    text-align:center;
  }
  .calendar-wrap thead td:not(:first-child),
  .calendar-wrap tbody td:not(:first-child) {
    border-left:1px solid black;
    text-align:center;
  }
  .calendar-wrap tbody td,
  .calendar-wrap tbody th {
    border-top:1px solid black;
    font-size:.8rem;
    font-weight:bold;
    height:2.85rem;
    line-height:1;
    padding:.25rem 1rem;
    position:relative;
    vertical-align:middle;
  }
  .calendar-wrap tbody td {
    padding:0;
  }
  .calendar-wrap tbody td div {
    height:100%;
    max-height:2.8rem;
    padding:.25rem .5rem 0;
  }
  .calendar-wrap tbody td div.asiste {
    background:lightgreen;
  }
  .calendar-wrap tbody td div.no-asiste {
    background:lightpink;
  }
</style>
