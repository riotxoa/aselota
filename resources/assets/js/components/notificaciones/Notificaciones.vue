<template>
  <div id="notificationArea" v-if="show && notificaciones.length">
    <b-row class="titlebar" v-bind:style="{ opacity: titleStyle.opacity }">
      <b-col cols="2">
      </b-col>
      <b-col cols="8">
        <p class="font-weight-bold m-0 py-1 small text-uppercase">Tienes {{ totalUnread }} notificaciones sin leer</p>
      </b-col>
      <b-col cols="2" class="text-right">
        <i class="cursor-pointer fas fa-times" title="Ocultar" v-on:click="toggleListado()"></i>
      </b-col>
    </b-row>
    <b-row class="listado" v-bind:style="{ maxHeight: listadoStyle.height, opacity: listadoStyle.opacity, padding: listadoStyle.padding }">
      <b-col cols="12">
        <div class="accordion" role="tablist">
          <b-card v-for="(notificacion, index) in notificaciones" :key="notificacion.id" no-body class="small">
            <b-card-header header-tag="header" role="tab" class="py-1" style="margin-bottom:-1px;">
              <div v-b-toggle="getAccordionId(index)" class="cursor-pointer" title="Mostrar/Ocultar contenido">
                <b-row class="px-1">
                  <b-col cols="9" class="px-0">
                    <div v-if="JSON.parse(notificacion.data).disponible" v-bind:class="[ null == notificacion.read_at ? 'unread' : 'read' ]">
                      SITUACIÓN MÉDICA DE {{ notificacion.pelotari_alias }}
                    </div>
                    <div v-else v-bind:class="[ null == notificacion.read_at ? 'unread' : 'read' ]">
                      {{ notificacion.pelotari_alias }} NO DISPONIBLE
                    </div>
                  </b-col>
                  <b-col cols="3" class="px-0" v-bind:class="[ null == notificacion.read_at ? 'unread' : 'read' ]">
                    {{ formatDateES(notificacion.created_at) }}
                  </b-col>
                </b-row>
              </div>
            </b-card-header>
            <b-collapse :id="getAccordionId(index)" accordion="my-accordion" role="tabpanel" class="p-2">
              <p class="m-0"><strong>Remitente:</strong>&nbsp;{{ notificacion.from_user_name }}&nbsp;<{{ notificacion.from_user_email }}></p>
              <p><strong>Pelotari:</strong>&nbsp;{{ notificacion.pelotari_alias }}</p>
              <div v-if="JSON.parse(notificacion.data).disponible">
                <p>El Departamento Médico de Baiko Pilota le envía la siguiente actualización de la situación médica del pelotari <strong>{{ notificacion.pelotari_alias }}</strong>:</p>
                <p class="text-monospace mx-4">{{ JSON.parse(notificacion.data).texto }}</p>
                <!-- <p>El pelotari sigue activo y disponible para su convocatoria en festivales, entrenamientos, etc.</p> -->
              </div>
              <div v-else>
                <!-- <p>El Departamento Médico de Baiko Pilota comunica que el pelotari <strong>{{ notificacion.pelotari_alias }}</strong> no estará disponible para su convocatoria en festivales, entrenamientos, etc. en el siguiente rango de fechas:</p> -->
                <p>El Departamento Médico de Baiko Pilota comunica que el pelotari <strong>{{ notificacion.pelotari_alias }}</strong> no estará disponible durante el siguiente rango de fechas:</p>
                <ul>
                  <li>Desde: <strong>{{ formatDateES(JSON.parse(notificacion.data).date_from) }}</strong></li>
                  <li>Hasta: <strong>{{ formatDateES(JSON.parse(notificacion.data).date_to) }}</strong></li>
                </ul>
                <p>Esta es la información adicional disponible sobre su situación:</p>
                <p class="text-monospace mx-4">{{ JSON.parse(notificacion.data).texto }}</p>
              </div>
              <b-row v-if="notificacion.read_at">
                <b-col cols="6">
                  <small class="text-danger">Leído el {{ formatDateES(notificacion.read_at) }}.</small>
                </b-col>
                <b-col cols="6">
                  <b-button size="sm" class="float-right small text-uppercase" variant="danger" v-on:click="markAsUnread(index)">
                    <small><strong>Marcar como NO leído</strong></small>
                  </b-button>
                </b-col>
              </b-row>
              <b-button v-else size="sm" class="float-right small text-uppercase" variant="primary" v-on:click="markAsRead(index)">
                <small><strong>Marcar como leído</strong></small>
              </b-button>
            </b-collapse>
          </b-card>
        </div>
      </b-col>
    </b-row>
    <b-row class="icono w-100">
      <i class="cursor-pointer far fa-bell kanpaia" v-on:click="toggleListado()" title="Mostrar/Ocultar notificaciones"></i>
      <div v-if="totalUnread" class="cursor-pointer text-center total-unread" title="Tienes notificaciones sin leer" v-on:click="toggleListado()">
        {{ totalUnread }}
      </div>
      <div v-if="!totalUnread && notificaciones.length" class="cursor-pointer text-center total-read" title="No tienes notificaciones pendientes de lectura" v-on:click="toggleListado()">
        {{ notificaciones.length }}
      </div>
    </b-row>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import _ from 'lodash';
  import $ from 'jquery';
  import Utils from '../utils/utils.js';

  export default {
    mixins: [ Utils ],
    data() {
      return {
        listadoIsOpen: true,
        listadoStyle: {
          height: null,
          padding: null,
          opacity: null,
        },
        read: [],
        show: false,
        titleStyle: {
          opacity:null,
        },
        totalUnread: 0,
        unread: [],
      }
    },
    computed: {
      ...mapGetters({
        notificaciones: 'notificaciones/notificaciones'
      })
    },
    methods: {
      ...mapActions({
        getNotifications: 'notificaciones/getNotificacionesByUserID',
        readNotificacion: 'notificaciones/readNotificacion',
        setNotificaciones: 'notificaciones/setNotificaciones',
        unreadNotificacion: 'notificaciones/unreadNotificacion'
      }),
      closeListado() {
        this.collapseAll();

        this.listadoStyle.height = 0;
        this.listadoStyle.padding = 0;
        this.listadoStyle.opacity = 0;

        this.titleStyle.opacity = 0;

        this.listadoIsOpen = false;
      },
      collapseAll() {
        var accordionId = $('.collapse.show').attr('id');
        this.$root.$emit('bv::toggle::collapse', accordionId);
      },
      filterReadNotifications() {
        var read = _.filter(this.notificaciones, (o) => {
          return o.read_at !== null;
        });
        _.orderBy(read, ['created_at', 'desc']);
        return read;
      },
      filterUnreadNotifications() {
        var unread = _.filter(this.notificaciones, { 'read_at': null });
        _.orderBy(unread, ['created_at', 'desc']);
        return unread;
      },
      getAccordionId( index )  {
        return "accordion-" + index;
      },
      markAsRead(index) {
        const notificacion_id = this.notificaciones[index].id;
        this.readNotificacion(notificacion_id).then( () => {
          this.notificaciones[index].read_at = new Date().toLocaleString('en-US');
          this.totalUnread--;
        });
      },

      markAsUnread(index) {
        const notificacion_id = this.notificaciones[index].id;
        this.unreadNotificacion(notificacion_id).then( () => {
          this.notificaciones[index].read_at = null;
          this.totalUnread++;
        });
      },
      openListado() {
        this.listadoStyle.height = '50vh';
        this.listadoStyle.padding = '20px';
        this.listadoStyle.opacity = 1;

        this.titleStyle.opacity = 1;

        this.listadoIsOpen = true;
      },
      orderNotificaciones() {
        const read = this.filterReadNotifications();
        const unread = this.filterUnreadNotifications();
        const allNotifications = unread.concat(read);

        this.totalUnread = unread.length;

        this.setNotificaciones(allNotifications);
      },
      toggleListado() {
        if( this.listadoIsOpen ) {
          this.closeListado();
        } else {
          this.openListado();
        }
      },
    },
    created() {
      this.getNotifications().then( () => {
        this.orderNotificaciones();
        if( this.totalUnread > 0 ) {
          this.openListado();
        } else {
          this.closeListado();
        }
        this.show = true;
      });
    }
  }
</script>

<style scoped>
  #notificationArea {
    bottom:50px;
    left:50px;
    position:absolute;
    z-index:9999;
  }

  #notificationArea .total-unread,
  #notificationArea .total-read {
    border-radius:100%;
    bottom:0;
    color:white;
    font-size:12px;
    font-weight:bold;
    height:22px;
    line-height:1;
    padding:5px;
    position:absolute;
    width:22px;
  }
  #notificationArea .total-unread {
    background-color:red;
  }
  #notificationArea .total-read {
    background-color:slategray;
  }
  #notificationArea .card-header {
    background-color:#f4f9e8;

    -webkit-transition:all .15s ease-in-out;
    -moz-transition:all .15s ease-in-out;
    -ms-transition:all .15s ease-in-out;
    -o-transition:all .15s ease-in-out;
    transition:all .15s ease-in-out;
  }
  #notificationArea .card-header:active,
  #notificationArea .card-header:focus,
  #notificationArea .card-header:hover {
    background-color:#e1f1b9;
  }
  #notificationArea .card-header .read {
    color:slategrey;
  }
  #notificationArea .card-header .unread {
    font-weight:bold;
  }
  #notificationArea .kanpaia {
    background-color:#94c11f;
    border-radius:100%;
    color:#ffffff;
    font-size:40px;
    padding:1rem;

    -webkit-transition:all .15s ease-in-out;
    -moz-transition:all .15s ease-in-out;
    -ms-transition:all .15s ease-in-out;
    -o-transition:all .15s ease-in-out;
    transition:all .15s ease-in-out;
  }
  #notificationArea .kanpaia:active,
  #notificationArea .kanpaia:hover,
  #notificationArea .kanpaia:focus {
    opacity:.5;
  }
  #notificationArea .listado,
  #notificationArea .titlebar {
    -webkit-transition:all. 25s ease-in-out;
    -moz-transition:all .25s ease-in-out;
    -ms-transition:all .25s ease-in-out;
    -o-transition:all .25s ease-in-out;
    transition:all .25s ease-in-out;
  }
  #notificationArea .listado {
    background-color:white;
    border:2px solid lightgray;
    border-top:none;
    display:inline-block;
    overflow-x:hidden;
    overflow-y:auto;
    width:400px;
  }
  #notificationArea .titlebar {
    background-color:#94c11f;
    color:white;
  }
</style>
