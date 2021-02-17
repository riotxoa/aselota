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
}
