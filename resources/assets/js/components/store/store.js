import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    festivales: [],
    total_festivales: 0,
    filter_festivales: [],
    header: {},
    partidos: [],
    costes: {
      importe_venta: 0,
      cliente_id: null,
      cliente_txt: '',
      porcentaje: 100,
      aportacion: 0,
      entradas: [],
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
    contactos: {
      contact_01_name: '',
      contact_01_desc: '',
      contact_01_email_1: '',
      contact_01_email_2: '',
      contact_01_telephone_1: '',
      contact_01_telephone_2: '',
      contact_02_name: '',
      contact_02_desc: '',
      contact_02_email_1: '',
      contact_02_email_2: '',
      contact_02_telephone_1: '',
      contact_02_telephone_2: '',
      observaciones: '',
    },
    coste: 0.00,
    edit: false,
    edit_evento: false,
    calendario: null,
    entrenamientos: [],
    entr_contenidos: [],
    entr_actitudes: [],
    entr_aprovechamientos: [],
    entr_evoluciones: [],
    pelotaris: [],
    eventos: [],
    evento_motivos: [],
    evento: {},
    provincias: [],
    municipios: [],
    municipios_filtered: [],
    frontones: [],
    frontones_filtered: [],
    campeonatos: [],
    entr_contenido: [],
  },
  getters: {
    festivales: state => state.festivales,
    total_festivales: state => state.total_festivales,
    filter_festivales: state => state.filter_festivales,
    header: state => state.header,
    television_txt: state => state.header.television_txt,
    partidos: state => state.partidos,
    costes: state => state.costes,
    coste: state => state.coste,
    facturacion: state => state.facturacion,
    contactos: state => state.contactos,
    edit: state => state.edit,
    edit_evento: state => state.edit_evento,
    calendario: state => state.calendario,
    entrenamientos: state => state.entrenamientos,
    entr_contenidos: state => state.entr_contenidos,
    entr_frontones: state => state.entr_frontones,
    entr_actitudes: state => state.entr_actitudes,
    entr_aprovechamientos: state => state.entr_aprovechamientos,
    entr_evoluciones: state => state.entr_evoluciones,
    pelotaris: state => state.pelotaris,
    eventos: state => state.eventos,
    evento_motivos: state => state.evento_motivos,
    evento: state => state.evento,
    provincias: state => state.provincias,
    municipios: state => state.municipios,
    municipios_filtered: state => state.municipios_filtered,
    frontones: state => state.frontones,
    frontones_filtered: state => state.frontones_filtered,
    campeonatos: state => state.campeonatos,
  },
  mutations: {
    SET_FESTIVALES (state, festivales) {
      state.festivales = festivales;
      this.commit('SET_TOTAL_FESTIVALES', festivales.length);
    },
    SET_TOTAL_FESTIVALES (state, total) {
      state.total_festivales = total;
    },
    SET_FILTER_FESTIVALES (state, filter) {
      state.filter_festivales = filter;
    },
    ADD_FILTER_FESTIVAL (state, value) {
      state.filter_festivales.push(value);
    },
    REMOVE_FILTER_FESTIVAL (state, index) {
      state.filter_festivales.splice(index, 1);
    },
    RESET_FESTIVAL_HEADER (state) {
      state.header = {}
    },
    RESET_FESTIVAL_BODY (state) {
      state.partidos = [];
      state.costes = {
        importe_venta: 0,
        cliente_id: null,
        cliente_txt: '',
        porcentaje: 100,
        aportacion: 0,
        entradas: [],
        num_espectadores: 0,
        ingreso_taquilla: 0,
        ingreso_ayto: 0,
        ingreso_otros: 0,
        precio_total: 0,
        total: 0,
      };
      state.facturacion = {
        fpago_id: null,
        fecha: null,
        importe: 0,
        enviar_id: null,
        observaciones: '',
        pagado: 0,
        seguimiento: '',
      };
      state.coste = 0.00;
      state.contactos = {
        c1_name: '',
        c1_desc: '',
        c1_email1: '',
        c1_email2: '',
        c1_phone1: '',
        c1_phone2: '',
        c2_name: '',
        c2_desc: '',
        c2_email1: '',
        c2_email2: '',
        c2_phone1: '',
        c2_phone2: '',
        observaciones: '',
      };
    },
    SET_HEADER (state, header) {
      state.header = header;
    },
    SET_HEADER_ID (state, id)  {
      state.header.id = id;
    },
    SET_TELEVISION_TXT (state, txt) {
      state.header.television_txt = txt;
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
        state.costes.precio_total = parseFloat(state.costes.aportacion);
        state.costes.entradas.forEach( (value, index) => {
          state.costes.precio_total += (value.amount * value.price);
        });
        state.costes.total = parseFloat(state.costes.ingreso_taquilla) + parseFloat(state.costes.ingreso_ayto) + parseFloat(state.costes.ingreso_otros);
      }
    },
    SET_COSTE (state, coste) {
      state.coste = coste;
    },
    INC_COSTE (state, partido) {
      state.coste += (partido.pelotari_1 ? ( 1 === partido.pelotari_1.asiste ? partido.pelotari_1.coste : partido.pelotari_1.sustituto_coste ) : 0);
      state.coste += (partido.pelotari_2 ? ( 1 === partido.pelotari_2.asiste ? partido.pelotari_2.coste : partido.pelotari_2.sustituto_coste ) : 0);
      state.coste += (partido.pelotari_3 ? ( 1 === partido.pelotari_3.asiste ? partido.pelotari_3.coste : partido.pelotari_3.sustituto_coste ) : 0);
      state.coste += (partido.pelotari_4 ? ( 1 === partido.pelotari_4.asiste ? partido.pelotari_4.coste : partido.pelotari_4.sustituto_coste ) : 0);
    },
    ADD_ENTRADAS (state, entradas) {
      state.costes.entradas.push(entradas);
    },
    UPDATE_ENTRADAS (state, entrada) {
      var index = _.findIndex( state.costes.entradas, { "id": entrada.id } );

      state.costes.entradas[index].name = entrada.name;
      state.costes.entradas[index].amount = entrada.amount;
      state.costes.entradas[index].price = entrada.price;
    },
    DELETE_ENTRADAS (state, entrada) {
      var index = _.findIndex( state.costes.entradas, { "id": entrada.id } );

      if( index > -1 ) {
        state.costes.entradas.splice(index, 1);
      }
    },
    ADD_ENTRENAMIENTO (state, entrenamiento) {
      state.entrenamientos.push(entrenamiento);
      state.entrenamientos = _.sortBy(state.entrenamientos, ['fecha']);
    },
    UPDATE_ENTRENAMIENTO (state, entrenamiento) {
      var index = _.findIndex( state.entrenamientos, { "id": entrenamiento.id } );

      state.entrenamientos[index].pelotari_id = entrenamiento.pelotari_id;
      state.entrenamientos[index].provincia_id = entrenamiento.provincia_id;
      state.entrenamientos[index].municipio_id = entrenamiento.municipio_id;
      state.entrenamientos[index].asistencia = entrenamiento.asistencia;
      state.entrenamientos[index].duracion = entrenamiento.duracion;
      state.entrenamientos[index].fecha = entrenamiento.fecha;
      state.entrenamientos[index].hora = entrenamiento.hora;
      state.entrenamientos[index].contenido_id = entrenamiento.contenido_id;
      state.entrenamientos[index].fronton_id = entrenamiento.fronton_id;
      state.entrenamientos[index].pre_entreno = entrenamiento.pre_entreno;
      state.entrenamientos[index].contenido = entrenamiento.contenido;
      state.entrenamientos[index].post_entreno = entrenamiento.post_entreno;
      state.entrenamientos[index].actitud_id = entrenamiento.actitud_id;
      state.entrenamientos[index].aprovechamiento_id = entrenamiento.aprovechamiento_id;
      state.entrenamientos[index].evolucion_id = entrenamiento.evolucion_id;
      state.entrenamientos[index].comentarios = entrenamiento.comentarios;
    },
    DELETE_ENTRENAMIENTO (state, entrenamiento) {
      var index = _.findIndex( state.entrenamientos, { "id": entrenamiento.id } );

      if( index > -1 ) {
        state.entrenamientos.splice(index, 1);
      }
    },
    ADD_EVENTO (state, evento) {
      state.eventos.push(evento);
      state.eventos = _.sortBy(state.eventos, ['fecha']);
    },
    UPDATE_EVENTO (state, evento) {
      var index = _.findIndex( state.eventos, { "id": evento.id } );

      state.eventos[index].motivo_id = evento.motivo_id;
      state.eventos[index].campeonato_id = evento.campeonato_id;
      state.eventos[index].provincia_id = evento.provincia_id;
      state.eventos[index].municipio_id = evento.municipio_id;
      state.eventos[index].fecha = evento.fecha;
      state.eventos[index].hora = evento.hora;
      state.eventos[index].desc = evento.desc;
    },
    DELETE_EVENTO (state, evento) {
      var index = _.findIndex( state.eventos, { "id": evento.id } );

      if( index > -1 ) {
        state.eventos.splice(index, 1);
      }
    },
    RESET_EVENTO (state) {
      state.evento = {};
    },
    ADD_EVENTO_PELOTARI (state, pelotari) {
      if( !state.evento.pelotaris )
        state.evento.pelotaris = [];

      state.evento.pelotaris.push(pelotari);
    },
    UPDATE_EVENTO_PELOTARI (state, pelotari) {
      var index = _.findIndex( state.evento.pelotaris, { "id": pelotari.id } );

      if( index > -1 ) {
        state.evento.pelotaris[index].asiste = pelotari.asiste;
        state.evento.pelotaris[index].motivo = pelotari.motivo;
      }
    },
    DEL_EVENTO_PELOTARI (state, id) {
      var index = _.findIndex( state.evento.pelotaris, { "id": id } );

      if( index > -1 ) {
        state.evento.pelotaris.splice(index, 1);
      }
    },
    SET_FACTURACION (state, facturacion) {
      if(facturacion.length) {
        state.facturacion = facturacion[0];
      }
    },
    SET_CONTACTOS (state, contactos) {
      state.contactos = contactos;
    },
    SET_EDIT (state, edit) {
      state.edit = edit;
      if( edit )
        this.dispatch('loadPartidos');
    },
    SET_EVENTO_EDIT (state, edit) {
      state.edit_evento = edit;
      if( edit )
        this.dispatch('loadEventos');
    },
    SET_CALENDARIO (state, calendario) {
      state.calendario = calendario;
    },
    SET_ENTRENAMIENTOS (state, entrenamientos) {
      state.entrenamientos = entrenamientos;
      this.commit('SET_TOTAL_ENTRENAMIENTOS', entrenamientos.length);
    },
    SET_TOTAL_ENTRENAMIENTOS (state, total) {
      state.total_entrenamientos = total;
    },
    SET_ENTR_CONTENIDOS (state, contenidos) {
      state.entr_contenidos = contenidos;
    },
    SET_ENTR_FRONTONES (state, frontones) {
      state.entr_frontones = frontones;
    },
    SET_ENTR_ACTITUDES (state, actitudes) {
      state.entr_actitudes = actitudes;
    },
    SET_ENTR_APROVECHAMIENTOS (state, aprovechamientos) {
      state.entr_aprovechamientos = aprovechamientos;
    },
    SET_ENTR_EVOLUCIONES (state, evoluciones) {
      state.entr_evoluciones = evoluciones;
    },
    SET_EVENTOS (state, eventos) {
      state.eventos = eventos;
    },
    SET_EVENTO_MOTIVOS (state, motivos) {
      state.evento_motivos = motivos;
    },
    SET_EVENTO_ID (state, id)  {
      state.evento.id = id;
    },
    SET_EVENTO (state, evento) {
      state.evento = evento;
    },
    SET_EVENTO_PELOTARIS (state, pelotaris) {
      state.evento.pelotaris = pelotaris;
    },
    SET_PELOTARIS (state, pelotaris) {
      state.pelotaris = pelotaris;
    },
    SET_PROVINCIAS (state, provincias) {
      state.provincias = provincias;
    },
    SET_MUNICIPIOS (state, municipios) {
      state.municipios = municipios;
    },
    SET_MUNICIPIOS_FILTERED (state, municipios) {
      state.municipios_filtered = municipios;
    },
    SET_FRONTONES (state, frontones) {
      state.frontones = frontones;
    },
    SET_FRONTONES_FILTERED (state, frontones) {
      state.frontones_filtered = frontones;
    },
    SET_CAMPEONATOS (state, campeonatos) {
      state.campeonatos = campeonatos;
    },
  },
  actions: {
    loadFestivales({ commit, dispatch }) {
      dispatch('filterFestivales');
    },
    addFilterFestival({ commit, dispatch }, value) {
      commit('ADD_FILTER_FESTIVAL', value);
      dispatch('filterFestivales');
    },
    removeFilterFestival({ commit, dispatch }, index) {
      commit('REMOVE_FILTER_FESTIVAL', index);
      dispatch('filterFestivales');
    },
    filterFestivales({ commit }) {
      let data = {
        params: {
          filter: this.getters.filter_festivales, // filter
        }
      };
      axios
        .get('/www/festivales', data)
        .then( r => r.data )
        .then( festivales => {
          commit('SET_FESTIVALES', festivales);
        });
    },
    loadHeader({ commit, dispatch }, id, is_gerente) {
      return new Promise( (resolve, reject) => {
        axios
        .get('/www/festivales/' + id)
        .then( r => r.data )
        .then( header => {
          commit('SET_HEADER', header);
          dispatch('loadPartidos');
          if( is_gerente ) {
            dispatch('loadCostes');
            dispatch('loadFacturacion');
            dispatch('loadContactos');
          }
          resolve(header);
        })
        .catch((error) => {
          reject(error);
        });
      });
    },
    clearHeader({ commit }) {
      commit('SET_HEADER', {});
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
    clearPartidos({ commit }) {
      commit('SET_PARTIDOS', []);
    },
    resetFestival({ commit }) {
      commit('RESET_FESTIVAL_HEADER');
      commit('RESET_FESTIVAL_BODY');
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
    updatePartido({ commit, dispatch }, partido) {
      let uri = '/www/partidos/' + partido.id + '/update';

      return new Promise( (resolve, reject) => {
        axios
          .post(uri, partido)
          .then( r => r.data )
          .then( (response) => {
            dispatch('loadPartidos');
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
    updatePartidoPelotari({ commit, dispatch }, pelotari) {
      let uri = '/www/partido-pelotaris/' + pelotari.id + '/update';

      return new Promise( (resolve, reject) => {
        axios
          .post(uri, pelotari)
          .then( r => r.data )
          .then( (response) => {
            this.dispatch('loadPartidos');
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      })
    },
    updatePartidoCelebrado({ commit }, partido) {
      let uri = '/www/partido-celebrado/' + partido.id + '/update';

      return new Promise( (resolve, rejecto) =>  {
        axios
          .post(uri, partido)
          .then( r => r.data )
          .then( (response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      })
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
    addCosteEntradas({ commit }, entradas) {
      let uri = '/www/festival-entradas';
      entradas.festival_id = this.getters.header.id;
      return new Promise( (resolve, reject) => {
        axios
          .post(uri, entradas)
          .then( r => r.data )
          .then((response) => {
            entradas.id = response.id;
            commit('ADD_ENTRADAS', entradas);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      })
    },
    updateCosteEntradas({ commit }, entrada) {
      let uri = '/www/festival-entradas/' + entrada.id + '/update';

      return new Promise( (resolve, reject) => {
        axios
          .post(uri, entrada)
          .then( r => r.data )
          .then((response) => {
            commit('UPDATE_ENTRADAS', entrada);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      })

    },
    deleteCosteEntradas({ commit }, entrada) {
      let uri = '/www/festival-entradas/' + entrada.id;

      return new Promise( (resolve, reject) => {
        axios
          .delete(uri)
          .then( r => r.data )
          .then(( response) => {
            commit('DELETE_ENTRADAS', entrada);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    addEntrenamiento({ commit }, entreno) {
      let uri = '/www/entrenamientos';
      return new Promise( (resolve, reject) => {
        axios
          .post(uri, entreno)
          .then( r => r.data )
          .then((response) => {
            commit('ADD_ENTRENAMIENTO', response);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    updateEntrenamiento({ commit }, entreno) {
      let uri = '/www/entrenamientos/' + entreno.id + '/update';
      return new Promise( (resolve, reject) => {
        axios
          .post(uri, entreno)
          .then( r => r.data )
          .then((response) => {
            commit('UPDATE_ENTRENAMIENTO', response);
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
    },
    loadContactos({ commit }) {
      let data = {
        params: {
          festival_id: this.getters.header.id,
        }
      };

      return new Promise( (resolve, reject) => {
        axios
          .get('/www/festival-contactos', data)
          .then( r => r.data )
          .then( contactos => {
              commit('SET_CONTACTOS', contactos);
            resolve(contactos);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    addContactos({ commit }, contactos) {
      let uri = '/www/festival-contactos';
      contactos.festival_id = this.getters.header.id;

      return new Promise( (resolve, reject) => {
        axios
          .post(uri, contactos)
          .then( r => r.data )
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    loadCalendario({ commit }, month) {
      let data = {
        params: {
          pelotaris: [],
          month: month,
        }
      };
      axios
        .get('/www/calendario', data)
        .then( r => r.data )
        .then( calendario => {
          commit('SET_CALENDARIO', calendario);
        });
    },
    loadEntrenamientos({ commit, dispatch }) {
      axios
        .get('/www/entrenamientos')
        .then( r => r.data )
        .then( entrenamientos => {
          commit('SET_ENTRENAMIENTOS', entrenamientos)
        });
    },
    loadEntrContenidos({ commit, dispatch }) {
      axios
        .get('/www/entrenamientos/contenidos')
        .then( r => r.data )
        .then( contenidos => {
          var stringified = JSON.stringify(contenidos).replace(/"id"/g, '"value"').replace(/name/g, "text");
          contenidos = JSON.parse(stringified);
          contenidos.unshift({ value: null, text: "Seleccionar contenido" });
          commit('SET_ENTR_CONTENIDOS', contenidos)
        });
    },
    loadEntrFrontones({ commit }) {
      axios
        .get('/www/entrenamientos/frontones')
        .then( r => r.data )
        .then( frontones => {
          var stringified = JSON.stringify(frontones).replace(/"id"/g, '"value"').replace(/name/g, "text");
          frontones = JSON.parse(stringified);
          frontones.unshift({ value: null, text: "Seleccionar frontón" });
          commit('SET_ENTR_FRONTONES', frontones);
        });
    },
    loadEntrActitudes({ commit, dispatch }) {
      axios
        .get('/www/entrenamientos/actitudes')
        .then( r => r.data )
        .then( actitudes => {
          var stringified = JSON.stringify(actitudes).replace(/"id"/g, '"value"').replace(/name/g, "text");
          actitudes = JSON.parse(stringified);
          actitudes.unshift({ value: null, text: "Seleccionar actitud" });
          commit('SET_ENTR_ACTITUDES', actitudes)
        });
    },
    loadEntrAprovechamientos({ commit, dispatch }) {
      axios
        .get('/www/entrenamientos/aprovechamientos')
        .then( r => r.data )
        .then( aprovechamientos => {
          var stringified = JSON.stringify(aprovechamientos).replace(/"id"/g, '"value"').replace(/name/g, "text");
          aprovechamientos = JSON.parse(stringified);
          aprovechamientos.unshift({ value: null, text: "Seleccionar aprovechamiento" });
          commit('SET_ENTR_APROVECHAMIENTOS', aprovechamientos)
        });
    },
    loadEntrEvoluciones({ commit, dispatch }) {
      axios
        .get('/www/entrenamientos/evoluciones')
        .then( r => r.data )
        .then( evoluciones => {
          var stringified = JSON.stringify(evoluciones).replace(/"id"/g, '"value"').replace(/name/g, "text");
          evoluciones = JSON.parse(stringified);
          evoluciones.unshift({ value: null, text: "Seleccionar evolución" });
          commit('SET_ENTR_EVOLUCIONES', evoluciones)
        });
    },
    resetEvento({ commit }) {
      commit('RESET_EVENTO');
    },
    loadEventos({ commit, dispatch }) {
      axios
        .get('/www/eventos')
        .then( r => r.data )
        .then( eventos => {
          commit('SET_EVENTOS', eventos);
        });
    },
    loadEventoMotivos({ commit, dispatch }) {
      axios
        .get('/www/eventos/motivos')
        .then( r => r.data )
        .then( motivos => {
          var stringified = JSON.stringify(motivos).replace(/"id"/g, '"value"').replace(/name/g, "text");
          motivos = JSON.parse(stringified);
          motivos.unshift({ value: null, text: "Seleccionar motivo" });
          commit('SET_EVENTO_MOTIVOS', motivos);
        });
    },
    addPelotariToEvento({ commit }, pelotari) {
      var uri = '/www/eventos/' + this.getters.evento.id + '/add/pelotari';

      return new Promise( (resolve, reject) => {
        axios
          .post(uri, pelotari)
          .then( r => r.data )
          .then( (response) => {
            commit('ADD_EVENTO_PELOTARI', response);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    updatePelotariFromEvento({ commit }, pelotari) {
      var uri = '/www/eventos/' + this.getters.evento.id + '/update/pelotari';
      axios
        .post(uri, pelotari)
        .then( r => r.data )
        .then( response => {
          commit('UPDATE_EVENTO_PELOTARI', response);
        });
    },
    deletePelotariFromEvento({ commit }, id) {
      var uri = '/www/eventos/' + this.getters.evento.id + '/delete/pelotari';
      axios
        .post(uri, { id: id })
        .then( r => r.data )
        .then( response => {
          commit('DEL_EVENTO_PELOTARI', id);
        });
    },
    loadPelotaris({ commit }, date) {
      axios
        .get('/www/pelotaris', {
            params: {
              fecha: date
            }
        })
        .then( r => r.data )
        .then( pelotaris => {
          var stringified = JSON.stringify(pelotaris).replace(/"id"/g, '"value"').replace(/alias/g, "text");
          pelotaris = JSON.parse(stringified);
          pelotaris.unshift({ value: null, text: "Seleccionar pelotari" });
          commit('SET_PELOTARIS', pelotaris)
        });
    },
    loadProvincias({ commit }) {
      axios
        .get('/www/provincias')
        .then( r => r.data )
        .then( provincias => {
          var stringified = JSON.stringify(provincias).replace(/"id"/g, '"value"').replace(/name/g, "text");
          provincias = JSON.parse(stringified);
          provincias.unshift({ value: null, text: "Seleccionar provincia" });
          commit('SET_PROVINCIAS', provincias)
        })
    },
    loadMunicipios({ commit }) {
      axios
        .get('/www/municipios')
        .then( r => r.data )
        .then( municipios => {
          var stringified = JSON.stringify(municipios).replace(/"id"/g, '"value"').replace(/name/g, "text");
          municipios = JSON.parse(stringified);
          municipios.unshift({ value: null, text: "Seleccionar municipio "});
          commit('SET_MUNICIPIOS', municipios);
          commit('SET_MUNICIPIOS_FILTERED', municipios);
        })
    },
    filterMunicipiosByProvincia({ commit }, id) {
      if (null === id) {
        commit('SET_MUNICIPIOS_FILTERED', this.getters.municipios);
        commit('SET_FRONTONES_FILTERED', this.getters.frontones);
      } else {
        var municipios = this.getters.municipios;
        var municipios_filtered = _.filter(municipios, { 'provincia_id': id });

        municipios_filtered.unshift({ value: null, text: "Seleccionar municipio" });
        commit('SET_MUNICIPIOS_FILTERED', municipios_filtered);

        var frontones = this.getters.frontones;
        var frontones_filtered = _.filter(frontones, { 'provincia_id': id });

        frontones_filtered.unshift({ value: null, text: "Seleccionar frontón" });
        commit('SET_FRONTONES_FILTERED', frontones_filtered);
      }
    },
    loadFrontones({ commit }) {
      axios
        .get('/www/frontones')
        .then( r => r.data )
        .then( frontones => {
          var stringified = JSON.stringify(frontones).replace(/"id"/g, '"value"').replace(/name/g, "text");
          frontones = JSON.parse(stringified);
          frontones.unshift({ value: null, text: "Seleccionar frontón "});
          commit('SET_FRONTONES', frontones);
        })
    },
    loadCampeonatos({ commit }) {
      axios
        .get('/www/campeonatos')
        .then( r => r.data )
        .then( campeonatos => {
          var stringified = JSON.stringify(campeonatos).replace(/"id"/g, '"value"').replace(/name/g, "text");
          campeonatos = JSON.parse(stringified);
          campeonatos.unshift({ value: null, text: "Seleccionar campeonato"});
          commit('SET_CAMPEONATOS', campeonatos);
        })
    },
  }
});
