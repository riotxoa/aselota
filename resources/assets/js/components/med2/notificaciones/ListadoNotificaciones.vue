<template>
  <div v-if="show">
    <b-table striped hover small responsive
      :items="notificaciones"
      :fields="fields"
      :current-page="currentPage"
      :per-page="perPage"
    >
      <template slot="created_at" slot-scope="row">
        <div style="line-height:1">
          <p class="m-0">{{ formatDateES(row.item.created_at) }}</p>
          <p class="m-0"><small>{{ formatHour(row.item.created_at) }}</small></p>
        </div>
      </template>
      <template slot="remitente" slot-scope="row">
        <div style="line-height:1">
          <p class="m-0">{{ row.item.from_user_name}}</p>
          <p class="m-0"><small>{{ row.item.from_user_email }}</small></p>
        </div>
      </template>
      <template slot="destinatario" slot-scope="row">
        <div style="line-height:1">
          <p class="m-0"><strong>{{ row.item.to_user_name}}</strong></p>
          <p class="m-0"><small><strong>{{ row.item.to_user_role }}</strong></small></p>
          <p class="m-0"><small>{{ row.item.to_user_email }}</small></p>
        </div>
      </template>
      <template slot="disponible" slot-scope="row">
        <div v-if="JSON.parse(row.item.data).disponible">
          <i class="fas fa-check-circle"></i>
        </div>
        <div v-else>
          <b-row>
            <b-col cols="3" class="px-0">
              <i class="fas fa-times-circle"></i>
            </b-col>
            <b-col cols="9" class="px-0 text-left">
              <p class="m-0" style="line-height:1"><small><strong>Desde:</strong>&nbsp;{{ formatDateES(JSON.parse(row.item.data).date_from) }}</small></p>
              <p class="m-0" style="line-height:1"><small><strong>Hasta:</strong>&nbsp;{{ formatDateES(JSON.parse(row.item.data).date_to) }}</small></p>
            </b-col>
          </b-row>
        </div>
      </template>
      <template slot="texto" slot-scope="row">
        <div style="font-size:13px; line-height:1.35; max-width:200px;">{{ JSON.parse(row.item.data).texto }}</div>
      </template>
      <template slot="leida" slot-scope="row">
        <div v-if="row.item.read_at">
          <p class="m-0" style="line-height:1"><i class="fas fa-check-circle"></i></p>
          <p class="m-0" style="line-height:1"><small>{{ formatDateES(row.item.read_at) }}</small></p>
        </div>
        <div v-else>
          <i class="fas fa-times-circle"></i>
        </div>
      </template>
      <template slot="actions" slot-scope="row">
        <b-button-group>
          <b-button size="sm" variant="secondary" @click.stop="row.toggleDetails" title="Mostrar/Ocultar Detalle">
            <span class="icon" v-bind:class="{ 'voyager-x': row.detailsShowing, 'voyager-eye': !row.detailsShowing }"></span>
          </b-button>
        </b-button-group>
      </template>
      <template slot="row-details" slot-scope="row">
        <b-card>
          <b-row>
            ROW details
          </b-row>
        </b-card>
      </template>
    </b-table>
    <b-pagination
      class="float-right"
      v-model="currentPage"
      :total-rows="notificaciones.length"
      :per-page="perPage"
    ></b-pagination>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Utils from '../../utils/utils.js';

  export default {
    props: [ 'pelotari' ],
    mixins: [ Utils ],
    data() {
      return {
        currentPage: 1,
        perPage: 10,
        fields: [
          { key: 'created_at', label: 'Fecha', class: 'align-middle text-center', sortable: true },
          { key: 'pelotari_alias', label: 'Pelotari', class: 'align-middle font-weight-bold text-left text-uppercase', sortable: true },
          { key: 'remitente', label: 'Remitente', class: 'align-middle text-left', sortable: true },
          { key: 'destinatario', label: 'Destinatario', class: 'align-middle text-left', sortable: true },
          { key: 'disponible', label: 'Pelotari Disponible', class: 'align-middle text-center', sortable: true },
          { key: 'texto', label: 'Texto', class: 'align-middle text-left', sortable: false },
          { key: 'leida', label: 'Leída', class: 'align-middle text-center', sortable: true },
          // { key: 'actions', label: 'Acciones', sortable: false, class: "align-middle text-center" },
        ],
        show: false
      }
    },
    computed: mapGetters({
      notificaciones: "med2/notificaciones",
    }),
    created() {
      if( this.pelotari ) {
        this.fields.splice(1, 1);
        this.getNotificacionesByPelotariID(this.pelotari).then( () => {
          this.show = true;
        });
      } else {
        this.getAllNotificaciones().then( () => {
          this.show = true;
        });
      }
    },
    methods: {
      ...mapActions({
        getAllNotificaciones: 'med2/getAllNotificaciones',
        getNotificacionesByPelotariID: 'med2/getNotificacionesByPelotariID'
      })
    }
  }
</script>

<style scoped>
  .fa-check-circle {
    color:green;
  }
  .fa-times-circle {
    color:red;
  }
</style>
