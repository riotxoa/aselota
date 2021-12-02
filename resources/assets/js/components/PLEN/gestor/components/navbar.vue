<template>
  <div id="plennavbar">
    <!-- Menu for small devices (tablets and mobiles) -->
    <b-col class="d-xl-none mb-4 p-0">

      <b-navbar toggleable type="dark" variant="dark">
        <b-container class="p-0 px-md-3">
          <b-navbar-brand :to="{ name: 'PLEN_GESTOR_Inicio' }"><i class="fa fa-home mr-2"></i>Inicio</b-navbar-brand>

          <b-navbar-toggle target="navbar-toggle-collapse" @click="() => { expanded = !expanded }" style="border-color:#cfcfcf; border-radius:0; color:#ffffff;">
            <i v-if="expanded" class="fa fa-caret-up" aria-hidden="true"></i>
            <i v-else class="fa fa-caret-down" aria-hidden="true"></i>
          </b-navbar-toggle>

          <b-collapse id="navbar-toggle-collapse" is-nav>
            <b-navbar-nav class="m-0">
              <b-nav-item :to="{ name: 'PLEN_GESTOR_Planificador' }">Planificador</b-nav-item>
              <b-nav-item :to="{ name: 'PLEN_GESTOR_Ejercicios' }">Ejercicios</b-nav-item>
              <b-nav-item-dropdown text="Auxiliares" right>
                <b-dropdown-item :to="{ name: 'PLEN_GESTOR_TiposMeso' }">Tipos de Mesociclo</b-dropdown-item>
                <b-dropdown-item :to="{ name: 'PLEN_GESTOR_TiposMicro' }">Tipos de Microciclo</b-dropdown-item>
                <b-dropdown-item :to="{ name: 'PLEN_GESTOR_TiposEjercicio' }">Tipos de Ejercicio</b-dropdown-item>
                <b-dropdown-item :to="{ name: 'PLEN_GESTOR_SubtiposEjercicio' }">Sub-Tipos de Ejercicio</b-dropdown-item>
                <b-dropdown-item :to="{ name: 'PLEN_GESTOR_Fases' }">Fases de Sesión</b-dropdown-item>
              </b-nav-item-dropdown>
            </b-navbar-nav>
          </b-collapse>
        </b-container>
      </b-navbar>
    </b-col>
    <!-- /Menu for small devices (tablets and mobiles) -->

    <!-- Menu for bigger devices (big tablets and desktop) -->
    <b-col class="d-none d-xl-inline">
      <b-nav class="plen-side-nav" vertical>
        <b-nav-item :to="{ name: 'PLEN_GESTOR_Inicio' }"><i class="fa fa-home mr-2"></i>Inicio</b-nav-item>
        <b-nav-item :to="{ name: 'PLEN_GESTOR_Planificador' }"><i class="fa fa-arrows-alt-h mr-2"></i>Planificador</b-nav-item>
        <b-nav-item :to="{ name: 'PLEN_GESTOR_Ejercicios' }"><i class="fa fa-chevron-right mr-2"></i>Ejercicios</b-nav-item>
        <b-dropdown-divider></b-dropdown-divider>
        <b-nav-item class="dropdown position-relative">
          <a class="nav-link dropdown-toggle p-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class='fas fa-table mr-2'></i>Auxiliares</a>
          <div id="dropdownmenu" class="dropdown-menu plen-drop-menu">
            <router-link class="dropdown-item" :to="{ name: 'PLEN_GESTOR_TiposMeso' }"><i class="far fa-calendar mr-2"></i>Tipos de Mesociclo</router-link>
            <router-link class="dropdown-item" :to="{ name: 'PLEN_GESTOR_TiposMicro' }"><i class="far fa-calendar-alt mr-2"></i>Tipos de Microciclo</router-link>
            <router-link class="dropdown-item" :to="{ name: 'PLEN_GESTOR_TiposEjercicio' }"><i class="fas fa-bars mr-2"></i>Tipos de Ejercicio</router-link>
            <router-link class="dropdown-item" :to="{ name: 'PLEN_GESTOR_SubtiposEjercicio' }"><i class="fas fa-stream mr-2"></i>Sub-Tipos de Ejercicio</router-link>
            <router-link class="dropdown-item" :to="{ name: 'PLEN_GESTOR_Fases' }"><i class="fas fa-sort-amount-down mr-2"></i>Fases de Sesión</router-link>
          </div>
        </b-nav-item>
      </b-nav>
    </b-col>
    <!-- /Menu for bigger devices (big tablets and desktop) -->
  </div>
</template>

<script>
  export default {
    data () {
      return {
        expanded: false,
      }
    },
  }
</script>

<style focused>
  .navbar-laravel > .container {
    max-width:calc(100% - 1rem);
  }
  #plennavbar {
    margin-top:-1.5rem;
  }

  @media all and (min-width:1200px) {
    #plennavbar {
      margin-top:-1.45rem;
    }
  }
</style>

<style>
  #dropdownmenu.plen-drop-menu {
    background:transparent;
    border:none;
    left:-1.25rem!important;
    top:.25rem!important;
  }

  #plennavbar .d-none li.nav-item:hover > a {
    background-color:#efefef;
  }
  #plennavbar .d-none a {
    color:#333333;
    text-decoration:none;

    -webkit-transition:all .15s ease-in-out;
    -moz-transition:all .15s ease-in-out;
    -ms-transition:all .15s ease-in-out;
    -o-transition:all .15s ease-in-out;
    transition:all .15s ease-in-out;
  }

  .plen-side-nav {
    background-color:#ffffff;
    border-right:2px solid #efefef;
    height:100vh;
    margin-top:-1.5rem;
    padding-bottom:1.5rem;
    padding-top:1.5rem;
    z-index:0;
  }
</style>
