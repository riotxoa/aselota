export default {
  // Tabla principal: Macrociclos
  ADD_MACROCICLO( state, value ) {
    state.macrociclos.unshift(value);
  },
  ADD_MESOCICLO_TO_MACROCICLO( state, value ) {
    state.macrociclo.mesociclos.unshift(value)
  },

  RESET_MACROCICLOS( state ) {
    state.macrociclos = [];
  },
  RESET_MACROCICLO( state ) {
    state.macrociclo = {
      id: null,
      order: null,
      fecha_ini: null,
      fecha_fin: null,
      description: '',
      objetivos: '',
      mesociclos: [],
    }
  },

  SET_MACROCICLOS( state, value ) {
    state.macrociclos = value;
  },
  SET_MACROCICLO_ID( state, value ) {
    state.macrociclo.id = value;
  },
  SET_MACROCICLO( state, value )  {
    state.macrociclo = value;
  },

  UPDATE_MESOCICLO_FROM_MACROCICLO( state, value ) {
    const index = state.macrociclo.mesociclos.findIndex(mesociclo => mesociclo.id === value.id)
    state.macrociclo.mesociclos[index] = value
  },

  // Tabla principal: Mesociclos
  ADD_MESOCICLO( state, value ) {
    state.macrociclo.mesociclos.unshift(value);
  },

  RESET_MESOCICLOS( state ) {
    state.macrociclo.mesociclos = [];
  },
  RESET_MESOCICLO( state ) {
    state.mesociclo = {
      id: null,
      order: null,
      macrociclo_id: null,
      macrociclo_desc: '',
      tipo_mesociclo_id: null,
      fecha_ini: null,
      fecha_fin: null,
      description: '',
      objetivos: '',
      microciclos: [],
    }
  },

  SET_MESOCICLO_ID( state, value ) {
    state.mesociclo.id = value;
  },
  SET_MESOCICLO( state, value )  {
    state.mesociclo = value;
  },
  SET_MESOCICLOS( state, value ) {
    state.macrociclo.mesociclos = value;
  },

  // Tabla principal: Microciclos
  ADD_MICROCICLO( state, value ) {
    state.mesociclo.microciclos.unshift(value);
  },

  RESET_MICROCICLOS( state ) {
    state.mesociclo.microciclos = [];
  },
  RESET_MICROCICLO( state ) {
    state.microciclo = {
      id: null,
      order: null,
      mesociclo_id: null,
      mesociclo_desc: '',
      tipo_microciclo_id: null,
      fecha_ini: null,
      fecha_fin: null,
      volumen: null,
      intensidad: null,
      descripcion: '',
      objetivos: '',
      sesiones: []
    }
  },

  SET_MICROCICLOS( state, value ) {
    state.mesociclo.microciclos = value;
  },
  SET_MICROCICLO_ID( state, value ) {
    state.microciclo.id = value;
  },
  SET_MICROCICLO( state, value )  {
    state.microciclo = value;
  },

  // Tabla principal: Sesiones
  ADD_SESION( state, value ) {
    state.microciclo.sesioness.unshift(value);
  },

  RESET_SESIONES( state ) {
    state.microciclo.sesiones = [];
  },
  RESET_SESION( state ) {
    state.sesion = {
      id: null,
      order: null,
      microciclo_id: null,
      fecha: null,
      hora: null,
      fronton_id: null,
      ejercicios: []
    }
  },

  SET_SESIONES( state, value ) {
    state.microciclo.sesiones = value;
  },
  SET_SESION_ID( state, value ) {
    state.sesion.id = value;
  },
  SET_SESION( state, value )  {
    state.sesion = value;
  },
  SET_EJERCICIOS( state, value ) {
    state.sesion.ejercicios = value;
  },

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
