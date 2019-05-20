<template>
  <div id="preloader" class="entreno-wrap">
    <b-form @submit="onSubmit" @reset="onReset" v-if="show">
      <div class="header">
        <div class="toolbar mb-2 py-1">
          <div class="container">
            <b-row>
              <div class="col-sm-3"></div>
              <div class="col-sm-6">
                <h4 class="text-white text-uppercase text-center font-weight-bold m-0">{{ this.formTitle }}</h4>
              </div>
              <div class="col-sm-3 text-right p-0">
                <b-button type="submit" variant="danger">Guardar</b-button>
                <b-button type="reset" variant="default">Volver</b-button>
              </div>
            </b-row>
          </div>
        </div>
        <div class="container">

          <b-row>
            <b-form-group label="Entrenamiento Promesas"
                          label-for="check_promesa"
                          class="col-sm-3 pl-0 pr-2">
                  <b-form-checkbox id="check_promesa"
                                class="col-md-1 text-right"
                                style="margin-right:0px !important;"
                                v-model="entreno.promesa"
                                value="1"
                                unchecked-value="0" 
                                @change="onChangePromesa">
                  </b-form-checkbox>
            </b-form-group>
            <b-form-group label="Fecha:"
                          label-for="fechaInput"
                          class="col-sm-2 pl-0 pr-2">
              <b-form-input id="fechaInput"
                            class="d-inline-block px-1"
                            type="date"
                            v-model="entreno.fecha"
                            required>
              </b-form-input>
            </b-form-group>
            <b-form-group label="Hora:"
                          label-for="hourInput"
                          class="col-sm-1 pl-0 pr-2">
              <b-form-input id="hourInput"
                            type="time"
                            required
                            v-model="entreno.hora">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Provincia:"
                          label-for="provinciaInput"
                          class="col-sm-3 pl-0 pr-2">
              <b-form-select id="provinciaInput"
                             :options="_provincias"
                             @change="onChangeProvincia"
                             v-model="entreno.provincia_id"
                             required>
              </b-form-select>
            </b-form-group>
            <b-form-group label="Municipio:"
                          label-for="municipioInput"
                          class="col-sm-3 pl-0 pr-2">
              <b-form-select id="municipioInput"
                             :options="_municipios_filtered"
                             @change="onChangeMunicipio"
                             v-model="entreno.municipio_id"
                             required>
              </b-form-select>
            </b-form-group>
          </b-row>
          <b-row>
            <b-form-group label="Pelotari:"
                          label-for="pelotariInput"
                          class="col-sm-3 pl-0 pr-2">
              <b-form-select id="pelotariInput"
                             :options="_pelotaris"
                             @change="onChangePelotari"
                             v-model="entreno.pelotari_id"
                             required>
              </b-form-select>
            </b-form-group>
            <b-form-group label="Contenido:"
                          label-for="contenidoInput"
                          class="col-sm-3 pl-0 pr-2">
              <b-form-select id="contenidoInput"
                             :options="_entr_contenidos"
                             v-model="entreno.contenido_id">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Frontón:"
                          label-for="frontonInput"
                          class="col-sm-3 pl-0 pr-2">
              <b-form-select id="frontonInput"
                             :options="_entr_frontones"
                             v-model="entreno.fronton_id">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Asistencia:"
                          label-for="asistenciaInput"
                          class="col-sm-2 pl-0 pr-2">
              <b-form-select id="asistenciaInput"
                             :options="asistencia"
                             v-model="entreno.asistencia">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Duración:"
                          label-for="duracionInput"
                          class="col-sm-1 px-0 pr-2">
              <b-form-input id="duracionInput"
                            v-model="entreno.duracion">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row>
            <div class="col-sm-2 pl-0 pt-2 pr-4 mt-3">
              <b-img :src="argazkia" thumbnail fluid alt="Responsive image" />
            </div>
            <div class="col-sm-10 pl-0">
              <b-row>
                <b-form-group label="Pre-Entrenamiento:"
                              label-for="preEntrInput"
                              class="col-12 px-0 pl-2">
                  <b-form-input id="preEntrInput"
                                v-model="entreno.pre_entreno">
                  </b-form-input>
                </b-form-group>
              </b-row>
              <b-row>
                <b-form-group label="Contenido:"
                              label-for="contEntrInput"
                              class="col-12 px-0 pl-2">
                  <b-form-input id="contEntrInput"
                                v-model="entreno.contenido">
                  </b-form-input>
                </b-form-group>
              </b-row>
              <b-row>
                <b-form-group label="Post-Entrenamiento:"
                              label-for="postEntrInput"
                              class="col-12 px-0 pl-2">
                  <b-form-input id="postEntrInput"
                                v-model="entreno.post_entreno">
                  </b-form-input>
                </b-form-group>
              </b-row>
            </div>
          </b-row>
        </div>
      </div>
      <div class="container mt-5">
        <b-row>
          <div class="col-1 p-0 text-center actitud">
            <div class="icon voyager-smile"></div>
          </div>
          <b-form-group label="Actitud:"
                        label-for="actitudInput"
                        class="col-11 col-sm-3 pl-2 pr-4 font-weight-bold">
            <b-form-select id="actitudInput"
                           :options="_entr_actitudes"
                           v-model="entreno.actitud_id">
            </b-form-select>
          </b-form-group>
          <div class="col-1 p-0 text-center aprovechamiento">
            <div class="icon voyager-star-half"></div>
          </div>
          <b-form-group label="Aprovechamiento:"
                        label-for="aprovechamientoInput"
                        class="col-11 col-sm-3 pl-2 pr-4 font-weight-bold">
            <b-form-select id="aprovechamientoInput"
                           :options="_entr_aprovechamientos"
                           v-model="entreno.aprovechamiento_id">
            </b-form-select>
          </b-form-group>
          <div class="col-1 p-0 text-center evolucion">
            <div class="icon voyager-bar-chart"></div>
          </div>
          <b-form-group label="Evolución:"
                        label-for="evolucionInput"
                        class="col-11 col-sm-3 pl-2 pr-4 font-weight-bold">
            <b-form-select id="evolucionInput"
                           :options="_entr_evoluciones"
                           v-model="entreno.evolucion_id">
            </b-form-select>
          </b-form-group>
        </b-row>
        <b-row class="mt-3">
          <b-form-group label="Comentarios:"
                        label-for="obsEntrInput"
                        class="col-12 px-0 pl-2 font-weight-bold">
            <b-form-textarea id="obsEntrInput"
                             v-model="entreno.comentarios"
                             :rows="4"
                             :max-rows="8">
            </b-form-textarea>
          </b-form-group>
        </b-row>
      </div>
    </b-form>
  </div>
</template>

<script>
  import { store } from '../store/store';
  import { mapState } from 'vuex';
  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';

  const showSnackbar = (msg) => {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";
    x.innerHTML = msg;

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  }
  export default {
    props: ['formTitle', 'isNewEntreno'],
    mixins: [APIGetters, Utils],
    data () {
      return {
        entreno: {},
        asistencia: { 0: 'No', 1: 'Sí'},
        argazkia: '/storage/avatars/default/default.jpg',
        edit: true,
        show: false,
        goBack: () => {
          window.history.length > 1
            ? this.$router.go(-1)
            : this.$router.push('/')
        }
      }
    },
    created: function() {
      console.log("FichaComponent created");

      if( this.isNewEntreno ) {
        this.edit = false;

        var myDate = new Date();
        var month = ('0' + (myDate.getMonth() + 1)).slice(-2);
        var date = ('0' + myDate.getDate()).slice(-2);
        var year = myDate.getFullYear();
        var formattedDate = year + '-' + month + '-' + date;

        this.entreno = {
          id: null,
          pelotari_id: null,
          provincia_id: null,
          municipio_id: null,
          asistencia: 0,
          duracion: 0,
          fecha: formattedDate,
          hora: '12:00',
          contenido_id: null,
          fronton_id: null,
          pre_entreno: '',
          contenido: '',
          post_entreno: '',
          actitud_id: null,
          aprovechamiento_id: null,
          evolucion_id: null,
          comentarios: '',
        }
      } else {
        this.edit = true;
        this.entreno = this.$route.query.entrenamiento;
        this.argazkia = this.$route.query.entrenamiento.foto;

        if(this.entreno.promesa){//promesas
          store.dispatch('loadPelotarisPromesa');
        }else{
          store.dispatch('loadPelotarisProfesional');
        }
      }

      this.show = true;
    },
    computed: mapState({
      _pelotaris: 'pelotaris',
      _provincias: 'provincias',
      _municipios: 'municipios',
      _municipios_filtered: 'municipios_filtered',
      _entr_contenidos: 'entr_contenidos',
      _entr_frontones: 'entr_frontones',
      _entr_actitudes: 'entr_actitudes',
      _entr_aprovechamientos: 'entr_aprovechamientos',
      _entr_evoluciones: 'entr_evoluciones',
    }),
    methods: {
      onSubmit(evt) {
        evt.preventDefault();
        if( this.edit ) {
          store.dispatch('updateEntrenamiento', this.entreno)
            .then( (response) => {
              this.showSnackbar("Entrenamiento actualizado");
              this.goBack();
            })
            .catch( (error) => {
              console.log("[error] error: " + JSON.stringify(error));
              this.showSnackbar("ERROR al actualizar Entrenamiento");
            });
        } else {
          store.dispatch('addEntrenamiento', this.entreno)
            .then( (response) => {
              this.showSnackbar("Entrenamiento creado");
              this.goBack();
            })
            .catch( (error) => {
              console.log("[error] error: " + JSON.stringify(error));
              this.showSnackbar("ERROR al crear Entrenamiento");
            });
        }
      },
      onReset() {
        this.goBack();
      },
      onChangePelotari(evt) {
        var pelotari = _.filter(this._pelotaris, { 'value': evt });
        this.argazkia = pelotari[0].foto;
      },
      onChangeProvincia(evt) {
        store.dispatch('filterMunicipiosByProvincia', evt);
      },
      onChangeMunicipio(evt) {
        if (null !== evt) {
          if (null === this.entreno.provincia_id) {
            this.entreno.provincia_id = _.filter(this._municipios, { 'value': evt })[0].provincia_id;
            this.onChangeProvincia(this.entreno.provincia_id);
          }
        }
      },
      onChangePromesa(evt) {
        if (null !== evt) {
          if(evt==1){//promesas
            store.dispatch('loadPelotarisPromesa');
            this.entreno.pelotari_id = null;
            this.pelotari_id = null;
            this.argazkia = '/storage/avatars/default/default.jpg';
          }else{
            store.dispatch('loadPelotarisProfesional');
            this.entreno.pelotari_id = null;
            this.pelotari_id = null;
            this.argazkia = '/storage/avatars/default/default.jpg';
          }
        }
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
  .header .form-group {
    margin-bottom:.5rem;
  }
  .header .form-control {
    padding: 0.075rem 0.75rem;
  }
  .header select.form-control:not([size]):not([multiple]) {
    height: calc(1.71rem + 2px);
  }

  .entreno-wrap img {
    width:100%;
  }
  .entreno-wrap .actitud .icon {
    background-color:#de0000;
  }
  .entreno-wrap .aprovechamiento .icon {
    background-color:#00398e;
  }
  .entreno-wrap .evolucion .icon {
    background-color:#009673;
  }
  .entreno-wrap .icon {
    border-radius:15px;
    color:rgba(255,255,255,.95);
    font-size:3rem;
    padding-top:.5rem;
  }
</style>
