export default {

  RESET_CAUSANTES: (state) => {
    state.aux.causantes = [];
  },
  RESET_CENTROS: (state) => {
    state.aux.centros = [];
  },
  RESET_GRADOS_LESION: (state) => {
    state.aux.grados_lesion = [];
  },
  RESET_LUGARES: (state) => {
    state.aux.lugares = [];
  },
  RESET_MEDICOS: (state) => {
    state.aux.medicos = [];
  },
  RESET_PRUEBAS: (state) => {
    state.aux.pruebas = [];
  },
  RESET_TIEMPOS_TRABAJO: (state) => {
    state.aux.tiempos_trabajo = [];
  },
  RESET_TIPOS_ASISTENCIA: (state) => {
    state.aux.tipos_asistencia = [];
  },

  RESET_PELOTARI: (state) => {
    state.pelotari = null;
  },
  RESET_PELOTARIS: (state) => {
    state.pelotaris = [];
  },
  RESET_PROMESAS: (state) => {
    state.promesas = [];
  },
  RESET_P_ACCIDENTE: (state) => {
    state.p_accidente =  {
      pelotari_id: null,
      fecha_parte: null,
      fecha_accidente: null,
      is_recaida: 0,
      is_baja: 0,
      med2_centro_id: null,
      med2_tipo_asistencia_id: null,
      med2_causante_id: null,
      med2_prueba_id: null,
      parte_cuerpo: '',
      diagnostico_ini: '',
      diagnostico_obs: '',
      evolucion: '',
      tratamiento: '',
    }
  },
  RESET_P_ENFERMEDAD: (state) => {
    state.p_enfermedad = {
      pelotari_id: null,
      fecha_asistencia: null,
      fecha_inicio: null,
      med2_tipo_asistencia_id: null,
      med2_prueba_id: null,
      diagnostico_ini: '',
      evolucion: '',
      tratamiento: '',
      fecha_alta: null,
    };
  },
  RESET_P_FISIOLOGIA: (state) => {
    state.p_fisiologia = {
      pelotari_id: null,
      fecha_asistencia: null,
      fecha_sintomas: null,
      fecha_pe: null,
      observaciones: '',
      test: '',
      observaciones_test: '',
      composicion: '',
      observaciones_comp: '',
      suplementacion: '',
    };
  },
  RESET_P_PREVENCION: (state) => {
    state.p_prevencion = {
      pelotari_id: null,
      fecha_asistencia: null,
      historia: '',
      reconocimiento: '',
      fecha_rec: null,
      analiticas: '',
      fecha_ana: null,
      pruebas: '',
      fecha_pru: null,
    };
  },
  RESET_P_DELTA: (state) => {
    state.p_delta = {
      p_accidente_id: null,
      fecha_accidente: null,
      fecha_baja: null,
      fecha_recaida: null,
      fecha_accidente_rec: null,
      med2_lugar_id: null,
      med2_causante_id: null,
      med2_tiempo_trabajo_id: null,
      parte_cuerpo: '',
      descripcion_lesion: '',
      med2_grado_lesion_id: null,
      med2_medico_id: null,
      med2_medico_txt: null,
      med2_tipo_asistencia_id: null,
      hora_at: null,
      tiempo_previsto: ''
    };
  },
  RESET_PARTES_ACC: (state) => {
    state.partes_acc = [];
  },
  RESET_PARTES_ENF: (state) => {
    state.partes_enf = [];
  },
  RESET_PARTES_FIS: (state) => {
    state.partes_fis = [];
  },
  RESET_PARTES_PRE: (state) => {
    state.partes_pre = [];
  },
  RESET_INFORMES_P_ACC: (state) => {
    state.informes_p_acc = [];
  },
  RESET_INFORMES_P_ENF: (state) => {
    state.informes_p_enf = [];
  },
  RESET_INFORMES_P_FIS: (state) => {
    state.informes_p_fis = [];
  },
  RESET_INFORMES_P_PRE: (state) => {
    state.informes_p_pre = [];
  },
  RESET_NOTIFICACION: (state) => {
    state.notificacion = {
      pelotari_id: null,
      destinatarios: [],
      disponible: true,
      date_from: '',
      date_to: '',
      texto: '',
    };
  },
  RESET_NOTIFICACIONES: (state) => {
    state.notificaciones = [];
  },


  SET_CAUSANTES: (state, value) => {
    state.aux.causantes = value;
  },
  SET_CENTROS: (state, value) => {
    state.aux.centros = value;
  },
  SET_GRADOS_LESION: (state, value) => {
    state.aux.grados_lesion = value;
  },
  SET_LUGARES: (state, value) => {
    state.aux.lugares = value;
  },
  SET_MEDICOS: (state, value) => {
    state.aux.medicos = value;
  },
  SET_PRUEBAS: (state, value) => {
    state.aux.pruebas = value;
  },
  SET_TIEMPOS_TRABAJO: (state, value) => {
    state.aux.tiempos_trabajo = value;
  },
  SET_TIPOS_ASISTENCIA: (state, value) => {
    state.aux.tipos_asistencia = value;
  },

  SET_PELOTARI: (state, value) => {
    state.pelotari = value;
  },
  SET_PELOTARIS: (state, value) => {
    state.pelotaris = value;
  },
  SET_PROMESAS: (state, value) => {
    state.promesas = value;
  },
  SET_INFORMES_P_ACCIDENTE: (state, value) => {
    state.informes_p_acc = value;
  },
  SET_INFORMES_P_ENFERMEDAD: (state, value) => {
    state.informes_p_enf = value;
  },
  SET_INFORMES_P_FISIOLOGIA: (state, value) => {
    state.informes_p_fis = value;
  },
  SET_INFORMES_P_PREVENCION: (state, value) => {
    state.informes_p_pre = value;
  },
  SET_P_ACCIDENTE: (state, value) => {
    state.p_accidente = value;
  },
  SET_P_ACCIDENTE_KEY: (state, value) => {
    const key = value.key;
    const val = value.val;

    state.p_accidente[key] = val;
  },
  SET_P_ACCIDENTE_FECHA_PARTE: (state, value) => {
    state.p_accidente.fecha_parte = value;
  },
  SET_P_ACCIDENTE_PELOTARI_ID: (state, value) => {
    state.p_accidente.pelotari_id = value;
  },
  SET_P_ENFERMEDAD: (state, value) => {
    state.p_enfermedad = value;
  },
  SET_P_ENFERMEDAD_KEY: (state, value) => {
    const key = value.key;
    const val = value.val;

    state.p_enfermedad[key] = val;
  },
  SET_P_ENFERMEDAD_PELOTARI_ID: (state, value) => {
    state.p_enfermedad.pelotari_id = value;
  },
  SET_P_FISIOLOGIA: (state, value) => {
    state.p_fisiologia = value;
  },
  SET_P_FISIOLOGIA_KEY: (state, value) => {
    const key = value.key;
    const val = value.val;

    state.p_fisiologia[key] = val;
  },
  SET_P_FISIOLOGIA_PELOTARI_ID: (state, value) => {
    state.p_fisiologia.pelotari_id = value;
  },
  SET_P_PREVENCION: (state, value) => {
    state.p_prevencion = value;
  },
  SET_P_PREVENCION_KEY: (state, value) => {
    const key = value.key;
    const val = value.val;

    state.p_prevencion[key] = val;
  },
  SET_P_PREVENCION_PELOTARI_ID: (state, value) => {
    state.p_prevencion.pelotari_id = value;
  },
  SET_NOTIFICACION: (state, value) => {
    state.notificacion = value;
  },
  SET_NOTIFICACION_KEY: (state, value) => {
    const key = value.key;
    const val = value.val;

    state.notificacion[key] = val;
  },
  SET_NOTIFICACIONES: (state, value) => {
    state.notificaciones = value;
  },
  SET_P_DELTA: (state, value) => {
    state.p_delta = value;
  },
  SET_PARTES_ACC: (state, value) => {
    state.partes_acc = value;
  },
  SET_PARTES_ENF: (state, value) => {
    state.partes_enf = value;
  },
  SET_PARTES_FIS: (state, value) => {
    state.partes_fis = value;
  },
  SET_PARTES_PRE: (state, value) => {
    state.partes_pre = value;
  },
}
