<template>
  <b-table striped hover small responsive
    id="table-nominas"
    ref="tableNominas"
    :items="nominas"
    :fields="fields"
    tbody-tr-class="cursor-pointer"
    @row-clicked="onClickTableRow"
  >
    <template slot="actions" slot-scope="row">
      <b-button v-if="row.detailsShowing" size="sm" variant="success" @click.stop="row.toggleDetails" title="Mostrar/Ocultar Detalle" class="px-1 py-0">
        <i class="fas fa-caret-up"></i>
      </b-button>
      <b-button v-else size="sm" variant="danger" @click.stop="row.toggleDetails" title="Mostrar/Ocultar Detalle" class="px-1 py-0">
        <i class="fas fa-caret-down"></i>
      </b-button>
    </template>

    <template slot="alias" slot-scope="row">
      <span v-if="row.item.num_trabajador">{{ row.item.num_trabajador }} - </span>{{ row.item.alias}}
    </template>

    <template slot="partidos_total" slot-scope="row">
      {{ row.item.partidos_jugados}} / {{ row.item.partidos_garantia }}
    </template>

    <template slot="asistencia" slot-scope="row">
      <span class="font-weight-bold text-black">{{ row.item.partidos_asistencia }}</span> / {{ row.item.partidos_convocado }}
    </template>

    <template slot="dieta_basica" slot-scope="row" v-bind:class="{ 'text-center': row.item.dieta_basica }">
      <span v-if="row.item.dieta_basica">{{ row.item.dieta_basica.toFixed(2) }}</span>
      <span v-else><i class="fas fa-exclamation-triangle"></i></span>
    </template>

    <template slot="dieta_partido" slot-scope="row" v-bind:class="{ 'text-center': row.item.dieta_basica }">
      <span v-if="row.item.dieta_partido">{{ row.item.dieta_partido.toFixed(2) }}</span>
      <span v-else><i class="fas fa-exclamation-triangle"></i></span>
    </template>

    <template slot="estelares" slot-scope="row">
      {{ row.item.estelares > 0 ? row.item.estelares : '·' }}
    </template>

    <template slot="prima_estelar" slot-scope="row" v-bind:class="{ 'text-center': row.item.dieta_basica }">
      <span v-if="row.item.prima_estelar">{{ row.item.prima_estelar.toFixed(2) }}</span>
      <span v-else><i class="fas fa-exclamation-triangle"></i></span>
    </template>

    <template slot="coste_partido" slot-scope="row">
      <div v-if="row.item.coste_partido == row.item.coste_partido_2 || !row.item.coste_partido_2">
        <span v-if="row.item.coste_partido">
          {{ row.item.coste_partido.toFixed(2) }}
        </span>
        <span v-else>
          <i class="fas fa-exclamation-triangle"></i>
        </span>
      </div>
      <div v-else>
        <span v-if="row.item.coste_partido">{{ row.item.coste_partido.toFixed(2) }}</span>
        <span v-else><i class="fas fa-exclamation-triangle"></i></span>
        /
        <span v-if="row.item.coste_partido_2">{{ row.item.coste_partido_2.toFixed(2) }}</span>
        <span v-else><i class="fas fa-exclamation-triangle"></i></span>
      </div>
    </template>


    <template slot="row-details" slot-scope="row">
      <b-row class="p-4 small">

        <b-col cols="12" md="5">
          <b-row class="mb-2">
            <b-col cols="9" class="font-weight-bold">Fecha inicio de contrato:</b-col>
            <b-col cols="3">{{ formatDateES(row.item.fecha_ini_contrato) }}</b-col>
          </b-row>
          <b-row>
            <b-col cols="9" class="font-weight-bold">Prima Campeón Manomanista:</b-col>
            <b-col cols="3" v-if="row.item.prima_manomanista">{{ formatCurrency(row.item.prima_manomanista) }}</b-col>
            <b-col cols="3" v-else v-html=""><i class="fas fa-exclamation-triangle"></i></b-col>
          </b-row>
          <b-row>
            <b-col cols="9" class="font-weight-bold">Prima Campeón Manomanista PROMO:</b-col>
            <b-col cols="3" v-if="row.item.prima_manomanista_promo">{{ row.item.prima_manomanista_promo.toFixed(2) }}&nbsp;&euro;</b-col>
            <b-col cols="3" v-else v-html=""><i class="fas fa-exclamation-triangle"></i></b-col>
          </b-row>
        </b-col>

        <b-col cols="12" md="7" id="detail-campeonatos" class="px-4">
          <b-row class="border">
            <b-col cols="3" class="font-weight-bold py-1 text-uppercase">Campeonato</b-col>
            <b-col cols="2" class="border-left font-weight-bold text-center py-1 text-uppercase">Jugados</b-col>
            <b-col cols="3" class="border-left font-weight-bold text-center py-1 text-uppercase">Fechas</b-col>
            <b-col cols="2" class="border-left font-weight-bold text-center py-1 text-uppercase">Prima</b-col>
            <b-col cols="2" class="border-left font-weight-bold text-center py-1 text-uppercase">Total</b-col>
          </b-row>
          <div v-for="fase in fases">
            <b-row v-if="row.item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['partidos']" class="border">
              <b-col cols="3" class="mt-2" style="line-height:1">
                <span class="font-weight-bold">{{ fase.name.replace("Campeonato Oficial de ", "") }}</span>
                <br>
                <span class="text-uppercase"><small>{{ fase.fase.replace("_", "&nbsp;") }}</small></span>
              </b-col>
              <b-col cols="2" class="border-left py-1 text-center">
                <span class="d-inline-block mt-1">{{ row.item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['partidos'] ? row.item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['partidos'] : '·' }}</span>
              </b-col>
              <b-col cols="3" class="border-left py-1 text-left">
                <p v-for="(fecha, index) in row.item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['fechas']" class="mb-0 pt-1" v-bind:class="{ 'border-top': index > 0}">
                  <b-form-checkbox
                    v-model="row.item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['fechas'][index]['incluido']"
                    :value="1"
                    :unchecked-value="0"
                    @input="onChangePrimaCampeonato( row.item )"
                  >
                    <span class="d-inline-block mb-2 mt-1" style="line-height:1.15">{{ formatDateES(fecha.fecha) }}<br>{{ fecha.municipio }}</span>
                  </b-form-checkbox>
                </p>
              </b-col>
              <b-col cols="2" class="border-left py-1 text-center">
                <span class="d-inline-block mt-1">{{ row.item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['prima'].toFixed(2) }}</span>
              </b-col>
              <b-col cols="2" class="border-left py-1 text-right">
                <span class="d-inline-block mt-1">{{ (row.item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['incluidos'] * row.item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['prima']).toFixed(2) }}</span>
              </b-col>
            </b-row>
          </div>
          <b-row class="border">
            <b-col cols="10" class="font-weight-bold py-1">TOTAL PRIMAS CAMPEONATOS</b-col>
            <b-col cols="2" class="border-left py-1 text-right">{{ getTotalPrimasCampeonato( row.item ).toFixed(2) }}</b-col>
          </b-row>
        </b-col>

      </b-row>

    </template>

  </b-table>
</template>

<script>
  import Functions from './nominas_functions.js';
  import Utils from '../utils/utils.js';

  export default {
    props: [ 'nominas', 'fases' ],
    mixins: [ Functions, Utils ],
    data() {
      return {
        fields: []
      }
    },
    created() {
      this.setTableFields();
    },
    methods: {
      onChangePrimaCampeonato( item ) {
        this.$emit('ChangePrimaCampeonato', item);
      },
      setTableFields() {
        this.fields = [
          { key: 'actions', label: '', thClass: "font-weight-bold text-black", thStyle: "line-height:1", class: "small text-center", sortable: false },
          { key: 'alias', label: 'Pelotari', thClass: "font-weight-bold text-black", thStyle: "line-height:1", class: "small", sortable: true },
          { key: 'partidos_total', label: 'Total<br>Jug./Gar.', thClass: "font-weight-bold text-black", thStyle: "line-height:1", class: "small text-center", sortable: false },
          { key: 'fecha_ini_contrato', label: 'Inicio<br>periodo', thClass: "font-weight-bold text-black", thStyle: "line-height:1", class: "small text-center", formatter: 'formatDateShort', sortable: true },
          { key: 'asistencia', label: 'Asiste / Conv', thClass: "font-weight-bold text-black", thStyle: "line-height:1", class: "small text-center", sortable: true },
          { key: 'dieta_basica', label: 'Dieta<br>Básica', thClass: "background-yellow font-weight-bold text-black", thStyle: "line-height:1", class: "background-yellow small text-right", formatter: 'format2dec', sortable: true },
          { key: 'dieta_partido', label: 'Dieta<br>Partido', thClass: "background-yellow font-weight-bold text-black", thStyle: "line-height:1", class: "background-yellow small text-right", formatter: 'format2dec', sortable: true },
          { key: 'total_dietas', label: 'Total&nbsp;€<br>Dietas', thClass: "background-lightblue font-weight-bold text-black", thStyle: "line-height:1", class: "background-lightblue font-weight-bold text-black small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'estelares', label: '<i class="far fa-star">', thClass: "background-yellow font-weight-bold text-black", thStyle: "line-height:1", class: "background-yellow small text-center", sortable: true },
          { key: 'prima_estelar', label: 'Prima<br>Estelar', thClass: "background-yellow font-weight-bold text-black", thStyle: "line-height:1", class: "background-yellow small text-right", formatter: 'format2dec', sortable: true },
          { key: 'total_estelares', label: 'Pluses&nbsp;€<br>Estelares', thClass: "background-lightblue font-weight-bold text-black", thStyle: "line-height:1", class: "background-lightblue font-weight-bold text-black small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'coste_partido', label: 'Coste<br>Partido', thClass: "background-yellow font-weight-bold text-black", thStyle: "line-height:1", class: "background-yellow small text-center", sortable: true },
          { key: 'total_partidos', label: 'Total&nbsp;€<br>Partidos', thClass: "background-lightblue font-weight-bold text-black", thStyle: "line-height:1", class: "background-lightblue font-weight-bold text-black small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'total_torneos', label: 'Pluses&nbsp;€<br>Torneos', thClass: "background-lightblue font-weight-bold text-black", thStyle: "line-height:1", class: "background-lightblue font-weight-bold text-black small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'total_pelotari', label: 'TOTAL&nbsp;€<br>PELOTARI', thClass: "background-deepblue font-weight-bold text-black", thStyle: "line-height:1", class: "background-deepblue font-weight-bold text-black small text-right", formatter: 'formatCurrency', sortable: true },
        ]
      },
    }
  }
</script>
