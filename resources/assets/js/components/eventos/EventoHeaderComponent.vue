<template>
  <div class="header">

      <b-form @submit="onSubmit" @reset="onReset" v-if="show">
        <div class="toolbar mb-0 py-1">
          <div class="container">
            <b-row>
              <div class="col-sm-4">
                &nbsp;
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
        <div class="container py-4">
          <b-row>
            <b-form-group label="Fecha:"
                          label-for="fechaInput"
                          class="col-sm-2">
              <b-form-input id="fechaInput"
                            class="d-inline-block px-1"
                            type="date"
                            v-model="_evento.fecha"
                            @change="onChangeDate()"
                            @input="onChangeDate()"
                            required>
              </b-form-input>
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
                            type="time"
                            required
                            v-model="_evento.hora">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Provincia:"
                          label-for="provinciaInput"
                          class="col-sm-3">
              <b-form-select id="provinciaInput"
                             :options="_provincias"
                             @change="onChangeProvincia"
                             v-model="_evento.provincia_id">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Municipio:"
                          label-for="municipioInput"
                          class="col-sm-3">
              <b-form-select id="municipioInput"
                             :options="_municipios"
                             @change="onChangeMunicipio"
                             v-model="_evento.municipio_id">
              </b-form-select>
            </b-form-group>
          </b-row>
          <b-row>
            <b-col class="col-sm-3">
              <b-form-group label="Motivo:"
                            label-for="motivoInput">
                <b-form-select id="motivoInput"
                               :options="_motivos"
                               required
                               v-model="_evento.motivo_id">
                </b-form-select>
              </b-form-group>
              <b-form-group label="Campeonato:"
                            label-for="campeonatoInput">
                <b-form-select id="campeonatoInput"
                               :options="_campeonatos"
                               v-model="_evento.campeonato_id">
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col class="col-sm-9">
              <b-form-group label="Descripción"
                            label-for="descInput">
                <b-form-textarea id="descInput"
                                 v-model="_evento.desc"
                                 :rows="4"
                                 :max-rows="4"
                                 style="max-height:95px">
                </b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>
        </div>
      </b-form>

  </div>
</template>

<script>
  import { store } from '../store/store';

  import Nav from '../utils/nav.js';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [Nav, Utils],
    props: ['formTitle'],
    data () {
      return {
        dia: '',
        show: true,
        destroy: true,
      }
    },
    created: function () {
      console.log("EventoHeaderComponent created");

      if (this._edit) {
        this._evento = this.$route.query.evento;
      } else {
        this._evento.fecha = this.formatDateEN();
        this._evento.hora = "";
        this._evento.provincia_id = null;
        this._evento.municipio_id = null;
        this._evento.motivo_id = null;
        this._evento.campeonato_id = null;
      }
    },
    beforeDestroy: function () {
      if( true === this.destroy ) {
        store.dispatch('resetEvento');
      }
    },
    computed: {
      _evento: {
        get() {
          return store.getters.evento;
        },
        set(evento) {
          store.commit('SET_EVENTO', evento);
        },
      },
      _motivos () {
        return store.getters.evento_motivos;
      },
      _provincias () {
        return store.getters.provincias;
      },
      _municipios () {
        return store.getters.municipios_filtered;
      },
      _campeonatos () {
        return store.getters.campeonatos;
      },
      _edit: {
        get() {
          return store.getters.edit_evento;
        },
        set(value) {
          store.commit('SET_EVENTO_EDIT', value);
        }
      },
    },
    methods: {
      dontDestroyComponent () {
        this.destroy = false;
      },
      onChangeDate () {
        switch (new Date(this._evento.fecha).getDay()) {
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
        store.dispatch('loadPelotaris', this._evento.fecha);
      },
      onChangeProvincia(evt) {
        store.dispatch('filterMunicipiosByProvincia', evt);
      },
      onChangeMunicipio(evt) {
        if (null !== evt) {
          this._evento.provincia_id = _.filter(this._municipios, { 'value': evt })[0].provincia_id;
          this.onChangeProvincia(this._evento.provincia_id);
        }
      },
      onSubmit (evt) {
        evt.preventDefault();

        let uri = '/www/eventos';

        if(this._edit || this._evento.id) {
          this.axios.post(uri + '/' + this._evento.id + '/update', this._evento)
            .then((response) => {
              this.showSnackbar("Evento actualizado");
              this.goBack();
            })
            .catch((error) => {
              console.log(error);
              this.showSnackbar("Se ha producido un ERROR");
            });
        } else {
          this.axios.post(uri, this._evento)
            .then((response) => {
              this._evento.id = response.data.id;
              this._edit = 1;
              this.showSnackbar("Evento creado");
            })
            .catch((error) => {
              console.log(error);
              this.showSnackbar("Se ha producido un ERROR");
            });
        }
      },
      onReset (evt) {
        evt.preventDefault();
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
