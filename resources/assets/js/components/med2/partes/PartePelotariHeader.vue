<template>
  <b-row class="justify-content-center">
    <div class="col-6 col-md-2 pr-0 mb-3 mb-md-0">
      <img id="partePelotariPhoto":src="pelotari.foto" class="img-responsive" style="width:100%;">
    </div>
    <div class="col-6 col-md-10 pl-0 pt-2">
      <h1 class="form-title text-secondary"><strong>{{ pelotari.alias }} <span v-if="pelotari.num_trabajador">({{ pelotari.num_trabajador }})</span></strong> - <small class="text-capitalize">{{ pelotari.posicion }}</small></h1>
      <p class="d-inline-block text-secondary mb-1">
        <i class="far fa-calendar-alt mr-2"></i>{{ pelotari.fecha_nac }} <i class="fas fa-phone ml-3 mr-2"></i>{{ pelotari.telefono }} <i class="far fa-envelope ml-3 mr-2"></i>{{ pelotari.email }}
      </p>
    </div>
  </b-row>
</template>

<script>
  export default {
    props: [ 'pelotari' ]
  }
</script>

<style scoped>
#partePelotariPhoto {
  max-width: 77.5px;
}
</style>
