<template>
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-12">
              <div class="card">
                  <div class="card-header bg-primary">
                    <div class="row">
                      <div class="col-sm-8 col-md-9 col-lg-10">
                        <router-link class="text-white" :to="this.home">{{ this.title }}</router-link>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <router-view></router-view>
                  </div>
              </div>
          </div>
      </div>
      <div id="snackbar"></div>
  </div>
</template>

<script>
  export default {
    props: ['userRole'],
    data() {
      return {
        home: '',
        title: '',
      }
    },
    created: function() {
      switch(this.userRole) {
        case 'rrhh':
          this.home = '/rrhh';
          this.title = "Gestión de RRHH";
          this.$router.push('/rrhh');
          break;
        default:
          this.title = "SIN PERMISOS";
          break;
      }
    }
  }
</script>

<style>
  .card,
  .card-header,
  .card-header:first-child,
  .btn,
  .page-item:first-child .page-link,
  .page-item:last-child .page-link,
  .nav-pills a,
  .modal-content,
  .modal-header,
  input, select {
    border-radius:0!important;
  }

  .modal-content {
    border:none;
  }
  .modal-header {
    background-color: #007fff;
    color:white;
    text-transform:uppercase;
  }
</style>
