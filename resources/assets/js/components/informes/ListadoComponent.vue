<template>
  <div class="admin row">
    <div class="col-12">
      <div class="btn btn-secondary" v-on:click="onClickTxosten01()">Pelotazos y Duración</div>
    </div>
    <ModalPelotazosDuracion />
  </div>
</template>

<script>
  import ModalPelotazosDuracion from './ModalPelotazosDuracionComponent.vue';

  export default {
    components: {
      ModalPelotazosDuracion
    },
    methods: {
      onClickTxosten01() {
        this.$root.$emit('bv::show::modal','modalTxosten01')
      },
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
