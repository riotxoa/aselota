<template>
  <div>
    <h4>Listado nóminas</h4>
    <hr/>
    <b-row>
      <b-form-group label="Mes"
                    label-for="inputMes"
                    label-class="font-weight-bold"
                    class="col-12 col-sm-6 col-md-3 mb-0">
        <b-form-select id="inputMes"
                      :options="meses"
                      v-model="mes"
                      required>
        </b-form-select>
      </b-form-group>
      <b-form-group label="Año"
                    label-for="inputAno"
                    label-class="font-weight-bold"
                    class="col-12 col-sm-6 col-md-3 mb-0">
        <b-form-input id="inputAno"
                      type="number"
                      v-model="ano"
                      min="2015"
                      :max="max_ano"
                      required />
      </b-form-group>
      <b-col cols="12" md="3" class="align-self-end text-md-right offset-md-3">
        <b-btn variant="primary" v-on:click="onClickGenerarNominas(false)">Generar nóminas</b-btn>
      </b-col>
    </b-row>
    <b-row v-if="show_nominas && !loading" class="mt-5 px-2">

      <b-alert :show="false !== created" variant="warning" class="w-100">
        Estas nóminas fueron generadas el pasado <strong>{{ formatDateES(created) }}</strong> a las <strong>{{ formatHour(created) }}</strong>. Si desea volver a regenerarlas pulse el botón refrescar.
        <b-btn class="float-right font-weight-bold text-white" size="sm" variant="warning" v-b-modal.modalRecalcular><i class="fas fa-sync mr-2"></i>Refrescar</b-btn>
      </b-alert>

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

        <template slot="partidos_total" slot-scope="row">
          {{ row.item.partidos_jugados}} / {{ row.item.partidos_garantia }}
        </template>

        <template slot="asistencia" slot-scope="row">
          <span class="font-weight-bold">{{ row.item.partidos_asistencia }}</span> / {{ row.item.partidos_convocado }}
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

    </b-row>

    <b-row v-if="show_nominas && !loading" class="small">
      <b-col cols="12" md="8">
        <JsonExcel
          :data="nominas"
          :fields="export_fields"
          :worksheet="export_worksheet"
          :name="export_name">
          <b-btn size="sm" variant="primary"><i class="fas fa-file-excel mr-2"></i>Exportar a Excel</b-btn>
        </JsonExcel>
      </b-col>
      <b-col cols="6" md="2"><big><strong>Total Pelotaris:</strong></big></b-col>
      <b-col cols="6" md="2" class="text-md-right"><big><strong>{{ formatCurrency(total_pelotaris) }}</strong></big></b-col>
      <b-col cols="6" md="2" class="offset-md-8">Total Torneos:</b-col>
      <b-col cols="6" md="2" class="text-md-right">{{ formatCurrency(total_torneos) }}</b-col>
      <b-col cols="6" md="2" class="offset-md-8">Total Partidos:</b-col>
      <b-col cols="6" md="2" class="text-md-right">{{ formatCurrency(total_partidos) }}</b-col>
      <b-col cols="6" md="2" class="offset-md-8">Total Estelares:</b-col>
      <b-col cols="6" md="2" class="text-md-right">{{ formatCurrency(total_estelares) }}</b-col>
      <b-col cols="6" md="2" class="offset-md-8">Total Dietas:</b-col>
      <b-col cols="6" md="2" class="text-md-right">{{ formatCurrency(total_dietas) }}</b-col>
    </b-row>

    <b-row v-if="loading" class="align-items-center mb-5 mt-3 py-5">
      <b-col cols="12" class="mb-5 mt-3 py-5 text-center">
        <i class="animate fas fa-sync"></i>
      </b-col>
    </b-row>

    <b-modal id="modalRecalcular" ref="modalRecalcular" title="Recalcular Nóminas" data-id="" hide-footer>
      <div class="modal-body">
        <p id="deleteMsg">Va a recalcular las nóminas correspondientes a <strong>{{ meses[mes].text }} de {{ ano }}</strong>.<br>Esto sobreescribirá la información actualmente almacenada en la base de datos con la resultante del recálculo.</p>
        <p>¿Desea continuar?</p>
      </div>
      <div class="modal-footer">
        <b-btn variant="danger" @click="onClickGenerarNominas(true)">Recalcular</b-btn>
        <b-btn @click="hideModal">Cancelar</b-btn>
      </div>
    </b-modal>

  </div>
</template>

<script>
  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';
  import { mapActions, mapState } from 'vuex';
  import { store } from '../store/store';
  import JsonExcel from 'vue-json-excel';

  export default {
    mixins: [APIGetters, Utils],
    data() {
      return {
        ano: 1900,
        max_ano: 1900,
        mes: 0,
        meses: [
          { value: 0 , text: 'Enero' },
          { value: 1 , text: 'Febrero' },
          { value: 2 , text: 'Marzo' },
          { value: 3 , text: 'Abril' },
          { value: 4 , text: 'Mayo' },
          { value: 5 , text: 'Junio' },
          { value: 6 , text: 'Julio' },
          { value: 7 , text: 'Agosto' },
          { value: 8 , text: 'Septiembre' },
          { value: 9 , text: 'Octubre' },
          { value: 10, text: 'Noviembre' },
          { value: 11, text: 'Diciembre' },
        ],
        loading: false,
        fases: null,
        nominas: null,
        created: false,
        show_nominas: false,
        show_error: false,
        error_msg: '',
        fields: [],
        total_dietas: 0,
        total_estelares: 0,
        total_partidos: 0,
        total_torneos: 0,
        total_pelotaris: 0,
        export_fields: {
          'Pelotari': 'alias',
          'Total Jug/Gar': {
            callback: (item) => {
              return item.partidos_jugados + "/" + item.partidos_garantia;
            }
          },
          'Inicio Periodo': {
            field: 'fecha_ini_contrato',
            callback: (fecha) => {
              return this.formatDateShort(fecha);
            }
          },
          'Asiste/Conv': {
            callback: (item) => {
              return item.partidos_asistencia + " / " + item.partidos_convocado;
            }
          },
          'Dieta Básica': {
            field: 'dieta_basica',
            callback: (value) => {
              return this.formatCurrencyShort(value);
            }
          },
          'Dieta Partido': {
            field: 'dieta_partido',
            callback: (value) => {
              return this.formatCurrencyShort(value);
            }
          },
          'TOTAL DIETAS': {
            field: 'total_dietas',
            callback: (value) => {
              return this.formatCurrencyShort(value);
            }
          },
          'Estelares': 'estelares',
          'Prima Estelar': {
            field: 'prima_estelar',
            callback: (value) => {
              return this.formatCurrencyShort(value);
            }
          },
          'PLUSES ESTELARES': {
            field: 'total_estelares',
            callback: (value) => {
              return this.formatCurrencyShort(value);
            }
          },
          'Coste Partido': {
            callback: (item) => {
              var coste = '';

              if (item.coste_partido == item.coste_partido_2 || !item.coste_partido_2) {
                coste += (item.coste_partido ? item.coste_partido : '--');
              } else {
                coste += (item.coste_partido ? item.coste_partido : '--') + " / " + item.coste_partido_2;
              }

              return coste;
            }
          },
          'TOTAL PARTIDOS': {
            field: 'total_partidos',
            callback: (total) => {
              return this.formatCurrencyShort(total);
            }
          },
          'Torneos': {
            callback: (item) => {
              var torneos = '';
              var sep = '';

              this.fases.map( (fase, key) => {
                if( item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['incluidos'] ) {
                  torneos += ( key > 0 ? sep : '' );
                  torneos += fase.name.replace("Campeonato Oficial de ", "") + " (" + fase.fase.replace("_", " ").toUpperCase() + "): ";
                  torneos += item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['incluidos'] + " (" + item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['prima'] + ")";
                  sep = '\n';
                }
              })

              return torneos;
            }
          },
          'TOTAL TORNEOS': {
            field: 'total_torneos',
            callback: (total) => {
              return this.formatCurrencyShort(total);
            }
          },
          'TOTAL PELOTARI': {
            field: 'total_pelotari',
            callback: (total) => {
              return this.formatCurrencyShort(total);
            }
          },
          ' ' : {
            callback: (item) => {
              return ' ';
            }
          },
          'Prima Campeón Manomanista': 'prima_manomanista',
          'Prima Campeón Manomanista PROMO': 'prima_manomanista_promo'
        },
        export_name: '',
        export_worksheet: '',
      }
    },
    computed: mapState({
      _calendario: 'calendario',
    }),
    created() {
      var today = new Date();

      this.max_ano = today.getFullYear();

      this.mes = today.getMonth();
      this.ano = this.max_ano;
    },
    methods: {
      ...mapActions([
        'getNominasMonth',
        'updateNomina'
      ]),
      calcularNominas() {
        this.nominas.map( (db_nomina, index) => {
          db_nomina.campeonatos = JSON.parse(db_nomina.campeonatos);
        });

        this.getTotales();

        this.export_worksheet = this.ano + " " + this.meses[this.mes].text;
        this.export_name = "Baiko Pilota - Nóminas " + this.export_worksheet + ".xls";
      },
      format2dec( value ) {
        if( value ) {
          return value.toFixed(2);
        } else {
          return value;
        }
      },
      formatCurrency( value ) {
        const formatter = new Intl.NumberFormat('es-ES', {
          style: 'currency',
          currency: 'EUR'
        })

        return formatter.format(value);
      },
      formatCurrencyShort( value ) {
        const formatter = new Intl.NumberFormat('es-ES')

        if( value && value >= 0 ) {
          return formatter.format(value);
        } else {
          return value;
        }
      },
      getTotales() {
        this.resetTotales();

        this.nominas.map( (db_nomina, index) => {
          this.total_dietas += db_nomina.total_dietas;
          this.total_estelares += db_nomina.total_estelares;
          this.total_partidos += db_nomina.total_partidos;
          this.total_torneos += db_nomina.total_torneos;
          this.total_pelotaris += db_nomina.total_pelotari;
        });
      },
      getTotalPelotari( item ) {
        return item.total_dietas + item.total_partidos + item.total_estelares + this.getTotalPrimasCampeonato( item );
      },
      getTotalPrimasCampeonato( item ) {
        var total = 0;

        this.fases.map( (fase, key) => {
          var incluidos = 0;

          item.campeonatos[fase.campeonato_id]['fases'][fase.fase].fechas.map( (val) => {
            incluidos += (val.incluido ? 1 : 0);
          });

          total += incluidos * item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['prima']
          item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['incluidos'] = incluidos
        })

        return total;
      },
      hideModal() {
        this.$refs['modalRecalcular'].hide()
      },
      onChangePrimaCampeonato( item ) {
        item.total_torneos = this.getTotalPrimasCampeonato( item );
        item.total_pelotari = this.getTotalPelotari( item );
        this.updateNomina( item );
        this.getTotales();
      },
      onClickGenerarNominas( overwrite ) {
        this.$refs['modalRecalcular'].hide();
        this.loading = true;
        store.dispatch('loadCalendario', this.mes + 1)
          .then( () => {
              this.getNominasMonth({ ano: this.ano, mes: this.mes, overwrite: overwrite })
                .then( (res) => {
                  this.loading = false;
                  this.fases = res.fases;
                  this.nominas = res.nominas;
                  this.created = (res.created ? res.created.updated_at : res.created);
                  this.setTableFields();
                  this.showNominas(true);
                })
                .catch( (err) => {
                  this.loading = false;
                  this.showError(true, err);
                });
          })
          .catch( (err) => {
              this.loading = false;
              this.showError(true, err);
          });
      },
      onClickTableRow( item, index, ev ) {
        this.$set(item, '_showDetails', !item._showDetails)
      },
      resetTotales() {
        this.total_dietas = 0;
        this.total_estelares = 0;
        this.total_partidos = 0;
        this.total_torneos = 0;
        this.total_pelotaris = 0;
      },
      setTableFields() {

        this.fields = [
          { key: 'actions', label: '', thClass: "font-weight-bold", thStyle: "line-height:1", class: "small text-center", sortable: false },
          { key: 'alias', label: 'Pelotari', thClass: "font-weight-bold", thStyle: "line-height:1", class: "small", sortable: true },
          { key: 'partidos_total', label: 'Total<br>Jug./Gar.', thClass: "font-weight-bold", thStyle: "line-height:1", class: "small text-center", sortable: false },
          { key: 'fecha_ini_contrato', label: 'Inicio<br>periodo', thClass: "font-weight-bold", thStyle: "line-height:1", class: "small text-center", formatter: 'formatDateShort', sortable: true },
          { key: 'asistencia', label: 'Asiste / Conv', thClass: "font-weight-bold", thStyle: "line-height:1", class: "small text-center", sortable: true },
          { key: 'dieta_basica', label: 'Dieta<br>Básica', thClass: "background-yellow font-weight-bold", thStyle: "line-height:1", class: "background-yellow small text-right", formatter: 'format2dec', sortable: true },
          { key: 'dieta_partido', label: 'Dieta<br>Partido', thClass: "background-yellow font-weight-bold", thStyle: "line-height:1", class: "background-yellow small text-right", formatter: 'format2dec', sortable: true },
          { key: 'total_dietas', label: 'Total&nbsp;€<br>Dietas', thClass: "background-lightblue font-weight-bold", thStyle: "line-height:1", class: "background-lightblue font-weight-bold small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'estelares', label: '<i class="far fa-star">', thClass: "background-yellow font-weight-bold", thStyle: "line-height:1", class: "background-yellow small text-center", sortable: true },
          { key: 'prima_estelar', label: 'Prima<br>Estelar', thClass: "background-yellow font-weight-bold", thStyle: "line-height:1", class: "background-yellow small text-right", formatter: 'format2dec', sortable: true },
          { key: 'total_estelares', label: 'Pluses&nbsp;€<br>Estelares', thClass: "background-lightblue font-weight-bold", thStyle: "line-height:1", class: "background-lightblue font-weight-bold small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'coste_partido', label: 'Coste<br>Partido', thClass: "background-yellow font-weight-bold", thStyle: "line-height:1", class: "background-yellow small text-center", sortable: true },
          { key: 'total_partidos', label: 'Total&nbsp;€<br>Partidos', thClass: "background-lightblue font-weight-bold", thStyle: "line-height:1", class: "background-lightblue font-weight-bold small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'total_torneos', label: 'Pluses&nbsp;€<br>Torneos', thClass: "background-lightblue font-weight-bold", thStyle: "line-height:1", class: "background-lightblue font-weight-bold small text-right", formatter: 'formatCurrency', sortable: true },
          { key: 'total_pelotari', label: 'TOTAL&nbsp;€<br>PELOTARI', thClass: "background-deepblue font-weight-bold", thStyle: "line-height:1", class: "background-deepblue font-weight-bold small text-right", formatter: 'formatCurrency', sortable: true },
        ]
      },
      showError( show, msg ) {
        if( true == show ) {
          this.nominas = [];

          this.show_nominas = false;
          this.show_error = true;
          this.error_msg = msg;
        } else {
          this.error_msg = '';
          this.show_error = false;
          this.show_nominas = true;
        }
      },
      showNominas( show ) {
        if( true == show ) {
          this.showError(false);
          this.calcularNominas();
        }
      }
    },
    components: {
      JsonExcel
    }
  }
</script>

<style focused>
  .background-deepblue {
    background-color:deepskyblue;
  }
  .background-green {
    background-color:yellowgreen;
  }
  .background-lightblue {
    background-color:powderblue;
  }
  .background-yellow {
    background-color:lightgoldenrodyellow;
  }

  .border-bottom-bold {
    border-bottom:2px solid black;
  }
  .border-bottom-thin {
    border-bottom:1px solid black;
  }
  .border-top-bold {
    border-top:2px solid black;
  }
  .border-top-thin {
    border-top:1px solid black;
  }

  .fa-exclamation-triangle {
    color:orangered;
  }

  #detail-campeonatos .border {
    border:1px solid black!important;
    margin-bottom:-1px;
  }
  #detail-campeonatos .border-left {
    border-left:1px solid black!important;
  }

  #table-nominas .b-table-details {
    background:#eeffee;
  }

  #table-nominas tbody tr:hover .background-deepblue {
    background-color:#1d96bf;
  }
  #table-nominas tbody tr:hover .background-green {
    background-color:#78a025;
  }
  #table-nominas tbody tr:hover .background-lightblue {
    background-color:#a2cbd0;
  }
  #table-nominas tbody tr:hover .background-yellow {
    background-color:#eaeaab;
  }

  .animate {
    font-size:25px;
    position: absolute;
    -webkit-animation:spin 1.5s linear infinite;
    -moz-animation:spin 1.5s linear infinite;
    animation:spin 1.5s linear infinite;

    filter:alpha(opacity=50);
    opacity:.5;
  }
  @-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
  @-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
  @keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
</style>
