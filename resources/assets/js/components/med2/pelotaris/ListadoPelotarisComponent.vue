<template>
  <div v-if="show">
    <h4 class="mb-3 font-weight-bold text-center text-uppercase">{{ title }}</h4>
    <b-table striped hover small responsive
      :items="pelotaris"
      :fields="fields"
    >

      <template slot="accidentes" slot-scope="row">
        {{ row.item.accidente.length ? row.item.accidente.length : 0 }}
      </template>
      <template slot="fecha_ult_acc" slot-scope="row">
        {{ row.item.accidente.length ? formatDateES(row.item.accidente[0].fecha_parte) : '--' }}
      </template>

      <template slot="enfermedades" slot-scope="row">
        {{ row.item.enfermedad.length ? row.item.enfermedad.length : 0 }}
      </template>
      <template slot="fecha_ult_enf" slot-scope="row">
        {{ row.item.enfermedad.length ? formatDateES(row.item.enfermedad[0].fecha_asistencia) : '--' }}
      </template>

      <template slot="prevenciones" slot-scope="row">
        {{ row.item.prevencion.length ? row.item.prevencion.length : 0 }}
      </template>
      <template slot="fecha_ult_pre" slot-scope="row">
        {{ row.item.prevencion.length ? formatDateES(row.item.prevencion[0].fecha_asistencia) : '--' }}
      </template>

      <template slot="fisiologias" slot-scope="row">
        {{ row.item.fisiologia.length ? row.item.fisiologia.length : 0 }}
      </template>
      <template slot="fecha_ult_fis" slot-scope="row">
        {{ row.item.fisiologia.length ? formatDateES(row.item.fisiologia[0].fecha_asistencia) : '--' }}
      </template>

      <template slot="actions" slot-scope="row">
        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
        <b-button-group>
          <b-button size="sm" variant="primary" @click.stop="edit(row.item.id, row.index)" title="Editar">
            <span class="icon voyager-edit"></span>
          </b-button>
        </b-button-group>
      </template>

    </b-table>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Utils from '../../utils/utils.js';

  export default {
    props: [ 'promesas' ],
    mixins: [Utils],
    data() {
      return {
        fields: [
          { key: 'alias', label: 'Pelotari', class: 'text-uppercase', sortable: true },
          { key: 'accidentes', label: 'Accidentes', class: 'text-center', variant: 'danger', sortable: true },
          { key: 'fecha_ult_acc', label: 'Últ.P.Acc.', class: 'text-center', variant: 'danger', sortable: true },
          { key: 'enfermedades', label: 'Enfermedad', class: 'text-center', variant: 'info', sortable: true },
          { key: 'fecha_ult_enf', label: 'Últ.P.Enf.', class: 'text-center', variant: 'info', sortable: true },
          { key: 'prevenciones', label: 'Prevención', class: 'text-center', variant: 'warning', sortable: true },
          { key: 'fecha_ult_pre', label: 'Últ.P.Prev.', class: 'text-center', variant: 'warning', sortable: true },
          { key: 'fisiologias', label: 'Fisiología', class: 'text-center', variant: 'success', sortable: true },
          { key: 'fecha_ult_fis', label: 'Últ.P.Fis.', class: 'text-center', variant: 'success', sortable: true },
          { key: 'actions', label: 'Acciones', sortable: false, class: "text-center" },
        ],
        pelotaris: null,
        show: false,
        title: ''
      }
    },
    computed: {
      ...mapGetters({
        p_profesionales: 'med2/pelotaris',
        p_promesas: 'med2/promesas'
      })
    },
    created() {
      if( this.promesas ) {
        this.getAllPromesas().then( () => {
          this.title = "Pelotaris Promesas";
          this.pelotaris = this.p_promesas;
          this.show = true;
        } );
      } else {
        this.getAllPelotaris().then( () => {
          this.title = "Pelotaris Profesionales";
          this.pelotaris = this.p_profesionales;
          this.show = true;
        } );
      }
    },
    methods: {
      ...mapActions({
        getAllPelotaris: 'med2/getAllPelotaris',
        getAllPromesas: 'med2/getAllPromesas'
      }),
      edit(id, index) {
        this.$router.push({
          path: '/modulo-medico/pelotari/' + id + '/edit/',
          // params: { pelotari: this.pelotaris[index], promesas: this.promesa }
        });
      }
    }
  }
</script>
