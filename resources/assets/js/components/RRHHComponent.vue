<template>
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-12">
              <div class="card">
                  <div class="card-header bg-primary">
                    <div class="row">
                      <div class="col-sm-8 col-md-9 col-lg-10">
                        <router-link class="text-white" to="/pelotaris">Mantenimiento de Pelotaris</router-link>
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
  
</script>
