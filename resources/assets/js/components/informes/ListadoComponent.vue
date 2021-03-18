<template>
  <div class="admin row">
    <div class="col-12">
      <div class="btn btn-secondary" v-on:click="onClickTxosten01()">Pelotazos y Duración</div>
    </div>
    <b-modal id="modalTxosten01" ref="modalTxosten01" title="Criterios de generación de informe" @ok="getTxosten01()">
      <b-form>
        <b-row>
          <b-col cols="12">
            <label for="fecha-ini">Desde fecha:</label>
            <input type="date" id="fecha-ini" v-model="fecha_ini" class="mb-2"></input>
          </b-col>
          <b-col cols="12">
            <label for="fecha-ini">Hasta fecha:</label>
            <input type="date" id="fecha-fin" v-model="fecha_fin" class="mb-2"></input>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Formato listado:">
              <!-- <b-form-radio v-model="formato" name="format-radios" value="PDF">Generar PDF</b-form-radio> -->
              <!-- <b-form-radio v-model="formato" name="format-radios" value="XLS">Generar Hoja de Cálculo (XLS)</b-form-radio> -->
              <b-form-radio-group
                id="radio-group-formato"
                v-model="formato"
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
  export default {
    data() {
      return {
        fecha_ini: null,
        fecha_fin: null,
        formato: 'PDF',
      }
    },
    methods: {
      getTxosten01() {
        var query = '';
        if( this.fecha_ini || this.fecha_fin || this.formato ) {
          query += '?';
        }
        if( this.fecha_ini ) {
          query += 'fecha_ini=' + this.fecha_ini + '&';
        }
        if( this.fecha_fin ) {
          query += 'fecha_fin=' + this.fecha_fin + '&';
        }
        if( this.formato ) {
          query += 'formato=' + this.formato + '&';
        }
        console.log("[getTxosten01] query: " + query);
        var redirectWindow = window.open('/www/informes/pilotakadak-eta-iraupena' + query, '_blank');
        redirectWindow.location;
      },
      onClickTxosten01() {
        this.$refs['modalTxosten01'].show()
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
