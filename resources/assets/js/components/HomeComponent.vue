<template>
  <router-view></router-view>
</template>

<script>
  export default {
    props: ['userRole'],
    data() {
      return {
        home: '',
        title: '',
      }
    },
    created: function() {
      switch(this.userRole) {
        case 'admin':
          this.home = '/administrador';
          this.title = "administrador";
          this.$router.push('/administrador');
          break;
        case 'rrhh':
          this.home = '/rrhh';
          this.title = "Gestión de RRHH";
          this.$router.push({
            name: 'RRHH',
            params: { userRole: this.userRole },
          })
          break;
        case 'gerente':
          this.home = '/gerente';
          this.title = "Gestión de Festivales";
          this.$router.push({
            name: 'GERENTE',
            params: { userRole: this.userRole },
          })
          break;
        case 'intendente':
          this.home = '/intendente';
          this.title= "Gestión de Festivales";
          this.$router.push({
            name: 'INTENDENTE',
            params: { userRole: this.userRole },
          })
          break;
        default:
          this.title = "SIN PERMISOS";
          break;
      }
    }
  }
</script>

<style>
  .card,
  .card-header,
  .card-header:first-child,
  .btn,
  .page-item:first-child .page-link,
  .page-item:last-child .page-link,
  .nav-pills a,
  .modal-content,
  .modal-header,
  input, select, textarea {
    border-radius:0!important;
  }

  .modal-content {
    border:none;
  }
  .modal-header {
    background-color: #007fff;
    color:white;
    text-transform:uppercase;
  }
</style>
