<template>
  <div>
    <b-nav>
      <b-nav-item-dropdown
        id="my-nav-informes"
        text="Informes"
        toggle-class="nav-link-custom"
        right
      >
        <b-dropdown-item v-on:click="onClickPelotazosDuracion()">PARTIDOS: Pelotazos y Duración</b-dropdown-item>
      </b-nav-item-dropdown>
    </b-nav>
    <ModalPelotazosDuracion />
  </div>
</template>

<script>
import ModalPelotazosDuracion from './ModalPelotazosDuracionComponent.vue';

export default {
  components: {
    ModalPelotazosDuracion,
  },
  methods: {
    onClickPelotazosDuracion() {
      this.$root.$emit('bv::show::modal','modalTxosten01')
    }
  }
}
</script>

<style>
#my-nav-informes a.dropdown-toggle {
  color:white;
}
</style>
