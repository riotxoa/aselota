export default {
  // Tabla principal: Ejercicios
  ADD_EJERCICIO( state, value ) {
    state.ejercicios.unshift(value);
  },

  RESET_EJERCICIOS( state ) {
    state.ejercicios = [];
  },
  RESET_EJERCICIO( state ) {
    state.ejercicio = {
      id: null,
      order: null,
      name: null,
      desc: null,
      tipo_ejercicio_id: null,
      subtipo_ejercicio_id: null,
      image: null,
      file: null,
      fileName: '',
      video: null,
      material: null,
    }
  },

  SET_EJERCICIOS( state, value ) {
    state.ejercicios = value;
  },
  SET_EJERCICIO_ID( state, value ) {
    state.ejercicio.id = value;
  },
  SET_EJERCICIO_IMAGEN( state, value ) {
    state.ejercicio.imagen = value.image;
    state.ejercicio.image = value.image;
    state.ejercicio.file = value.file;
    state.ejercicio.fileName = value.fileName;
  },
  SET_EJERCICIO( state, value )  {
    state.ejercicio = value;
  },


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
  RESET_SUBTIPOS_EJERCICIO( state ) {
    state.subtipos_ejercicio = [];
  },
  RESET_TIPOS_EJERCICIO( state )  {
    state.tipos_ejercicio = [];
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
  },

  SET_SUBTIPOS_EJERCICIO( state, value ) {
    state.subtipos_ejercicio = value;
  },
  SET_TIPOS_EJERCICIO( state, value ) {
    state.tipos_ejercicio = value;
  }
}
