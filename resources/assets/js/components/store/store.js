import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    header: {},
    partidos: [],
    costes: {
      importe_venta: 0,
      cliente_id: null,
      cliente_txt: '',
      aportacion: 0,
      num_entradas: 0,
      precio_entradas: 0,
      num_espectadores: 0,
      ingreso_taquilla: 0,
      ingreso_ayto: 0,
      ingreso_otros: 0,
      precio_total: 0,
      total: 0,
    },
    facturacion: {
      fpago_id: null,
      fecha: null,
      importe: 0,
      enviar_id: null,
      observaciones: '',
      pagado: 0,
      seguimiento: '',
    },
    coste: 0.00,
    edit: false,
  },
  getters: {
    header: state => state.header,
    partidos: state => state.partidos,
    coste: state => state.coste,
    facturacion: state => state.facturacion,
    edit: state => state.edit,
  },
  mutations: {
    SET_HEADER (state, header) {
      state.header = header;
    },
    SET_HEADER_ID (state, id)  {
      state.header.id = id;
    },
    SET_PARTIDOS (state, partidos) {
      state.partidos = partidos;

      state.coste = 0;

      partidos.map((val,key) => {
        this.commit('INC_COSTE', val);
      });
    },
    ADD_PARTIDO (state, partido) {
      state.partidos.push(partido);
      state.partidos = _.sortBy(state.partidos, ['orden']);

      this.commit('INC_COSTE', partido);
    },
    SET_COSTES (state, costes) {
      if(costes.length) {
        state.costes = costes[0];
        state.costes.precio_total = parseFloat(state.costes.aportacion) + (state.costes.num_entradas * state.costes.precio_entradas);
        state.costes.total = parseFloat(state.costes.ingreso_taquilla) + parseFloat(state.costes.ingreso_ayto) + parseFloat(state.costes.ingreso_otros);
      }
    },
    SET_COSTE (state, coste) {
      state.coste = coste;
    },
    INC_COSTE (state, partido) {
      state.coste += (partido.pelotari_1 ? partido.pelotari_1.coste : 0);
      state.coste += (partido.pelotari_2 ? partido.pelotari_2.coste : 0);
      state.coste += (partido.pelotari_3 ? partido.pelotari_3.coste : 0);
      state.coste += (partido.pelotari_4 ? partido.pelotari_4.coste : 0);
    },
    SET_FACTURACION (state, facturacion) {
      if(facturacion.length) {
        state.facturacion = facturacion[0];
      }
    },
    SET_EDIT (state, edit) {
      state.edit = edit;
    },
  },
  actions: {
    loadHeader({ commit, dispatch }, id) {
      axios
        .get('/www/festivales/' + id)
        .then( r => r.data )
        .then( header => {
          commit('SET_HEADER', header);
          dispatch('loadPartidos');
          dispatch('loadCostes');
          dispatch('loadFacturacion');
        });
    },
    loadPartidos({ commit }) {
      let data = {
        params: {
          festival_id: this.getters.header.id,
          festival_fecha: this.getters.header.fecha
        }
      };
      axios
        .get('/www/partidos/', data)
        .then( r => r.data )
        .then( partidos => {
          commit('SET_PARTIDOS', partidos);
        });
    },
    addPartido({ commit }, partido) {
      let uri = '/www/partidos';
      return new Promise( (resolve, reject) => {
        axios
          .post(uri, partido)
          .then( r => r.data )
          .then((response) => {
            commit('ADD_PARTIDO', response);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    updatePartido({ commit }, partido) {
      let uri = '/www/partidos/' + partido.id + '/update';

      return new Promise( (resolve, reject) => {
        axios
          .post(uri, partido)
          .then( r => r.data )
          .then( (response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    deletePartido({ commit, dispatch }, id) {
      let uri = '/www/partidos/' + id;

      return new Promise( (resolve, reject) => {
        axios
          .delete(uri)
          .then( r => r.data )
          .then(( response) => {
            dispatch('loadPartidos');
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    loadCostes({ commit }) {
      let data = {
        params: {
          festival_id: this.getters.header.id,
        }
      };
      axios
        .get('/www/festival-costes', data)
        .then( r => r.data )
        .then( costes => {
          commit('SET_COSTES', costes);
        });
    },
    addCostes({ commit }, costes) {
      let uri = '/www/festival-costes';
      costes.festival_id = this.getters.header.id;
      costes.coste_empresa = this.getters.coste;
      return new Promise( (resolve, reject) => {
        axios
          .post(uri, costes)
          .then( r => r.data )
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    loadFacturacion({ commit }) {
      let data = {
        params: {
          festival_id: this.getters.header.id,
        }
      };
      axios
        .get('/www/festival-facturacion', data)
        .then( r => r.data)
        .then( facturacion =>  {
          commit('SET_FACTURACION', facturacion);
        });
    },
    addFacturacion({ commit }, facturacion) {
      let uri = '/www/festival-facturacion';
      facturacion.festival_id = this.getters.header.id;
      return new Promise( (resolve, reject) => {
        axios
          .post(uri, facturacion)
          .then( r => r.data )
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    }
  }
});
