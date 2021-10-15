export default {
  // Tabla Primaria: Macrociclos
  macrociclos: state => state.macrociclos,
  macrociclo: state => state.macrociclo,
  mesociclo: state => state.mesociclo,
  microciclo: state => state.microciclo,
  sesion: state => state.sesion,

  // Tabla Primaria: Ejercicios
  ejercicios: state => state.ejercicios,
  ejercicio: state => state.ejercicio,

  // Tabla Auxiliar: Fases de Sesión
  fases: state => state.fases,
  fase: state => state.fase,
  subtipos_ejercicio: state => state.subtipos_ejercicio,
  tipos_ejercicio: state => state.tipos_ejercicio,

  pelotaris: state => state.pelotaris,
}
