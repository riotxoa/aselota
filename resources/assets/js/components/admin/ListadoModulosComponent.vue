<template>
  <div class="admin row">
    <div class="col-12">
      <b-button><router-link class="text-white" :to="{ name: 'RRHH', params: { userRole: 'admin' } }">Módulo RRHH</router-link></b-button>
    </div>
    <div class="col-12">
      <b-button><router-link class="text-white" :to="{ name: 'GERENTE', params: { userRole: 'admin' } }">Módulo Gestión Festivales</router-link></b-button>
    </div>
    <div class="col-12">
      <b-button><router-link class="text-white" :to="{ name: 'INTENDENTE', params: { userRole: 'admin' } }">Módulo Técnico Deportivo</router-link></b-button>
    </div>
    <div class="col-12">
      <b-button disabled>Módulo Entrenador</b-button>
    </div>
    <div class="col-12">
      <b-button disabled>Módulo Comunicación</b-button>
    </div>
    <div class="col-12">
      <b-button disabled>Módulo Médico</b-button>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {

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
</style>
