<template>
  <div class="admin row">
    <div class="col-12">
      <div class="btn btn-secondary" v-on:click="onClickTxosten01()">Pelotazos y Duración</div>
    </div>
    <b-modal id="modalTxosten01" ref="modalTxosten01" title="INFORME DE PELOTAZOS Y DURACIÓN DE PARTIDOS" size="lg" @ok="getTxosten01()">
      <b-form>
        <b-row>
          <b-col cols="12" md="4" class="text-left">
            <b-form-group label="Desde fecha:" label-class="font-weight-bold">
              <input type="date" id="fecha-ini" v-model="txosten01.fecha_ini" class="mb-2"></input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4" class="text-left">
            <b-form-group label="Hasta fecha:" label-class="font-weight-bold">
              <input type="date" id="fecha-fin" v-model="txosten01.fecha_fin" class="mb-2"></input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12" md="4" class="text-left">
            <b-form-group label="Campeonato" label-class="font-weight-bold">
              <b-form-select v-model="txosten01.campeonato_id" :options="campeonatos"></b-form-select>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4" class="text-left">
            <b-form-group label="Tipo de Partido" label-class="font-weight-bold">
              <b-form-select v-model="txosten01.tipo_partido_id" :options="tipos_partido"></b-form-select>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4" class="text-left">
            <b-form-group label="Pelotari" label-class="font-weight-bold">
              <b-form-select v-model="txosten01.pelotari_id" :options="pelotaris"></b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row class="border-top">
          <b-col cols="12" class="mt-3 text-center">
            <b-form-group label="Formato listado:" label-class="font-weight-bold">
              <b-form-radio-group
                id="radio-group-formato"
                v-model="txosten01.formato"
              >
                <b-form-radio value="PDF">Generar PDF</b-form-radio>
                <b-form-radio value="XLS">Generar Hoja de Cálculo (XLS)</b-form-radio>
              </b-form-radio-group>
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-modal>
  </div>
</template>

<script>
  import APIGetters from '../utils/getters.js';

  export default {
    mixins: [APIGetters],
    data() {
      return {
        txosten01: {
          campeonato_id: null,
          fecha_ini: null,
          fecha_fin: null,
          formato: 'PDF',
          pelotari_id: null,
          show: false,
          tipo_partido_id: null,
        },
      }
    },
    created() {
      this.getTiposPartido();
      this.getCampeonatos().then( () => {
        this.getPelotaris().then( () => {
          this.show = true;
        });
      })
    },
    methods: {
      getTxosten01() {
        var query = '?';
        if( this.txosten01.fecha_ini ) {
          query += 'fecha_ini=' + this.txosten01.fecha_ini + '&';
        }
        if( this.txosten01.fecha_fin ) {
          query += 'fecha_fin=' + this.txosten01.fecha_fin + '&';
        }
        if( this.txosten01.campeonato_id ) {
          query += 'campeonato_id=' + this.txosten01.campeonato_id + '&';
        }
        if( this.txosten01.tipo_partido_id ) {
          query += 'tipo_partido_id=' + this.txosten01.tipo_partido_id + '&';
        }
        if( this.txosten01.pelotari_id ) {
          query += 'pelotari_id=' + this.txosten01.pelotari_id + '&';
        }
        if( this.txosten01.formato ) {
          query += 'formato=' + this.txosten01.formato + '&';
        }

        var redirectWindow = window.open('/www/informes/pilotakadak-eta-iraupena' + query, '_blank');
        redirectWindow.location;
      },
      onClickTxosten01() {
        this.resetTxosten01();
        this.$refs['modalTxosten01'].show()
      },
      resetTxosten01() {
        this.txosten01 = {
          campeonato_id: null,
          fecha_ini: null,
          fecha_fin: null,
          formato: 'PDF',
          pelotari_id: null,
          show: false,
          tipo_partido_id: null,
        }
      }
    }
  }
</script>

<style>
  .admin.row {
    text-align:center;
  }
  .admin .btn {
    margin-bottom:.5rem;
    width:250px;
  }
  .admin .btn a:active,
  .admin .btn a:focus,
  .admin .btn a:hover {
    text-decoration:none;
  }
</style>
