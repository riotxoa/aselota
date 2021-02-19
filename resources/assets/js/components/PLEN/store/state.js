export default {

  // Macrociclos
  macrocilos: [],
  macrociclo: {
    id: null,
    order: null,
    fecha_ini: null,
    fecha_fin: null,
    description: '',
    objetivos: '',
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
