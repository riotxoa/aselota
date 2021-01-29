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
  deleteTipoEjercicio( dispatch, id ) {
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
  getTiposEjercicio( dispatch ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/tipos-ejercicio')
        .then( (res) => {
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  saveTipoEjercicio( dispatch, item ) {
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
  updateTipoEjercicio( dispatch, item ) {
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
  deleteTipoMesociclo( dispatch, id ) {
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
  getTiposMesociclo( dispatch ) {
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
  saveTipoMesociclo( dispatch, item ) {
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
  updateTipoMesociclo( dispatch, item ) {
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
  deleteTipoMicrociclo( dispatch, id ) {
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
  getTiposMicrociclo( dispatch ) {
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
  saveTipoMicrociclo( dispatch, item ) {
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
  updateTipoMicrociclo( dispatch, item ) {
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
  deleteSubtipoEjercicio( dispatch, id ) {
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
  getSubtiposEjercicio( dispatch ) {
    return new Promise( (resolve, reject) => {
      axios.get('/www/PLEN/subtipos-ejercicio')
        .then( (res) => {
          resolve(res.data);
        })
        .catch( (err) => {
          reject(err);
        });
    });
  },
  saveSubtipoEjercicio( dispatch, item ) {
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
  updateSubtipoEjercicio( dispatch, item ) {
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
