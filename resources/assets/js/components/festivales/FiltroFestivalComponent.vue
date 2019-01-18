<template>
  <div>
    <b-row class="p-0 m-0">
      <b-form-select
        class="col-sm-5 mb-0"
        @change="onChangeFilterType"
        v-model="selectedFilterType"
        :options="filterTypeOptions" />
      <div class="col-sm-1"></div>
      <b-form-select
        v-if=" 'festivales.fecha_ini' != selectedFilterType && 'festivales.fecha_fin' != selectedFilterType "
        class="col-sm-5 mb-0"
        @change="onChangeFilterValue"
        v-model="selectedFilterValue"
        :options="filterValueOptions" />
      <b-form-input
        v-else
        class="col-sm-5 mb-0"
        @change="onChangeFilterDate"
        style="min-width:127px;width:calc(100% - 25px);"
        type="date"
        v-model="filterValueDate" />
      <div class="col-sm-1">
        <b-button v-if="filterHasValue" size="sm" variant="danger" class="mt-1" @click.stop="addFilterValue" title="Añadir filtro">
          <i class="fas fa-plus"></i>
        </b-button>
        <b-button v-else size="sm" variant="default" class="mt-1" title="Añadir filtro" disabled>
          <i class="fas fa-plus"></i>
        </b-button>
      </div>
    </b-row>

    <b-row v-if="_filterValues.length" class="p-0 mx-0 mb-0 mt-3">
      <ul class="filter-value-list p-0 m-0">
        <li v-for="(value, index) in _filterValues" class="d-inline-block mr-4">
          <i class="fas fa-times-circle mr-1" @click.stop="removeFilterValue(index)"></i>{{ value.columnTxt }}: {{ value.valueTxt }}
        </li>
      </ul>
    </b-row>
  </div>
</template>

<script>
  import APIGetters from '../utils/getters.js';
  import { store } from '../store/store';
  import { mapState } from 'vuex';

  export default {
    mixins: [ APIGetters ],
    data () {
      return {
        selectedFilterType: null,
        filterTypeOptions: [
          { value: null, text: 'Seleccionar filtro' },
          { value: 'festival_partidos.campeonato_id', text: 'Campeonato' },
          { value: 'festivales.estado_id', text: 'Estado' },
          { value: 'festivales.fecha_ini', text: 'Fecha desde' },
          { value: 'festivales.fecha_fin', text: 'Fecha hasta' },
          { value: 'festivales.fronton_id', text: 'Frontón' },
          { value: 'frontones.municipio_id', text: 'Municipio' },
          { value: 'festivales.organizador', text: 'Organizador' },
          { value: 'festival_facturacion.pagado', text: 'Pagado' },
          { value: 'festival_partido_pelotaris.pelotari_id', text: 'Pelotari' },
          { value: 'frontones.provincia_id', text: 'Provincia' },
          { value: 'festivales.television', text: 'Televisión' },
          { value: 'festival_partidos.tipo_partido_id', text: 'Tipo de Partido' },
        ],
        selectedFilterValue: null,
        filterValueOptions: [
          { value: null, text: "Seleccionar filtro"}
        ],
        filterValueDate: null,
        filterHasValue: false,
      }
    },
    created() {
      console.log("FiltroFestivalComponent created");
      this.getProvincias();
      this.getMunicipios();
      this.getFrontones();
      this.getFestivalEstados();
      this.getCampeonatos();
      this.getFasesCampeonato();
      this.getTiposPartido();
      this.getPelotaris();
    },
    computed: {...mapState({
      _filterValues: 'filter_festivales',
    })},
    methods: {
      onChangeFilterType(ft) {
        this.selectedFilterValue = null;
        this.filterHasValue = false;
        switch(ft) {
          case 'festival_partidos.campeonato_id':
            this.filterValueOptions = this.campeonatos;
            break;
          case 'festivales.estado_id':
            this.filterValueOptions = this.festivalEstados;
            break;
          case 'festivales.fecha_ini':
            break;
          case 'festivales.fecha_fin':
            break;
          case 'festivales.fronton_id':
            this.filterValueOptions = this.frontones;
            break;
          case 'frontones.municipio_id':
            this.filterValueOptions = this.municipios;
            break;
          case 'festivales.organizador':
            this.filterValueOptions = [
              { value: null, text: "Seleccionar valor" },
              { value: "gugeu", text: "Baiko Pilota" },
              { value: "beste", text: "Aspe" },
            ];
            break;
          case 'festival_facturacion.pagado':
            this.filterValueOptions = [
              { value: null, text: "Seleccionar valor" },
              { value: 0, text: "No" },
              { value: 1, text: "Sí" },
            ];
            break;
          case 'festival_partido_pelotaris.pelotari_id':
            this.filterValueOptions = this.pelotaris;
            break;
          case 'frontones.provincia_id':
            this.filterValueOptions = this.provincias;
            break;
          case 'festivales.television':
            this.filterValueOptions = [
              { value: null, text: "Seleccionar valor" },
              { value: 0, text: "No" },
              { value: 1, text: "Sí" },
            ];
            break;
          case 'festival_partidos.tipo_partido_id':
            this.filterValueOptions = this.tipos_partido;
            break;
        }
      },
      onChangeFilterValue (fv) {
        if( null == fv )
          this.filterHasValue = false;
        else
          this.filterHasValue = true;
      },
      onChangeFilterDate (fv) {
        if( null == fv )
          this.filterHasValue = false;
        else
          this.filterHasValue = true;
      },
      addFilterValue () {
        if( this.filterHasValue ) {

          let operator = '=';
          let column;
          let value;
          let val;
          let valTxt;

          if( 'festivales.fecha_ini' == this.selectedFilterType ) {
            operator = '>=';
            column = 'festivales.fecha';
            val = this.filterValueDate;
            valTxt = this.filterValueDate;
          } else if( 'festivales.fecha_fin' == this.selectedFilterType ) {
            operator = '<=';
            column = 'festivales.fecha';
            val = this.filterValueDate;
            valTxt = this.filterValueDate;
          } else if( 'festival_partidos.campeonato_id' == this.selectedFilterType ||
                     'festival_partido_pelotaris.pelotari_id' == this.selectedFilterType ||
                     'festival_partidos.tipo_partido_id' == this.selectedFilterType ) {
            operator = 'in';
            column = this.selectedFilterType;
            val = this.selectedFilterValue;
            valTxt = _.find(this.filterValueOptions, { value: this.selectedFilterValue}).text;
          } else {
            operator = '=';
            column = this.selectedFilterType;
            val = this.selectedFilterValue;
            valTxt =  _.find(this.filterValueOptions, { value: this.selectedFilterValue}).text;
          }

          value = {
            column: column,
            columnTxt: _.find(this.filterTypeOptions, { value: this.selectedFilterType }).text,
            operator: operator,
            value: val,
            valueTxt: valTxt,
          }

          store.dispatch('addFilterFestival', value);
        }

        this.resetForm();
      },
      removeFilterValue (index) {
        store.dispatch('removeFilterFestival', index);
      },
      resetForm () {
        this.filterHasValue = false;
        this.selectedFilterType = null;
        this.selectedFilterValue = null;
        this.filterValueOptions = [ { value: null, text: "Seleccionar filtro"} ];
        this.filterValueDate = null;
      }
    }
  }
</script>

<style>
  .filter-value-list li {
    color:#007bff;
  }
  .filter-value-list li .fas {
    color:#dd3545;
    cursor:pointer;
  }
</style>
