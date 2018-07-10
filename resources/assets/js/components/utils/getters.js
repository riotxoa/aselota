var APIGetters = {

  data () {
    return {
      provincias: [],
      municipios: [],
      municipios_filtered: [],
      frontones: [],
      frontones_filtered: [],
      festivalEstados: [],
    }
  },
  methods: {

    getProvincias () {
      let uri = '/www/provincias';
      axios.get(uri).then((response) => {
          var stringified = JSON.stringify(response.data).replace(/id/g, "value").replace(/name/g, "text");
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

    getMunicipios () {
      let uri = '/www/municipios';
      axios.get(uri).then((response) => {
          var stringified = JSON.stringify(response.data).replace(/id/g, "value").replace(/name/g, "text");
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

    getFrontones () {
      let uri = '/www/frontones';
      axios.get(uri).then((response) => {
          var stringified = JSON.stringify(response.data).replace(/id/g, "value").replace(/name/g, "text");
          this.frontones = JSON.parse(stringified);
          this.frontones.unshift({ value: null, text: "Seleccionar frontón" });
          this.frontones_filtered = this.frontones;
      });
    },

    onChangeFronton (evt) {
      if (null === this.municipio_id) {
        this.municipio_id = _.filter(this.frontones, { 'value': evt })[0].municipio_value;
        this.onChangeMunicipio(this.municipio_id);
      }
    },

    getFestivalEstados () {
      let uri = '/www/festival-estados';
      axios.get(uri).then((response) => {
        var stringified = JSON.stringify(response.data).replace(/id/g, "value").replace(/name/g, "text");
        this.festivalEstados = JSON.parse(stringified);
        this.festivalEstados.unshift({ value: null, text: "Seleccionar estado" });
      });
    },

  }

}
export default APIGetters;
