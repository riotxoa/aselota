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

      <b-tabs pills card v-model="tabIndex" class="w-100">
        <b-tab title="Vista compacta" title-item-class="text-uppercase font-weight-bold" class="px-0">
          <NominasCompactas :nominas="nominas" :fases="fases" v-on:ChangePrimaCampeonato="onChangePrimaCampeonato"/>
        </b-tab>
        <b-tab title="Vista detallada" title-item-class="text-uppercase font-weight-bold" class="px-0">
          <NominasDetalladas :nominas="nominas" :fases="fases" v-on:ChangePrimaCampeonato="onChangePrimaCampeonato"/>
        </b-tab>
      </b-tabs>
    </b-row>

    <b-row v-if="show_nominas && !loading" class="small">
      <b-col cols="12" md="8">
        <JsonExcel v-if="tabIndex > 0"
          :data="nominas"
          :fields="export_fields"
          :worksheet="export_worksheet"
          :name="export_name">
          <b-btn size="sm" variant="primary"><i class="fas fa-file-excel mr-2"></i>Excel Vista Detallada</b-btn>
        </JsonExcel>
        <JsonExcel v-else
          :data="nominas"
          :fields="export_fields_compacta"
          :worksheet="export_worksheet"
          :name="export_name">
          <b-btn size="sm" variant="primary"><i class="fas fa-file-excel mr-2"></i>Excel Vista Compacta</b-btn>
        </JsonExcel>
      </b-col>
      <b-col cols="6" md="2"><big><strong>Total Pelotaris:</strong></big></b-col>
      <b-col cols="6" md="2" class="text-md-right"><big><strong>{{ formatCurrency(total_pelotaris) }}</strong></big></b-col>
      <b-col cols="6" md="2" class="offset-md-8">Total Partidos:</b-col>
      <b-col cols="6" md="2" class="text-md-right">{{ formatCurrency(total_partidos) }}</b-col>
      <b-col cols="6" md="2" class="offset-md-8">Total Estelares:</b-col>
      <b-col cols="6" md="2" class="text-md-right">{{ formatCurrency(total_estelares) }}</b-col>
      <b-col cols="6" md="2" class="offset-md-8">Total Torneos:</b-col>
      <b-col cols="6" md="2" class="text-md-right">{{ formatCurrency(total_torneos) }}</b-col>
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
  import Functions from './nominas_functions.js';
  import NominasCompactas from './NominasCompactas';
  import NominasDetalladas from './NominasDetalladas';

  export default {
    mixins: [APIGetters, Functions, Utils],
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
        total_dietas: 0,
        total_estelares: 0,
        total_partidos: 0,
        total_torneos: 0,
        total_pelotaris: 0,
        export_fields: {
          'NºTrabajador': 'num_trabajador',
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
        export_fields_compacta: {
          'Nº TRABAJADOR': 'num_trabajador',
          'Nº PART. PAGAR': 'partidos_asistencia',
          'IMPORTE PARTIDOS': {
            field: 'total_partidos',
            callback: (total) => {
              return this.formatCurrencyShort(total);
            }
          },
          'PLUS ESTELARES': {
            field: 'total_estelares',
            callback: (value) => {
              return this.formatCurrencyShort(value);
            }
          },
          'PLUS CAMPEONATOS': {
            field: 'total_torneos',
            callback: (total) => {
              return this.formatCurrencyShort(total);
            }
          },
          'IRPF': {
            callback: (item) => {
              return this.formatCurrencyShort(item.total_partidos + item.total_estelares + item.total_torneos);
            }
          },
          'TOTAL DIETAS': 'total_dietas'
        },
        export_name: '',
        export_worksheet: '',
        tabIndex: 0,
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
      resetTotales() {
        this.total_dietas = 0;
        this.total_estelares = 0;
        this.total_partidos = 0;
        this.total_torneos = 0;
        this.total_pelotaris = 0;
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
      JsonExcel,
      NominasCompactas,
      NominasDetalladas
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
