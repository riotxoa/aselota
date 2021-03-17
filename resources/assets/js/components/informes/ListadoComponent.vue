<template>
  <div class="admin row">
    <div class="col-12">
      <div class="btn btn-secondary" v-on:click="onClickTxosten01()">Pilotakadak eta iraupena</div>
    </div>
  </div>
</template>

<script>
  export default {
    methods: {
      onClickTxosten01() {
        var redirectWindow = window.open('/www/informes/pilotakadak-eta-iraupena', '_blank');
        redirectWindow.location;
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
