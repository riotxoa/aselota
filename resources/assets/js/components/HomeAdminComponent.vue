<template>
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-12">
              <div class="card">
                  <div class="card-header bg-primary">
                    <div class="row">
                      <div class="col-12 font-weight-bold text-uppercase text-center">
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
    data () {
      return {
        home: '/administrador',
        title: 'Administrador PilotApp',
      }
    }
  }
</script>

<style>
  .home-icon {
    text-align:right;
  }
  .home-icon a {
    color:white;

    -webkit-transition:all .15s ease-in-out;
    -moz-transition:all .15s ease-in-out;
    -ms-transition:all .15s ease-in-out;
    -o-transition:all .15s ease-in-out;
    transition:all .15s ease-in-out;
  }
  .home-icon a:active,
  .home-icon a:focus,
  .home-icon a:hover {
    color:rgba(255,255,255,.5)!important;
    text-decoration:none;
  }
</style>
