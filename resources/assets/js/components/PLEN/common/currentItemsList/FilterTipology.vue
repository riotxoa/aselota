<template>
  <b-button-group>
    <b-button class="btn-tipology" variant="outline-primary" size="sm" @click="onClickTipologyMacrociclos" :pressed="activeTipology['macrociclos']">Macrociclos</b-button>
    <b-button class="btn-tipology" variant="outline-primary" size="sm" @click="onClickTipologyMesociclos" :pressed="activeTipology['mesociclos']">Mesociclos</b-button>
    <b-button class="btn-tipology" variant="outline-primary" size="sm" @click="onClickTipologyMicrociclos" :pressed="activeTipology['microciclos']">Microciclos</b-button>
    <b-button class="btn-tipology" variant="outline-primary" size="sm" @click="onClickTipologySesiones" :pressed="activeTipology['sesiones']">Sesiones</b-button>
    <b-button class="btn-tipology" variant="outline-primary" size="sm" @click="onClickTipologyAll" :pressed="activeTipology['all']">Todos</b-button>
  </b-button-group>
</template>

<script>
  export default {
    data() {
      return {
        activeTipology: {
          'macrociclos': true,
          'mesociclos': false,
          'microciclos': false,
          'sesiones': false,
          'all': false
        },
      }
    },
    methods: {
      deactivateAllTipologies() {
        const keys = Object.keys(this.activeTipology);
        keys.map( (val) => {
          this.activeTipology[val] = false;
        });
      },
      onClickTipologyMacrociclos() {
        this.setActiveTipology('macrociclos');
      },
      onClickTipologyMesociclos() {
        this.setActiveTipology('mesociclos');
      },
      onClickTipologyMicrociclos() {
        this.setActiveTipology('microciclos');
      },
      onClickTipologySesiones() {
        this.setActiveTipology('sesiones');
      },
      onClickTipologyAll() {
        this.setActiveTipology('all');
      },
      setActiveTipology(tipology) {
        this.deactivateAllTipologies();
        this.activeTipology[tipology] = true;
      }
    }
  }
</script>
