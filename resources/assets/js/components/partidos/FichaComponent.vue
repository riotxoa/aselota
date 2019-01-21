<template>
  <b-form @submit="onSubmit">

    <b-row>

      <b-form-group label="Partido:"
                    label-for="partidoInput"
                    class="col-sm-2 font-weight-bold px-1">
        <b-form-select id="partidoInput"
                       :options="{ '1': '1º Partido', '2': '2º Partido', '3': '3º Partido', '4': '4º Partido', '5': '5º Partido' }"
                       required
                       v-model="orden">
        </b-form-select>
      </b-form-group>
      <b-form-group label="Estelar:"
                    label-for="estelarInput"
                    class="col-sm-2 font-weight-bold px-1">
        <b-form-select id="estelarInput"
                       :options="{ '0': 'No', '1': 'Sí' }"
                       v-model="estelar">
        </b-form-select>
      </b-form-group>
      <b-form-group label="Campeonato:"
                    label-for="campeonatoInput"
                    class="col-sm-3 font-weight-bold px-1">
        <b-form-select id="campeonatoInput"
                       :options="campeonatos"
                       @change="onChangeCampeonato"
                       v-model="campeonato_id">
        </b-form-select>
      </b-form-group>
      <b-form-group label="Tipo Partido:"
                    label-for="tipoInput"
                    class="col-sm-2 font-weight-bold px-1">
        <b-form-select id="tipoInput"
                       :options="tipos_partido_filtered"
                       @change="onChangeTiposPartido"
                       required
                       v-model="tipo_partido_id">
        </b-form-select>
      </b-form-group>
      <b-form-group label="Fase:"
                    label-for="faseInput"
                    class="col-sm-3 font-weight-bold px-1"
                    v-if="campeonato_id">
        <b-form-select id="faseInput"
                       :options="fases_campeonato"
                       v-model="fase">
        </b-form-select>
      </b-form-group>

    </b-row>

    <b-row>

      <div class="col-sm-3 pelotari gorri px-1 form-group">
        <label for="pelotari1gorriaInput" class="font-weight-bold pt-0 col-form-label">
          Pelotari 1:
        </label>
        <b-form-checkbox
                   class="float-right m-0"
                   v-model="pelotari_1_asegarce"
                   value="1"
                   unchecked-value="0"
                   @change="changeEmpresaPelotari1">
        </b-form-checkbox>
        <img v-if="1 == pelotari_1_asegarce" src="/storage/baiko.jpg" width="20" class="float-right asegarce-icon"/>
        <img v-if="0 == pelotari_1_asegarce" src="/storage/baiko.jpg" width="20" class="float-right asegarce-icon grayscale"/>
        <b-form-select id="pelotari1gorriaInput"
                       :options="pelotaris"
                       v-model="pelotari_1"
                       v-if="1 == pelotari_1_asegarce">
        </b-form-select>
        <b-form-select id="pelotari1gorriaInput"
                       :options="pelotaris_aspe"
                       v-model="pelotari_1"
                       v-if="0 == pelotari_1_asegarce">
        </b-form-select>
      </div>

      <div v-if="is_partido_parejas" class="col-sm-3 pelotari gorri px-1 form-group">
        <label for="pelotari2gorriaInput" class="font-weight-bold pt-0 col-form-label">
          Pelotari 2:
        </label>
        <b-form-checkbox
                   class="float-right m-0"
                   v-model="pelotari_2_asegarce"
                   value="1"
                   unchecked-value="0"
                   @change="changeEmpresaPelotari2">
        </b-form-checkbox>
        <img v-if="1 == pelotari_2_asegarce" src="/storage/baiko.jpg" width="20" class="float-right asegarce-icon"/>
        <img v-if="0 == pelotari_2_asegarce" src="/storage/baiko.jpg" width="20" class="float-right asegarce-icon grayscale"/>
        <b-form-select id="pelotari2gorriaInput"
                       :options="pelotaris"
                       v-model="pelotari_2"
                       v-if="1 == pelotari_2_asegarce">
        </b-form-select>
        <b-form-select id="pelotari2gorriaInput"
                       :options="pelotaris_aspe"
                       v-model="pelotari_2"
                       v-if="0 == pelotari_2_asegarce">
        </b-form-select>
      </div>

      <b-form-group label="&nbsp;"
                    label-for="exchangeBtn"
                    class="col-sm-3 text-center exchange px-1">
        <b-btn class="p-0" @click="exchangePelotaris" variant="link"><i class="voyager-code"></i></b-btn>
      </b-form-group>

      <div class="col-sm-3 pelotari urdina px-1 form-group">
        <label for="pelotari1urdinaInput" class="font-weight-bold pt-0 col-form-label">
          Pelotari 1:
        </label>
        <b-form-checkbox
                   class="float-right m-0"
                   v-model="pelotari_3_asegarce"
                   value="1"
                   unchecked-value="0"
                   @change="changeEmpresaPelotari3">
        </b-form-checkbox>
        <img v-if="1 == pelotari_3_asegarce" src="/storage/baiko.jpg" width="20" class="float-right asegarce-icon"/>
        <img v-if="0 == pelotari_3_asegarce" src="/storage/baiko.jpg" width="20" class="float-right asegarce-icon grayscale"/>
        <b-form-select id="pelotari1urdinaInput"
                       :options="pelotaris"
                       v-model="pelotari_3"
                       v-if="1 == pelotari_3_asegarce">
        </b-form-select>
        <b-form-select id="pelotari1urdinaInput"
                       :options="pelotaris_aspe"
                       v-model="pelotari_3"
                       v-if="0 == pelotari_3_asegarce">
        </b-form-select>
      </div>

      <div v-if="is_partido_parejas" class="col-sm-3 pelotari urdina px-1 form-group">
        <label for="pelotari2urdinaInput" class="font-weight-bold pt-0 col-form-label">
          Pelotari 2:
        </label>
        <b-form-checkbox
                   class="float-right m-0"
                   v-model="pelotari_4_asegarce"
                   value="1"
                   unchecked-value="0"
                   @change="changeEmpresaPelotari4">
        </b-form-checkbox>
        <img v-if="1 == pelotari_4_asegarce" src="/storage/baiko.jpg" width="20" class="float-right asegarce-icon"/>
        <img v-if="0 == pelotari_4_asegarce" src="/storage/baiko.jpg" width="20" class="float-right asegarce-icon grayscale"/>
        <b-form-select id="pelotari2urdinaInput"
                       :options="pelotaris"
                       v-model="pelotari_4"
                       v-if="1 == pelotari_4_asegarce">
        </b-form-select>
        <b-form-select id="pelotari2urdinaInput"
                       :options="pelotaris_aspe"
                       v-model="pelotari_4"
                       v-if="0 == pelotari_4_asegarce">
        </b-form-select>
      </div>

    </b-row>

    <div v-if="this.edit">
      <b-button variant="danger" type="submit" style="margin-left:-10px;">Guardar</b-button>
      <b-button variant="default" @click="onClickCancelar()" class="ml-2">Cancelar</b-button>
    </div>

    <div v-else>
      <b-button variant="danger" type="submit" style="margin-left:-10px;">Añadir</b-button>
    </div>

  </b-form>
</template>

<script>
  import { mapState } from 'vuex';
  import APIGetters from '../utils/getters.js';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [APIGetters, Utils],
    props: ['edit', 'partido', 'modalId'],
    data () {
      return {
        id: null,
        fecha_festival: null,
        festival_id: null,
        orden: 0,
        estelar: 0,
        campeonato_id: null,
        campeonato_name: '',
        tipo_partido_id: null,
        tipo_partido_name: '',
        fase: null,
        pelotari_1: null,
        pelotari_1_asegarce: 1,
        pelotari_2: null,
        pelotari_2_asegarce: 1,
        pelotari_3: null,
        pelotari_3_asegarce: 1,
        pelotari_4: null,
        pelotari_4_asegarce: 1,
        is_partido_parejas: true,
      }
    },
    created: function () {
      console.log("FestivalNuevoPartidoComponent created");

      this.getCampeonatos();
      this.getTiposPartido();
      this.getFasesCampeonato();

      if( this.edit ) {
        this.id = this.partido.id;
        this.festival_id = this.partido.festival_id;
        this.orden = this.partido.orden;
        this.estelar = this.partido.estelar;
        this.campeonato_id = this.partido.campeonato_id;
        this.tipo_partido_id = this.partido.tipo_partido_id;
        this.fase = this.partido.fase;
        this.pelotari_1 = (this.partido.pelotari_1 ? this.partido.pelotari_1.id : null);
        this.pelotari_1_asegarce = (this.partido.pelotari_1 ? this.partido.pelotari_1.asegarce : 1);
        this.pelotari_2 = (this.partido.pelotari_2 ? this.partido.pelotari_2.id : null);
        this.pelotari_2_asegarce = (this.partido.pelotari_2 ? this.partido.pelotari_2.asegarce : 1);
        this.pelotari_3 = (this.partido.pelotari_3 ? this.partido.pelotari_3.id : null);
        this.pelotari_3_asegarce = (this.partido.pelotari_3 ? this.partido.pelotari_3.asegarce : 1);
        this.pelotari_4 = (this.partido.pelotari_4 ? this.partido.pelotari_4.id : null);
        this.pelotari_4_asegarce = (this.partido.pelotari_4 ? this.partido.pelotari_4.asegarce : 1);
        this.is_partido_parejas = this.partido.is_partido_parejas;
      }
    },
    computed: mapState({
      _header: 'header',
    }),
    updated: function () {
      if(!this.fecha_festival) {
        this.fecha_festival = this._header.fecha;
        this.getPelotaris(this.fecha_festival);
      }
    },
    methods: {
      changeEmpresaPelotari1() {
        this.pelotari_1 = null;
      },
      changeEmpresaPelotari2() {
        this.pelotari_2 = null;
      },
      changeEmpresaPelotari3() {
        this.pelotari_3 = null;
      },
      changeEmpresaPelotari4() {
        this.pelotari_4 = null;
      },
      exchangePelotaris() {
        this.bak_1 = this.pelotari_1;
        this.bak_2 = this.pelotari_2;
        this.bak_asegarce_1 = this.pelotari_1_asegarce;
        this.bak_asegarce_2 = this.pelotari_2_asegarce;

        this.pelotari_1 = this.pelotari_3;
        this.pelotari_1_asegarce = this.pelotari_3_asegarce;
        this.pelotari_2 = this.pelotari_4;
        this.pelotari_2_asegarce = this.pelotari_4_asegarce;
        this.pelotari_3 = this.bak_1;
        this.pelotari_3_asegarce = this.bak_asegarce_1;
        this.pelotari_4 = this.bak_2;
        this.pelotari_4_asegarce = this.bak_asegarce_2;
      },
      resetForm () {
        this.orden = 0;
        this.estelar = 0;
        this.campeonato_id = null;
        this.tipo_partido_id = null;
        this.fase = null;
        this.pelotari_1 = null;
        this.pelotari_1_asegarce = 1;
        this.pelotari_2 = null;
        this.pelotari_2_asegarce = 1;
        this.pelotari_3 = null;
        this.pelotari_3_asegarce = 1;
        this.pelotari_4 = null;
        this.pelotari_4_asegarce = 1;
        this.is_partido_parejas = true;

        this.getTiposPartido();
      },
      closeModal () {
        this.$root.$emit('bv::hide::modal', this.modalId);
      },
      onSubmit (evt) {
        evt.preventDefault();
        var p1, p2, p3, p4;

        if( this.pelotari_1 ) {
          if( 1 == this.pelotari_1_asegarce )
            p1 = JSON.parse(JSON.stringify(_.filter(this.pelotaris, { value: this.pelotari_1 })[0]).replace(/value/g, "id").replace(/text/g, "alias"));
          else
            p1 = JSON.parse(JSON.stringify(_.filter(this.pelotaris_aspe, { value: this.pelotari_1 })[0]).replace(/value/g, "id").replace(/text/g, "alias"));
        } else {
          p1 = null;
        }
        if( this.pelotari_2 && this.is_partido_parejas) {
          if( 1 == this.pelotari_2_asegarce )
            p2 = JSON.parse(JSON.stringify(_.filter(this.pelotaris, { value: this.pelotari_2 })[0]).replace(/value/g, "id").replace(/text/g, "alias"));
          else
            p2 = JSON.parse(JSON.stringify(_.filter(this.pelotaris_aspe, { value: this.pelotari_2 })[0]).replace(/value/g, "id").replace(/text/g, "alias"));
        } else {
          p2 = null;
        }
        if( this.pelotari_3 ) {
          if( 1 == this.pelotari_3_asegarce )
            p3 = JSON.parse(JSON.stringify(_.filter(this.pelotaris, { value: this.pelotari_3 })[0]).replace(/value/g, "id").replace(/text/g, "alias"));
          else
            p3 = JSON.parse(JSON.stringify(_.filter(this.pelotaris_aspe, { value: this.pelotari_3 })[0]).replace(/value/g, "id").replace(/text/g, "alias"));
        } else {
          p3 = null;
        }
        if( this.pelotari_4 && this.is_partido_parejas) {
          if( 1 == this.pelotari_4_asegarce )
            p4 = JSON.parse(JSON.stringify(_.filter(this.pelotaris, { value: this.pelotari_4 })[0]).replace(/value/g, "id").replace(/text/g, "alias"));
          else
            p4 = JSON.parse(JSON.stringify(_.filter(this.pelotaris_aspe, { value: this.pelotari_4 })[0]).replace(/value/g, "id").replace(/text/g, "alias"));
        } else {
          p4 = null;
        }

        var data = {
          festival_id: (this._header.id ? this._header.id : this.festival_id),
          fecha: this._header.fecha,
          orden: this.orden,
          estelar: this.estelar,
          campeonato_id: this.campeonato_id,
          campeonato_name: _.filter(this.campeonatos, { value: this.campeonato_id })[0].text,
          tipo_partido_id: this.tipo_partido_id,
          tipo_partido_name: (this.campeonato_id ? null : _.filter(this.tipos_partido, {value: this.tipo_partido_id})[0].text),
          fase: (this.campeonato_id ? this.fase : null),
          pelotari_1: p1,
          pelotari_1_asegarce: parseInt(this.pelotari_1_asegarce),
          pelotari_2: p2,
          pelotari_2_asegarce: parseInt(this.pelotari_2_asegarce),
          pelotari_3: p3,
          pelotari_3_asegarce: parseInt(this.pelotari_3_asegarce),
          pelotari_4: p4,
          pelotari_4_asegarce: parseInt(this.pelotari_4_asegarce),
          is_partido_parejas: this.is_partido_parejas,
        }

        if(this.edit) {
          data.id = this.partido.id;
          this.$store.dispatch('updatePartido', data)
            .then((response) => {
              this.showSnackbar("Partido actualizado");
              this.$emit('update-partido', response);
              this.closeModal();
            })
            .catch((error) => {
              console.log(error);
              this.showSnackbar("Se ha producido un ERROR");
              this.closeModal();
            });
        } else {
          this.$store.dispatch('addPartido', data)
            .then((response) => {
              this.showSnackbar("Partido creado");
              this.$emit('new-partido', response);
              this.resetForm();
            })
            .catch((error) => {
              console.log(error);
              this.showSnackbar("Se ha producido un ERROR");
            });
        }
      },
      onClickCancelar() {
        this.closeModal();
      }
    }
  }
</script>

<style>
  .pelotari {
    max-width:23.75%;
  }
  .pelotari::before {
    border-radius:50%;
    content:" ";
    font-size:0;
    margin-right:5px;
    padding:7px;
    vertical-align:middle;
  }
  .pelotari.gorri::before {
    background-color:#d82a1f;
  }
  .pelotari.urdina::before {
    background-color:#0a4ea1;
  }
  .pelotari .asegarce-icon {
    margin-right:5px;
    margin-top:5px;
  }
  .pelotari .asegarce-icon.grayscale {
    -webkit-filter:grayscale(1) opacity(.5);
    filter:grayscale(1) opacity(.5);
  }
  .exchange {
    max-width:5%;
    overflow:hidden;
  }
  .exchange i {
    color:gray;
    font-size:1.25rem;
  }
  .exchange .btn-link:hover,
  .exchange .btn-link:active,
  .exchange .btn-link:focus {
    text-decoration:none;
  }
</style>
