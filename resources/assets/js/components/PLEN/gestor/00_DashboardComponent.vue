<template>
  <b-container fluid>

    <b-row>

      <b-col v-if="showMenu" xl="2" class="px-0">
        <plen-navbar></plen-navbar>
      </b-col>

      <b-col :xl="showMenu ? 10 : 12" class="position-relative">
        <a id="toggleButton" class="position-absolute" :title="showMenu ? 'Ocultar menú' : 'Mostrar menú'" @click="toggleMenu()">
          <b-icon-double-left v-if="showMenu"></b-icon-double-left>
          <b-icon-double-right v-else></b-icon-double-right>
        </a>
        <div class="p-xl-3">
          <h1>KAIXO MUNDUA!! Gestor</h1>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
  import { BIconChevronDoubleLeft, BIconChevronDoubleRight } from 'bootstrap-vue';
  import PlenNavbar from './components/navbar.vue';
  import $ from 'jquery';

  export default {
    data() {
      return {
        showMenu: true,
      }
    },
    methods: {
      toggleMenu() {
        this.showMenu = !this.showMenu;
      }
    },
    components: {
      'b-icon-double-left': BIconChevronDoubleLeft,
      'b-icon-double-right': BIconChevronDoubleRight,
      'plen-navbar': PlenNavbar,
    },
  }
</script>

<style focused>
  #toggleButton {
    background-color:#ffffff;
    border-bottom:2px solid #efefef;
    border-right:2px solid #efefef;
    cursor:pointer;
    height:40px;
    left:-0.1rem;
    padding-top:.5rem;
    text-align:center;
    top:-1.5rem;
    width:40px;

    -webkit-transition:all .15s ease-in-out;
    -moz-transition:all .15s ease-in-out;
    -ms-transition:all .15s ease-in-out;
    -o-transition:all .15s ease-in-out;
    transition:all .15s ease-in-out;
  }
  #toggleButton:hover {
    background-color:#fafafa;
  }
</style>
