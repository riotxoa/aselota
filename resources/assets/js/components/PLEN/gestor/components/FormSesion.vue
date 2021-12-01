<template>
  <div>
    <b-form v-if="show">
      <b-row class="macrociclo-wrap">
        <b-col sm="2">
          <label class="font-weight-bold mb-1">Macrociclo:</label>
        </b-col>
        <b-col sm="4" lg="3" class="px-sm-0">
          <p class="border mb-1 px-2" style="letter-spacing:-0.5px">{{ macrociclo_dates }}</p>
        </b-col>
        <b-col sm="6" lg="7" class="pl-sm-1">
          <p class="border mb-1 px-2">{{ macrociclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row class="mesociclo-wrap">
        <b-col sm="2">
          <label class="font-weight-bold mb-1">Mesociclo:</label>
        </b-col>
        <b-col sm="4" lg="3" class="px-sm-0">
          <p class="border mb-1 px-2" style="letter-spacing:-0.5px">{{ mesociclo_dates }}</p>
        </b-col>
        <b-col sm="6" lg="7" class="pl-sm-1">
          <p class="border mb-1 px-2">{{ mesociclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row class="border-bottom mb-3 microciclo-wrap">
        <b-col sm="2">
          <label class="font-weight-bold">Microciclo:</label>
        </b-col>
        <b-col sm="4" lg="3" class="px-sm-0">
          <p class="border mb-1 px-2" style="letter-spacing:-0.5px">{{ microciclo_dates }}</p>
        </b-col>
        <b-col sm="6" lg="7" class="pl-sm-1">
          <p class="border px-2">{{ microciclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="3" sm="2">
          <label for="fecha" class="font-weight-bold">Fecha:</label>
        </b-col>
        <b-col cols="9" sm="4" md="3" class="px-sm-0">
          <b-form-input
            id="fecha"
            v-model="sesion.fecha"
            v-on:input="onInputFecha"
            type="date"
            :min="microciclo.fecha_ini"
            :max="microciclo.fecha_fin"
            size="sm"
            :disabled="readonly"
            required
          ></b-form-input>
          <p id="errFecha" class="font-weight-bold m-0 position-absolute small">{{ error_msg.fecha }}</p>
        </b-col>
        <b-col cols="3" sm="1" md="1" class="offset-sm-2 offset-md-0 px-sm-0 px-md-3">
          <label for="hora" class="font-weight-bold">Hora:</label>
        </b-col>
        <b-col cols="9" sm="3" md="2" class="pl-sm-1 px-md-0">
          <b-form-input
            id="hora"
            v-model="sesion.hora"
            type="time"
            size="sm"
            :disabled="readonly"
            required
          ></b-form-input>
          <p id="errHora" class="font-weight-bold m-0 position-absolute small">{{ error_msg.hora }}</p>
        </b-col>
        <b-col cols="3" sm="2" md="1">
          <label for="lugar" class="font-weight-bold">Lugar:</label>
        </b-col>
        <b-col cols="9" sm="4" md="3" class="px-sm-0 pl-md-0 pr-md-3">
          <b-form-select
            id="fronton"
            v-model="sesion.fronton_id"
            :options="frontones"
            size="sm"
            :disabled="readonly"
            required
          ></b-form-select>
        </b-col>
      </b-row>

      <!-- Pelotaris convocados -->
      <b-row class="border-top mt-3 pt-3">
        <b-col cols="12" sm="4" md="5">
          <label v-if="readonly" for="pelotariInput" class="font-weight-bold">Pelotaris convocados:</label>
          <b-button
            v-if="!readonly"
            class="font-weight-bold text-dark text-uppercase"
            variant="warning"
            size="sm"
            v-on:click="onClickOpenDesigner">
            <i class="fas fa-highlighter mr-2"></i>Abrir Diseñador
          </b-button>
        </b-col>
        <b-col v-if="!readonly" cols="12" sm="4" md="3" class="mb-1 mb-sm-0 pr-md-0">
          <b-form-select
            id="pelotariInput"
            class="w-100"
            :options="pelotaris_select"
            v-model="pelotari_selected"
            size="sm">
          </b-form-select>
        </b-col>
        <b-col v-if="!readonly" cols="12" sm="4" class="pl-sm-0 pl-md-3">
          <b-btn
            id="addPelotariBtn"
            class="font-weight-bold text-uppercase w-100"
            variant="primary"
            size="sm"
            v-on:click="onClickAddPelotari">
            <i class="fas fa-user-plus mr-2"></i>Añadir Pelotari
          </b-btn>
        </b-col>
      </b-row>
      <b-row class="mt-3">
        <b-col cols="12" class="pelotaris-wrap">
          <div v-for="pelotari in sesion.pelotaris" v-bind:key="pelotari.id" class="pelotari-wrap" v-if="!pelotari.delete">
            <b-button block size="sm" v-b-toggle="'accordion-' + pelotari.id" variant="outline-info" class="border-info d-inline-block font-weight-bold pelotari-btn text-uppercase" v-bind:class="{ 'w-100': readonly }"><i class="fas fa-caret-down float-left"></i>{{ pelotari.alias }}</b-button>
            <b-button v-if="!readonly" block size="sm" class="d-inline-block delete-btn" variant="danger" v-on:click="onClickRemovePelotari(pelotari.id)"><i class="fas fa-trash-alt"></i></b-button>
            <b-collapse :id="'accordion-' + pelotari.id" role="tabpanel">
              <b-card class="ejercicios-wrap mb-3" body-class="p-3">
                <b-row class="border-bottom border-dark mx-0 table-header">
                  <b-col cols="2" md="1" class="pl-0 pr-2"><small class="font-weight-bold text-uppercase">&nbsp;</small></b-col>
                  <b-col cols="3" sm="2" md="2" class="pl-0 pr-2"><small class="font-weight-bold text-uppercase">Fase</small></b-col>
                  <b-col cols="5" md="6" class="pl-0 pr-2"><small class="font-weight-bold text-uppercase">Ejercicio</small></b-col>
                  <b-col cols="1" md="1" class="d-none d-md-block pl-0 pr-2 text-center"><small class="font-weight-bold">Vol.</small></b-col>
                  <b-col cols="1" md="1" class="d-none d-md-block pl-0 pr-2 text-center"><small class="font-weight-bold">Int.</small></b-col>
                  <b-col cols="1" md="1" class="d-none d-sm-block d-md-none pl-0 pr-2 text-center"><small class="font-weight-bold">V/I</small></b-col>
                  <b-col cols="2" md="1" class="px-0">&nbsp;</b-col>
                </b-row>
                <b-row v-for="(ejercicio, index) in pelotari.ejercicios" v-bind:key="index" class="border-bottom mx-0 ejercicios-row">
                  <b-col cols="2" md="1" class="pl-0 pr-2 py-1 text-left">
                    <b-button v-if="index > 0" size="sm" variant="link" class="arrow d-none d-sm-block float-left p-0 text-dark" title="Desplazar hacia arriba en el orden del listado" @click="onClickOrderUp(index, pelotari.id)">
                      <i class="fas fa-long-arrow-alt-up"></i>
                    </b-button>
                    <span v-else class="d-none d-sm-block float-left pl-1">&nbsp;</span>
                    <b-button v-if="index < (pelotari.ejercicios.length - 1)" size="sm" variant="link" class="arrow d-none d-sm-block float-left p-0 text-dark" title="Desplazar hacia abajo en el orden del listado" @click="onClickOrderDown(index, pelotari.id)">
                      <i class="fas fa-long-arrow-alt-down"></i>
                    </b-button>
                    <span v-else class="d-none d-sm-block float-left pl-1">&nbsp;</span>
                    <small class="ml-2">{{ ejercicio.order }}</small>
                  </b-col>
                  <b-col cols="3" sm="2" md="2" class="pl-0 pr-2 py-1">
                    <small>{{ ejercicio.fase_desc }}</small>
                  </b-col>
                  <b-col cols="5" md="6" class="pl-0 pr-2 py-1">
                    <small>{{ ejercicio.name }}</small>
                  </b-col>
                  <b-col cols="1" md="1" class="d-none d-md-block pl-0 pr-2 py-1 text-center">
                    <b-button size="sm" variant="link" class="d-none d-sm-inline-block p-0 plus text-dark" title="Reducir el Volumen" @click="onClickVolumenDown(index, pelotari.id)">
                      <i class="fas fa-minus"></i>
                    </b-button>
                    <small class="border px-2 py-1">{{ ejercicio.volumen }}</small>
                    <b-button size="sm" variant="link" class="d-none d-sm-inline-block minus p-0 text-dark" title="Aumentar el Volumen" @click="onClickVolumenUp(index, pelotari.id)">
                      <i class="fas fa-plus"></i>
                    </b-button>
                  </b-col>
                  <b-col cols="1" md="1" class="d-none d-md-block pl-0 pr-2 py-1 text-center">
                    <b-button size="sm" variant="link" class="d-none d-sm-inline-block p-0 plus text-dark" title="Reducir el Volumen" @click="onClickIntensidadDown(index, pelotari.id)">
                      <i class="fas fa-minus"></i>
                    </b-button>
                    <small class="border px-2 py-1">{{ ejercicio.intensidad }}</small>
                    <b-button size="sm" variant="link" class="d-none d-sm-inline-block minus p-0 text-dark" title="Aumentar el Volumen" @click="onClickIntensidadUp(index, pelotari.id)">
                      <i class="fas fa-plus"></i>
                    </b-button>
                  </b-col>
                  <b-col cols="1" md="1" class="d-none d-sm-block d-md-none pl-0 pr-2 py-1 text-center">
                    <small>{{ ejercicio.volumen }}/{{ ejercicio.intensidad}}</small>
                  </b-col>
                  <b-col cols="2" md="1" class="px-0 py-1 text-right">
                    <b-button v-if="!readonly" size="sm" variant="link" class="action-btn mx-0 mx-sm-2 p-0 text-primary" title="Editar Ejercicio" @click="onClickEditEjercicio(pelotari.id, ejercicio.ejercicio_id)">
                      <i class="fas fa-edit"></i>
                    </b-button>
                    <b-button v-if="!readonly" size="sm" variant="link" class="action-btn p-0 text-danger" title="Eliminar Ejercicio" @click="onClickRemoveEjercicio(pelotari.id, ejercicio.ejercicio_id)">
                      <i class="fas fa-trash-alt"></i>
                    </b-button>
                  </b-col>
                </b-row>
                <b-row v-if="!pelotari.ejercicios.length"  class="border-bottom mx-0">
                  <b-col cols="12">&nbsp;</b-col>
                </b-row>
                <b-row class="mt-3">
                  <b-col cols="12" class="text-right">
                    <b-button
                      v-if="!readonly"
                      class="font-weight-bold text-uppercase"
                      variant="outline-secondary"
                      size="sm"
                      v-on:click="onClickAddEjercicio(pelotari.id)">
                      <i class="fas fa-plus mr-2"></i>Añadir Ejercicio
                    </b-button>
                  </b-col>
                </b-row>
              </b-card>
            </b-collapse>
          </div>
        </b-col>
      </b-row>
      <!-- /Pelotaris convocados -->
    </b-form>
    <!-- Formulario Ejercicios Programados -->
    <div v-if="showFormSesionEjercicio" id="formSesionEjercicio" class="px-2 py-1">
      <FormSesionEjercicio class="form-wrap" :pelotari_id="pelotari_ejercicio" :order="order" :frontones="frontones" :ejercicio_id="ejercicio_id" />
    </div>
    <!-- /Formulario Ejercicios Programados -->

    <!-- Diseñador de sesion (pelotaris y ejercicios por fases) -->
    <div v-if="showFormSesionDesigner" id="formSesionDesigner" class="px-2 py-1">
      <SesionDesigner class="designer-wrap form-wrap" :pelotaris="pelotaris_all" :frontones="frontones" />
    </div>
    <!-- /Diseñador de sesion (pelotaris y ejercicios por fases) -->
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import APIGetters from '../../../utils/getters.js';
  import moment from 'moment';

  import FormSesionEjercicio from './SesionEjercicio/FormSesionEjercicio.vue';
  import SesionDesigner from './SesionEjercicio/SesionDesigner.vue';

  export default {
    mixins: [ APIGetters ],
    props: [ 'readonly' ],
    data() {
      return {
        ejercicio_id: 0,
        error_msg: null,
        macrociclo_dates: '',
        macrociclo_desc: '',
        mesociclo_dates: '',
        mesociclo_desc: '',
        microciclo_dates: '',
        microciclo_desc: '',
        order: 10,
        pelotari_ejercicio: null,
        pelotari_selected: null,
        pelotaris_all: [],
        pelotaris_select: [],
        show: false,
        showFormSesionDesigner: false,
        showFormSesionEjercicio: false
      }
    },
    computed: mapState({
      macrociclo: state => state.plen.macrociclo,
      mesociclo: state => state.plen.mesociclo,
      microciclo: state => state.plen.microciclo,
      sesion: state => state.plen.sesion,
    }),
    created() {
      this.getEjercicios().then( () => {
        this.getSubtiposEjercicio().then( () => {
          this.getTiposEjercicio().then( () => {
            this.getFases().then( () => {
              this.resetErrorMsg();
              this.getFrontones();
              this.loadPelotaris();
              this.$root.$on('saveEditSesion', this.hideFormSesionEjercicio);
              this.$root.$on('cancelEditSesion', this.hideFormSesionEjercicio);
              this.$root.$on('hideFormSesionEjercicio', this.hideFormSesionEjercicio);
              this.$root.$on('hideFormSesionDesigner', this.hideFormSesionDesigner);
            });
          });
        });
      });
    },
    mounted() {
      this.$root.$on('bv::modal::show', (bvEvent, modalId) => {
        if( 'editSesion' == bvEvent.target.id ) {
          this.resetErrorMsg();
        }
      });
    },
    updated() {
      this.macrociclo_desc = (this.macrociclo.description ? this.macrociclo.description : '-');
      this.macrociclo_dates = moment(this.macrociclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.macrociclo.fecha_fin).format('DD/MM/YYYY');
      this.mesociclo_desc = (this.mesociclo.description ? this.mesociclo.description : '-');
      this.mesociclo_dates = moment(this.mesociclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.mesociclo.fecha_fin).format('DD/MM/YYYY');
      this.microciclo_desc = (this.microciclo.description ? this.microciclo.description : '-');
      this.microciclo_dates = moment(this.microciclo.fecha_ini).format('DD/MM/YYYY') + " - " + moment(this.microciclo.fecha_fin).format('DD/MM/YYYY');
    },
    methods: {
      ...mapActions({
        getAllPelotaris: 'plen/getPelotaris',
        getEjercicios: 'plen/getEjercicios',
        getFases: 'plen/getFases',
        getSubtiposEjercicio: 'plen/getSubtiposEjercicio',
        getTiposEjercicio: 'plen/getTiposEjercicio'
      }),
      hideFormSesionDesigner() {
        this.showFormSesionDesigner = false;
      },
      hideFormSesionEjercicio() {
        this.showFormSesionEjercicio = false;
        this.pelotari_ejercicio = null;
        this.$root.$emit('disable-modal-sesion-footer-buttons', this.showFormSesionEjercicio);
      },
      loadPelotaris() {
        this.getAllPelotaris(this.sesion.fecha).then( (res) => {
          var stringified = JSON.stringify(res).replace(/"id"/g, '"value"').replace(/alias/g, "text");
          this.pelotaris_select = JSON.parse(stringified);

          this.pelotaris_select.map( (val, index) => {
            if(val.fecha_fin_contrato <= this.sesion.fecha) {
              this.pelotaris_select.splice(index, 1);
              res.splice(index, 1);
            }
          });

          this.pelotaris_select.unshift({ value: null, text: "Seleccionar pelotari" });

          this.pelotaris_all = res;

          this.show = true;
        });
      },
      onClickAddPelotari() {
        let pelotari = _.find(this.pelotaris_all, { id: this.pelotari_selected });

        if( !_.find(this.sesion.pelotaris, { id: this.pelotari_selected }) ) {
          pelotari.new = true;
          pelotari.ejercicios = [];
          this.sesion.pelotaris.push(pelotari);
        } else {
          alert("El Pelotari " + pelotari.alias + " ya se encuentra convocado");
        }

        this.pelotari_selected = null;
      },
      onClickAddEjercicio(pelotari_id) {
        const pelotari = _.find(this.sesion.pelotaris, { id: pelotari_id });
        const maxOrder = _.maxBy(pelotari.ejercicios, 'order');

        this.pelotari_ejercicio = pelotari_id;
        this.order = (maxOrder && maxOrder.order ? Math.round(maxOrder.order / 10) * 10 + 10 : this.order);
        this.ejercicio_id = 0;

        this.showFormSesionEjercicio = true;

        this.$root.$emit('disable-modal-sesion-footer-buttons', this.showFormSesionEjercicio);
      },
      onClickEditEjercicio(pelotari_id, ejercicio_id) {
        const pelotari = _.find(this.sesion.pelotaris, { id: pelotari_id });
        const ejercicio = _.find(pelotari.ejercicios, { ejercicio_id: ejercicio_id });

        this.pelotari_ejercicio = pelotari_id;
        this.order = ejercicio.order;
        this.ejercicio_id = ejercicio_id;

        this.showFormSesionEjercicio = true;

        this.$root.$emit('disable-modal-sesion-footer-buttons', this.showFormSesionEjercicio);
      },
      onClickIntensidadDown(index, pelotari_id) {
        let pelotari = _.find(this.sesion.pelotaris, { id: pelotari_id });
        if( pelotari.ejercicios[index].intensidad > 1 ) {
          pelotari.ejercicios[index].intensidad--;
        }
      },
      onClickIntensidadUp(index, pelotari_id) {
        let pelotari = _.find(this.sesion.pelotaris, { id: pelotari_id });
        if( pelotari.ejercicios[index].intensidad == 0 || pelotari.ejercicios[index].intensidad < 5 ) {
          pelotari.ejercicios[index].intensidad++;
        }
      },
      onClickOpenDesigner() {
        this.showFormSesionDesigner = true;
      },
      onClickOrderDown(index, pelotari_id) {
        let pelotari = _.find(this.sesion.pelotaris, { id: pelotari_id });
        const currentOrder = pelotari.ejercicios[index].order;
        const nextOrder = pelotari.ejercicios[index + 1].order;

        pelotari.ejercicios[index].order = nextOrder;
        pelotari.ejercicios[index + 1].order = currentOrder;

        pelotari.ejercicios = _.orderBy(pelotari.ejercicios, 'order', 'asc');
      },
      onClickOrderUp(index, pelotari_id) {
        let pelotari = _.find(this.sesion.pelotaris, { id: pelotari_id });
        const currentOrder = pelotari.ejercicios[index].order;
        const prevOrder = pelotari.ejercicios[index - 1].order;

        pelotari.ejercicios[index].order = prevOrder;
        pelotari.ejercicios[index - 1].order = currentOrder;

        pelotari.ejercicios = _.orderBy(pelotari.ejercicios, 'order', 'asc');
      },
      onClickRemoveEjercicio(pelotari_id, ejercicio_id) {
        const pelotari = _.find(this.sesion.pelotaris, { id: pelotari_id });
        const ejercicio = _.find(pelotari.ejercicios, { ejercicio_id: ejercicio_id });
        if( confirm("¿Desea BORRAR el Ejercicio '" + ejercicio.name + "' de la lista de ejercicios programados del pelotari '" + pelotari.alias + "'?") ) {
          this.removeEjercicio(pelotari_id, ejercicio_id);
        }
      },
      onClickRemovePelotari(id) {
        const pelotari = _.find(this.pelotaris_all, { id: id });
        if( confirm("¿Desea BORRAR al Pelotari " + pelotari.alias + " de la lista de convocados?\n\nTambién se borrarán los ejercicios vinculados a este Pelotari y Sesión.") ) {
          this.removePelotari(id);
        }
      },
      onClickVolumenDown(index, pelotari_id) {
        let pelotari = _.find(this.sesion.pelotaris, { id: pelotari_id });
        if( pelotari.ejercicios[index].volumen > 1 ) {
          pelotari.ejercicios[index].volumen--;
        }
      },
      onClickVolumenUp(index, pelotari_id) {
        let pelotari = _.find(this.sesion.pelotaris, { id: pelotari_id });
        if( pelotari.ejercicios[index].volumen == 0 || pelotari.ejercicios[index].volumen < 5 ) {
          pelotari.ejercicios[index].volumen++;
        }
      },
      onInputFecha(fecha) {
        this.error_msg.fecha = '';
        if( fecha < this.macrociclo.fecha_ini || fecha > this.macrociclo.fecha_fin ||
            fecha < this.mesociclo.fecha_ini  || fecha > this.mesociclo.fecha_fin  ||
            fecha < this.microciclo.fecha_ini || fecha > this.microciclo.fecha_fin ) {
          this.error_msg.fecha = "La fecha debe estar dentro del intervalo";
        } else {
          this.loadPelotaris();
        }
        this.setErrorMsgExists();
      },
      removeEjercicio(pelotari_id, ejercicio_id) {
        const index_pelotaris = _.findIndex(this.sesion.pelotaris, { 'id': pelotari_id });
        const index_ejercicios = _.findIndex(this.sesion.pelotaris[index_pelotaris].ejercicios, { 'ejercicio_id': ejercicio_id});
        this.sesion.pelotaris[index_pelotaris].ejercicios.splice(index_ejercicios, 1);
      },
      removePelotari(id) {
        const index = _.findIndex(this.sesion.pelotaris, { 'id': id });
        this.sesion.pelotaris.splice(index, 1);
      },
      resetErrorMsg() {
        this.error_msg = {
          exists: false,
          fecha: ''
        };
      },
      setErrorMsgExists() {
        this.error_msg.exists = this.error_msg.fecha.length > 0;
        this.$root.$emit('disable-modal-sesion-save-button', this.error_msg.exists);
      },
      setFrontones(arr) {
        this.tipos.push({
          value: null,
          text: "Seleccionar Frontón"
        });
        arr.map( (val, res) => {
          this.tipos.push({
            value: val.id,
            text:  val.desc
          });
        });
      }
    },
    components: {
      FormSesionEjercicio,
      SesionDesigner
    }
  }
</script>

<style scoped>
.ejercicios-wrap .ejercicios-row{
  line-height: 1;
}
.ejercicios-wrap .arrow:focus {
  box-shadow:none;
}
.ejercicios-wrap .minus .fas,
.ejercicios-wrap .plus .fas {
  font-size:8px;
}
#errFecha {
  color:red;
  line-height:1.1;
  padding-top:5px;
}
#addPelotariBtn {
  width:130px;
}
#pelotariInput {
  width:calc(98% - 130px);
}
#formSesionDesigner {
  background-color:rgba(0,0,0,0.65);
  left:0;
  height:100%;
  position:fixed;
  top:0;
  width:100%;
}
#formSesionDesigner .designer-wrap {
  background-color:white;
  height:calc(100% - 90px);
  margin:45px auto;
  max-width:1250px;
  padding-bottom:1rem;
  width:calc(100% - 90px);
}
#formSesionEjercicio {
  background-color:rgba(60, 60, 60, 0.75);
  height:100%;
  left:0;
  position:absolute;
  top:0;
  width:100%;
}
#formSesionEjercicio .form-wrap {
  background-color:white;
  border:2px solid rgba(0, 127, 255, 0.5);
  left:2.5%;
  position:absolute;
  top:50%;
  width:95%;

  -webkit-transform:translateY(-50%);
  -moz-transform:translateY(-50%);
  -ms-transform:translateY(-50%);
  -o-transform:translateY(-50%);
  transform:translateY(-50%);
}
.ejercicios-wrap .action-btn:active,
.ejercicios-wrap .action-btn:focus,
.ejercicios-wrap .action-btn:hover {
  opacity:.8;
}
.macrociclo-wrap p {
  background-color:rgba(255, 192, 203, 0.55);
}
.mesociclo-wrap p {
  background-color:rgba(173, 216, 230, 0.55);
}
.microciclo-wrap p {
  background-color:rgba(173, 230, 183, 0.55);
}
.pelotaris-wrap .pelotari-wrap:first-child .delete-btn,
.pelotaris-wrap .pelotari-wrap:first-child .pelotari-btn {
  margin-top:0;
}
.pelotaris-wrap .pelotari-wrap:not(:first-child) .delete-btn,
.pelotaris-wrap .pelotari-wrap:not(:first-child) .pelotari-btn {
  margin-top:0.25rem;
}
.pelotaris-wrap .pelotari-wrap .pelotari-btn {
  width:calc(100% - 41px);
}
.pelotaris-wrap .pelotari-wrap .delete-btn {
  width:37px;
}
</style>
