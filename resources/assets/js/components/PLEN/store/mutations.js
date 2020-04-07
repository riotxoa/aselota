export default {
  // Tabla auxiliar: Fases de Sesión
  RESET_FASES( state ) {
    state.fases = [];
  },
  RESET_FASE( state ) {
    state.fase = {
      id: null,
      order: null,
      desc: '',
    }
  },

  SET_FASES( state, value ) {
    state.fases = value;
  },
  SET_FASE_ID( state, value ) {
    state.fase.id = value;
  },
  SET_FASE_ORDER( state, value ) {
    state.fase.order = value;
  },
  SET_FASE_DESC( state, value ) {
    state.fase.desc = value;
  }
}
