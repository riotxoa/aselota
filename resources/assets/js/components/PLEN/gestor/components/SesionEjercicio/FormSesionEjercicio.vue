<template>
  <div>
    <header class="modal-header">PROGRAMAR EJERCICIO<button type="button" aria-label="Close" class="close" @click="onClickCancel">×</button></header>
    <b-form v-if="show" class="p-3" @submit="onSubmit">
      <b-row class="border-bottom mb-3 pb-3">
        <b-col cols="12" v-if="disable">
          <b-row class="px-0">
            <b-col cols="12" sm="6" lg="4">
              <strong>Pelotari:</strong><span class="float-right text-secondary">{{ getPelotariAlias(pelotari) }}</span>
            </b-col>
            <b-col cols="12" sm="6" lg="4" class="d-inline-block d-lg-none">
              <strong>Frontón:</strong><span class="float-right text-secondary">{{ getFrontonName(sesion.fronton_id) }}</span>
            </b-col>
            <b-col cols="6" sm="6" lg="3">
              <strong>Fecha:</strong><span class="float-right text-secondary">{{ getSesionFecha(sesion.fecha) }}</span>
            </b-col>
            <b-col cols="6" sm="6" lg="1">
              <strong class="d-lg-none">Hora:</strong><span class="float-right text-secondary">{{ sesion.hora.substr(0,5) }}</span>
            </b-col>
            <b-col cols="12" lg="4" class="d-none d-lg-inline-block">
              <strong>Frontón:</strong><span class="float-right text-secondary">{{ getFrontonName(sesion.fronton_id) }}</span>
            </b-col>
          </b-row>
        </b-col>
        <b-col v-else cols="12" md="4" class="pr-md-0">
          <b-form-select
            id="pelotariInput"
            :options="pelotaris_options"
            v-model="pelotari"
            size="sm"
            :disabled="disable">
          </b-form-select>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6" md="4" class="pr-md-0">
          <b-form-group label="Orden:" label-for="ordenInput" label-class="font-weight-bold small">
            <b-form-input
              id="ordenInput"
              type="number"
              v-model="orden"
              size="sm">
            </b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="6" md="4" class="pr-md-0">
          <b-form-group label="Fase:" label-for="faseInput" label-class="font-weight-bold small">
            <b-form-select
              id="faseInput"
              :options="fases_options"
              v-model="fase_sesion_id"
              size="sm">
            </b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="6" md="2" class="pr-md-0">
          <b-form-group :label="'Vol. <span style=color:#e87c86; font-weight:normal;>(' + microciclo.volumen + ')</span>:'" label-for="volumenInput" label-class="font-weight-bold small">
            <b-form-input
              id="volumenInput"
              type="number"
              min="1"
              max="5"
              v-model="volumen"
              size="sm">
            </b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="6" md="2">
          <b-form-group :label="'Int. <span style=color:#e87c86; font-weight:normal;>(' + microciclo.intensidad + ')</span>:'" label-for="intensidadInput" label-class="font-weight-bold small">
            <b-form-input
              id="intensidadInput"
              type="number"
              min="1"
              max="5"
              v-model="intensidad"
              size="sm">
            </b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" md="4" class="pr-md-0">
          <b-form-group label="Tipo de ejercicio:" label-for="tipoEjercicioInput" label-class="font-weight-bold small">
            <b-form-select
              id="tipoEjercicioInput"
              :options="tipos_options"
              v-model="tipo_ejercicio"
              size="sm">
            </b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4" class="pr-md-0">
          <b-form-group label="Subtipo de ejercicio:" label-for="subtipoEjercicioInput" label-class="font-weight-bold small">
            <b-form-select
              id="subtipoEjercicioInput"
              :options="subtipos_options"
              v-model="subtipo_ejercicio"
              size="sm">
            </b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Ejercicio:" label-for="ejercicioInput" label-class="font-weight-bold small">
            <b-form-select
              id="ejercicioInput"
              :options="ejercicios_options"
              v-model="ejercicio"
              size="sm"
              required>
            </b-form-select>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12">
          <b-button
            type="submit"
            class="float-right font-weight-bold text-uppercase"
            variant="primary"
            size="sm"
          >
            <span v-if="editing">Actualizar Ejercicio</span>
            <span v-else>Añadir Ejercicio</span>
          </b-button>
          <b-button
            class="float-right font-weight-bold mr-2 text-uppercase"
            variant="secondary"
            size="sm"
            v-on:click="onClickCancel"
          >
            Cancelar
          </b-button>
        </b-col>
      </b-row>
    </b-form>
  </div>
</template>

<script>
  import { mapState } from 'vuex';
  import moment from 'moment';

  export default {
    props: ['pelotari_id', 'order', 'frontones', 'ejercicio_id'],
    data() {
      return {
        disable: false,
        editing: false,
        ejercicio: null,
        ejercicios_options: [],
        fase_sesion_id: null,
        fases_options: [],
        intensidad: null,
        orden: null,
        pelotari: null,
        pelotaris_options: [],
        show: false,
        subtipo_ejercicio: null,
        subtipos_options: [],
        tipo_ejercicio: null,
        tipos_options: [],
        volumen: null,
      }
    },
    computed: mapState({
      ejercicios: state => state.plen.ejercicios,
      fases: state => state.plen.fases,
      sesion: state => state.plen.sesion,
      microciclo: state => state.plen.microciclo,
      subtipos_ejercicio: state => state.plen.subtipos_ejercicio,
      tipos_ejercicio: state => state.plen.tipos_ejercicio,
    }),
    mounted() {
      this.loadOptionsEjercicios();
      this.loadOptionsSubtiposEjercicio();
      this.loadOptionsTiposEjercicio();
      this.loadOptionsFases();
      this.loadOptionsPelotaris();
      this.pelotari = this.pelotari_id;
      this.orden = this.order;
      this.disable = (this.pelotari_id ? true : false);
      if( this.ejercicio_id && this.ejercicio_id > 0 ) {
        this.editing = true;
        this.loadEjercicio(this.pelotari_id, this.ejercicio_id);
      }
      this.show = true;
    },
    methods: {
      getFrontonName(id) {
        const fronton = _.find(this.frontones, { 'value': id });
        return fronton.text;
      },
      getPelotariAlias(id) {
        const pelotari = _.find(this.pelotaris_options, { 'value': id });
        return pelotari.text;
      },
      getSesionFecha(fecha) {
        return moment(fecha).format("DD/MM/YYYY")
      },
      loadEjercicio(pelotari_id, ejercicio_id) {
        const _pelotari = _.find(this.sesion.pelotaris, { id: pelotari_id });
        const _ejercicio = _.find(_pelotari.ejercicios, { ejercicio_id: ejercicio_id });

        this.pelotari = pelotari_id;
        this.fase_sesion_id = _ejercicio.fase_sesion_id;
        this.volumen = _ejercicio.volumen;
        this.intensidad = _ejercicio.intensidad;
        this.tipo_ejercicio = _ejercicio.tipo_ejercicio_id;
        this.subtipo_ejercicio = _ejercicio.subtipo_ejercicio_id;
        this.ejercicio = ejercicio_id;
      },
      loadOptionsEjercicios() {
        var stringified = JSON.stringify(this.ejercicios).replace(/"id"/g, '"value"').replace(/"name"/g, '"text"');
        this.ejercicios_options = JSON.parse(stringified);
        this.ejercicios_options.unshift({ value: null, text: "Seleccionar ejercicio" });
      },
      loadOptionsFases() {
        var stringified = JSON.stringify(this.fases).replace(/"id"/g, '"value"').replace(/"desc"/g, '"text"');
        this.fases_options = JSON.parse(stringified);
        this.fases_options.unshift({ value: null, text: "Seleccionar fase" });
      },
      loadOptionsPelotaris() {
        var stringified = JSON.stringify(this.sesion.pelotaris).replace(/"id"/g, '"value"').replace(/"alias"/g, '"text"');
        this.pelotaris_options = JSON.parse(stringified);
        this.pelotaris_options.unshift({ value: 'XX', text: "Todos los pelotaris convocados" });
        this.pelotaris_options.unshift({ value: null, text: "Seleccionar pelotari" });
      },
      loadOptionsSubtiposEjercicio() {
        var stringified = JSON.stringify(this.subtipos_ejercicio).replace(/"id"/g, '"value"').replace(/"desc"/g, '"text"');
        this.subtipos_options = JSON.parse(stringified);
        this.subtipos_options.unshift({ value: null, text: "Seleccionar subtipo" });
      },
      loadOptionsTiposEjercicio() {
        var stringified = JSON.stringify(this.tipos_ejercicio).replace(/"id"/g, '"value"').replace(/"desc"/g, '"text"');
        this.tipos_options = JSON.parse(stringified);
        this.tipos_options.unshift({ value: null, text: "Seleccionar tipo" });
      },
      onSubmit(e) {
        e.preventDefault();
        const fase = _.find(this.fases, { id: this.fase_sesion_id });
        const index_pelotaris = _.findIndex(this.sesion.pelotaris, { id: this.pelotari });
        const index_ejercicios = _.findIndex(this.sesion.pelotaris[index_pelotaris].ejercicios, { ejercicio_id: this.ejercicio });
        const ejercicio = _.find(this.ejercicios, { id: this.ejercicio });

        let item = {
          tipo_ejercicio_id: ejercicio.tipo_ejercicio_id,
          subtipo_ejercicio_id: ejercicio.subtipo_ejercicio_id,
          ejercicio_id: ejercicio.id,
          name: ejercicio.name,
          order: this.orden,
          fase_sesion_id: this.fase_sesion_id,
          fase_desc: (fase ? fase.desc : ''),
          volumen: this.volumen,
          intensidad: this.intensidad
        }

        if( this.editing ) {
          this.sesion.pelotaris[index_pelotaris].ejercicios[index_ejercicios] = item;
        } else {
          this.sesion.pelotaris[index_pelotaris].ejercicios.push(item);
          this.sesion.pelotaris[index_pelotaris].ejercicios = _.orderBy(this.sesion.pelotaris[index_pelotaris].ejercicios, ['order', 'asc']);
        }

        this.$root.$emit('hideFormSesionEjercicio');
      },
      onClickCancel() {
        this.$root.$emit('hideFormSesionEjercicio');
      }
    }
  }
</script>
