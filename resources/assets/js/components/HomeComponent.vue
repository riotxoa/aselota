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
