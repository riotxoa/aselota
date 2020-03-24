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
        v-if=" 'med_partes.fecha_parte_ini' != selectedFilterType && 'med_partes.fecha_parte_fin' != selectedFilterType && 'med_partes.fecha_accidente_ini' != selectedFilterType && 'med_partes.fecha_accidente_fin' != selectedFilterType"
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
          { value: 'med_partes.pelotari_id', text: 'Pelotari' },
          { value: 'med_partes.fecha_parte_ini', text: 'Fecha parte desde' },
          { value: 'med_partes.fecha_parte_fin', text: 'Fecha parte hasta' },
          { value: 'med_partes.fecha_accidente_ini', text: 'Fecha accidente desde' },
          { value: 'med_partes.fecha_accidente_fin', text: 'Fecha accidente hasta' },
          { value: 'med_partes.med_diagnostico_id', text: 'Diagnóstico' },
          { value: 'med_partes.med_evolucion_id', text: 'Evolución' },
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
      this.getPelotaris();
    },
    computed: {...mapState({
      _filterValues: 'filter_partes',
      _partes_aux: 'partes_aux',
    })},
    methods: {
      onChangeFilterType(ft) {
        this.selectedFilterValue = null;
        this.filterHasValue = false;
        switch(ft) {

          case 'med_partes.pelotari_id':
            this.filterValueOptions = this.pelotaris;
            break;
          case 'med_partes.fecha_parte_ini':
            break;
          case 'med_partes.fecha_parte_fin':
            break;
          case 'med_partes.fecha_accidente_ini':
            break;
          case 'med_partes.fecha_accidente_fin':
            break;
          case 'med_partes.med_diagnostico_id':
            this.filterValueOptions = this._partes_aux.diagnosticos;
            break;
          case 'med_partes.med_evolucion_id':
            this.filterValueOptions = this._partes_aux.evoluciones;
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

          if( 'med_partes.fecha_parte_ini' == this.selectedFilterType ) {
            operator = '>=';
            column = 'med_partes.fecha_parte';
            val = this.filterValueDate;
            valTxt = this.filterValueDate;
          } else if( 'med_partes.fecha_parte_fin' == this.selectedFilterType ) {
            operator = '<=';
            column = 'med_partes.fecha_parte';
            val = this.filterValueDate;
            valTxt = this.filterValueDate;
          } else   if( 'med_partes.fecha_accidente_ini' == this.selectedFilterType ) {
            operator = '>=';
            column = 'med_partes.fecha_accidente';
            val = this.filterValueDate;
            valTxt = this.filterValueDate;
          } else if( 'med_partes.fecha_accidente_fin' == this.selectedFilterType ) {
            operator = '<=';
            column = 'med_partes.fecha_accidente';
            val = this.filterValueDate;
            valTxt = this.filterValueDate;
          } else if( 'med_partes.pelotari_id' == this.selectedFilterType ||
                     'med_partes.med_diagnostico_id' == this.selectedFilterType ||
                     'med_partes.med_evolucion_id' == this.selectedFilterType ) {
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

          store.dispatch('addFilterPartes', value);
        }

        this.resetForm();
      },
      removeFilterValue (index) {
        store.dispatch('removeFilterPartes', index);
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
