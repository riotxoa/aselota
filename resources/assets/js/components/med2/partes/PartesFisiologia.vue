<template>
  <div>
    <b-row>
      <b-col cols="12" class="my-3">
        <BotonNewFisiologia />
      </b-col>
      <b-col cols="12">
        <b-table striped hover small responsive
          :items="pelotari.fisiologia"
          :fields="fields"
        >
          <template slot="fecha_asistencia" slot-scope="row">
            {{ formatDateES(row.item.fecha_asistencia) }}
          </template>
          <template slot="fecha_sintomas" slot-scope="row">
            {{ formatDateES(row.item.fecha_sintomas) }}
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
    <b-modal id="delParte" ref="delParteModal" title="Eliminar Parte de Fisiología" size="md" @shown="focusModal" hide-footer lazy>
        <b-row class="mx-0">
          Se va a eliminar el siguiente Parte de Fisiología.
        </b-row>
        <b-row v-if="delParteFisiologia" class="mx-0">
          <ul class="mt-3">
            <li><strong>Pelotari</strong>: {{ pelotari.alias }}</li>
            <li><strong>F.Asistencia</strong>: {{ formatDateES(delParteFisiologia.fecha_asistencia) }}</li>
            <li><strong>F.Síntomas</strong>: {{ formatDateES(delParteFisiologia.fecha_sintomas) }}</li>
          </ul>
        </b-row>
        <b-row class="mx-0">
          ¿Está seguro?
        </b-row>
        <hr/>
        <b-row class="mx-0 float-right">
          <b-btn class="mr-3" variant="primary" @click.stop="deleteParteFisiologia">Borrar</b-btn>
          <b-btn variant="default" ref="focusThis" @click.stop="hideParteFisiologiaModal">Cancelar</b-btn>
        </b-row>
    </b-modal>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Utils from '../../utils/utils.js';
  import BotonNewFisiologia from './fisiologia/BotonNewFisiologia'

  export default {
    mixins: [ Utils ],
    data() {
      return {
        delParteFisiologia: null,
        fields: [
          { key: 'id', label: 'ID', class: 'text-uppercase', sortable: true },
          { key: 'fecha_asistencia', label: 'F.Asistencia', class: 'text-center', sortable: true },
          { key: 'fecha_sintomas', label: 'F.Síntomas', class: 'text-center', sortable: true },
          { key: 'actions', label: 'Acciones', sortable: false, class: "text-center" },
        ],
      }
    },
    computed: mapGetters({
      pelotari: 'med2/pelotari'
    }),
    methods: {
      ...mapActions({
        delPFisiologia: 'med2/delPFisiologia',
        setPFisiologia: 'med2/setPFisiologia',
        setPelotari: 'med2/setPelotari',
      }),
      deleteParteFisiologia(entrada) {
        this.delPFisiologia(this.delParteFisiologia.id).then( (res) => {
          this.delParteFisiologia = null;
          this.showSnackbar("Parte de Fisiología eliminado");
          this.setPelotari( res );
          this.$refs.delParteModal.hide();
        });
        this.$refs.delParteModal.hide();
      },
      focusModal() {
        this.$refs.focusThis.focus();
      },
      hideParteFisiologiaModal() {
        this.$refs.delParteModal.hide();
      },
      onClickDeleteParte(data) {
        this.delParteFisiologia = data;
        this.$refs.delParteModal.show();
      },
      onClickEditParte(data) {
        this.setPFisiologia(data);
        this.$root.$emit('edit-parte-fisiologia', data.id);
        this.$root.$emit('bv::show::modal', 'modal-parte-fisiologia', '#btnNewPFisiologia');
      },
    },
    components: {
      BotonNewFisiologia
    }
  }
</script>
