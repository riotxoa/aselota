export default {
  causantes: (state) => ( state.aux.causantes ),
  centros: (state) => ( state.aux.centros ),
  grados_lesion: (state) => ( state.aux.grados_lesion ),
  lugares: (state) => ( state.aux.lugares ),
  medicos: (state) => ( state.aux.medicos ),
  pruebas: (state) => ( state.aux.pruebas ),
  tiempos_trabajo: (state) => ( state.aux.tiempos_trabajo ),
  tipos_asistencia: (state) => ( state.aux.tipos_asistencia ),

  pelotari: (state) => ( state.pelotari ),
  pelotaris: (state) => ( state.pelotaris ),
  promesas: (state) => ( state.promesas ),
  p_accidente: (state) => ( state.p_accidente ),
  informes_p_acc: (state) => ( state.informes_p_acc ),
  p_delta: (state) => ( state.p_delta ),
  p_enfermedad: (state) => ( state.p_enfermedad ),
  informes_p_enf: (state) => ( state.informes_p_enf ),
  p_fisiologia: (state) => ( state.p_fisiologia ),
  informes_p_fis: (state) => ( state.informes_p_fis ),
  p_prevencion: (state) => ( state.p_prevencion ),
  informes_p_pre: (state) => ( state.informes_p_pre ),

  notificacion: (state) => ( state.notificacion ),
  notificaciones: (state) => ( state.notificaciones )
}
