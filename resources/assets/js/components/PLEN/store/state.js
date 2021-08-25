export default {

  // Macrociclos
  macrociclos: [],
  macrociclo: {
    id: null,
    order: null,
    fecha_ini: null,
    fecha_fin: null,
    description: '',
    objetivos: '',
    mesociclos: [],
  },
  mesociclo: {
    id: null,
    order: null,
    macrociclo_id: null,
    macrociclo_desc: '',
    tipo_mesociclo_id: null,
    fecha_ini: null,
    fecha_fin: null,
    description: '',
    objetivos: '',
    microciclos: []
  },
  microciclo: {
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
  },

  // Ejercicios
  ejercicios: [],
  ejercicio: {
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
  },

  // *********************************************
  // ** Tablas Auxiliares
  // *********************************************

  // Fases de Sesión
  fases: [],
  fase: {
    id: null,
    order: null,
    desc: '',
  },
  subtipos_ejercicio: [],
  tipos_ejercicio: [],
}
