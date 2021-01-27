export default {
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
}
