<template>
  <div>
    <b-row>
      <b-col v-if="showAlias" cols="12" class="my-3">
        <h3 class="font-weight-bold text-center">PARTES ENFERMEDAD</h3>
      </b-col>
      <b-col v-else cols="12" class="my-3">
        <BotonNewEnfermedad />
      </b-col>
      <b-col cols="12">
        <b-table striped hover small responsive
          :items="partes"
          :fields="fields"
        >
        <template v-if="showAlias" slot="alias" slot-scope="row">
          {{ row.item.alias }}
        </template>
          <template slot="fecha_asistencia" slot-scope="row">
            {{ formatDateES(row.item.fecha_asistencia) }}
          </template>
          <template slot="fecha_inicio" slot-scope="row">
            {{ formatDateES(row.item.fecha_inicio) }}
          </template>
          <template slot="actions" slot-scope="row">
            <b-button size="sm" variant="danger" title="Borrar Parte" @click.stop="onClickDeleteParte(row.item)">
              <span class="icon voyager-trash"></span>
            </b-button>
            <b-button size="sm" variant="primary" title="Editar Parte" @click.stop="onClickEditParte(row.item)">
              <span class="icon voyager-edit"></span>
            </b-button>
          </template>
        </b-table>
      </b-col>
    </b-row>
    <b-modal id="delParte" ref="delParteModal" title="Eliminar Parte de Enfermedad" size="md" @shown="focusModal" hide-footer lazy>
        <b-row class="mx-0">
          Se va a eliminar el siguiente Parte de Enfermedad.
        </b-row>
        <b-row v-if="delParteEnfermedad" class="mx-0">
          <ul class="mt-3">
            <li><strong>Pelotari</strong>: {{ showAlias ? delParteFisiologia.alias : alias }}</li>
            <li><strong>F.Asistencia</strong>: {{ formatDateES(delParteEnfermedad.fecha_asistencia) }}</li>
            <li><strong>F.Inicio</strong>: {{ formatDateES(delParteEnfermedad.fecha_inicio) }}</li>
            <li><strong>Diagnóstico</strong>: {{ delParteEnfermedad.diagnostico_ini }}</li>
          </ul>
        </b-row>
        <b-row class="mx-0">
          ¿Está seguro?
        </b-row>
        <hr/>
        <b-row class="mx-0 float-right">
          <b-btn class="mr-3" variant="primary" @click.stop="deleteParteEnfermedad">Borrar</b-btn>
          <b-btn variant="default" ref="focusThis" @click.stop="hideParteEnfermedadModal">Cancelar</b-btn>
        </b-row>
    </b-modal>
    <ModalParteEnfermedad />
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Utils from '../../utils/utils.js';
  import BotonNewEnfermedad from './enfermedad/BotonNewEnfermedad';
  import ModalParteEnfermedad from '../partes/enfermedad/ModalParteEnfermedad';

  export default {
    props: ['partes', 'alias', 'showAlias'],
    mixins: [ Utils ],
    data() {
      return {
        delParteEnfermedad: null,
        fields: [
          { key: 'id', label: 'ID', class: 'text-uppercase', sortable: true },
          { key: 'fecha_asistencia', label: 'F.Asistencia', class: 'text-center', sortable: true },
          { key: 'fecha_inicio', label: 'F.Inicio', class: 'text-center', sortable: true },
          { key: 'diagnostico_ini', label: 'Diagnóstico', class: 'text-left', sortable: true },
          { key: 'actions', label: 'Acciones', sortable: false, class: "text-center" },
        ],
      }
    },
    created() {
      if( this.showAlias ) {
        this.fields.splice( 1, 0, { key: 'alias', label: 'Pelotari', sortable: true } );
      }
    },
    methods: {
      ...mapActions({
        delPEnfermedad: 'med2/delPEnfermedad',
        setPEnfermedad: 'med2/setPEnfermedad',
        setPelotari: 'med2/setPelotari',
      }),
      deleteParteEnfermedad(entrada) {
        this.delPEnfermedad(this.delParteEnfermedad.id).then( (res) => {
          this.delParteEnfermedad = null;
          this.showSnackbar("Parte de Enfermedad eliminado");
          this.setPelotari( res );
          this.$refs.delParteModal.hide();
        });
        this.$refs.delParteModal.hide();
      },
      focusModal() {
        this.$refs.focusThis.focus();
      },
      hideParteEnfermedadModal() {
        this.$refs.delParteModal.hide();
      },
      onClickDeleteParte(data) {
        this.delParteEnfermedad = data;
        this.$refs.delParteModal.show();
      },
      onClickEditParte(data) {
        if( this.showAlias) {
          this.setPelotari(data);
        }
        this.setPEnfermedad(data);
        this.$root.$emit('edit-parte-enfermedad', data.id);
        this.$root.$emit('bv::show::modal', 'modal-parte-enfermedad', '#btnNewPEnfermedad');
      },
    },
    components: {
      BotonNewEnfermedad,
      ModalParteEnfermedad
    }
  }
</script>
