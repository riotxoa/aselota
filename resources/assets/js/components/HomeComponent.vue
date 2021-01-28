<template>
  <div class="w-100">
    <router-view></router-view>
    <Notificaciones />
  </div>
</template>

<script>
  import Notificaciones from './notificaciones/Notificaciones';

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
        case 'entrenador':
          this.home = '/entrenador';
          this.title= "Gestión de Entrenamientos";
          this.$router.push({
            name: 'ENTRENADOR',
            params: { userRole: this.userRole },
          })
          break;
        case 'prensa':
          this.home = '/prensa';
          this.title = "Gestión de Eventos de Prensa";
          this.$router.push({
            name: 'PRENSA',
            params: { userRole: this.userRole },
          })
          break;
        case 'cuadro':/* #MODIF4 */
          this.home = '/cuadro';
          this.title = "Cuadro de Mando";
          this.$router.push({
            name: 'CUADRO',
            params: { userRole: this.userRole },
          })
          break;
        case 'medico':
          this.home = '/modulo-medico';
          this.title = "Partes Médicos";
          this.$router.push({
            name: 'MÉDICO 2',
            params: { userRole: this.userRole },
          })
          break;
        case 'plen_gestor': // PLEN: Gestor de Planificación de Entrenamientos
          this.home = '/PLEN';
          this.title = "PLANIFICACIÓN DE ENTRENAMIENTOS";
          this.$router.push({
            name: 'PLEN_GESTOR',
            params: { userRole: this.userRole },
          })
          break;
        case 'plen_entrenador': // PLEN: Entrenador
            this.home = '/PLEN';
            this.title = "PLANIFICACIÓN DE ENTRENAMIENTOS";
            this.$router.push({
              name: 'PLEN_ENTRENADOR',
              params: { userRole: this.userRole },
            })
            break;
        default:
          this.title = "SIN PERMISOS";
          break;
      }
    },
    components: {
      Notificaciones
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
