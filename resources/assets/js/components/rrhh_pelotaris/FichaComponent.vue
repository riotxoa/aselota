<template>
<div>
    <div class="main-header pelotaris">
      <div class="toolbar mb-0 py-1">
        <div class="container">
          <b-row>
            <div class="col-sm-3">&nbsp;</div>
            <h4 v-if="edit" class="col-sm-6 text-white font-weight-bold">EDITAR FICHA DEL PELOTARI</h4>
            <h4 v-if="!edit" class="col-sm-6 text-white font-weight-bold">NUEVA FICHA DE PELOTARI</h4>
            <div class="col-sm-3 text-right home-icon">
              <b-button v-if="this.$route.params.userRole === 'admin'" size="sm" variant="outline" href="/" class="mt-1"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Admin Menú</b-button>
            </div>
          </b-row>
        </div>
      </div>
    </div>

  <div style="min-height:625px;" class="container py-4">
    <b-row class="justify-content-center">
      <div class="col-6 col-md-2 col-lg-1 pr-0 mb-3 mb-md-0">
        <img :src="pelotari.image" class="img-responsive" style="width:100%;">
      </div>
      <div class="col-10 col-md-9 col-lg-10">
        <p class="d-inline-block text-secondary mb-1">{{ formTitle }}</p>
        <h1 class="form-title text-secondary"><strong>{{ this.pelotari.alias }}</strong> - <small class="text-capitalize">{{ this.pelotari.posicion }}</small></h1>
      </div>
      <div class="col-2 col-md-1 col-lg-1">
        <b-button class="d-inline-block float-right text-right" size="sm" variant="outline-secondary" alt="Borrar Pelotari" title="Borrar Pelotari" style="width:30px;" @click.stop="onClickDelete(pelotari.id, pelotari.alias)" v-if="edit"><span class="icon voyager-trash"></span></b-button>
      </div>
    </b-row>

    <b-form @submit="onSubmit" @reset="onReset" v-if="show">

      <b-card no-body class="my-3">
        <b-tabs card pills>

          <b-col cols="3" offset="9" class="position-absolute fixed-top">
            <b-row class="pt-3 mt-1">
              <label class="col-md-10 text-right">Promesa</label>
              <b-form-checkbox id="check_promesa"
                            class="col-md-2 text-right"
                            style="margin-right:0px !important;"
                            v-model="pelotari.promesa"
                            value="1"
                            unchecked-value="0" >
              </b-form-checkbox>
            </b-row>
          </b-col>

          <b-tab title="Ficha" active>
            <b-row>
              <div class="col-md-12">
                <b-row>
                  <b-form-group label="Nombre deportivo:"
                                label-for="alias"
                                class="col-sm-6 col-md-3">
                    <b-form-input id="alias"
                                  type="text"
                                  v-model="pelotari.alias"
                                  maxlength="30"
                                  required
                                  placeholder="Nombre deportivo">
                    </b-form-input>
                  </b-form-group>
                  <b-form-group label="Posicion:"
                                label-for="posicion"
                                class="col-sm-6 col-md-3">
                    <b-form-select id="posicion"
                                  :options="posiciones"
                                  required
                                  v-model="pelotari.posicion">
                    </b-form-select>
                  </b-form-group>
                  <b-form-group label="DNI:"
                                label-for="dniInput"
                                class="col-sm-6 col-md-3">
                    <b-form-input id="dniInput"
                                  type="text"
                                  v-model="pelotari.dni"
                                  maxlength="9"
                                  placeholder="DNI">
                    </b-form-input>
                  </b-form-group>
                  <b-form-group label="NºSeg.Social:"
                                label-for="ssInput"
                                class="col-sm-6 col-md-3">
                    <b-form-input id="ssInput"
                                  type="text"
                                  v-model="pelotari.num_ss"
                                  maxlength="12"
                                  placeholder="NºSeg.Social">
                    </b-form-input>
                  </b-form-group>
                </b-row>
                <b-row>
                  <b-form-group label="Nombre:"
                                label-for="nombre"
                                class="col-sm-6 col-md-4 col-lg-3">
                    <b-form-input id="nombre"
                                  type="text"
                                  v-model="pelotari.nombre"
                                  maxlength="30"
                                  placeholder="Nombre">
                    </b-form-input>
                  </b-form-group>
                  <b-form-group label="Apellidos:"
                                label-for="apellidos"
                                class="col-sm-6 col-md-4 col-lg-6">
                    <b-form-input id="apellidos"
                                  type="text"
                                  v-model="pelotari.apellidos"
                                  maxlength="60"
                                  placeholder="Apellidos">
                    </b-form-input>
                  </b-form-group>
                  <b-form-group label="Fecha nacimiento:"
                                label-for="fecha_nac"
                                class="col-sm-6 col-md-4 col-lg-3">
                    <b-form-input id="fecha_nac"
                                  type="date"
                                  v-model="pelotari.fecha_nac"
                                  placeholder="dd/mm/yyyy">
                    </b-form-input>
                  </b-form-group>
                </b-row>
                <b-row>
                  <b-form-group label="Dirección:"
                                label-for="direccion"
                                class="col-12">
                    <b-form-input id="direccion"
                                  type="text"
                                  v-model="pelotari.direccion"
                                  maxlength="100"
                                  placeholder="Dirección completa">
                    </b-form-input>
                  </b-form-group>
                  <b-form-group label="Provincia:"
                                label-for="provincia"
                                class="col-sm-6 col-md-4">
                    <b-form-select id="provincia"
                                  :options="provincias"
                                  @change = "onChangeProvincia"
                                  v-model="pelotari.provincia_id">
                    </b-form-select>
                  </b-form-group>
                  <b-form-group label="Municipio:"
                                label-for="municipio"
                                class="col-sm-6 col-md-4">
                    <b-form-select id="municipio"
                                  :options="municipios_filtered"
                                  v-model="pelotari.municipio_id">
                    </b-form-select>
                  </b-form-group>
                  <b-form-group label="Código Postal:"
                                label-for="cod_postal"
                                class="col-sm-6 col-md-4">
                    <b-form-input id="cod_postal"
                                  type="text"
                                  v-model="pelotari.cod_postal"
                                  maxlength="5"
                                  required
                                  placeholder="Código Postal">
                    </b-form-input>
                  </b-form-group>
                  <div class="w-100 d-md-none"></div>
                  <b-form-group label="Correo electrónico:"
                                label-for="email"
                                class="col-sm-6 col-lg-4 col-xl-3">
                    <b-form-input id="email"
                                  type="email"
                                  v-model="pelotari.email"
                                  maxlength="50"
                                  placeholder="example@example.com">
                    </b-form-input>
                  </b-form-group>
                  <div class="w-100 d-none d-sm-none d-lg-block d-xl-none"></div>
                  <b-form-group label="Teléfono Fijo:"
                                label-for="telefono"
                                class="col-sm-6 col-lg-4 col-xl-3">
                    <b-form-input id="telefono"
                                  type="text"
                                  v-model="pelotari.telefono"
                                  maxlength="15"
                                  placeholder="Teléfono">
                    </b-form-input>
                  </b-form-group>
                  <b-form-group label="Teléfono Móvil:"
                                label-for="telefono_mov"
                                class="col-sm-6 col-lg-4 col-xl-3">
                    <b-form-input id="telefono_mov"
                                  type="text"
                                  v-model="pelotari.telefono_2"
                                  maxlength="15"
                                  placeholder="Teléfono Móvil">
                    </b-form-input>
                  </b-form-group>
                  <b-form-group label="Teléfono Alternativo:"
                                label-for="telefono_alt"
                                class="col-sm-6 col-lg-4 col-xl-3">
                    <b-form-input id="telefono_alt"
                                  type="text"
                                  v-model="pelotari.telefono_3"
                                  maxlength="15"
                                  placeholder="Teléfono Alternativo">
                    </b-form-input>
                  </b-form-group>
                </b-row>
                <b-row>
                  <b-form-group label="NºHijos:"
                                label-for="num_hijos"
                                class="col-sm-3 col-lg-4 col-xl-3">
                    <b-form-input id="num_hijos"
                                  type="number"
                                  v-model="pelotari.num_hijos"
                                  maxlength="1"
                                  placeholder="NºHijos">
                    </b-form-input>
                  </b-form-group>
                  <b-form-group label="Cód.IBAN:"
                                label-for="iban"
                                class="col-sm-9 col-lg-8 col-xl-9">
                    <b-form-input id="iban"
                                  type="text"
                                  v-model="pelotari.iban"
                                  maxlength="28"
                                  placeholder="Cód.IBAN">
                    </b-form-input>
                  </b-form-group>
                </b-row>
                <b-row>
                  <b-form-group label="Fotografía"
                                class="col-sm-12">
                    <b-row>
                      <b-form-file class="mt-0 col-sm-10"
                                   v-on:change="onPhotoChange"
                                   accept=".jpg"
                                   plain>
                      </b-form-file>
                      <div class="col-sm-2" v-if="!edit">
                        <b-button type="reset" variant="danger" class="float-right mr-1" size="sm">Reset</b-button>
                      </div>
                    </b-row>
                  </b-form-group>
                </b-row>
              </div>
            </b-row>
          </b-tab>
          <b-tab v-if="edit" title="Contratos">
            <listado-contratos :pelotari-id="this.pelotari.id" :pelotari-alias="this.pelotari.alias"></listado-contratos>
          </b-tab>
          <b-tab v-if="edit" title="Campeonatos">
            <listado-campeonatos :pelotari-id="this.pelotari.id" :pelotari-alias="this.pelotari.alias"></listado-campeonatos>
          </b-tab>
        </b-tabs>
      </b-card>

      <b-button type="submit" variant="primary">Guardar</b-button>
      <b-button variant="default" @click="onCancel">Cancelar</b-button>

    </b-form>

    <b-modal ref="modalDelete" title="BORRAR Pelotari" hide-footer>
      <div class="modal-body">
        <p>Se va a borrar la ficha completa de <strong id="deleteAlias"></strong>. ¿Desea continuar?</p>
      </div>
      <div class="modal-footer">
        <b-btn variant="danger" @click="remove">Borrar</b-btn>
        <b-btn @click="hideModalDelete">Cancelar</b-btn>
      </div>
    </b-modal>
  </div>
</div>
</template>

<script>
  Vue.component('listado-contratos', require('../rrhh_contratos/ListadoComponent.vue'));
  Vue.component('listado-campeonatos', require('../rrhh_contratos_campeonatos/ListadoComponent.vue'));
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
    props: ['formTitle'],
    data () {
      return {
        pelotari: {
          id: null,
          dni: '',
          alias: '',
          posicion: null,
          nombre: '',
          apellidos: '',
          direccion: '',
          provincia_id: null,
          municipio_id: null,
          cod_postal: '',
          email: '',
          telefono: '',
          fotoName: null,
          file: null,
          image: '/storage/avatars/default/default.jpg',
          num_ss: '',
          fecha_nac: null,
          telefono_2: '',
          telefono_3: '',
          iban: '',
          num_hijos: null,
          promesa: 0,
          created: null,
          updated: null,
        },
        posiciones: [
          { value: null, text: 'Seleccionar posición' },
          { value: 'Delantero', text: 'Delantero '},
          { value: 'Zaguero', text: 'Zaguero' },
        ],
        provincias: [],
        municipios: [],
        municipios_filtered: [],
        edit: false,
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
      this.fetchMunicipios();
      this.fetchProvincias();

      if(this.$route.params.id) {
        this.pelotari.id = this.$route.params.id;
        this.edit = true;
        this.fetchPelotari(this.$route.params.id);
      } else {
        this.edit = false;
        this.show = true;
      }
    },
    methods: {
      fetchMunicipios() {
        let uri = '/www/municipios';
        this.axios.get(uri).then((response) => {
            var stringified = JSON.stringify(response.data).replace(/id/g, "value").replace(/name/g, "text");
            this.municipios = JSON.parse(stringified);
            this.municipios.unshift({ value: null, text: "Seleccionar municipio" });
            this.municipios_filtered = this.municipios;
        });
      },
      fetchProvincias() {
        let uri = '/www/provincias';
        this.axios.get(uri).then((response) => {
            var stringified = JSON.stringify(response.data).replace(/id/g, "value").replace(/name/g, "text");
            this.provincias = JSON.parse(stringified);
            this.provincias.unshift({ value: null, text: "Seleccionar provincia" });
        });
      },
      fetchPelotari(id) {
        let uri = '/www/pelotaris/' + id;
        this.axios.get(uri).then((response) => {
            var stringified = JSON.stringify(response.data);
            var pelotari = JSON.parse(stringified);

            this.pelotari.id = id;
            this.pelotari.dni = pelotari.DNI;
            this.pelotari.alias = pelotari.alias;
            this.pelotari.posicion = pelotari.posicion;
            this.pelotari.nombre = pelotari.nombre;
            this.pelotari.apellidos = pelotari.apellidos;
            this.pelotari.direccion = pelotari.direccion;
            this.pelotari.provincia_id = pelotari.provincia_id;
            this.pelotari.municipio_id = pelotari.municipio_id;
            this.pelotari.cod_postal = pelotari.cod_postal;
            this.pelotari.email = pelotari.email;
            this.pelotari.telefono = pelotari.telefono;
            this.pelotari.fotoName = pelotari.foto;
            this.pelotari.num_ss = pelotari.num_ss;
            this.pelotari.fecha_nac = pelotari.fecha_nac;
            this.pelotari.telefono_2 = pelotari.telefono_2;
            this.pelotari.telefono_3 = pelotari.telefono_3;
            this.pelotari.iban = pelotari.iban;
            this.pelotari.num_hijos = pelotari.num_hijos;
            this.pelotari.promesa = pelotari.promesa;
            this.pelotari.created = pelotari.created_at;
            this.pelotari.updated = pelotari.updated_at;

            this.pelotari.image = (pelotari.foto ? pelotari.foto : this.pelotari.image);

            this.show = true;
        });
      },
      onChangeProvincia (evt) {
        if (null === evt) {
          this.municipios_filtered = this.municipios;
        } else {
          this.municipios_filtered = _.filter(this.municipios, { 'provincia_value': evt });
          this.municipios_filtered.unshift({ value: null, text: "Seleccionar municipio" });
        }
      },
      onPhotoChange(e) {
          let files = e.target.files || e.dataTransfer.files;
          if (!files.length)
              return;
          this.createImage(files[0]);
      },
      createImage(file) {
          let reader = new FileReader();
          let vm = this;
          reader.onload = (e) => {
            vm.pelotari.image = e.target.result;
            vm.pelotari.file = file;
            vm.pelotari.fotoName = file.name;
          };
          reader.readAsDataURL(file);
      },
      onSubmit (evt) {
        evt.preventDefault();

        const config = { headers: { 'Content-Type': 'multipart/form-data' } };

        let uri = '/www/pelotaris';
        let data = new FormData();

        data.append('form', JSON.stringify(this.pelotari));
        if(this.pelotari.file)
          data.append('photo', this.pelotari.file);

        if(this.edit) {
          this.axios.post(uri + '/' + this.pelotari.id + '/update', data, config)
            .then((response) => {
              showSnackbar("Pelotari actualizado");
              this.goBack();
            })
            .catch((error) => {
              console.log(error);
              showSnackbar("Se ha producido un ERROR");
            });
        } else {
          this.axios.post(uri, data, config)
            .then((response) => {
              showSnackbar("Pelotari creado");
              this.pelotari.id = response.data.id;
              this.edit = true;
              // this.goBack();
            })
            .catch((error) => {
              console.log(error);
              showSnackbar("Se ha producido un ERROR");
            });
        }
      },
      onReset (evt) {
        evt.preventDefault();
        /* Reset our form values */
        this.pelotari.dni = '';
        this.pelotari.alias = '';
        this.pelotari.posicion = null;
        this.pelotari.nombre = '';
        this.pelotari.apellidos = '';
        this.pelotari.direccion = '';
        this.pelotari.provincia_id = null;
        this.pelotari.municipio_id = null;
        this.pelotari.cod_postal = '';
        this.pelotari.email = '';
        this.pelotari.telefono = '';
        this.pelotari.num_ss = '';
        this.pelotari.fecha_nac = null;
        this.pelotari.telefono_2 = '';
        this.pelotari.telefono_3 = '';
        this.pelotari.iban = '';
        this.pelotari.num_hijos = null;
        this.pelotari.promesa = 0;
        /* Trick to reset/clear native browser form validation state */
        this.show = false;
        this.$nextTick(() => { this.show = true });
      },
      onCancel (evt) {
        this.goBack();
      },
      remove () {
        let uri = '/www/pelotaris/' + this.pelotari.id;
        this.axios.delete(uri)
          .then((response) => {
            this.$refs.modalDelete.hide();
            showSnackbar("Pelotari BORRADO");
            this.goBack();
          })
          .catch((error) => {
            console.log("[remove] error: " + error);
            this.$refs.modalDelete.hide();
            showSnackbar("ERROR al borrar");
          });
      },
      onClickDelete (id, name) {
        jQuery('#deleteAlias').html(name);
        this.$refs.modalDelete.show();
      },
      hideModalDelete() {
        this.$refs.modalDelete.hide();
      }
    }
  }
</script>

<style>
  .nav-pills .nav-link {
    background-color:#dddddd;
    color:gray;
    margin-right:.25rem;

    -webkit-transition:.15s all ease-in-out;
    -moz-transition:.15s all ease-in-out;
    -ms-transition:.15s all ease-in-out;
    -o-transition:.15s all ease-in-out;
    transition:.15s all ease-in-out;
  }
  .nav-pills .nav-link.active,
  .nav-pills .show > .nav-link {
    background-color:#28a745;
    font-weight:bold;
  }
  .nav-pills .nav-link:hover,
  .nav-pills .nav-link:active,
  .nav-pills .nav-link:focus {
    -webkit-filter:opacity(.85);
    filter:opacity(.85);
  }
  form label,
  form legend {
    font-weight:bold;
  }
</style>
