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
      <router-link class="text-white" :to="{ name: 'ENTRENADOR', params: { userRole: 'admin' } }"><div class="btn btn-secondary">Módulo Gestión Entrenamientos</div></router-link>
    </div>
    <div class="col-12">
      <router-link class="text-white" :to="{ name: 'PRENSA', params: { userRole: 'admin' } }"><div class="btn btn-secondary">Módulo Comunicación</div></router-link>
    </div>
    <div class="col-12">
      <router-link class="text-white" :to="{ name: 'MÉDICO', params: { userRole: 'admin' } }"><div class="btn btn-secondary">Módulo Médico</div></router-link>
    </div>
    <div class="col-12">
      <router-link class="text-white" :to="{ name: 'MÉDICO 2', params: { userRole: 'admin' } }"><div class="btn btn-secondary">Módulo Médico 2</div></router-link>
    </div>
    <div class="col-12"><!-- #MODIF4 -->
      <router-link class="text-white" :to="{ name: 'CUADRO', params: { userRole: 'admin' } }"><div class="btn btn-secondary">Cuadro de Mando</div></router-link>
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
