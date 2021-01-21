export default {
  aux: {
    causantes: [],
    centros: [],
    grados_lesion: [],
    lugares: [],
    medicos: [],
    pruebas: [],
    tiempos_trabajo: [],
    tipos_asistencia: []
  },
  informes_p_acc: [],
  informes_p_enf: [],
  informes_p_fis: [],
  informes_p_pre: [],
  notificacion: {
    pelotari_id: null,
    destinatarios: [],
    disponible: true,
    date_from: '',
    date_to: '',
    texto: '',
  },
  notificaciones: [],
  partes_acc: [],
  partes_enf: [],
  partes_fis: [],
  partes_pre: [],
  pelotari: null,
  pelotaris: [],
  promesas: [],
  p_accidente: {
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
  },
  p_enfermedad: {
    pelotari_id: null,
    fecha_asistencia: null,
    fecha_inicio: null,
    med2_tipo_asistencia_id: null,
    med2_prueba_id: null,
    diagnostico_ini: '',
    evolucion: '',
    tratamiento: '',
    fecha_alta: null,
  },
  p_fisiologia: {
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
  },
  p_prevencion: {
    pelotari_id: null,
    fecha_asistencia: null,
    historia: '',
    reconocimiento: '',
    fecha_rec: null,
    analiticas: '',
    fecha_ana: null,
    pruebas: '',
    fecha_pru: null,
  },
  p_delta: {
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
  }
}
