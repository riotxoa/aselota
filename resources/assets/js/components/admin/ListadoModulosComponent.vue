<template>
  <div class="admin row">
    <div class="col-12">
      <router-link class="text-white" :to="{ name: 'RRHH', params: { userRole: 'admin' } }"><div class="btn btn-secondary">Módulo RRHH</div></router-link>
    </div>
    <div class="col-12">
      <router-link class="text-white" :to="{ name: 'GERENTE', params: { userRole: 'admin' } }"><div class="btn btn-secondary">Módulo Gestión Festivales</div></router-link>
    </div>
    <div class="col-12">
      <router-link class="text-white" :to="{ name: 'INTENDENTE', params: { userRole: 'admin' } }"><div class="btn btn-secondary">Módulo Técnico Deportivo</div></router-link>
    </div>
    <div class="col-12">
      <div class="btn btn-secondary disabled">
        Módulo Entrenador
      </div>
    </div>
    <div class="col-12">
      <div class="btn btn-secondary disabled">
        Módulo Comunicación
      </div>
    </div>
    <div class="col-12">
      <div class="btn btn-secondary disabled">
        Módulo Médico
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {

      }
    },
    created() {
      console.log("SORTUTA")
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
