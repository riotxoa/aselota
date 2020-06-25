var Functions = {
  methods: {
    formatCurrency( value ) {
      const formatter = new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'EUR'
      })

      return formatter.format(value);
    },
    getTotalPrimasCampeonato( item ) {
      var total = 0;

      this.fases.map( (fase, key) => {
        var incluidos = 0;

        item.campeonatos[fase.campeonato_id]['fases'][fase.fase].fechas.map( (val) => {
          incluidos += (val.incluido ? 1 : 0);
        });

        total += incluidos * item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['prima']
        item.campeonatos[fase.campeonato_id]['fases'][fase.fase]['incluidos'] = incluidos
      })

      return total;
    },
    onClickTableRow( item, index, ev ) {
      this.$set(item, '_showDetails', !item._showDetails)
    },
  }
}

export default Functions;
