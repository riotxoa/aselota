<template>
  <div>
    <b-row>
      <b-col v-if="showAlias" cols="12" class="my-3">
        <h3 class="font-weight-bold text-center">PARTES ACCIDENTE</h3>
      </b-col>
      <b-col v-else cols="12" class="my-3">
        <BotonNewAccidente />
      </b-col>
      <b-col cols="12">
        <b-table striped hover small responsive
          :items="partes"
          :fields="fields"
        >
          <template v-if="showAlias" slot="alias" slot-scope="row">
            {{ row.item.alias }}
          </template>
          <template slot="fecha_parte" slot-scope="row">
            {{ formatDateES(row.item.fecha_parte) }}
          </template>
          <template slot="fecha_accidente" slot-scope="row">
            {{ formatDateES(row.item.fecha_accidente) }}
          </template>
          <template slot="is_baja" slot-scope="row">
            <i v-if="row.item.is_baja" class="fas fa-check-circle" style="color:red"></i>
            <i v-else class="fas fa-times" style="color:gray"></i>
          </template>
          <template slot="is_recaida" slot-scope="row">
            <i v-if="row.item.is_recaida" class="fas fa-check-circle" style="color:red"></i>
            <i v-else class="fas fa-times" style="color:gray"></i>
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
    <b-modal id="delParte" ref="delParteModal" title="Eliminar Parte de Accidente" size="md" @shown="focusModal" hide-footer lazy>
        <b-row class="mx-0">
          Se va a eliminar el siguiente Parte de Accidente.
        </b-row>
        <b-row v-if="delParteAccidentes" class="mx-0">
          <ul class="mt-3">
            <li><strong>Pelotari</strong>: {{ showAlias ? delParteAccidentes.alias : alias }}</li>
            <li><strong>F.Parte</strong>: {{ formatDateES(delParteAccidentes.fecha_parte) }}</li>
            <li><strong>F.Accidente</strong>: {{ formatDateES(delParteAccidentes.fecha_accidente) }}</li>
            <li><strong>Parte del cuerpo</strong>: {{ delParteAccidentes.parte_cuerpo }}</li>
          </ul>
        </b-row>
        <b-row class="mx-0">
          ¿Está seguro?
        </b-row>
        <hr/>
        <b-row class="mx-0 float-right">
          <b-btn class="mr-3" variant="primary" @click.stop="deleteParteAccidente">Borrar</b-btn>
          <b-btn variant="default" ref="focusThis" @click.stop="hideParteAccidenteModal">Cancelar</b-btn>
        </b-row>
    </b-modal>
    <ModalParteAccidente />
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Utils from '../../utils/utils.js';
  import BotonNewAccidente from './accidente/BotonNewAccidente';
  import ModalParteAccidente from '../partes/accidente/ModalParteAccidente';

  export default {
    props: ['partes', 'alias', 'showAlias'],
    mixins: [ Utils ],
    data() {
      return {
        delParteAccidentes: null,
        fields: [
          { key: 'id', label: 'ID', class: 'text-uppercase', sortable: true },
          { key: 'fecha_parte', label: 'F.Parte', class: 'text-center', sortable: true },
          { key: 'fecha_accidente', label: 'F.Accidente', class: 'text-center', sortable: true },
          { key: 'parte_cuerpo', label: 'Parte del cuerpo', class: 'text-left', sortable: true },
          { key: 'is_baja', label: 'Baja', class: 'text-center', sortable: true },
          { key: 'is_recaida', label: 'Recaída', class: 'text-center', sortable: true },
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
        delPAccidente: 'med2/delPAccidente',

        getPDelta: 'med2/getPDelta',
        resetPDelta: 'med2/resetPDelta',
        setPAccidente: 'med2/setPAccidente',
        setPDelta: 'med2/setPDelta',
        setPelotari: 'med2/setPelotari',
      }),
      deleteParteAccidente(entrada) {
        this.delPAccidente(this.delParteAccidentes.id).then( (res) => {
          this.delParteAccidentes = null;
          this.showSnackbar("Parte de Accidente eliminado");
          this.setPelotari( res );
          this.$refs.delParteModal.hide();
        });
        this.$refs.delParteModal.hide();
      },
      focusModal() {
        this.$refs.focusThis.focus();
      },
      hideParteAccidenteModal() {
        this.$refs.delParteModal.hide();
      },
      onClickDeleteParte(data) {
        this.delParteAccidentes = data;
        this.$refs.delParteModal.show();
      },
      onClickEditParte(data) {
        if( this.showAlias) {
          this.setPelotari(data);
        }
        this.getPDelta(data.id).then( (delta) => {
          this.setPAccidente(data);
          if( delta ) {
            this.setPDelta(delta);
          } else {
            this.resetPDelta();
          }
          this.$root.$emit('edit-parte-accidente', data.id);
          this.$root.$emit('bv::show::modal', 'modal-parte-accidente', '#btnNewPAccidente');
        });
      },
    },
    components: {
      BotonNewAccidente,
      ModalParteAccidente
    }
  }
</script>
