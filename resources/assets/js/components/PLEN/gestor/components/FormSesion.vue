<template>
  <div>
    <b-form v-if="show">
      <b-row class="macrociclo-wrap">
        <b-col cols="12" sm="2">
          <label class="font-weight-bold">Macrociclo:</label>
        </b-col>
        <b-col cols="12" sm="3" class="px-sm-0">
          <p class="border mb-1 px-2">{{ macrociclo_dates }}</p>
        </b-col>
        <b-col cols="12" sm="7">
          <p class="border mb-2 px-2">{{ macrociclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row class="mesociclo-wrap">
        <b-col cols="12" sm="2">
          <label class="font-weight-bold">Mesociclo:</label>
        </b-col>
        <b-col cols="12" sm="3" class="px-sm-0">
          <p class="border mb-1 px-2">{{ mesociclo_dates }}</p>
        </b-col>
        <b-col cols="12" sm="7">
          <p class="border mb-2 px-2">{{ mesociclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row class="border-bottom mb-3 microciclo-wrap">
        <b-col cols="12" sm="2">
          <label class="font-weight-bold">Microciclo:</label>
        </b-col>
        <b-col cols="12" sm="3" class="px-sm-0">
          <p class="border mb-1 px-2">{{ microciclo_dates }}</p>
        </b-col>
        <b-col cols="12" sm="7">
          <p class="border px-2">{{ microciclo_desc }}</p>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" sm="4">
          <b-row>
            <b-col cols="3">
              <label for="fecha" class="font-weight-bold">Fecha:</label>
            </b-col>
            <b-col cols="9">
              <b-form-input
                id="fecha"
                v-model="sesion.fecha"
                v-on:input="onInputFecha"
                type="date"
                :min="microciclo.fecha_ini"
                :max="microciclo.fecha_fin"
                size="sm"
                required
              ></b-form-input>
            </b-col>
            <b-col cols="12">
              <p id="errFecha" class="font-weight-bold m-0 position-absolute small">{{ error_msg.fecha }}</p>
            </b-col>
          </b-row>
        </b-col>
        <b-col cols="12" sm="3" class="pl-sm-0">
          <b-row>
            <b-col cols="3">
              <label for="hora" class="font-weight-bold">Hora:</label>
            </b-col>
            <b-col cols="9">
              <b-form-input
                id="hora"
                v-model="sesion.hora"
                type="time"
                size="sm"
                required
              ></b-form-input>
            </b-col>
            <b-col cols="12">
              <p id="errHora" class="font-weight-bold m-0 position-absolute small">{{ error_msg.hora }}</p>
            </b-col>
          </b-row>
        </b-col>
        <b-col cols="12" sm="5" class="pl-sm-0">
          <b-row>
            <b-col cols="3">
              <label for="lugar" class="font-weight-bold">Lugar:</label>
            </b-col>
            <b-col cols="9">
              <b-form-select
                id="fronton"
                v-model="sesion.fronton_id"
                :options="frontones"
                size="sm"
                required
              ></b-form-select>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      <b-row class="border-top mt-3 pt-3">
        <b-col cols="12" sm="7">
          <label for="pelotariInput" class="font-weight-bold">Pelotaris convocados:</label>
        </b-col>
        <b-col cols="12" sm="5" class="pl-sm-0">
          <b-form-select
            id="pelotariInput"
            :options="pelotaris_select"
            v-model="pelotari_selected"
            size="sm">
          </b-form-select>
          <b-btn
            id="addPelotariBtn"
            class="font-weight-bold text-uppercase"
            variant="primary" size="sm"
            v-on:click="onClickAddPelotari">
            Añadir Pelotari
          </b-btn>
        </b-col>
      </b-row>
      <b-row class="mt-3">
        <b-col cols="12" class="pelotaris-wrap">
          <div v-for="pelotari in sesion.pelotaris" v-bind:key="pelotari.id" class="pelotari-wrap" v-if="!pelotari.delete">
            <b-button block size="sm" v-b-toggle="'accordion-' + pelotari.id" variant="light" class="border d-inline-block font-weight-bold pelotari-btn text-uppercase">{{ pelotari.alias }}</b-button>
            <b-button block size="sm" class="d-inline-block delete-btn" variant="danger" v-on:click="onClickRemovePelotari(pelotari.id)"><i class="fas fa-trash-alt"></i></b-button>
            <b-collapse :id="'accordion-' + pelotari.id" role="tabpanel">
              <b-card class="mb-3">
                <b-card-body>
                  {{ pelotari }}
                </b-card-body>
              </b-card>
            </b-collapse>
          </div>
        </b-col>
      </b-row>
    </b-form>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import APIGetters from '../../../utils/getters.js';
  import moment from 'moment';

  export default {
    mixins: [ APIGetters ],
    data() {
      return {
        error_msg: null,
        macrociclo_dates: '',
        macrociclo_desc: '',
        mesociclo_dates: '',
        mesociclo_desc: '',
        microciclo_dates: '',
        microciclo_desc: '',
        pelotari_selected: null,
        pelotaris_all: [],
        pelotaris_select: [],
        show: false
      }
    },
    computed: mapState({
      macrociclo: state => state.plen.macrociclo,
      mesociclo: state => state.plen.mesociclo,
      microciclo: state => state.plen.microciclo,
      sesion: state => state.plen.sesion,
    }),
    created() {
      this.resetErrorMsg();
      this.getFrontones();
      this.loadPelotaris();
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
      }),
      loadPelotaris() {
        this.getAllPelotaris(this.sesion.fecha).then( (res) => {
          var stringified = JSON.stringify(res).replace(/"id"/g, '"value"').replace(/alias/g, "text");
          this.pelotaris_select = JSON.parse(stringified);

          this.pelotaris_select.map( (val) => {
            if(val.fecha_fin_contrato < this.sesion.fecha) {
              val.text = "**" + val.text + " (Fin Contrato: " + val.fecha_fin_contrato + ")";
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
          this.sesion.pelotaris.push(pelotari);
        } else {
          alert("El Pelotari " + pelotari.alias + " ya se encuentra convocado");
        }

        this.pelotari_selected = null;
      },
      onClickRemovePelotari(id) {
        const pelotari = _.find(this.pelotaris_all, { id: id });
        if( confirm("¿Desea BORRAR al Pelotari " + pelotari.alias + " de la lista de convocados?\n\nTambién se borrarán los ejercicios vinculados a este Pelotari y Sesión.") ) {
          this.removePelotari(id);
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
    }
  }
</script>

<style scoped>
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
