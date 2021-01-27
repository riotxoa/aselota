import axios from 'axios';

export default {
  getNotificacionesByUserID: (context, user_id) => {
    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/user/notificaciones')
        .then( r => r.data )
        .then( (val) => {
          context.commit('SET_NOTIFICACIONES', val)
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },

  readNotificacion: (context, notificacion_id) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/notificacion/' + notificacion_id + '/read')
        .then( r => r.data )
        .then( (res) => {
          // context.commit('SET_NOTIFICACIONES', res)
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  unreadNotificacion: (context, notificacion_id) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/notificacion/' + notificacion_id + '/unread')
        .then( r => r.data )
        .then( (res) => {
          // context.commit('SET_NOTIFICACIONES', res)
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },

  resetAll: (context) => {
    context.commit('RESET_NOTIFICACIONES');
    context.commit('RESET_NOTIFICACION');
  },
  resetNotificacion: (context) => {
    context.commit('RESET_NOTIFICACION');
  },
  resetNotificaciones: (context) => {
    context.commit('RESET_NOTIFICACIONES');
  },

  setNotificacion: (context, value) => {
    context.commit('SET_NOTIFICACION', value);
  },
  setNotificacion_key: (context, value) => {
    context.commit('SET_NOTIFICACION_KEY', value);
  },
  setNotificaciones: (context, value) => {
    context.commit('SET_NOTIFICACIONES', value)
  },
}
