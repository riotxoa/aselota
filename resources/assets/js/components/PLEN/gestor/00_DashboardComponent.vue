<template>
  <b-container fluid>

    <b-row>

      <b-col v-if="showMenu" xl="2" class="px-0">
        <plen-navbar></plen-navbar>
      </b-col>

      <b-col :xl="showMenu ? 10 : 12" class="position-relative">
        <a id="toggleButton" class="d-none d-xl-inline position-fixed" :title="showMenu ? 'Ocultar menú' : 'Mostrar menú'" @click="toggleMenu()">
          <i v-if="showMenu" class="fas fa-angle-double-left"></i>
          <i v-else class="fas fa-angle-double-right"></i>
        </a>
        <div id="render-route">
          <router-view></router-view>
        </div>
      </b-col>
    </b-row>
    <div id="snackbar"></div>
  </b-container>
</template>

<script>
  import PlenNavbar from './components/navbar.vue';
  import $ from 'jquery';

  export default {
    data() {
      return {
        showMenu: true,
      }
    },
    created() {
      this.$router.push({ name: 'PLEN_GESTOR_Inicio' });
    },
    methods: {
      toggleMenu() {
        this.showMenu = !this.showMenu;
      }
    },
    components: {
      'plen-navbar': PlenNavbar,
    },
  }
</script>

<style focused>
  #render-route {
    left:0;
    margin-top:-1.5rem;
    position:absolute;
    top:0;
    width:100%;
    z-index:0;
  }
  #toggleButton {
    background-color:#444444bb;
    color:#ffffff;
    cursor:pointer;
    height:25px;
    left:0;
    padding-top:.05rem;
    text-align:center;
    top:3.5rem;
    width:41px;
    z-index:1;

    -webkit-transition:all .15s ease-in-out;
    -moz-transition:all .15s ease-in-out;
    -ms-transition:all .15s ease-in-out;
    -o-transition:all .15s ease-in-out;
    transition:all .15s ease-in-out;
  }
  #toggleButton:hover {
    background-color:#444444;
  }
</style>

<style>
  .module-title {
    background-color:#94c11f;
    color:white;
    font-weight: bold;
    text-transform: uppercase;
  }
</style>
