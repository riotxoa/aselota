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
}
