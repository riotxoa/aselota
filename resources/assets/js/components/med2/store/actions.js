import axios from 'axios';

export default {
  delPAccidente: (context, id) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_accidente/' + id + '/remove')
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  delPEnfermedad: (context, id) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_enfermedad/' + id + '/remove')
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  delPFisiologia: (context, id) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_fisiologia/' + id + '/remove')
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  delPPrevencion: (context, id) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_prevencion/' + id + '/remove')
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },

  getMed2Aux: (context) => {
    context.dispatch('resetAux');

    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/aux')
        .then( r => r.data )
        .then( (aux) => {
          context.dispatch('setAux', aux);
          resolve(aux);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  getAllPelotaris: (context) => {
    context.dispatch('resetPelotaris');

    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/pelotaris')
        .then( r => r.data)
        .then( (val) => {
          context.dispatch('setPelotaris', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  getAllPromesas: (context) => {
    context.dispatch('resetPromesas');

    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/promesas')
        .then( r => r.data)
        .then( (val) => {
          context.dispatch('setPromesas', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  getAllPAccidente: (context) => {
    context.dispatch('resetPartesAcc');

    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/p_accidente')
        .then( r => r.data )
        .then( (val) => {
          context.dispatch('setPartesAcc', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  getAllPEnfermedad: (context) => {
    context.dispatch('resetPartesEnf');

    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/p_enfermedad')
        .then( r => r.data )
        .then( (val) => {
          context.dispatch('setPartesEnf', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  getAllPFisiologia: (context) => {
    context.dispatch('resetPartesFis');

    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/p_fisiologia')
        .then( r => r.data )
        .then( (val) => {
          context.dispatch('setPartesFis', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  getAllPPrevencion: (context) => {
    context.dispatch('resetPartesPre');

    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/p_prevencion')
        .then( r => r.data )
        .then( (val) => {
          context.dispatch('setPartesPre', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },

  getInformesPAcc: (context, parte_id) => {
    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/p_accidente/' + parte_id + '/informes')
        .then( r => r.data)
        .then( (val) => {
          context.commit('SET_INFORMES_P_ACCIDENTE', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  getInformesPEnf: (context, parte_id) => {
    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/p_enfermedad/' + parte_id + '/informes')
        .then( r => r.data)
        .then( (val) => {
          context.commit('SET_INFORMES_P_ENFERMEDAD', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  getInformesPFis: (context, parte_id) => {
    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/p_fisiologia/' + parte_id + '/informes')
        .then( r => r.data)
        .then( (val) => {
          context.commit('SET_INFORMES_P_FISIOLOGIA', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  getInformesPPre: (context, parte_id) => {
    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/p_prevencion/' + parte_id + '/informes')
        .then( r => r.data)
        .then( (val) => {
          context.commit('SET_INFORMES_P_PREVENCION', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  getAllNotificaciones: (context) => {
    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/notificaciones/')
        .then( r => r.data )
        .then( (val) => {
          context.commit('SET_NOTIFICACIONES', val);
          resolve(val);
        })
        .catch( (err) => {
          reject(err);
        });
    })
  },
  getNotificacionesByPelotariID: (context, pelotari_id) => {
    return new Promise( (resolve, reject) => {
      axios.get('/www/med2/notificaciones/' + pelotari_id)
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
  getPDelta: (context, p_accidente_id) => {
    return new Promise( (resolve, reject) => {
      resolve( _.find( context.state.pelotari.delta, [ 'p_accidente_id', p_accidente_id ] ) );
    } );
  },

  resetAll: (context) => {
    context.commit('RESET_CAUSANTES');
    context.commit('RESET_CENTROS');
    context.commit('RESET_GRADOS_LESION');
    context.commit('RESET_INFORMES_P_ACC');
    context.commit('RESET_INFORMES_P_ENF');
    context.commit('RESET_INFORMES_P_FIS');
    context.commit('RESET_INFORMES_P_PRE');
    context.commit('RESET_LUGARES');
    context.commit('RESET_MEDICOS');
    context.commit('RESET_PRUEBAS');
    context.commit('RESET_TIEMPOS_TRABAJO');
    context.commit('RESET_TIPOS_ASISTENCIA');
    context.commit('RESET_PELOTARIS');
    context.commit('RESET_PROMESAS');
    context.commit('RESET_P_ACCIDENTE');
    context.commit('RESET_P_ENFERMEDAD');
    context.commit('RESET_P_FISIOLOGIA');
    context.commit('RESET_P_PREVENCION');
    context.commit('RESET_P_DELTA');
    context.commit('RESET_NOTIFICACIONES');
    context.commit('RESET_NOTIFICACION');
  },
  resetAux: (context) => {
    context.dispatch('resetCausantes');
    context.dispatch('resetCentros');
    context.dispatch('resetGradosLesion');
    context.dispatch('resetLugares');
    context.dispatch('resetMedicos');
    context.dispatch('resetPruebas');
    context.dispatch('resetTiemposTrabajo');
    context.dispatch('resetTiposAsistencia');
  },
  resetCausantes: (context) => {
    context.commit('RESET_CAUSANTES');
  },
  resetCentros: (context) => {
    context.commit('RESET_CENTROS');
  },
  resetGradosLesion: (context) => {
    context.commit('RESET_GRADOS_LESION');
  },
  resetLugares: (context) => {
    context.commit('RESET_LUGARES');
  },
  resetMedicos: (context) => {
    context.commit('RESET_MEDICOS');
  },
  resetPruebas: (context) => {
    context.commit('RESET_PRUEBAS');
  },
  resetTiemposTrabajo: (context) => {
    context.commit('RESET_TIEMPOS_TRABAJO');
  },
  resetTiposAsistencia: (context) => {
    context.commit('RESET_TIPOS_ASISTENCIA');
  },
  resetPelotari: (context) => {
    context.commit('RESET_PELOTARI');
  },
  resetPelotaris: (context) => {
    context.commit('RESET_PELOTARIS');
  },
  resetPromesas: (context) => {
    context.commit('RESET_PROMESAS');
  },
  resetPAccidente: (context) => {
    context.commit('RESET_P_ACCIDENTE');
  },
  resetPEnfermedad: (context) => {
    context.commit('RESET_P_ENFERMEDAD');
  },
  resetPFisiologia: (context) => {
    context.commit('RESET_P_FISIOLOGIA');
  },
  resetPPrevencion: (context) => {
    context.commit('RESET_P_PREVENCION');
  },
  resetPDelta: (context) => {
    context.commit('RESET_P_DELTA');
  },
  resetPartesAcc: (context) => {
    context.commit('RESET_PARTES_ACC');
  },
  resetPartesEnf: (context) => {
    context.commit('RESET_PARTES_ENF');
  },
  resetPartesFis: (context) => {
    context.commit('RESET_PARTES_FIS');
  },
  resetPartesPre: (context) => {
    context.commit('RESET_PARTES_PRE');
  },
  resetNotificacion: (context) => {
    context.commit('RESET_NOTIFICACION');
  },
  resetNotificaciones: (context) => {
    context.commit('RESET_NOTIFICACIONES');
  },

  saveNotificacion: (context, data) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/notificacion', data)
        .then( r => r.data )
        .then( (res) => {
          context.commit('SET_NOTIFICACIONES', res)
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },

  saveParteAccidente: (context, data) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_accidente', data)
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    })
  },
  saveParteEnfermedad: (context, data) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_enfermedad', data)
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    })
  },
  saveParteFisiologia: (context, data) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_fisiologia', data)
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    })
  },
  savePartePrevencion: (context, data) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_prevencion', data)
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    })
  },

  setAux: (context, value) => {
    // causantes
    context.dispatch('setCausantes', value.causantes);
    // centros
    context.dispatch('setCentros', value.centros);
    // grados_lesion
    context.dispatch('setGradosLesion', value.grados_lesion);
    // lugares
    context.dispatch('setLugares', value.lugares);
    // medicos
    context.dispatch('setMedicos', value.medicos);
    // pruebas
    context.dispatch('setPruebas', value.pruebas);
    // tiempos_trabajo
    context.dispatch('setTiemposTrabajo', value.tiempos_trabajo);
    // tipos_asistencia
    context.dispatch('setTiposAsistencia', value.tipos_asistencia);
  },
  setCausantes: (context, value) => {
    context.commit('SET_CAUSANTES', value);
  },
  setCentros: (context, value) => {
    context.commit('SET_CENTROS', value);
  },
  setGradosLesion: (context, value) => {
    context.commit('SET_GRADOS_LESION', value);
  },
  setLugares: (context, value) => {
    context.commit('SET_LUGARES', value);
  },
  setMedicos: (context, value) => {
    context.commit('SET_MEDICOS', value);
  },
  setPruebas: (context, value) => {
    context.commit('SET_PRUEBAS', value);
  },
  setTiemposTrabajo: (context, value) => {
    context.commit('SET_TIEMPOS_TRABAJO', value);
  },
  setTiposAsistencia: (context, value) => {
    context.commit('SET_TIPOS_ASISTENCIA', value);
  },

  setPelotari: (context, value) => {
    context.dispatch('resetPelotari');
    context.commit('SET_PELOTARI', value);
  },
  setPelotaris: (context, value) => {
    context.commit('SET_PELOTARIS', value);
  },
  setPromesas: (context, value) => {
    context.commit('SET_PROMESAS', value);
  },

  setInformesByParteAcc: (context, value) => {
    return new Promise((resolve, reject) => {
      axios.get('/www/med2/p_accidente/' + context.state.p_accidente.id + '/informes')
        .then( x => x.data )
        .then( (res) => {
          context.commit("SET_INFORMES_P_ACCIDENTE", res);
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  setInformesByParteEnf: (context, value) => {
    return new Promise((resolve, reject) => {
      axios.get('/www/med2/p_enfermedad/' + context.state.p_enfermedad.id + '/informes')
        .then( x => x.data )
        .then( (res) => {
          context.commit("SET_INFORMES_P_ENFERMEDAD", res);
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  setInformesByParteFis: (context, value) => {
    return new Promise((resolve, reject) => {
      axios.get('/www/med2/p_fisiologia/' + context.state.p_fisiologia.id + '/informes')
        .then( x => x.data )
        .then( (res) => {
          context.commit("SET_INFORMES_P_FISIOLOGIA", res);
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  setInformesByPartePre: (context, value) => {
    return new Promise((resolve, reject) => {
      axios.get('/www/med2/p_prevencion/' + context.state.p_prevencion.id + '/informes')
        .then( x => x.data )
        .then( (res) => {
          context.commit("SET_INFORMES_P_PREVENCION", res);
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },

  setPAccidente: (context, value) => {
    context.commit('SET_P_ACCIDENTE', value);
  },
  setPAccidente_key: (context, value) => {
    context.commit('SET_P_ACCIDENTE_KEY', value);
  },
  setPAccidente_fecha_parte: (context, value) => {
    context.commit('SET_P_ACCIDENTE_FECHA_PARTE');
  },
  setPAccidente_pelotari_id: (context, value) => {
    context.commit('SET_P_ACCIDENTE_PELOTARI_ID');
  },

  setPEnfermedad: (context, value) => {
    context.commit('SET_P_ENFERMEDAD', value);
  },
  setPEnfermedad_key: (context, value) => {
    context.commit('SET_P_ENFERMEDAD_KEY', value);
  },
  setPEnfermedad_pelotari_id: (context, value) => {
    context.commit('SET_P_ENFERMEDAD_PELOTARI_ID');
  },

  setPFisiologia: (context, value) => {
    context.commit('SET_P_FISIOLOGIA', value);
  },
  setPFisiologia_key: (context, value) => {
    context.commit('SET_P_FISIOLOGIA_KEY', value);
  },
  setPFisiologia_pelotari_id: (context, value) => {
    context.commit('SET_P_FISIOLOGIA_PELOTARI_ID');
  },

  setPPrevencion: (context, value) => {
    context.commit('SET_P_PREVENCION', value);
  },
  setPPrevencion_key: (context, value) => {
    context.commit('SET_P_PREVENCION_KEY', value);
  },
  setPPrevencion_pelotari_id: (context, value) => {
    context.commit('SET_P_PREVENCION_PELOTARI_ID');
  },

  setNotificacion: (context, value) => {
    context.commit('SET_NOTIFICACION', value);
  },
  setNotificacion_key: (context, value) => {
    context.commit('SET_NOTIFICACION_KEY', value);
  },

  setPDelta: (context, value) => {
    context.commit('SET_P_DELTA', value);
  },

  setPartesAcc: (context, value) => {
    context.commit('SET_PARTES_ACC', value);
  },
  setPartesEnf: (context, value) => {
    context.commit('SET_PARTES_ENF', value);
  },
  setPartesFis: (context, value) => {
    context.commit('SET_PARTES_FIS', value);
  },
  setPartesPre: (context, value) => {
    context.commit('SET_PARTES_PRE', value);
  },

  setNotificaciones: (context, value) => {
    context.commit('SET_NOTIFICACIONES', value)
  },

  updateParteAccidente: (context, data) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_accidente/' + data.p_accidente.id + '/update', data)
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    })
  },
  deleteInformeParteAcc: (context, id) => {
    return new Promise((resolve, reject) => {
      axios.delete('/www/med2/p_accidente/informe/' + id)
        .then( (x) => x.data )
        .then(function (res) {
          resolve(res);
        }).catch(function (err) {
          reject(err);
        });
    });
  },
  updateInformeParteAcc: (context, informe) => {
    return new Promise((resolve, reject) => {
      axios.post('/www/med2/p_accidente/update-informe/' + context.state.p_accidente.id, informe)
      .then( (x) => x.data )
      .then(function (res) {
        resolve(res);
      }).catch(function (err) {
        reject(err);
      });
    });
  },
  uploadInformeParteAcc: (context, informe) => {
    return new Promise((resolve, reject) => {
      axios.post('/www/med2/p_accidente/upload-informe/' + context.state.p_accidente.id, informe)
        .then( (x) => x.data )
        .then(function (res) {
          resolve(res);
        }).catch(function (err) {
          reject(err);
        });
    });
  },

  updateParteEnfermedad: (context, data) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_enfermedad/' + data.p_enfermedad.id + '/update', data)
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    })
  },
  deleteInformeParteEnf: (context, id) => {
    return new Promise((resolve, reject) => {
      axios.delete('/www/med2/p_enfermedad/informe/' + id)
        .then( (x) => x.data )
        .then(function (res) {
          resolve(res);
        }).catch(function (err) {
          reject(err);
        });
    });
  },
  updateInformeParteEnf: (context, informe) => {
    return new Promise((resolve, reject) => {
      axios.post('/www/med2/p_enfermedad/update-informe/' + context.state.p_enfermedad.id, informe)
      .then( (x) => x.data )
      .then(function (res) {
        resolve(res);
      }).catch(function (err) {
        reject(err);
      });
    });
  },
  uploadInformeParteEnf: (context, informe) => {
    return new Promise((resolve, reject) => {
      axios.post('/www/med2/p_enfermedad/upload-informe/' + context.state.p_enfermedad.id, informe)
        .then( (x) => x.data )
        .then(function (res) {
          resolve(res);
        }).catch(function (err) {
          reject(err);
        });
    });
  },

  updateParteFisiologia: (context, data) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_fisiologia/' + data.p_fisiologia.id + '/update', data)
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    })
  },
  deleteInformeParteFis: (context, id) => {
    return new Promise((resolve, reject) => {
      axios.delete('/www/med2/p_fisiologia/informe/' + id)
        .then( (x) => x.data )
        .then(function (res) {
          resolve(res);
        }).catch(function (err) {
          reject(err);
        });
    });
  },
  updateInformeParteFis: (context, informe) => {
    return new Promise((resolve, reject) => {
      axios.post('/www/med2/p_fisiologia/update-informe/' + context.state.p_fisiologia.id, informe)
      .then( (x) => x.data )
      .then(function (res) {
        resolve(res);
      }).catch(function (err) {
        reject(err);
      });
    });
  },
  uploadInformeParteFis: (context, informe) => {
    return new Promise((resolve, reject) => {
      axios.post('/www/med2/p_fisiologia/upload-informe/' + context.state.p_fisiologia.id, informe)
        .then( (x) => x.data )
        .then(function (res) {
          resolve(res);
        }).catch(function (err) {
          reject(err);
        });
    });
  },

  updatePartePrevencion: (context, data) => {
    return new Promise( (resolve, reject) => {
      axios.post('/www/med2/p_prevencion/' + data.p_prevencion.id + '/update', data)
        .then( r => r.data )
        .then( (res) => {
          resolve(res);
        })
        .catch( (err) => {
          reject(err);
        });
    })
  },
  deleteInformePartePre: (context, id) => {
    return new Promise((resolve, reject) => {
      axios.delete('/www/med2/p_prevencion/informe/' + id)
        .then( (x) => x.data )
        .then(function (res) {
          resolve(res);
        }).catch(function (err) {
          reject(err);
        });
    });
  },
  updateInformePartePre: (context, informe) => {
    return new Promise((resolve, reject) => {
      axios.post('/www/med2/p_prevencion/update-informe/' + context.state.p_prevencion.id, informe)
      .then( (x) => x.data )
      .then(function (res) {
        resolve(res);
      }).catch(function (err) {
        reject(err);
      });
    });
  },
  uploadInformePartePre: (context, informe) => {
    return new Promise((resolve, reject) => {
      axios.post('/www/med2/p_prevencion/upload-informe/' + context.state.p_prevencion.id, informe)
        .then( (x) => x.data )
        .then(function (res) {
          resolve(res);
        }).catch(function (err) {
          reject(err);
        });
    });
  },
}
