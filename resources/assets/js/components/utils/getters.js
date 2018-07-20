var APIGetters = {

  data () {
    return {
      provincias: [],
      municipios: [],
      municipios_filtered: [],
      frontones: [],
      frontones_filtered: [],
      festivalEstados: [],
      campeonatos: [],
      tipos_partido: [],
      tipos_partido_filtered: [],
      is_partido_parejas: true,
      pelotaris: [],
    }
  },
  methods: {

    /* PROVINCIAS */
    getProvincias () {
      let uri = '/www/provincias';
      axios.get(uri).then((response) => {
          var stringified = JSON.stringify(response.data).replace(/"id"/g, '"value"').replace(/name/g, "text");
          this.provincias = JSON.parse(stringified);
          this.provincias.unshift({ value: null, text: "Seleccionar provincia" });
      });
    },

    onChangeProvincia (evt) {
      this.provincia_id = evt;
      if (null === evt) {
        this.municipios_filtered = this.municipios;
        this.frontones_filterd = this.frontones;
      } else {
        this.municipios_filtered = _.filter(this.municipios, { 'provincia_value': evt });
        this.municipios_filtered.unshift({ value: null, text: "Seleccionar municipio" });

        this.frontones_filtered = _.filter(this.frontones, { 'provincia_value': evt});
        this.frontones_filtered.unshift({ value: null, text: "Seleccionar frontón" });
      }
    },

    /* MUNICIPIOS */
    getMunicipios () {
      let uri = '/www/municipios';
      axios.get(uri).then((response) => {
          var stringified = JSON.stringify(response.data).replace(/"id"/g, '"value"').replace(/name/g, "text");
          this.municipios = JSON.parse(stringified);
          this.municipios.unshift({ value: null, text: "Seleccionar municipio" });
          this.municipios_filtered = this.municipios;
      });
    },

    onChangeMunicipio (evt) {
      if (null !== evt) {
        if (null === this.provincia_id) {
          this.provincia_id = _.filter(this.municipios, { 'value': evt })[0].provincia_value;
          this.onChangeProvincia(this.provincia_id);
        }

        this.frontones_filtered = _.filter(this.frontones, { 'municipio_value': evt});
        this.frontones_filtered.unshift({ value: null, text: "Seleccionar frontón" });
      } else {
        if (null === this.provincia_id) {
          this.frontones_filtered = this.frontones;
        } else {
          this.frontones_filtered = _.filter(this.frontones, { 'provincia_value': this.provincia_id});
          this.frontones_filtered.unshift({ value: null, text: "Seleccionar frontón" });
        }
      }
    },

    /* FRONTONES */
    getFrontones () {
      let uri = '/www/frontones';
      axios.get(uri).then((response) => {
          var stringified = JSON.stringify(response.data).replace(/"id"/g, '"value"').replace(/name/g, "text");
          this.frontones = JSON.parse(stringified);
          this.frontones.unshift({ value: null, text: "Seleccionar frontón" });
          this.frontones_filtered = this.frontones;
      });
    },

    onChangeFronton (evt) {
      console.log("[onChangeFronton] evt: " + evt);
      if (null === this.municipio_id) {
        this.municipio_id = _.filter(this.frontones, { 'value': evt })[0].municipio_value;
        this.onChangeMunicipio(this.municipio_id);
      }
    },

    /* ESTADOS DE FESTIVAL */
    getFestivalEstados () {
      let uri = '/www/festival-estados';
      axios.get(uri).then((response) => {
        var stringified = JSON.stringify(response.data).replace(/"id"/g, '"value"').replace(/name/g, "text");
        this.festivalEstados = JSON.parse(stringified);
        this.festivalEstados.unshift({ value: null, text: "Seleccionar estado" });
      });
    },

    /* CAMPEONATOS */
    getCampeonatos () {
      let uri = '/www/campeonatos';
      axios.get(uri).then((response) => {
        var stringified = JSON.stringify(response.data).replace(/"id"/g, '"value"').replace(/name/g, "text");
        this.campeonatos = JSON.parse(stringified);
        this.campeonatos.unshift({ value: null, text: "Seleccionar campeonato" });
      });
    },

    onChangeCampeonato (evt) {
      if( null === evt) {
        this.tipos_partido_filtered = this.tipos_partido;
      } else {
        var campeonato = _.find(this.campeonatos, { 'value': evt });

        this.tipos_partido_filtered = _.filter(this.tipos_partido, { 'value': campeonato.tipo_partido_id });
        this.tipo_partido_id = campeonato.tipo_partido_id;

        this.onChangeTiposPartido(this.tipo_partido_id);
      }
    },

    /* FASES DE CAMPEONATO */
    getFasesCampeonato () {
      this.fases_campeonato = [
        { value: null, text: 'Seleccionar fase'},
        { value: 'campeon', text: 'Campeón' },
        { value: 'sucampeon', text: 'Subcampeón' },
        { value: 'liga_semifinal', text: 'Liga Semifinales' },
        { value: 'liga_cuartos', text: 'Liga Cuartos' },
        { value: 'semifinal', text: 'Semifinal' },
        { value: 'cuartos', text: 'Cuartos' },
        { value: 'octavos', text: 'Octavos' },
        { value: 'dieciseisavos', text: 'Dieciseisavos' },
        { value: 'treintaidosavos', text: 'Treintaidosavos' },
      ];
    },

    /* TIPOS DE PARTIDO */
    getTiposPartido () {
      let uri = '/www/tipos-partido';
      axios.get(uri).then((response) => {
        var stringified = JSON.stringify(response.data).replace(/"id"/g, '"value"').replace(/name/g, "text");
        this.tipos_partido = JSON.parse(stringified);
        this.tipos_partido.unshift({ value: null, text: "Seleccionar tipo" });
        this.tipos_partido_filtered = this.tipos_partido;
      });
    },

    onChangeTiposPartido (evt) {
      if (null === evt) {
        this.is_partido_parejas = true;
      } else {
        var tipo_partido = _.find(this.tipos_partido, {'value': evt});
        this.is_partido_parejas = tipo_partido.parejas;
      }
    },

    /* PELOTARIS */
    getPelotaris () {
      let uri = '/www/pelotaris';
      axios.get(uri).then((response) => {
        var stringified = JSON.stringify(response.data).replace(/"id"/g, '"value"').replace(/alias/g, "text");
        this.pelotaris = JSON.parse(stringified);
        this.pelotaris.unshift({ value: null, text: "Seleccionar pelotari" });
      });
    },

  }

}
export default APIGetters;
