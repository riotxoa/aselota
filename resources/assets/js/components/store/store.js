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
      sanidad: 0,
      num_auxiliares: 1,
      num_taquilleros: 0,
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
      explotacion_id: null,
    },
    explotaciones: [],
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
    costes_fijos: [],
    coste_pelotaris: 0.00,
    coste_jueces: 0.00,
    coste_cancha: 0.00,
    coste_material: 0.00,
    coste_auxiliares: 0.00,
    coste_taquillera: 0.00,
    coste_tasa: 0.00,
    coste_sanidad: 0.00,
    coste_tv: 0,
    importe_tv: 0,
    margen_beneficio: 0,
    correo_aviso_margen: "",
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

    // Módulo MÉDICO
    filter_partes: [],
    parte_edit: false,
    partes: [],
    partes_aux: {},
    parte: {
      id: null,
      fecha_parte: null,
      fecha_accidente: null,
      fecha_baja: null,
      fecha_alta: null,
      fecha_proxima_consulta: null,
      fecha_siguiente: null,
      pelotari_id: null,
      med_diagnostico_id: null,
      med_diagnostico_txt: '',
      med_centro_id: null,
      med_tipo_asistencia_id: null,
      med_motivo_alta_id: null,
      med_evolucion_id: null,
      med_evolucion_txt: '',
      med_tratamiento_id: null,
      med_tratamiento_txt: '',
      is_recaida: false,
      is_baja: false,
    },
    parte_delta: {
      id: null,
      med_parte_id: null,
      fecha_recaida: null,
      fecha_accidente_recaida: null,
      fronton_id: null,
      hora_at: null,
      med_lesion_id: null,
      med_lesion_txt: '',
      med_parte_cuerpo_id: null,
      med_medico_id: null,
      med_medico_txt: '',
      med_tiempo_trabajo_id: null,
      desc_accidente: '',
      med_causante_id: null,
      med_causante_txt: '',
      med_grado_lesion_id: null,
      med_tipo_atencion_id: null,
      tiempo_previsto: null,
      tiempo_previsto_txt: '',
    },
    parte_lesion: {
      id: null,
      med_parte_id: null,
      med_lesion_desc_id: null,
      med_partes_cuerpo_id: null,
      med_partes_cuerpo_txt: '',
      med_medico_id: null,
      med_medico_txt: '',
      med_causante_id: null,
      med_causante_txt: '',
      med_grado_lesion_id: null,
      med_tipo_atencion_id: null,
      descripcion: '',
      observaciones: '',
    },
  },
  getters: {
    festivales: state => state.festivales,
    total_festivales: state => state.total_festivales,
    filter_festivales: state => state.filter_festivales,
    header: state => state.header,
    television_txt: state => state.header.television_txt,
    partidos: state => state.partidos,
    costes: state => state.costes,
    costes_fijos: state => state.costes_fijos,
    coste_pelotaris: state => state.coste_pelotaris,
    coste_jueces: state => state.coste_jueces,
    coste_cancha: state => state.coste_cancha,
    coste_material: state => state.coste_material,
    coste_auxiliares: state => state.coste_auxiliares,
    coste_taquillera: state => state.coste_taquillera,
    coste_tasa: state => state.coste_tasa,
    coste_sanidad: state => state.coste_sanidad,
    coste_tv: state => state.coste_tv,
    importe_tv: state => state.importe_tv,
    margen_beneficio: state => state.margen_beneficio,
    correo_aviso_margen: state => state.correo_aviso_margen,
    coste: state => state.coste,
    facturacion: state => state.facturacion,
    explotaciones: state => state.explotaciones,
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
    /* PARTES MÉDICOS*/
    filter_partes: state => state.filter_partes,
    parte: state => state.parte,
    parte_delta: state => state.parte_delta,
    parte_lesion: state => state.parte_lesion,
    partes: state => state.partes,
    partes_aux: state => state.partes_aux,
    parte_edit: state => state.parte_edit,
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
        sanidad: 0,
        num_auxiliares: 1,
        num_taquilleros: 0,
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
      state.costes_fijos = [];
      state.coste_pelotaris = 0.00;
      state.coste_jueces = 0.00;
      state.coste_cancha = 0.00;
      state.coste_material = 0.00;
      state.coste_auxiliares = 0.00;
      state.coste_taquillera = 0.00;
      state.coste_tasa = 0.00;
      state.coste_sanidad = 0.00;
      state.coste_tv = 0;
      state.importe_tv = 0;
      state.margen_beneficio = 0;
      state.correo_aviso_margen = "";
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

      //cargamos los costes a 0
      state.coste_pelotaris = 0.00;//en INC_COSTE
      state.coste_jueces = 0.00;//en INC_COSTE
      state.coste_cancha = 0.00;
      state.coste_material = 0.00;
      state.coste_auxiliares = 0.00;
      state.coste_taquillera = 0.00;
      state.coste_tasa = 0.00;
      state.coste_sanidad = 0.00;
      state.coste_tv = 0;
      state.importe_tv = 0;
      state.margen_beneficio = 0;
      state.correo_aviso_margen = "";
      state.coste = 0.00;
    },
    SET_HEADER_ID (state, id)  {
      state.header.id = id;
    },
    SET_TELEVISION_TXT (state, txt) {
      state.header.television_txt = txt;
    },
    SET_PARTIDOS (state, partidos) {
      state.partidos = partidos;

      partidos.map((val,key) => {
        this.commit('INC_COSTE', val);
      });
      this.commit('SET_COSTE');
      this.commit('SUM_COSTE');
    },
    ADD_PARTIDO (state, partido) {
      state.partidos.push(partido);
      state.partidos = _.sortBy(state.partidos, ['orden']);

      this.commit('INC_COSTE', partido);
      this.commit('SUM_COSTE', state);
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
    SET_COSTES_FIJOS (state, costes_fijos) {
      state.costes_fijos = costes_fijos;

      //Coste de material
      // state.coste_material = _.filter(state.costes_fijos, { 'id': 1 })[0].coste;
      state.coste_material = _.filter(state.costes_fijos, { 'descripcion': 'Material del festival' })[0].coste;

      //Coste de auxiliares
      // var coste_aux = _.filter(state.costes_fijos, { 'id': 2 })[0].coste;
      var coste_aux = _.filter(state.costes_fijos, { 'descripcion': 'Tarifa para auxiliares' })[0].coste;
      state.coste_auxiliares = coste_aux*state.costes.num_auxiliares;

      //Coste de taquilleros
      // var coste_taq = _.filter(state.costes_fijos, { 'id': 3 })[0].coste;
      var coste_taq = _.filter(state.costes_fijos, { 'descripcion': 'Tarifa para taquillera' })[0].coste;
      state.coste_taquillera = coste_taq*state.costes.num_taquilleros;

      //Coste de sanidad
      this.commit('SET_COSTE_SANIDAD', state.costes.sanidad);

      //Coste incremento por televisión
      if(state.header.television){
        // state.coste_tv = _.filter(state.costes_fijos, { 'id': 5 })[0].coste;
        state.coste_tv = _.filter(state.costes_fijos, { 'descripcion': 'Festival televisado (%)' })[0].coste;
      }

      //Margen de beneficio del festival
      // state.margen_beneficio = _.filter(state.costes_fijos, { 'id': 6 })[0].coste;
      // state.correo_aviso_margen = _.filter(state.costes_fijos, { 'id': 6 })[0].correo;
      state.margen_beneficio = _.filter(state.costes_fijos, { 'descripcion': 'Margen de beneficio (%)' })[0].coste;
      state.correo_aviso_margen = _.filter(state.costes_fijos, { 'descripcion': 'Margen de beneficio (%)' })[0].correo;

    },
    SET_COSTE_AUXILIARES (state, num_auxiliares){

      if(state.costes_fijos.length){
        // var coste_aux = _.filter(state.costes_fijos, { 'id': 2 })[0].coste;
        var coste_aux = _.filter(state.costes_fijos, { 'descripcion': 'Tarifa para auxiliares' })[0].coste;
        state.costes.num_auxiliares = num_auxiliares;
        state.coste_auxiliares = coste_aux*num_auxiliares;
        this.commit('SUM_COSTE', state);
      }
    },
    SET_COSTE_TAQUILLEROS (state, num_taquilleros){
      if(state.costes_fijos.length){
        // var coste_aux = _.filter(state.costes_fijos, { 'id': 3 })[0].coste;
        var coste_aux = _.filter(state.costes_fijos, { 'descripcion': 'Tarifa para taquillera' })[0].coste;
        state.costes.num_taquilleros = num_taquilleros;
        state.coste_taquillera = coste_aux*num_taquilleros;
        this.commit('SUM_COSTE', state);
      }
    },
    SET_COSTE_SANIDAD (state, sanidad){
        //Coste de servicio sanitario
      if(sanidad){
        // state.coste_sanidad = _.filter(state.costes_fijos, { 'id': 4 })[0].coste;
        state.coste_sanidad = _.filter(state.costes_fijos, { 'descripcion': 'Servicio sanitario' })[0].coste;
      }else{
        state.coste_sanidad = 0;
      }
      state.costes.sanidad = sanidad;
      this.commit('SUM_COSTE', state);
    },
    INC_COSTE (state, partido) {
      //CAMBIO2
      //Coste pelotaris
      state.coste_pelotaris += (partido.pelotari_1 ? ( 1 === partido.pelotari_1.asiste ? partido.pelotari_1.coste : partido.pelotari_1.sustituto_coste ) : 0);
      state.coste_pelotaris += (partido.pelotari_2 ? ( 1 === partido.pelotari_2.asiste ? partido.pelotari_2.coste : partido.pelotari_2.sustituto_coste ) : 0);
      state.coste_pelotaris += (partido.pelotari_3 ? ( 1 === partido.pelotari_3.asiste ? partido.pelotari_3.coste : partido.pelotari_3.sustituto_coste ) : 0);
      state.coste_pelotaris += (partido.pelotari_4 ? ( 1 === partido.pelotari_4.asiste ? partido.pelotari_4.coste : partido.pelotari_4.sustituto_coste ) : 0);

      //Coste jueces
      if(partido.campeonato_id!=null){
        var campeonato = _.filter(state.campeonatos, { 'value': partido.campeonato_id })[0];
        if(campeonato!=null){
          if(partido.fase=="finales"){
            state.coste_jueces += campeonato.coste_jueces_f;
          }else if(partido.fase=="semifinal"){
            state.coste_jueces += campeonato.coste_jueces_s;
          }else{
            state.coste_jueces += campeonato.coste_jueces_p;
          }
        }
      }
    },
    SUM_COSTE (state) {

      //Hacemos la suma de todos los costes
      state.coste = 0;
      state.coste += state.coste_pelotaris;
      state.coste += state.coste_jueces;
      state.coste += state.coste_cancha;
      state.coste += state.coste_material;
      state.coste += state.coste_auxiliares;
      state.coste += state.coste_taquillera;
      state.coste += state.coste_tasa;
      state.coste += state.coste_sanidad;

      //sumamos el porcentaje por televisión
      state.importe_tv = state.coste * state.coste_tv / 100;
      state.coste += state.importe_tv;

      //Calculamos el importe de venta ideal
      state.costes.importe_venta = parseInt(state.coste+((state.coste*state.margen_beneficio)/100));
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
    SET_EXPLOTACIONES (state, explotaciones) {
      state.explotaciones = explotaciones;
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
    SET_COSTE (state) {
      //Coste cancha
      var fronton = _.filter(state.frontones, { 'value': state.header.fronton_id })[0];
      if(fronton!=null && fronton.coste_alquiler!=null){
        state.coste_cancha = fronton.coste_alquiler;
      }

      //Coste de tasa de juego
      if(fronton!=null){//ya lo hemos recogido antes
        var provincia = _.filter(state.provincias, { 'value': fronton.provincia_id })[0];
        if(provincia!=null){
          state.coste_tasa = provincia.tasa_juego;
        }
      }
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

    // Módulo MÉDICO
    SET_PARTES (state, partes) {
      state.partes = partes;
    },
    SET_PARTES_AUX (state, aux) {
      state.partes_aux.causantes = aux.causantes;
      state.partes_aux.centros = aux.centros;
      state.partes_aux.diagnosticos = aux.diagnosticos;
      state.partes_aux.evoluciones = aux.evoluciones;
      state.partes_aux.grados_lesion = aux.grados_lesion;
      state.partes_aux.lesiones_dsc = aux.lesiones_dsc;
      state.partes_aux.medicos = aux.medicos;
      state.partes_aux.motivos_alta = aux.motivos_alta;
      state.partes_aux.partes_cuerpo = aux.partes_cuerpo;
      state.partes_aux.tiempos_trabajo = aux.tiempos_trabajo;
      state.partes_aux.tipos_asistencia = aux.tipos_asistencia;
      state.partes_aux.tipos_atencion = aux.tipos_atencion;
      state.partes_aux.tratamientos = aux.tratamientos;
      state.partes_aux.lugares = aux.lugares;
    },
    SET_PARTE_HEADER_ID (state, id) {
      state.parte.id = id;
    },

    SET_LESION_PARTE_ID( state, val ) {
      state.parte_lesion.med_parte_id = val;
    },
    SET_LESION_LESION_DESC_ID( state, val ) {
      state.parte_lesion.med_lesion_desc_id = val;
    },
    SET_LESION_PARTES_CUERPO_ID( state, val ) {
      state.parte_lesion.med_partes_cuerpo_id = val;
    },
    SET_LESION_PARTES_CUERPO_TXT( state, val ) {
      state.parte_lesion.med_partes_cuerpo_txt = val;
    },
    SET_LESION_MEDICO_ID( state, val ) {
      state.parte_lesion.med_medico_id = val;
    },
    SET_LESION_MEDICO_TXT( state, val ) {
      state.parte_lesion.med_medico_txt = val;
    },
    SET_LESION_CAUSANTE_ID( state, val ) {
      state.parte_lesion.med_causante_id = val;
    },
    SET_LESION_CAUSANTE_TXT( state, val ) {
      state.parte_lesion.med_causante_txt = val;
    },
    SET_LESION_GRADO_LESION_ID( state, val ) {
      state.parte_lesion.med_grado_lesion_id = val;
    },
    SET_LESION_TIPO_ATENCION_ID( state, val ) {
      state.parte_lesion.med_tipo_atencion_id = val;
    },
    SET_LESION_DESCRIPCION( state, val ) {
      state.parte_lesion.descripcion = val;
    },
    SET_LESION_OBSERVACIONES( state, val ) {
      state.parte_lesion.observaciones = val;
    },

    SET_PARTE_HEADER( state, header ) {
      state.parte = header;
    },
    SET_PARTE_DELTA( state, parte_delta ) {
      state.parte_delta = parte_delta;
    },
    SET_PARTE_LESION( state, parte_lesion ) {
      state.parte_lesion = parte_lesion;
    },
    SET_PARTE_EDIT( state, val ) {
      state.parte_edit = val;
    },
    SET_PARTE_FECHA_PARTE( state, val ) {
      state.parte.fecha_parte = val;
    },
    SET_PARTE_IS_BAJA( state, val ) {
      state.parte.is_baja = val;
    },
    SET_PARTE_FECHA_ACCIDENTE( state, val ) {
      state.parte.fecha_accidente = val;
    },
    SET_PARTE_FECHA_BAJA( state, val ) {
      state.parte.fecha_baja = val;
    },
    SET_PARTE_FECHA_ALTA( state, val ) {
      state.parte.fecha_alta = val;
    },
    SET_PARTE_FECHA_PROXIMA_CONSULTA( state, val ) {
      state.parte.fecha_proxima_consulta = val;
    },
    SET_PARTE_FECHA_SIGUIENTE( state, val ) {
      state.parte.fecha_siguiente = val;
    },
    SET_PARTE_PELOTARI( state, val ) {
      state.parte.pelotari_id = val;
    },
    SET_PARTE_DIAGNOSTICO( state, val ) {
      state.parte.med_diagnostico_id = val;
    },
    SET_PARTE_DIAGNOSTICO_TXT( state, val ) {
      state.parte.med_diagnostico_txt = val;
    },
    SET_PARTE_CENTRO( state, val ) {
      state.parte.med_centro_id = val;
    },
    SET_PARTE_TIPO_ASISTENCIA( state, val ) {
      state.parte.med_tipo_asistencia_id = val;
    },
    SET_PARTE_MOTIVO_ALTA( state, val ) {
      state.parte.med_motivo_alta_id = val;
    },
    SET_PARTE_EVOLUCION( state, val ) {
      state.parte.med_evolucion_id = val;
    },
    SET_PARTE_EVOLUCION_TXT( state, val ) {
      state.parte.med_evolucion_txt = val;
    },
    SET_PARTE_TRATAMIENTO( state, val ) {
      state.parte.med_tratamiento_id = val;
    },
    SET_PARTE_TRATAMIENTO_TXT( state, val ) {
      state.parte.med_tratamiento_txt = val;
    },
    SET_PARTE_IS_RECAIDA( state, val ) {
      state.parte.is_recaida = val;
    },

    SET_FILTER_PARTES (state, filter) {
      state.filter_partes = filter;
    },
    ADD_FILTER_PARTES (state, value) {
      state.filter_partes.push(value);
    },
    REMOVE_FILTER_PARTES (state, index) {
      state.filter_partes.splice(index, 1);
    },

    RESET_PARTE (state) {
      state.parte.id = null;
      state.parte.fecha_parte = null;
      state.parte.fecha_accidente = null;
      state.parte.fecha_baja = null;
      state.parte.fecha_alta = null;
      state.parte.fecha_proxima_consulta = null;
      state.parte.fecha_siguiente = null;
      state.parte.pelotari_id = null;
      state.parte.med_diagnostico_id = null;
      state.parte.med_diagnostico_txt = '';
      state.parte.med_centro_id = null;
      state.parte.med_tipo_asistencia_id = null;
      state.parte.med_motivo_alta_id = null;
      state.parte.med_evolucion_id = null;
      state.parte.med_evolucion_txt = '';
      state.parte.med_tratamiento_id = null;
      state.parte.med_tratamiento_txt = '';
      state.parte.is_recaida = false;
      state.parte.is_baja = false;
    },
    RESET_PARTE_DELTA (state) {
      state.parte_delta.id = null;
      state.parte_delta.med_parte_id = null;
      state.parte_delta.fecha_recaida = null;
      state.parte_delta.fecha_accidente_recaida = null;
      state.parte_delta.fronton_id = null;
      state.parte_delta.hora_at = null;
      state.parte_delta.med_lesion_id = null;
      state.parte_delta.med_lesion_txt = '';
      state.parte_delta.med_parte_cuerpo_id = null;
      state.parte_delta.med_medico_id = null;
      state.parte_delta.med_medico_txt = '';
      state.parte_delta.med_tiempo_trabajo_id = null;
      state.parte_delta.desc_accidente = '';
      state.parte_delta.med_causante_id = null;
      state.parte_delta.med_causante_txt = '';
      state.parte_delta.med_grado_lesion_id = null;
      state.parte_delta.med_tipo_atencion_id = null;
      state.parte_delta.tiempo_previsto = null;
      state.parte_delta.tiempo_previsto_txt = '';
    },
    RESET_PARTE_LESION (state) {
      state.parte_lesion.id = null;
      state.parte_lesion.med_parte_id = null;
      state.parte_lesion.med_lesion_desc_id = null;
      state.parte_lesion.med_partes_cuerpo_id = null;
      state.parte_lesion.med_partes_cuerpo_txt = '';
      state.parte_lesion.med_medico_id = null;
      state.parte_lesion.med_medico_txt = '';
      state.parte_lesion.med_causante_id = null;
      state.parte_lesion.med_causante_txt = '';
      state.parte_lesion.med_grado_lesion_id = null;
      state.parte_lesion.med_tipo_atencion_id = null;
      state.parte_lesion.descripcion = '';
      state.parte_lesion.observaciones = '';
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
          dispatch('loadCostesFijos');
          dispatch('loadFrontones');
          dispatch('loadCampeonatos');
          dispatch('loadProvincias');
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
    loadCostesFijos({ commit }) {
      return new Promise( (resolve, reject) => {
        let data = {};
        axios
          .get('/www/costes-fijos', data)
          .then( r => r.data )
          .then( costes_fijos => {
            commit('SET_COSTES_FIJOS', costes_fijos);
            resolve( costes_fijos );
          })
          .catch( error => {
            reject( error );
          });
      });
    },
    loadCostes({ commit }) {
      return new Promise( (resolve, reject) => {
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
            resolve(costes);
          })
          .catch( err => {
            reject(err);
          });
      });
    },
    addCostes({ commit }, costes) {
      let uri = '/www/festival-costes';
      costes.festival_id = this.getters.header.id;

      //costes de la empresa desglosados
      costes.coste_pelotaris = this.getters.coste_pelotaris;
      costes.coste_jueces = this.getters.coste_jueces;
      costes.coste_cancha = this.getters.coste_cancha;
      costes.coste_material = this.getters.coste_material;
      costes.coste_auxiliares = this.getters.coste_auxiliares;
      costes.coste_taquillera = this.getters.coste_taquillera;
      costes.coste_sanidad = this.getters.coste_sanidad;

      //total coste de empresa
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
    updateCosteAuxiliares({ commit }, num_auxiliares) {
      commit('SET_COSTE_AUXILIARES', num_auxiliares);
    },
    updateCosteTaquilleros({ commit }, num_taquilleros) {
      commit('SET_COSTE_TAQUILLEROS', num_taquilleros);
    },
    updateCosteSanidad({ commit}, sanidad) {
      commit('SET_COSTE_SANIDAD', sanidad);
    },
    envioCorreoConfirmacion({commit}) {
      let uri = '/envio-confirmacion-email';
      let importe_sugerido = parseInt(parseInt(this.getters.costes.coste_empresa)+((parseInt(this.getters.costes.coste_empresa)*parseInt(this.getters.margen_beneficio))/100));

      let data = {
        festival_id: this.getters.header.id,
        festival_fecha: this.getters.header.fecha,
        festival_fronton: this.getters.header.fronton,
        organizador: this.getters.header.organizador,
        televisado: this.getters.header.television,
        partidos: this.getters.partidos,
        coste_empresa: this.getters.coste,
        importe_ideal: this.getters.costes.importe_venta,
        importe_sugerido: importe_sugerido,
        correo_aviso_margen: this.getters.correo_aviso_margen
      };

      return new Promise( (resolve, reject) => {
        axios
          .post(uri, data)
          .then( r => r.data)
          .then( response =>  {
            alert(response);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    exportarCuadroMando({ commit }){
      let uri = '/exportar-cuadro-mando';
      return new Promise( (resolve, reject) => {
        axios
          .get(uri)
          .then( r => r.data)
          .then( response =>  {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
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

      return new Promise( (resolve, reject) => {
        axios
          .get('/www/festival-facturacion', data)
          .then( r => r.data)
          .then( facturacion =>  {
            commit('SET_FACTURACION', facturacion);
            resolve(facturacion);
          })
          .catch((error) => {
            reject(error);
          });
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
    delFacturacion({ commit }, id) {
      return new Promise( (resolve, reject) => {

        let uri = '/www/festival-facturacion/' + id;

        axios
          .delete(uri)
          .then( r => r.data )
          .then(( response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });

      });
    },
    loadExplotaciones({ commit }) {
      return new Promise( (resolve, reject) => {
        axios
          .get('/www/explotaciones')
          .then( r => r.data )
          .then( explotaciones => {
            commit('SET_EXPLOTACIONES', explotaciones);
            var stringified = JSON.stringify(explotaciones).replace(/"id"/g, '"value"').replace(/desc/g, "text");
            explotaciones = JSON.parse(stringified);
            explotaciones.unshift({ value: null, text: "Seleccionar" });
            resolve(explotaciones);
          })
          .catch((error) => {
            reject(error);
          });
      })
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
              commit('SET_CONTACTOS', contactos[0]);
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
    loadPelotarisProfesional({ commit }, date) {
      axios
        .get('/www/pelotaris-profesional', {
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
    loadPelotarisPromesa({ commit }, date) {
      axios
        .get('/www/pelotaris-promesa', {
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

    // Módulo MÉDICO
    addFilterPartes({ commit, dispatch }, value) {
      commit('ADD_FILTER_PARTES', value);
      dispatch('filterPartes');
    },
    removeFilterPartes({ commit, dispatch }, index) {
      commit('REMOVE_FILTER_PARTES', index);
      dispatch('filterPartes');
    },
    filterPartes({ commit }) {
      let data = {
        params: {
          filter: this.getters.filter_partes, // filter
        }
      };
      axios
        .get('/www/partes', data)
        .then( r => r.data )
        .then( partes => {
          commit('SET_PARTES', partes);
        });
    },
    getInfoAuxPartesMedicos({ commit }) {
      return new Promise( (resolve, reject) => {
        axios
          .get('/www/partes-medicos-aux')
          .then( r => r.data )
          .then( aux => {
            var stringified = JSON.stringify(aux).replace(/"id"/g, '"value"').replace(/desc/g, "text").replace(/name/g, "text");
            aux = JSON.parse(stringified);
            commit( 'SET_PARTES_AUX', aux );
            resolve(aux);
          })
          .catch( err => {
            reject(err);
          });
      });

    },
    loadPartes({ commit }) {
      return new Promise( (resolve, reject) => {
        axios
          .get('/www/partes')
          .then( r => r.data )
          .then( partes => {
            commit('SET_PARTES', partes);
            resolve( partes );
          })
          .catch( err => {
            reject( err );
          });
      });
    },
    resetParte({ commit }) {
      commit('RESET_PARTE');
      commit('RESET_PARTE_DELTA');
      commit('RESET_PARTE_LESION');
    },
    resetParteDelta({ commit }) {
      commit('RESET_PARTE_DELTA');
    },
    resetParteLesion({ commit }) {
      commit('RESET_PARTE_LESION');
    },

    saveParte({ commit }, data) {
      return new Promise( (resolve, reject) => {
        let uri = '/www/partes';

        axios.post(uri, data)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    updateParte({ commit }, data) {
      return new Promise( (resolve, reject) => {
        let uri = '/www/partes/' + data.header.id + '/update';

        axios.post(uri, data)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },

    updateLesionParte({ commit }, val) {
      commit('SET_LESION_PARTE_ID', val);
    },
    updateLesionLesionDesc({ commit }, val) {
      commit('SET_LESION_LESION_DESC_ID', val);
    },
    updateLesionPartesCuerpo({ commit }, val) {
      commit('SET_LESION_PARTES_CUERPO_ID', val);
    },
    updateLesionPartesCuerpoTxt({ commit }, val) {
      commit('SET_LESION_PARTES_CUERPO_TXT', val);
    },
    updateLesionMedico({ commit }, val) {
      commit('SET_LESION_MEDICO_ID', val);
    },
    updateLesionMedicoTxt({ commit }, val) {
      commit('SET_LESION_MEDICO_TXT', val);
    },
    updateLesionCausante({ commit }, val) {
      commit('SET_LESION_CAUSANTE_ID', val);
    },
    updateLesionCausanteTxt({ commit }, val) {
      commit('SET_LESION_CAUSANTE_TXT', val);
    },
    updateLesionGradoLesion({ commit }, val) {
      commit('SET_LESION_GRADO_LESION_ID', val);
    },
    updateLesionTipoAtencion({ commit }, val) {
      commit('SET_LESION_TIPO_ATENCION_ID', val);
    },
    updateLesionDescripcion({ commit }, val) {
      commit('SET_LESION_DESCRIPCION', val);
    },
    updateLesionObservaciones({ commit }, val) {
      commit('SET_LESION_OBSERVACIONES', val);
    },

    updateParteFechaParte({ commit }, val) {
      commit('SET_PARTE_FECHA_PARTE', val);
    },
    updateParteIsBaja({ commit }, val) {
      commit('SET_PARTE_IS_BAJA', val);
    },
    updateParteFechaAccidente({ commit }, val) {
      commit('SET_PARTE_FECHA_ACCIDENTE', val);
    },
    updateParteFechaBaja({ commit }, val) {
      commit('SET_PARTE_FECHA_BAJA', val);
    },
    updateParteFechaAlta({ commit }, val) {
      commit('SET_PARTE_FECHA_ALTA', val);
    },
    updateParteFechaProximaConsulta({ commit }, val) {
      commit('SET_PARTE_FECHA_PROXIMA_CONSULTA', val);
    },
    updateParteFechaSiguiente({ commit }, val) {
      commit('SET_PARTE_FECHA_SIGUIENTE', val);
    },
    updatePartePelotari({ commit }, val) {
      commit('SET_PARTE_PELOTARI', val);
    },
    updateParteDiagnostico({ commit }, val) {
      commit('SET_PARTE_DIAGNOSTICO', val);
    },
    updateParteDiagnosticoTxt({ commit }, val) {
      commit('SET_PARTE_DIAGNOSTICO_TXT', val);
    },
    updateParteCentro({ commit }, val) {
      commit('SET_PARTE_CENTRO', val);
    },
    updateParteTipoAsistencia({ commit }, val) {
      commit('SET_PARTE_TIPO_ASISTENCIA', val);
    },
    updateParteMotivoAlta({ commit }, val) {
      commit('SET_PARTE_MOTIVO_ALTA', val);
    },
    updateParteEvolucion({ commit }, val) {
      commit('SET_PARTE_EVOLUCION', val);
    },
    updateParteEvolucionTxt({ commit }, val) {
      commit('SET_PARTE_EVOLUCION_TXT', val);
    },
    updateParteTratamiento({ commit }, val) {
      commit('SET_PARTE_TRATAMIENTO', val);
    },
    updateParteTratamientoTxt({ commit }, val) {
      commit('SET_PARTE_TRATAMIENTO_TXT', val);
    },
    updateParteIsRecaida({ commit }, val) {
      commit('SET_PARTE_IS_RECAIDA', val);
    },
  }
});
