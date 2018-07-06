<template>
  <div class="container-fluid">
    <h1>Festivales</h1>
  </div>
</template>

<script>
  const showSnackbar = (msg) => {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";
    x.innerHTML = msg;

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  }
  export default {
      data () {
        return {

        }
      },
      created() {

      },
      methods: {
        
      }
  }
</script>
