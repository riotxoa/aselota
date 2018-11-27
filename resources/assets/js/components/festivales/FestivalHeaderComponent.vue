<template>
  <div class="header">

      <b-form @submit="onSubmit" @reset="onReset" v-if="show">
        <div class="toolbar mb-0 py-1">
          <div class="container">
            <b-row>
              <div class="col-sm-4">
                <router-link v-if="1 == isGerente" to="/gerente/calendario" class="text-white"><b-btn variant="outline-link" class="mb-0" title="Calendario de Pelotaris" @click="dontDestroyComponent"><div class="icon voyager-calendar"></div></b-btn></router-link>
              </div>
              <div class="col-sm-4">
                <h4 class="text-white text-uppercase text-center font-weight-bold m-0">{{ this.formTitle }}</h4>
              </div>
              <div class="col-sm-4 text-right">
                <b-button type="submit" variant="danger">Guardar</b-button>
                <b-button type="reset" variant="default">Volver</b-button>
              </div>
            </b-row>
          </div>
        </div>
        <div class="toolbar lightgray py-1 mb-2">
          <div class="container">
            <b-row>
              <div style="margin:0 auto;">
                <label for="fechaPresuInput">Fecha Presupuesto:</label>
                <b-form-input id="fechaPresuInput"
                              class="d-inline-block px-1"
                              style="min-width:127px;max-width:145px;"
                              :readonly="!editdatepresu || editdate && _edit"
                              :disabled="!isGerente"
                              type="date"
                              v-model="_header.fecha_presu"
                              required>
                </b-form-input>
                <b-btn v-if="_edit && isGerente" variant="secondary" size="sm" class="d-inline-block" style="margin-top:-2px;" @click="editDatePresu(true)">
                  <i class="voyager-pen"></i>
                </b-btn>
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
                            :readonly="(!editdate && _edit) || editdatepresu"
                            :disabled="!isGerente"
                            type="date"
                            v-model="_header.fecha"
                            @change="onChangeDate()"
                            @input="onChangeDate()"
                            required>
              </b-form-input>
              <b-link v-if="_edit && isGerente" class="pl-1 d-inline-block" @click="editDate(true)">
                <i class="voyager-pen"></i>
              </b-link>
            </b-form-group>
            <b-form-group label="Día:"
                          label-for="diaInput"
                          class="col-sm-2">
              <b-form-input id="diaInput"
                            type="text"
                            v-model="dia"
                            readonly
                            :disabled="!isGerente">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Hora:"
                          label-for="hourInput"
                          class="col-sm-2">
              <b-form-input id="hourInput"
                            :readonly="editdate || editdatepresu"
                            :disabled="!isGerente"
                            type="time"
                            required
                            v-model="_header.hora">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Provincia:"
                          label-for="provinciaInput"
                          class="col-sm-2">
              <b-form-select id="provinciaInput"
                             :readonly="editdate || editdatepresu"
                             :disabled="!isGerente"
                             :options="provincias"
                             @change="onChangeProvincia"
                             v-model="_header.provincia_id">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Municipio:"
                          label-for="municipioInput"
                          class="col-sm-3">
              <b-form-select id="municipioInput"
                             :readonly="editdate || editdatepresu"
                             :disabled="!isGerente"
                             :options="municipios_filtered"
                             @change="onChangeMunicipio"
                             v-model="_header.municipio_id">
              </b-form-select>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Frontón:"
                          label-for="frontonInput"
                          class="col-sm-3">
              <b-form-select id="frontonInput"
                             :readonly="editdate || editdatepresu"
                             :disabled="!isGerente"
                             :options="frontones_filtered"
                             @change="onChangeFronton"
                             required
                             v-model="_header.fronton_id">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Televisión:"
                          label-for="televisionInput"
                          class="col-sm-2">
              <b-form-select id="televisionInput"
                            :readonly="editdate || editdatepresu"
                            :disabled="!isGerente"
                            :options="television"
                            required
                            v-model="_header.television">
              </b-form-select>
            </b-form-group>
            <b-form-group label=" "
                          label-for="televisionTxtInput"
                          class="col-sm-4 mt-1">
              <b-form-input id="televisionTxtInput"
                            :readonly="editdate || editdatepresu"
                            :disabled="!isGerente"
                            v-model="_header.television_txt">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Estado festival:"
                          label-for="estadoInput"
                          class="col-sm-3">
              <b-form-select id="estadoInput"
                             :readonly="editdate || editdatepresu"
                             :options="festivalEstados"
                             required
                             v-model="_header.estado_id">
              </b-form-select>
            </b-form-group>
          </b-row>
        </div>
      </b-form>

  </div>
</template>

<script>
  import { store } from '../store/store';

  import APIGetters from '../utils/getters.js';
  import Nav from '../utils/nav.js';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [APIGetters, Nav, Utils],
    props: ['formTitle', 'isGerente'],
    data () {
      return {
        dia: '',
        television: [
          { value: 0, text: "No" },
          { value: 1, text: "Sí" },
        ],
        editdate: false,
        editdatepresu: false,
        show: true,
        destroy: true,
      }
    },
    created: function () {
      console.log("FestivalHeaderComponent created");
      this.getMunicipios();
      this.getProvincias();
      this.getFrontones();
      this.getFestivalEstados();

      if (this._edit) {
        store.dispatch('loadHeader', (this._header.id ? this._header.id : this.$route.params.id), this.isGerente);
      } else {
        this._header.fecha = this.formatDateEN();
        this._header.hora = "";
        this._header.provincia_id = null;
        this._header.municipio_id = null;
        this._header.fronton_id = null;
        this._header.television = 0;
        this._header.television_txt = "";
        this._header.estado_id = 1;
        this._header.fecha_presu = this.formatDateEN();
      }
    },
    beforeDestroy: function () {
      if( true === this.destroy ) {
        store.dispatch('clearHeader');
        store.dispatch('clearPartidos');
      }
    },
    computed: {
      _header () {
        return store.getters.header;
      },
      _edit () {
        return store.getters.edit;
      },
      _costes () {
        return store.getters.costes;
      },
      _facturacion() {
        return store.getters.facturacion;
      },
    },
    methods: {
      dontDestroyComponent () {
        this.destroy = false;
      },
      onChangeDate () {
        switch (new Date(this._header.fecha).getDay()) {
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
        store.commit('SET_EDIT', !this._edit);
      },
      editDatePresu (edit) {
        this.editdatepresu = edit;
        store.commit('SET_EDIT', !this._edit);
      },
      onSubmit (evt) {
        evt.preventDefault();

        this._header.municipio_id = this.municipio_id;
        this._header.provincia_id = this.provincia_id;

        let uri = '/www/festivales';

        if(this.edit || this._header.id) {
          var data = {
            header: this._header,
            costes: this._costes,
            facturacion: this._facturacion,
          }

          this.axios.post(uri + '/' + this._header.id + '/update', data)
            .then((response) => {
              this.showSnackbar("Festival actualizado");
              if(this.editdate) {
                this.editDate(false);
              } else if(this.editdatepresu) {
                this.editDatePresu(false);
              } else {
                if(this.isGerente)
                  this.goBack();
              }
            })
            .catch((error) => {
              console.log(error);
              this.showSnackbar("Se ha producido un ERROR");
            });
        } else {
          this.axios.post(uri, this._header)
            .then((response) => {
              this._header.id = response.data.id;
              store.commit('SET_EDIT', 1);
              this.editdate = false;
              this.editdatepresu = false;
              this.showSnackbar("Festival creado");
            })
            .catch((error) => {
              console.log(error);
              this.showSnackbar("Se ha producido un ERROR");
            });
        }
      },
      onReset (evt) {
        evt.preventDefault();

        if(this.editdate)
          this.editDate(false);
        else if(this.editdatepresu)
          this.editDatePresu(false);
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
  .header .toolbar.lightgray {
    background-color:lightgray;
  }
  .header h4 {
    line-height:1.75;
  }
  .header label {
    font-weight:bold;
  }
  .header .form-group {
    margin-bottom:.5rem;
  }
  .header .form-control {
    padding: 0.075rem 0.75rem;
  }
  .header select.form-control:not([size]):not([multiple]) {
    height: calc(1.71rem + 2px);
  }
</style>
