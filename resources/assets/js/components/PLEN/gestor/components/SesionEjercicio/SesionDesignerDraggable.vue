<template>
  <div class="sd-draggable-wrap">
    <div v-if="title" class="mb-2">
      <h6 class="font-weight-bold text-center text-uppercase">{{ title }}</h6>
      <b-form-input
        v-if="!group.put"
        class="border-info mt-4"
        size="sm"
        v-model="filter"
        :placeholder="'Filtrar ' + title"
        @input="onInputFilterItems" />
      <b-form-select
        v-if="'ejercicio' === group.type"
        id="tipoEjercicioInput"
        class="border-info"
        style="margin-top:-1px"
        :options="tipos_options"
        v-model="tipo_ejercicio"
        @input="onClickTipoEjercicio"
        size="sm">
      </b-form-select>
      <b-form-select
        v-if="'ejercicio' === group.type"
        id="subtipoEjercicioInput"
        class="border-info"
        style="margin-top:-1px"
        :options="subtipos_options"
        v-model="subtipo_ejercicio"
        @input="onClickSubtipoEjercicio"
        size="sm">
      </b-form-select>
    </div>
    <b-row v-if="'sesion' === group.type && group.put && !title && realValue.length" class="mb-2" style="margin-top:-1.95rem;">
      <b-col cols="12" class="text-right">
        <b-link class="text-dark text-decoration-none" @click="onClickCollapseAll"><small>Contraer</small></b-link>
        <span class="text-muted" style="font-size:11">&nbsp;|&nbsp;</span>
        <b-link class="text-dark text-decoration-none" @click="onClickExpandAll"><small>Expandir</small></b-link>
      </b-col>
    </b-row>
    <draggable
      class="item-container list-group"
      v-bind:class="[{ 'bg-light border dragArea': group.put }, group.type]"
      :list="listFiltered"
      :group="{ name: group.name, pull: group.pull, put: group.put }"
      :move="onMoveCallback"
      @change="onChangeCallback"
      :value="value"
      @input="emitter"
    >
      <div
        v-for="(item, idx) in realValue"
        :key="item.id"
        class="list-group-item px-2 py-1"
        v-bind:class="[ { 'cursor-move': !group.put || 'ejercicio' === item.type }, { 'cursor-pointer': group.put && 'ejercicio' !== item.type }, item.type ]"
      >
        <div
          class="item"
          v-bind:class="[
            { 'bg-info font-weight-bold text-center text-white py-1': group.type === 'sesion' },
            { 'text-monospace text-muted': group.put && item.type === 'ejercicio' },
            { 'font-weight-bold mb-1 text-dark': item.type === 'fase' }
          ]"
          v-on:click="onClickToggleCollapse(idx)"
        >
          <a
            v-if="['sesion', 'fase'].includes(group.type)"
            class="cursor-pointer float-left"
            v-bind:class="[ { 'ml-2 text-white': 'sesion' === group.type }, {'ml-0 mr-1 text-info font-weight-bold': 'fase' === group.type } ]"
            v-bind:style="{ marginRight: 'sesion' === group.type ? '-3rem' : '0' }"
            title="Eliminar elemento de la sesión"
            v-on:click.stop="onClickRemove(idx)">
            X
          </a>
          {{ item.name }}
          <!-- <br><span class="text-unicode">{{ item }}</span> -->
          <a
            v-if="['pelotari', 'fase'].includes(item.type)"
            class="cursor-pointer float-right mr-2"
            v-bind:class="[ { 'text-white': 'pelotari' === item.type }, { 'text-info': 'fase' === item.type } ]"
            v-on:click="onClickToggleCollapse(idx)">
            <i v-if="true == item.collapsed" class="fas fa-caret-down"></i>
            <i v-else class="fas fa-caret-up"></i>
          </a>
        </div>
        <SDDraggable v-if="item.type === 'fase' || group.type === 'sesion'" class="item-sub" :list="item.elements" :group="{ name: group.name, type: item.type, pull: group.pull, put: group.put }" :title="false" />
      </div>
    </draggable>
  </div>
</template>

<script>
  import { mapState } from 'vuex';
  import draggable from 'vuedraggable'

  export default {
    name: 'SDDraggable',
    props: [ 'title', 'list', 'value', 'group', 'expand'],
    data() {
      return {
        fasesWrap: [],
        filter: '',
        listFiltered: [],
        subtipo_ejercicio: null,
        subtipos_options: [],
        tipo_ejercicio: null,
        tipos_options: []
      }
    },
    computed: {
      ...mapState({
          fases: state => state.plen.fases,
          subtipos_ejercicio: state => state.plen.subtipos_ejercicio,
          tipos_ejercicio: state => state.plen.tipos_ejercicio,
      }),
      realValue() {
        return this.value ? this.value : this.listFiltered;
      }
    },
    created() {
      this.listFiltered = this.list;
      if( this.group.type === 'ejercicio' ) {
        this.loadOptionsSubtiposEjercicio();
        this.loadOptionsTiposEjercicio();
      } else if( this.group.type === 'pelotari' && !this.group.put ) {
        this.$root.$on('sd-restore-pelotari', this.restorePelotari);
      }
    },
    methods: {
      emitter(value) {
        this.$emit("input", value);
      },
      loadOptionsSubtiposEjercicio(tipo = false) {
        let stringified = '';
        let subtipos = this.subtipos_ejercicio;

        if( tipo ) {
          subtipos = _.filter(subtipos, { plen_tipos_ejercicio_id: tipo });
        }

        stringified = JSON.stringify(subtipos).replace(/"id"/g, '"value"').replace(/"desc"/g, '"text"');
        this.subtipos_options = JSON.parse(stringified);
        this.subtipos_options.unshift({ value: null, text: "Seleccionar subtipo" });
      },
      loadOptionsTiposEjercicio() {
        var stringified = JSON.stringify(this.tipos_ejercicio).replace(/"id"/g, '"value"').replace(/"desc"/g, '"text"');
        this.tipos_options = JSON.parse(stringified);
        this.tipos_options.unshift({ value: null, text: "Seleccionar tipo" });
      },
      onChangeCallback(event) {
        if( event.added ) {
          if( event.added.element.type === 'pelotari') {
            this.fases.map( (val, idx) => {
              this.realValue[event.added.newIndex].elements.push({
                id: val.id,
                name: val.desc,
                type: 'fase',
                elements: [],
                collapsed: false
              })
            })
            this.emitter(this.realValue);
          }
        }
      },
      onClickCollapseAll() {
        this.realValue.map( (val, index) => {
          val.elements.map( (v, i) => {
            this.realValue[index].elements[i].collapsed = true;
          })
          this.realValue[index].collapsed = true;
        } );
        this.emitter(this.realValue);
      },
      onClickExpandAll() {
        this.realValue.map( (val, index) => {
          val.elements.map( (v, i) => {
            this.realValue[index].elements[i].collapsed = false;
          })
          this.realValue[index].collapsed = false;
        } );
        this.emitter(this.realValue);
      },
      onClickToggleCollapse(idx) {
        this.realValue[idx].collapsed = !this.realValue[idx].collapsed;
        this.emitter(this.realValue);
      },
      onClickRemove(idx) {
        if( this.realValue[idx].type === 'pelotari') {
          this.$root.$emit('sd-restore-pelotari', this.realValue[idx]);
        }
        this.realValue.splice(idx, 1);
        this.emitter(this.realValue);
      },
      onClickSubtipoEjercicio(value) {
        if( value ) {
          const subtipo = _.find(this.subtipos_ejercicio, { id: value });
          this.listFiltered = _.filter(this.list, { subtipo_ejercicio_id: subtipo.id });
        } else {
          this.listFiltered = ( this.tipo_ejercicio ? _.filter(this.list, { tipo_ejercicio_id: this.tipo_ejercicio }) : this.list);
        }
      },
      onClickTipoEjercicio(value) {
        this.loadOptionsSubtiposEjercicio(value);
        this.listFiltered = ( value ? _.filter(this.list, { tipo_ejercicio_id: value }) : this.list );
        this.subtipo_ejercicio = null;
      },
      onInputFilterItems(value) {
        if( value.length > 0 ) {
          value = value.toUpperCase();
          this.listFiltered = _.filter(this.list, (item) => {
            return item.name.toUpperCase().includes(value);
          });
        } else {
          this.listFiltered = this.list;
        }
      },
      onMoveCallback(evt, originalEvent) {
        let canMove = false;

        // FROM pelotari TO sesion
        if( !evt.from.className.includes('dragArea') &&
            evt.from.className.includes('pelotari') &&
            evt.to.className.includes('dragArea') &&
            evt.to.className.includes('sesion') ) {
          canMove = true;
        }
        // FROM ejercicio TO fase
        if( !evt.from.className.includes('dragArea') &&
            evt.from.className.includes('ejercicio') &&
            evt.to.className.includes('dragArea') &&
            evt.to.className.includes('fase') ) {
          canMove = true;
        }
        // FROM fase TO fase
        if( evt.from.className.includes('dragArea') &&
            evt.from.className.includes('fase') &&
            evt.to.className.includes('dragArea') &&
            evt.to.className.includes('fase') ) {
          canMove = true;
        }

        return canMove;
      },
      restorePelotari(pelotari) {
        pelotari.elements = [];
        this.list.push(pelotari);
        this.list = _.sortBy(this.list, 'name');
        this.onInputFilterItems(this.filter);
      },
    },
    components: {
      draggable
    }
  }
</script>

<style>
  .sd-draggable-wrap .border {
    border-radius: 0;
    border-style: dotted!important;
    border-width: 2px!important;
  }
  .sd-draggable-wrap .dragArea.pelotari {
    border:none!important;
  }
  .sd-draggable-wrap .dragArea:not(.pelotari) {
    padding-bottom:20px;
  }
  .sd-draggable-wrap .list-group-item {
    font-size:90%;
  }

  /* NESTED TEST */
  .sd-draggable-wrap .item-container {
  }
  .sd-draggable-wrap .list-group {
    overflow-y:auto;
  }
  .sd-draggable-wrap .list-group.ejercicio {
    max-height:400px;
  }
  .sd-draggable-wrap .list-group.pelotari {
    max-height:495px;
  }
  .sd-draggable-wrap .list-group.sesion {
    max-height:495px;
  }
  .sd-draggable-wrap .item a:hover {
    opacity:0.5;
    text-decoration:none;
  }
  .sd-draggable-wrap .item-sub {
  }

</style>
