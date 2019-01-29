<template>
  <div class="festival-contactos">
    <b-form @submit="onSubmit">
      <b-row>

        <div class="col-md-1">&nbsp;</div>

        <div class="col-md-10">
          <div class="card mb-3">
            <div class="card-body">
              <b-row>
                <h5 class="col-12">Contacto 1</h5>
              </b-row>
              <b-row>
                <b-form-group label="Nombre"
                              label-for="c1_nombre"
                              class="col-md-4">
                  <b-form-input id="c1_nombre"
                                type="text"
                                v-model="contactos.contact_01_name">
                  </b-form-input>
                </b-form-group>
                <b-form-group label="Descripción"
                              label-for="c1_descripcion"
                              class="col-md-8">
                  <b-form-input id="c1_descripcion"
                                type="text"
                                v-model="contactos.contact_01_desc">
                  </b-form-input>
                </b-form-group>
              </b-row>
              <b-row>
                <b-form-group label="Email 1"
                              label-for="c1_email1"
                              class="col-md-4">
                  <b-form-input id="c1_email1"
                                type="email"
                                maxlength="50"
                                v-model="contactos.contact_01_email_1">
                  </b-form-input>
                </b-form-group>
                <b-form-group label="Email 2"
                              label-for="c1_email2"
                              class="col-md-4">
                  <b-form-input id="c1_email2"
                                type="email"
                                maxlength="50"
                                v-model="contactos.contact_01_email_2">
                  </b-form-input>
                </b-form-group>
              </b-row>
              <b-row>
                <b-form-group label="Teléfono 1"
                              label-for="c1_phone1"
                              class="col-md-4">
                  <b-form-input id="c1_email1"
                                type="text"
                                maxlength="18"
                                v-model="contactos.contact_01_telephone_1">
                  </b-form-input>
                </b-form-group>
                <b-form-group label="Teléfono 2"
                              label-for="c1_phone2"
                              class="col-md-4">
                  <b-form-input id="c1_email2"
                                type="text"
                                maxlength="18"
                                v-model="contactos.contact_01_telephone_2">
                  </b-form-input>
                </b-form-group>
              </b-row>

            </div>
          </div>
        </div>

        <div class="col-md-1">&nbsp;</div>

      </b-row>

      <b-row>

        <div class="col-md-1">&nbsp;</div>

        <div class="col-md-10">
          <div class="card mb-3">
            <div class="card-body">
              <b-row>
                <h5 class="col-12">Contacto 2</h5>
              </b-row>

              <b-row>
                <b-form-group label="Nombre"
                              label-for="c1_nombre"
                              class="col-md-4">
                  <b-form-input id="c1_nombre"
                                type="text"
                                v-model="contactos.contact_02_name">
                  </b-form-input>
                </b-form-group>
                <b-form-group label="Descripción"
                              label-for="c1_descripcion"
                              class="col-md-8">
                  <b-form-input id="c1_descripcion"
                                type="text"
                                v-model="contactos.contact_02_desc">
                  </b-form-input>
                </b-form-group>
              </b-row>
              <b-row>
                <b-form-group label="Email 1"
                              label-for="c1_email1"
                              class="col-md-4">
                  <b-form-input id="c1_email1"
                                type="email"
                                maxlength="50"
                                v-model="contactos.contact_02_email_1">
                  </b-form-input>
                </b-form-group>
                <b-form-group label="Email 2"
                              label-for="c1_email2"
                              class="col-md-4">
                  <b-form-input id="c1_email2"
                                type="email"
                                maxlength="50"
                                v-model="contactos.contact_02_email_2">
                  </b-form-input>
                </b-form-group>
              </b-row>
              <b-row>
                <b-form-group label="Teléfono 1"
                              label-for="c1_phone1"
                              class="col-md-4">
                  <b-form-input id="c1_email1"
                                type="text"
                                maxlength="18"
                                v-model="contactos.contact_02_telephone_1">
                  </b-form-input>
                </b-form-group>
                <b-form-group label="Teléfono 2"
                              label-for="c1_phone2"
                              class="col-md-4">
                  <b-form-input id="c1_email2"
                                type="text"
                                maxlength="18"
                                v-model="contactos.contact_02_telephone_2">
                  </b-form-input>
                </b-form-group>
              </b-row>

            </div>
          </div>

        </div>

        <div class="col-md-1">&nbsp;</div>

      </b-row>

      <b-row>
        <div class="col-md-1">&nbsp;</div>
        <b-form-group label="Observaciones:"
                      label-for="observaciones"
                      class="col-md-10">
          <b-form-textarea id="observaciones"
                           :rows="3"
                           :max-rows="6"
                           v-model="contactos.observaciones">
          </b-form-textarea>
        </b-form-group>
        <div class="col-md-1">&nbsp;</div>
      </b-row>

      <b-row class="botonera">
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-10 text-right">
          <b-button variant="danger" @click="onSubmit" class="ml-1">Guardar</b-button>
        </div>
        <div class="col-md-1">&nbsp;</div>
      </b-row>

    </b-form>
  </div>
</template>

<script>
  import { store } from '../store/store';

  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [APIGetters, Utils],
    data () {
      return {
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
      }
    },
    created: function () {
      store.dispatch('loadContactos').then( response => {
        if( response[0] ) {
          this.contactos = response[0];
        }
      });
    },
    computed: {
      _contactos () {
        return store.getters.contactos;
      }
    },
    methods: {
      onSubmit() {
        store.dispatch('addContactos', this.contactos)
          .then((response) => {
            this.showSnackbar("Contactos GUARDADOS");
          })
          .catch((error) => {
            console.log(error);
            this.showSnackbar("Se ha producido un ERROR al guardar los CONTACTOS");
          });
      }
    }
  }
</script>

<style>
  .festival-contactos .card {
    border-color:#707f8f;
  }
  .festival-contactos label {
    font-weight:bold;
    margin-right:-15px;
  }
  .festival-contactos input {
    height:27px;
  }
  .festival-contactos input[type="text"],
  .festival-contactos input[type="number"] {
    padding:0 5px;
  }
  .festival-contactos div[role="radiogroup"] .custom-radio:first-child {
    margin-right:35px;
  }
  .festival-contactos .botonera {
    margin-left:0;
  }
</style>
