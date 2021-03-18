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
      }
    },
    methods: {
      getTxosten01() {
        var query = '';
        if( this.fecha_ini || this.fecha_fin ) {
          query += '?';
        }
        if( this.fecha_ini ) {
          query += 'fecha_ini=' + this.fecha_ini + '&';
        }
        if( this.fecha_fin ) {
          query += 'fecha_fin=' + this.fecha_fin + '&';
        }
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
