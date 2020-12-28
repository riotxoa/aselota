<template>
  <div class="main-header">
    <div class="toolbar mb-0 py-1">
      <div class="container">
        <b-row>
          <div class="col-sm-3 text-left home-icon">
            <b-button v-if="routerHistory" size="sm" variant="info" @click="goBack()" class="mt-1 text-white"><i class="fas fa-chevron-left" aria-hidden="true"></i></b-button>
          </div>
          <h4 class="col-sm-6 text-white font-weight-bold">PARTES MÉDICOS</h4>
          <div class="col-sm-3 text-right home-icon">
            <b-button v-if="this.$route.params.userRole === 'admin'" size="sm" variant="outline" href="/" class="mt-1"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Admin Menú</b-button>
          </div>
        </b-row>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        routerHistory: false
      }
    },
    created() {
      this.routerHistory = ( window.history.length > 1 ? true : false );
    },
    methods: {
      goBack() {
        this.$router.go(-1);
      }
    }
  }
</script>

<style scoped>
  .main-header {
    margin-bottom:2rem;
    margin-top:-1.45rem;
  }
  .main-header .toolbar {
    background-color:slategray;
    padding:10px 0;
    text-align:center;
  }
  .main-header h4 {
    line-height:1.75;
    margin:0 auto;
  }
</style>
