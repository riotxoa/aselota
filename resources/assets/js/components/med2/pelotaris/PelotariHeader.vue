<template>
  <b-row class="justify-content-center">
    <div class="col-6 col-md-2 col-lg-1 pr-0 mb-3 mb-md-0">
      <img :src="pelotari.foto" class="img-responsive" style="width:100%;">
    </div>
    <div class="col-6 col-md-10 col-lg-11">
      <p class="d-inline-block text-secondary mb-1">Ficha Médica del Pelotari</p>
      <h1 class="form-title text-secondary"><strong>{{ pelotari.alias }} <span v-if="pelotari.num_trabajador">({{ pelotari.num_trabajador }})</span></strong> - <small class="text-capitalize">{{ pelotari.posicion }}</small></h1>
      <p class="d-inline-block text-secondary mb-1">
        <i class="far fa-calendar-alt mr-2"></i>{{ formatDateES(pelotari.fecha_nac) }} <i class="fas fa-phone ml-3 mr-2"></i>{{ pelotari.telefono }} <i class="far fa-envelope ml-3 mr-2"></i>{{ pelotari.email }}
      </p>
    </div>
  </b-row>
</template>

<script>
  import Utils from '../../utils/utils.js';

  export default {
    mixins: [ Utils ],
    props: [ 'pelotari' ]
  }
</script>
