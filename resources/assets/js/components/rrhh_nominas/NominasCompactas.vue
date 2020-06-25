<template>
  <b-table striped hover small responsive
    id="table-nominas"
    class="w-100"
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

    <template slot="irpf" slot-scope="row">
      {{ formatCurrency(row.item.total_partidos + row.item.total_estelares + row.item.total_torneos) }}
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
          { key: 'actions', label: '', thClass: "font-weight-bold", thStyle: "line-height:1", class: "small text-center", sortable: false },
          { key: 'alias', label: 'NºTrabajador', thClass: "font-weight-bold", thStyle: "line-height:1", class: "small text-left", sortable: true },
          { key: 'partidos_asistencia', label: 'NºPart.Pagar', thClass: "font-weight-bold", thStyle: "line-height:1", class: "small text-center", sortable: true },
          { key: 'total_partidos', label: 'Total<br>Partidos', thClass: "background-yellow font-weight-bold", thStyle: "line-height:1", class: "background-yellow small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'total_estelares', label: 'Plus<br>Estelar', thClass: "background-yellow font-weight-bold", thStyle: "line-height:1", class: "background-yellow small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'total_torneos', label: 'Plus<br>Torneos', thClass: "background-yellow font-weight-bold", thStyle: "line-height:1", class: "background-yellow small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'irpf', label: 'Base<br>IRPF', thClass: "background-lightblue font-weight-bold", thStyle: "line-height:1", class: "background-lightblue font-weight-bold small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'total_dietas', label: 'Total<br>Dietas', thClass: "background-lightblue font-weight-bold", thStyle: "line-height:1", class: "background-lightblue font-weight-bold small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'total_pelotari', label: 'TOTAL<br>PELOTARI', thClass: "background-deepblue font-weight-bold", thStyle: "line-height:1", class: "background-deepblue font-weight-bold small text-right", formatter: 'formatCurrency', sortable: true },
        ]
      },
    }
  }
</script>
