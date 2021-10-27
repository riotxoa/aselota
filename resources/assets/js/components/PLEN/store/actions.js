import axios from 'axios';

export default {
  // GLOBAL
  showSnackBar( { commit }, msg) {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";
    x.innerHTML = msg;

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 1500);
  },

  // Tabla Macrociclos
  deleteMacrociclo( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.delete('/www/PLEN/macrociclos/' + id)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  getMacrociclos( { commit } ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/macrociclos')
        .then( (res) => {
          res.data.map( val => {
            val._showDetails = false;
          })
          commit('SET_MACROCICLOS', res.data);
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  saveMacrociclo( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.post('/www/PLEN/macrociclos', item)
      .then((response) => {
        response.data._showDetails = false;
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  setMacrociclo( { commit }, value ) {
    commit('SET_MACROCICLO', value);
  },
  setMacrociclos( { commit }, value ) {
    commit('SET_MACROCICLOS', value);
  },
  setMacrocicloMesociclos( { commit }, value ) {
    commit('SET_MACROCICLO_MESOCICLOS', value);
  },
  updateMacrociclo( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.put('/www/PLEN/macrociclos/' + item.id, item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },

  // Tabla Mesociclos
  deleteMesociclo( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.delete('/www/PLEN/mesociclos/' + id)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  getMesociclos( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/mesociclos/' + id)
        .then( (res) => {
          commit('SET_MESOCICLOS', res.data);
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  resetMesociclo( { commit } ) {
    commit('RESET_MESOCICLO');
  },
  saveMesociclo( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.post('/www/PLEN/mesociclos', item)
      .then((response) => {
        commit('ADD_MESOCICLO_TO_MACROCICLO', response.data)
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  setMesociclo( { commit }, value ) {
    commit('SET_MESOCICLO', value);
  },
  setMesocicloMicrociclos( { commit }, value ) {
    commit('SET_MESOCICLO_MICROCICLOS', value);
  },
  updateMesociclo( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.put('/www/PLEN/mesociclos/' + item.id, item)
      .then((response) => {
        commit('UPDATE_MESOCICLO_FROM_MACROCICLO', response.data)
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },

  // Tabla Microciclos
  deleteMicrociclo( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.delete('/www/PLEN/microciclos/' + id)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  getMicrociclos( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/microciclos/' + id)
        .then( (res) => {
          commit('SET_MICROCICLOS', res.data);
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  resetMicrociclo( { commit } ) {
    commit('RESET_MICROCICLO');
  },
  saveMicrociclo( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.post('/www/PLEN/microciclos', item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  setMicrociclo( { commit }, value ) {
    commit('SET_MICROCICLO', value);
  },
  updateMicrociclo( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.put('/www/PLEN/microciclos/' + item.id, item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },

  // Tabla Sesiones
  deleteSesion( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.delete('/www/PLEN/sesiones/' + id)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  getSesiones( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/sesiones/' + id)
        .then( (res) => {
          commit('SET_SESIONES', res.data);
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  resetSesion( { commit } ) {
    commit('RESET_SESION');
  },
  saveSesion( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.post('/www/PLEN/sesiones', item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  setSesion( { commit }, value ) {
    commit('SET_SESION', value);
  },
  updateSesion( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.put('/www/PLEN/sesiones/' + item.id, item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },

  // Tabla 40_Ejercicios
  addEjercicio( { commit }, item ) {
    commit('ADD_EJERCICIO', item);
  },
  deleteEjercicio( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.delete('/www/PLEN/ejercicios/' + id)
      .then((response) => {
        commit('SET_EJERCICIOS', response.data);
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  getEjercicios( { commit } ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/ejercicios')
        .then( (response) => {
          commit('SET_EJERCICIOS', response.data);
          resolve(response.data);
        })
        .catch( (error) => {
          reject(error);
        });
    });
  },
  resetEjercicio( { commit } ) {
    commit('RESET_EJERCICIO');
  },
  saveEjercicio( { commit }, item ) {
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    let data = new FormData();

    data.append('form', JSON.stringify(item));
    if(item.file) {
      data.append('photo', item.file);
    }

    return new Promise( (resolve, reject) => {
      axios.post('/www/PLEN/ejercicios', data, config)
      .then((response) => {
        commit('SET_EJERCICIOS', response.data);
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  setEjercicio( { commit }, value ) {
    commit('SET_EJERCICIO', value);
  },
  setEjercicioImagen( { commit }, imagen ) {
    commit('SET_EJERCICIO_IMAGEN', imagen);
  },


  // Tabla Auxiliar: Fases de Sesión
  deleteFase( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.delete('/www/PLEN/fases-sesion/' + id)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  getFases( { commit } ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/fases-sesion')
        .then( (res) => {
          commit('SET_FASES', res.data);
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  saveFase( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.post('/www/PLEN/fases-sesion', item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  updateFase( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.put('/www/PLEN/fases-sesion/' + item.id, item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },

  // Tabla Auxiliar: Tipos de Ejercicio
  deleteTipoEjercicio( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.delete('/www/PLEN/tipos-ejercicio/' + id)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  getTiposEjercicio( { commit } ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/tipos-ejercicio')
        .then( (res) => {
          commit('SET_TIPOS_EJERCICIO', res.data);
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  saveTipoEjercicio( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.post('/www/PLEN/tipos-ejercicio', item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  updateTipoEjercicio( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.put('/www/PLEN/tipos-ejercicio/' + item.id, item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },

  // Tabla Auxiliar: Tipos de Mesociclo
  deleteTipoMesociclo( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.delete('/www/PLEN/tipos-mesociclo/' + id)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  getTiposMesociclo( { commit } ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/tipos-mesociclo')
        .then( (res) => {
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  saveTipoMesociclo( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.post('/www/PLEN/tipos-mesociclo', item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  updateTipoMesociclo( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.put('/www/PLEN/tipos-mesociclo/' + item.id, item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },

  // Tabla Auxiliar: Tipos de Microciclo
  deleteTipoMicrociclo( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.delete('/www/PLEN/tipos-microciclo/' + id)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  getTiposMicrociclo( { commit } ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/tipos-microciclo')
        .then( (res) => {
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  saveTipoMicrociclo( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.post('/www/PLEN/tipos-microciclo', item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  updateTipoMicrociclo( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.put('/www/PLEN/tipos-microciclo/' + item.id, item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },

  // Tabla Auxiliar: Tipos de Mesociclo
  deleteSubtipoEjercicio( { commit }, id ) {
    return new Promise( (resolve, reject) => {
      axios.delete('/www/PLEN/subtipos-ejercicio/' + id)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  getSubtiposEjercicio( { commit } ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/subtipos-ejercicio')
        .then( (res) => {
          commit('SET_SUBTIPOS_EJERCICIO', res.data);
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  saveSubtipoEjercicio( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.post('/www/PLEN/subtipos-ejercicio', item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },
  updateSubtipoEjercicio( { commit }, item ) {
    return new Promise( (resolve, reject) => {
      axios.put('/www/PLEN/subtipos-ejercicio/' + item.id, item)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  },

  // PELOTARIS
  getPelotaris( { commit }, date ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/pelotaris', {
          params: {
            fecha: date
          }
      })
      .then((response) => {
        commit('SET_PELOTARIS', response.data);
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      });
    });
  }
}
