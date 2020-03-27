<template>
  <div id="plennavbar">
    <!-- Menu for small devices (tablets and mobiles) -->
    <b-col class="d-xl-none mb-4 p-0">

      <b-navbar toggleable type="dark" variant="dark">
        <b-container class="p-0 px-md-3">
          <b-navbar-brand href="#"><b-icon-home class="mr-2"></b-icon-home>Inicio Entrenador</b-navbar-brand>

          <b-navbar-toggle target="navbar-toggle-collapse" @click="() => { expanded = !expanded }" style="border-color:#cfcfcf; border-radius:0; color:#ffffff;">
            <b-icon-arrow-up v-if="expanded"></b-icon-arrow-up>
            <b-icon-arrow-down v-else></b-icon-arrow-down>
          </b-navbar-toggle>

          <b-collapse id="navbar-toggle-collapse" is-nav>
            <b-navbar-nav class="m-0">
              <b-nav-item href="#">Mesociclos</b-nav-item>
              <b-nav-item href="#">Sesiones</b-nav-item>
              <b-nav-item-dropdown text="Auxiliares" right>
                <b-dropdown-item href="#">Tipos de Mesociclo</b-dropdown-item>
                <b-dropdown-item href="#">Tipos de Microciclo</b-dropdown-item>
                <b-dropdown-item href="#">Tipos de Ejercicio</b-dropdown-item>
                <b-dropdown-item href="#">Sub-Tipos de Ejercicio</b-dropdown-item>
                <b-dropdown-item href="#">Fases de Sesión</b-dropdown-item>
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
        <b-nav-item href="#"><b-icon-home class="mr-2"></b-icon-home>Inicio Entrenador</b-nav-item>
        <b-nav-item href="#"><b-icon-macro class="mr-2"></b-icon-macro>Mesociclos</b-nav-item>
        <b-nav-item href="#"><b-icon-sesion class="mr-2"></b-icon-sesion>Sesiones</b-nav-item>
        <b-dropdown-divider></b-dropdown-divider>
        <b-nav-item-dropdown id="dropdownmenu" :html="auxiliares_label" toggle-class="nav-link-custom" menu-class="plen-drop-menu" left>
          <b-dropdown-item href="#"><b-icon-tipomeso class="mr-2"></b-icon-tipomeso>Tipos de Mesociclo</b-dropdown-item>
          <b-dropdown-item href="#"><b-icon-tipomicro class="mr-2"></b-icon-tipomicro>Tipos de Microciclo</b-dropdown-item>
          <b-dropdown-item href="#"><b-icon-tipoejercicio class="mr-2"></b-icon-tipoejercicio>Tipos de Ejercicio</b-dropdown-item>
          <b-dropdown-item href="#"><b-icon-subtipoejercicio class="mr-2"></b-icon-subtipoejercicio>Sub-Tipos de Ejercicio</b-dropdown-item>
          <b-dropdown-item href="#"><b-icon-fases class="mr-2"></b-icon-fases>Fases de Sesión</b-dropdown-item>
        </b-nav-item-dropdown>
      </b-nav>
    </b-col>
    <!-- /Menu for bigger devices (big tablets and desktop) -->
  </div>
</template>

<script>
  import { BIcon, BIconBraces, BIconCardList, BIconChevronBarUp, BIconChevronBarDown, BIconHouse, BIconLayers, BIconList, BIconListNested, BIconSoundwave, BIconTable, BIconViewList, BIconWatch } from 'bootstrap-vue'

  export default {
    data () {
      return {
        auxiliares_label: '<svg class="bi bi-table mr-2" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M14 1H2a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V2a1 1 0 00-1-1zM2 0a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V2a2 2 0 00-2-2H2z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M15 4H1V3h14v1z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M5 15.5v-14h1v14H5zm5 0v-14h1v14h-1z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M15 8H1V7h14v1zm0 4H1v-1h14v1z" clip-rule="evenodd"/><path d="M0 2a2 2 0 012-2h12a2 2 0 012 2v2H0V2z"/></svg>Auxiliares',
        expanded: false,
      }
    },
    components: {
      'b-icon': BIcon,
      'b-icon-arrow-up': BIconChevronBarUp,
      'b-icon-arrow-down': BIconChevronBarDown,
      'b-icon-card': BIconCardList,
      'b-icon-fases': BIconLayers,
      'b-icon-home': BIconHouse,
      'b-icon-macro': BIconViewList,
      'b-icon-sesion': BIconWatch,
      'b-icon-subtipoejercicio': BIconListNested,
      'b-icon-tabla': BIconTable,
      'b-icon-tipoejercicio': BIconList,
      'b-icon-tipomeso': BIconSoundwave,
      'b-icon-tipomicro': BIconBraces,
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
  #dropdownmenu .plen-drop-menu {
    background:transparent;
    border:none;
    /* left:-7.5px!important; */
  }

  #plennavbar li.nav-item:hover > a {
    background-color:#efefef;
  }
  #plennavbar a {
    color:#333333;

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
