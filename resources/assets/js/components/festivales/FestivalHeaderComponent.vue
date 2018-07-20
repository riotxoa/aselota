<template>
  <div class="header">

      <b-form @submit="onSubmit" @reset="onReset" v-if="show">
        <div class="toolbar mb-2 py-1">
          <div class="container">
            <b-row>
              <h4 class="col-sm-6 text-white font-weight-bold m-0">{{ this.formTitle }}</h4>
              <div class="col-sm-6 text-right">
                <b-button type="submit" variant="danger">Guardar</b-button>
                <b-button type="reset" variant="default">Volver</b-button>
              </div>
            </b-row>
          </div>
        </div>
        <div class="container">
          <b-row>
            <b-form-group label="Fecha:"
                          label-for="fechaInput"
                          class="col-sm-3">
              <b-form-input id="fechaInput"
                            class="d-inline-block px-1"
                            style="min-width:127px;width:calc(100% - 25px);"
                            :readonly="!editdate"
                            type="date"
                            v-model="header.fecha"
                            :change="onChangeDate()"
                            required>
              </b-form-input>
              <b-link v-if="edit" class="pl-1 d-inline-block" @click="editDate(true)">
                <i class="voyager-pen"></i>
              </b-link>
            </b-form-group>
            <b-form-group label="Día:"
                          label-for="diaInput"
                          class="col-sm-2">
              <b-form-input id="diaInput"
                            type="text"
                            v-model="dia"
                            readonly>
              </b-form-input>
            </b-form-group>
            <b-form-group label="Hora:"
                          label-for="hourInput"
                          class="col-sm-2">
              <b-form-input id="hourInput"
                            :readonly="editdate"
                            type="time"
                            required
                            v-model="header.hora">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Provincia:"
                          label-for="provinciaInput"
                          class="col-sm-2">
              <b-form-select id="provinciaInput"
                             :readonly="editdate"
                             :options="provincias"
                             @change="onChangeProvincia"
                             v-model="provincia_id">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Municipio:"
                          label-for="municipioInput"
                          class="col-sm-3">
              <b-form-select id="municipioInput"
                             :readonly="editdate"
                             :options="municipios_filtered"
                             @change="onChangeMunicipio"
                             v-model="municipio_id">
              </b-form-select>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Frontón:"
                          label-for="frontonInput"
                          class="col-sm-3">
              <b-form-select id="frontonInput"
                             :readonly="editdate"
                             :options="frontones_filtered"
                             @change="onChangeFronton"
                             required
                             v-model="header.fronton_id">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Televisión:"
                          label-for="televisionInput"
                          class="col-sm-2">
              <b-form-select id="televisionInput"
                            :readonly="editdate"
                            :options="television"
                            required
                            v-model="header.television">
              </b-form-select>
            </b-form-group>
            <b-form-group label=" "
                          label-for="televisionTxtInput"
                          class="col-sm-4 mt-1">
              <b-form-input id="televisionTxtInput"
                            :readonly="editdate"
                            v-model="header.television_txt">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Estado festival:"
                          label-for="estadoInput"
                          class="col-sm-3">
              <b-form-select id="estadoInput"
                             :readonly="editdate"
                             :options="festivalEstados"
                             required
                             v-model="header.estado_id">
              </b-form-select>
            </b-form-group>
          </b-row>
        </div>
      </b-form>

  </div>
</template>

<script>
  import APIGetters from '../utils/getters.js';
  import Nav from '../utils/nav.js';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [APIGetters, Nav, Utils],
    props: ['formTitle', 'festivalId', 'edit'],
    data () {
      return {
        header: {
          id: null,
          fecha: '',
          hora: '',
          fronton_id: null,
          television: 0,
          television_txt: '',
          estado_id: 1,
        },
        dia: '',
        provincia_id: null,
        municipio_id: null,
        television: [
          { value: 0, text: "No" },
          { value: 1, text: "Sí" },
        ],
        editdate: !this.edit,
        show: true
      }
    },
    created: function () {
      console.log("FestivalHeaderComponent created");
      this.getMunicipios();
      this.getProvincias();
      this.getFrontones();
      this.getFestivalEstados();

      this.header.fecha = this.formatDateEN();

      if (this.edit) {
        this.header.id = (this.festivalId ? this.festivalId : this.$route.params.id);
        this.fetchFestivalHeader();
      }
    },
    methods: {
      fetchFestivalHeader () {
        this.showPreloader();
        let uri = '/www/festivales/' + this.header.id;
        this.axios.get(uri).then((response) => {
            var stringified = JSON.stringify(response.data);
            var header = JSON.parse(stringified);

            this.header.fecha = header.fecha;
            this.header.hora = header.hora;
            this.header.fronton_id = header.fronton_id;
            this.header.television = header.television;
            this.header.television_txt = header.television_txt;
            this.header.estado_id = header.estado_id;

            this.provincia_id = header.provincia_id;
            this.municipio_id = header.municipio_id;

            this.hidePreloader();
        });
      },
      onChangeDate () {
        switch (new Date(this.header.fecha).getDay()) {
          case 0:
            this.dia = "Domingo";
            break;
          case 1:
            this.dia = "Lunes";
            break;
          case 2:
            this.dia = "Martes";
            break;
          case 3:
            this.dia = "Miércoles";
            break;
          case 4:
            this.dia = "Jueves";
            break;
          case 5:
            this.dia = "Viernes";
            break;
          case 6:
            this.dia = "Sábado";
            break;
        }
      },
      editDate (edit) {
        this.editdate = edit;
        this.$emit('toggle-edit', this.header);
      },
      onSubmit (evt) {
        evt.preventDefault();

        this.header.municipio_id = this.municipio_id;
        this.header.provincia_id = this.provincia_id;

        let uri = '/www/festivales';

        if(this.edit || this.header.id) {
          this.axios.post(uri + '/' + this.header.id + '/update', this.header)
            .then((response) => {
              this.showSnackbar("Festival actualizado");
              if(this.editdate) {
                this.editDate(false);
              } else {
                this.goBack();
              }
            })
            .catch((error) => {
              console.log(error);
              this.showSnackbar("Se ha producido un ERROR");
            });
        } else {
          this.axios.post(uri, this.header)
            .then((response) => {
              this.header.id = response.data.id;

              this.editdate = false;
              this.showSnackbar("Festival creado");
              this.$emit('toggle-edit', this.header);
            })
            .catch((error) => {
              console.log(error);
              this.showSnackbar("Se ha producido un ERROR");
            });
        }
      },
      onReset (evt) {
        if(this.editdate)
          this.editDate(false)
        else
          this.goBack();
      }
    }
  }
</script>

<style>
  .header {
    background-color:white;
    border-bottom:10px solid slategray;
    margin-top:-1.45rem;
    padding-bottom:.25rem;
    padding-top:0;
  }
  .header .toolbar {
    background-color:slategray;
  }
  .header h4 {
    line-height:1.75;
  }
  .header label {
    font-weight:bold;
  }
  .form-group {
    margin-bottom:.5rem;
  }
  .form-control {
    padding: 0.075rem 0.75rem;
  }
  select.form-control:not([size]):not([multiple]) {
    height: calc(1.71rem + 2px);
  }
</style>
