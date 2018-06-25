<template>
  <div>
    <h1 class="form-title">{{ formTitle }}</h1>
    <hr/>
    <b-form @submit="onSubmit" @reset="onReset" v-if="show">
      <b-row>
        <div class="col-md-2">
          <img :src="image" class="img-responsive" style="width:100%;">
        </div>
        <div class="col-md-10">
          <div class="row">
            <b-form-group label="DNI:"
                          label-for="dniInput"
                          class="col-sm-4">
              <b-form-input id="dniInput"
                            type="text"
                            v-model="form.dni"
                            maxlength="9"
                            placeholder="DNI">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Nombre deportivo:"
                          label-for="alias"
                          class="col-sm-4">
              <b-form-input id="alias"
                            type="text"
                            v-model="form.alias"
                            maxlength="30"
                            required
                            placeholder="Nombre deportivo">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Posicion:"
                          label-for="posicion"
                          class="col-sm-4">
              <b-form-select id="posicion"
                            :options="posiciones"
                            required
                            v-model="form.posicion">
              </b-form-select>
            </b-form-group>
          </div>
          <div class="row">
            <b-form-group label="Nombre:"
                          label-for="nombre"
                          class="col-sm-6 col-md-4">
              <b-form-input id="nombre"
                            type="text"
                            v-model="form.nombre"
                            maxlength="30"
                            placeholder="Nombre">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Apellidos:"
                          label-for="apellidos"
                          class="col-sm-6 col-md-8">
              <b-form-input id="apellidos"
                            type="text"
                            v-model="form.apellidos"
                            maxlength="60"
                            placeholder="Apellidos">
              </b-form-input>
            </b-form-group>
          </div>
          <div class="row">
            <b-form-group label="Dirección:"
                          label-for="direccion"
                          class="col-12">
              <b-form-input id="direccion"
                            type="text"
                            v-model="form.direccion"
                            maxlength="100"
                            placeholder="Dirección completa">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Provincia:"
                          label-for="provincia"
                          class="col-md-4">
              <b-form-select id="provincia"
                            :options="provincias"
                            @change = "onChangeProvincia"
                            v-model="form.provincia_id">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Municipio:"
                          label-for="municipio"
                          class="col-md-4">
              <b-form-select id="municipio"
                            :options="municipios_filtered"
                            v-model="form.municipio_id">
              </b-form-select>
            </b-form-group>
            <b-form-group label="Código Postal:"
                          label-for="cod_postal"
                          class="col-md-4">
              <b-form-input id="cod_postal"
                            type="text"
                            v-model="form.cod_postal"
                            maxlength="5"
                            required
                            placeholder="Código Postal">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Correo electrónico:"
                          label-for="email"
                          class="col-md-6">
              <b-form-input id="email"
                            type="email"
                            v-model="form.email"
                            maxlength="50"
                            placeholder="example@example.com">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Teléfono:"
                          label-for="telefono"
                          class="col-md-6">
              <b-form-input id="telefono"
                            type="text"
                            v-model="form.telefono"
                            maxlength="15"
                            placeholder="Teléfono">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Fotografía"
                          class="col-sm-12">
              <b-form-file class="mt-3"
                           v-on:change="onPhotoChange"
                           accept=".jpg"
                           plain>
              </b-form-file>
            </b-form-group>
          </div>
          <hr/>
          <b-button type="submit" variant="primary">Guardar</b-button>
          <b-button variant="default" @click="onCancel">Cancelar</b-button>
          <b-button type="reset" variant="danger" class="float-right">Reset</b-button>
        </div>
      </b-row>
    </b-form>
  </div>
</template>

<script>
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
        form: {
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
        },
        file: null,
        image: '',
        posiciones: [
          { value: null, text: 'Seleccionar posición' },
          { value: 'Delantero', text: 'Delantero '},
          { value: 'Zaguero', text: 'Zaguero' },
        ],
        provincias: [],
        municipios: [],
        municipios_filtered: [],
        edit: false,
        show: true,
        goBack: () => {
          window.history.length > 1
            ? this.$router.go(-1)
            : this.$router.push('/')
        }
      }
    },
    created: function() {
      this.fetchMunicipios();
      this.fetchProvincias();

      if(this.$route.params.id) {
        this.edit = true;
        this.fetchPelotari(this.$route.params.id);
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

            this.form.id = id;
            this.form.dni = pelotari.DNI;
            this.form.alias = pelotari.alias;
            this.form.posicion = pelotari.posicion;
            this.form.nombre = pelotari.nombre;
            this.form.apellidos = pelotari.apellidos;
            this.form.direccion = pelotari.direccion;
            this.form.provincia_id = pelotari.provincia_id;
            this.form.municipio_id = pelotari.municipio_id;
            this.form.cod_postal = pelotari.cod_postal;
            this.form.email = pelotari.email;
            this.form.telefono = pelotari.telefono;
            this.form.fotoName = pelotari.foto;
            this.image = pelotari.foto;
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
            vm.image = e.target.result;
            this.file = file;
            this.form.fotoName = file.name;
          };
          reader.readAsDataURL(file);
      },
      onSubmit (evt) {
        evt.preventDefault();

        const config = { headers: { 'Content-Type': 'multipart/form-data' } };

        let uri = '/www/pelotaris';
        let data = new FormData();

        data.append('form', JSON.stringify(this.form));
        if(this.file)
          data.append('photo', this.file);

        if(this.edit) {
          this.axios.post(uri + '/' + this.form.id + '/update', data, config)
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
              this.goBack();
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
        this.form.dni = '';
        this.form.alias = '';
        this.form.posicion = null;
        this.form.nombre = '';
        this.form.apellidos = '';
        this.form.direccion = '';
        this.form.provincia_id = null;
        this.form.municipio_id = null;
        this.form.cod_postal = '';
        this.form.email = '';
        this.form.telefono = '';
        /* Trick to reset/clear native browser form validation state */
        this.show = false;
        this.$nextTick(() => { this.show = true });
      },
      onCancel (evt) {
        this.goBack();
      }
    }
  }
</script>
