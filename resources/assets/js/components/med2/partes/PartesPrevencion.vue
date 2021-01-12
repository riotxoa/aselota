<template>
  <div>
    <b-row>
      <b-col v-if="showAlias" cols="12" class="my-3">
        <h3 class="font-weight-bold text-center">PARTES PREVENCIÓN</h3>
      </b-col>
      <b-col v-else cols="12" class="my-3">
        <BotonNewPrevencion />
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
          <template slot="fecha_rec" slot-scope="row">
            {{ formatDateES(row.item.fecha_rec) }}
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
    <b-modal id="delParte" ref="delParteModal" title="Eliminar Parte de Prevención" size="md" @shown="focusModal" hide-footer lazy>
        <b-row class="mx-0">
          Se va a eliminar el siguiente Parte de Prevención.
        </b-row>
        <b-row v-if="delPartePrevencion" class="mx-0">
          <ul class="mt-3">
            <li><strong>Pelotari</strong>: {{ showAlias ? delParteFisiologia.alias : alias }}</li>
            <li><strong>F.Asistencia</strong>: {{ formatDateES(delPartePrevencion.fecha_asistencia) }}</li>
            <li><strong>F.Reconocimiento</strong>: {{ formatDateES(delPartePrevencion.fecha_rec) }}</li>
          </ul>
        </b-row>
        <b-row class="mx-0">
          ¿Está seguro?
        </b-row>
        <hr/>
        <b-row class="mx-0 float-right">
          <b-btn class="mr-3" variant="primary" @click.stop="deletePartePrevencion">Borrar</b-btn>
          <b-btn variant="default" ref="focusThis" @click.stop="hidePartePrevencionModal">Cancelar</b-btn>
        </b-row>
    </b-modal>
    <ModalPartePrevencion />
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Utils from '../../utils/utils.js';
  import BotonNewPrevencion from './prevencion/BotonNewPrevencion';
  import ModalPartePrevencion from '../partes/prevencion/ModalPartePrevencion';

  export default {
    props: ['partes', 'alias', 'showAlias'],
    mixins: [ Utils ],
    data() {
      return {
        delPartePrevencion: null,
        fields: [
          { key: 'id', label: 'ID', class: 'text-uppercase', sortable: true },
          { key: 'fecha_asistencia', label: 'F.Asistencia', class: 'text-center', sortable: true },
          { key: 'fecha_rec', label: 'F.Síntomas', class: 'text-center', sortable: true },
          { key: 'reconocimiento', label: 'Reconocimiento', class: 'text-left', sortable: false },
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
        delPPrevencion: 'med2/delPPrevencion',
        setPPrevencion: 'med2/setPPrevencion',
        setPelotari: 'med2/setPelotari',
      }),
      deletePartePrevencion(entrada) {
        this.delPPrevencion(this.delPartePrevencion.id).then( (res) => {
          this.delPartePrevencion = null;
          this.showSnackbar("Parte de Prevención eliminado");
          this.setPelotari( res );
          this.$refs.delParteModal.hide();
        });
        this.$refs.delParteModal.hide();
      },
      focusModal() {
        this.$refs.focusThis.focus();
      },
      hidePartePrevencionModal() {
        this.$refs.delParteModal.hide();
      },
      onClickDeleteParte(data) {
        this.delPartePrevencion = data;
        this.$refs.delParteModal.show();
      },
      onClickEditParte(data) {
        if( this.showAlias) {
          this.setPelotari(data);
        }
        this.setPPrevencion(data);
        this.$root.$emit('edit-parte-prevencion', data.id);
        this.$root.$emit('bv::show::modal', 'modal-parte-prevencion', '#btnNewPPrevencion');
      },
    },
    components: {
      BotonNewPrevencion,
      ModalPartePrevencion
    }
  }
</script>
