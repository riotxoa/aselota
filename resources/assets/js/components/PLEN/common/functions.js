import { mapActions, mapState } from 'vuex';
import moment from 'moment';

export default {
  methods: {
    getFechaDB(fecha) {
      return moment(fecha).format('YYYY-MM-DD');
    },
    getFechaES(fecha, format = 'long') {
      let fechaES = fecha;

      switch( format ) {
        case 'short':
          fechaES = moment(fecha).format('D MMM');
          break;
        default:
          fechaES = moment(fecha).format('DD/MM/YYYY');
          break;
      }

      return fechaES;
    },
    getFechaWeekday(fecha, format = 'long') {
      let weekday = '';

      switch( format ) {
        case 'short':
          weekday = moment(fecha).locale('es').format('dd');
          break;
        default:
          weekday = moment(fecha).locale('es').format('dddd');
          break;
      }

      return weekday;
    },
    getRangoFechas(f_ini, f_fin, format = 'long') {
      return this.getFechaES(f_ini, format) + ' - ' + this.getFechaES(f_fin, format)
    },
  }
}

const mesociclo = {
  data() {
    return {
      editMesociclo: {
        item: null,
        callback: null,
      },
      mesociclo_backup: {
        id: null,
        mesociclo_id: null,
        tipo_microciclo_id: null,
        fecha_ini: null,
        fecha_fin: null,
        volumen: null,
        intensidad: null,
        description: '',
        objetivos: ''
      }
    }
  },
  computed: mapState({
    mesociclo: state => state.plen.mesociclo
  }),
  methods: {
    ...mapActions({
      setMesociclo: 'plen/setMesociclo'
    }),
    cancelEditMesociclo() {
      this.setMesociclo(this.editMesociclo.item);
      this.resetEditMesociclo();
    },
    makeMesocicloBackup() {
      this.resetMesocicloBackup();
      this.mesociclo_backup = JSON.parse(JSON.stringify(this.mesociclo));
    },
    resetEditMesociclo() {
      this.editMesociclo = {
        item: null,
        callback: null
      }
    },
    resetMesocicloBackup() {
      this.mesociclo_backup = {
        id: null,
        macrociclo_id: null,
        tipo_mesociclo_id: null,
        fecha_ini: null,
        fecha_fin: null,
        description: '',
        objetivos: '',
        microciclos: []
      };
    },
    restoreMesocicloBackup() {
      const mesociclo_backup = JSON.parse(JSON.stringify(this.mesociclo_backup));
      this.mesociclo.id = mesociclo_backup.id;
      this.mesociclo.macrociclo_id = mesociclo_backup.macrociclo_id;
      this.mesociclo.tipo_mesociclo_id = mesociclo_backup.tipo_mesociclo_id;
      this.mesociclo.fecha_ini = mesociclo_backup.fecha_ini;
      this.mesociclo.fecha_fin = mesociclo_backup.fecha_fin;
      this.mesociclo.description = mesociclo_backup.description;
      this.mesociclo.objetivos = mesociclo_backup.objetivos;
      this.mesociclo.microciclos = mesociclo_backup.microciclos;
      this.setMesociclo(this.mesociclo);
    },
    setEditMesociclo( item, callback = null ) {
      this.editMesociclo = {
        item: item,
        callback: callback
      }
    },
  }
}

const microciclo = {
  data() {
    return {
      editMicrociclo: {
        item: null,
        callback: null,
      },
      microciclo_backup: {
        id: null,
        mesociclo_id: null,
        tipo_microciclo_id: null,
        fecha_ini: null,
        fecha_fin: null,
        volumen: null,
        intensidad: null,
        description: '',
        objetivos: ''
      }
    }
  },
  computed: mapState({
    microciclo: state => state.plen.microciclo
  }),
  methods: {
    ...mapActions({
      setMicrociclo: 'plen/setMicrociclo'
    }),
    cancelEditMicrociclo() {
      this.setMicrociclo(this.editMicrociclo.item);
      this.resetEditMicrociclo();
    },
    makeMicrocicloBackup() {
      this.resetMicrocicloBackup();
      this.microciclo_backup = JSON.parse(JSON.stringify(this.microciclo));
    },
    resetEditMicrociclo() {
      this.editMicrociclo = {
        item: null,
        callback: null
      }
    },
    resetMicrocicloBackup() {
      this.microciclo_backup = {
        id: null,
        mesociclo_id: null,
        tipo_microciclo_id: null,
        fecha_ini: null,
        fecha_fin: null,
        volumen: null,
        intensidad: null,
        description: '',
        objetivos: ''
      };
    },
    restoreMicrocicloBackup() {
      const microciclo_backup = JSON.parse(JSON.stringify(this.microciclo_backup));
      this.microciclo.id = microciclo_backup.id;
      this.microciclo.mesociclo_id = microciclo_backup.mesociclo_id;
      this.microciclo.tipo_microciclo_id = microciclo_backup.tipo_microciclo_id;
      this.microciclo.fecha_ini = microciclo_backup.fecha_ini;
      this.microciclo.fecha_fin = microciclo_backup.fecha_fin;
      this.microciclo.volumen = microciclo_backup.volumen;
      this.microciclo.intensidad = microciclo_backup.intensidad;
      this.microciclo.description = microciclo_backup.description;
      this.microciclo.objetivos = microciclo_backup.objetivos;
      this.setMicrociclo(this.microciclo);
    },
    setEditMicrociclo( item, callback = null ) {
      this.editMicrociclo = {
        item: item,
        callback: callback
      }
    },
  }
}

const sesion = {
  data() {
    return {
      editSesion: {
        item: null,
        callback: null,
      },
      sesion_backup: {
        id: null,
        microciclo_id: null,
        fecha: null,
        hora: null,
        fronton_id: null,
        pelotaris: [],
      }
    }
  },
  computed: mapState({
    sesion: state => state.plen.sesion
  }),
  methods: {
    ...mapActions({
      setSesion: 'plen/setSesion'
    }),
    cancelEditSesion() {
      this.setSesion(this.editSesion.item);
      this.resetEditSesion();
    },
    makeSesionBackup() {
      this.resetSesionBackup();
      this.sesion_backup = JSON.parse(JSON.stringify(this.sesion));
    },
    resetEditSesion() {
      this.editSesion = {
        item: null,
        callback: null
      }
    },
    resetSesionBackup() {
      this.sesion_backup = {
        id: null,
        microciclo_id: null,
        fecha: null,
        hora: null,
        fronton_id: null,
        pelotaris: [],
      };
    },
    restoreSesionBackup() {
      const sesion_backup = JSON.parse(JSON.stringify(this.sesion_backup));
      this.sesion.id = sesion_backup.id;
      this.sesion.microciclo_id = sesion_backup.microciclo_id;
      this.sesion.fecha = sesion_backup.fecha;
      this.sesion.hora = sesion_backup.hora;
      this.sesion.fronton_id = sesion_backup.fronton_id;
      this.sesion.pelotaris = [];
      sesion_backup.pelotaris.map( (val, key) => {
        const index = this.sesion.pelotaris.length;
        this.sesion.pelotaris.push(val);
        let ejercicios = [];
        sesion_backup.pelotaris[key].ejercicios.map( (v, k) => {
          ejercicios.push(v);
        });
        this.sesion.pelotaris[index].ejercicios = ejercicios;
      });
      this.setSesion(this.sesion);
    },
    setEditSesion( item, callback = null ) {
      this.editSesion = {
        item: item,
        callback: callback
      }
    },
  }
}

export { mesociclo, microciclo, sesion };
