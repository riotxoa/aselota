<template>
  <b-row id="sesionesView" class="bg-white border px-0 py-3">
    <b-col cols="12" class="text-center">
      <h4 class="border-bottom font-weight-bold mb-4 pb-3 text-uppercase text-info">Próximas sesiones</h4>
    </b-col>
    <b-col cols="12" class="">
      <hooper :settings="hooperSettings" style="height:auto;">
        <slide v-for="(resource, resourceIndex) in resources" :key="resourceIndex" :index="resourceIndex" class="border mx-1 p-3" style="min-height:225px;">
          <div class="clickable cursor-pointer" style="min-height:95px" @click="onClickSesion(resource)">
            <p class="mb-0 small text-center text-uppercase">{{ resource.weekday }}</p>
            <p class="font-weight-bold initialism mb-0 text-center">{{ resource.fecha }}</p>
            <p class="initialism mb-0 text-center">{{ resource.hora }}</p>
            <p class="font-weight-bold mb-0 small text-center text-capitalize">{{ resource.fronton }}</p>
          </div>
          <div class="accordion mt-4" role="tablist">
            <b-card no-body class="mb-2">
              <b-button block v-b-toggle="'accordion-pelotaris-' + resourceIndex" variant="outline-success" size="sm">Pelotaris</b-button>
              <b-collapse :id="'accordion-pelotaris-' + resourceIndex" role="tabpanel">
                <b-card-body class="border-bottom pb-2 pt-0 px-3">
                  <b-card-text>
                    <ul class="mt-3 pl-2 text-left">
                      <li v-for="(pelotari, pelotariIndex) in resource.pelotaris" v-bind:key="pelotariIndex" class="initialism">
                        {{ pelotari }}
                      </li>
                    </ul>
                  </b-card-text>
                </b-card-body>
              </b-collapse>
            </b-card>
            <b-card no-body class="mb-2">
              <b-button block v-b-toggle="'accordion-material-' + resourceIndex" variant="outline-info" size="sm">Material</b-button>
              <b-collapse :id="'accordion-material-' + resourceIndex" role="tabpanel">
                <b-card-body class="border-bottom pb-2 pt-0 px-3">
                  <b-card-text>
                    <ul class="mt-3 pl-2 text-left">
                      <li v-for="(material, materialIndex) in resource.materiales" v-bind:key="materialIndex" class="initialism">
                        {{ material }}
                      </li>
                    </ul>
                  </b-card-text>
                </b-card-body>
              </b-collapse>
            </b-card>
          </div>
        </slide>
        <hooper-navigation slot="hooper-addons"></hooper-navigation>
      </hooper>
    </b-col>
  </b-row>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import functions from '../functions.js';
  import { sesion } from '../functions.js';

  import { Hooper, Slide, Navigation as HooperNavigation } from 'hooper';
  import 'hooper/dist/hooper.css';

  export default {
    mixins: [ functions, sesion ],
    data() {
      return {
        hooperSettings: {
          itemsToShow: 1,
          keysControl: false,
          wheelControl: false,
          breakpoints: {
            400: {
              itemsToShow: 2
            },
            600: {
              itemsToShow: 3
            },
            768: {
              itemsToShow: 2
            },
            990: {
              itemsToShow: 3
            },
            1200: {
              itemsToShow: 4
            }
          }
        },
        items: {},
        resources: [],
      }
    },
    computed: {
      ...mapState({
        sesiones: state => state.plen.sesiones
      })
    },
    created() {
      const today = Date.now();
      this.getActiveItemsThisYear(today).then( (items) => {
        this.items = items;
        this.loadResources(today);
      });
    },
    methods: {
      ...mapActions({
        getActiveItemsThisYear: 'plen/getActiveItemsThisYear',
        setMacrociclo: 'plen/setMacrociclo',
        setMesociclo: 'plen/setMesociclo',
        setMicrociclo: 'plen/setMicrociclo',
        setSesion: 'plen/setSesion',
      }),
      loadResources(date) {
        const fecha = this.getFechaDB(new Date(date));
        let resources = [];
        let resource = {};
        let sesiones = _.filter(this.items.sesiones, (o) => {
          return o.fecha >= fecha;
        });
        sesiones = _.orderBy(sesiones, ['fecha', 'hora']);

        sesiones.map( (sesion) => {
          resource = {
            id: sesion.id,
            fecha: this.getFechaES(sesion.fecha),
            weekday: this.getFechaWeekday(sesion.fecha),
            hora: (sesion.hora ? sesion.hora.substr(0,5) : '---'),
            fronton: sesion.fronton,
            pelotaris: [],
            materiales: []
          };

          // Recuperar pelotaris vinculados a cada sesion
          const pelotaris = _.filter(this.items.pelotaris, { sesion_id: sesion.id });
          pelotaris.map( (pelotari) => {
            pelotari.ejercicios = [];
            // Recuperar ejercicios vinculados a cada pelotari
            const ejercicios = _.filter(this.items.ejercicios, { sesion_pelotari_id: pelotari.id });
            ejercicios.map( (ejercicio)  => {
              pelotari.ejercicios.push(ejercicio.name);
              // Incluimos material dentro de la ficha de recurso del día
              if( ejercicio.material && ejercicio.material.length ) {
                resource.materiales.push(ejercicio.material);
              }
            });

            resource.pelotaris.push(pelotari.alias);
          });

          this.resources.push(resource);
        })
      },
      onClickSesion(resource) {
        const sesion_id = resource.id;
        let pelotaris = _.filter(this.items.pelotaris, { sesion_id: sesion_id });
        let sesion = _.find(this.items.sesiones, { id: sesion_id });
        const microciclo = _.find(this.items.microciclos, { id: sesion.microciclo_id });
        const mesociclo = _.find(this.items.mesociclos, { id: microciclo.mesociclo_id });
        const macrociclo = _.find(this.items.macrociclos, { id: mesociclo.macrociclo_id });

        pelotaris.map( (pelotari) => {
          const ejercicios = _.filter(this.items.ejercicios, { sesion_pelotari_id: pelotari.id });
          pelotari.ejercicios = ejercicios;
        })
        sesion.pelotaris = pelotaris;

        this.setMacrociclo( macrociclo );
        this.setMesociclo( mesociclo );
        this.setMicrociclo( microciclo );
        this.setSesion( sesion );
        this.setEditSesion( sesion );

        this.$root.$emit("bv::show::modal", "editSesion");
        this.$root.$emit("enable-modal-sesion-readonly");
      }
    },
    components: {
      Hooper,
      Slide,
      HooperNavigation
    }
  }
</script>

<style scoped>
  #sesionesView .clickable:hover {
    box-shadow:rgba(225,225,225,0.5) 2px 3px 15px 4px;
  }
</style>
