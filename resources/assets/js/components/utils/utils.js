import moment from 'moment';

var Utils = {

  methods: {

    formatDateES (date) {
      if (date)
        return moment(String(date)).format('DD/MM/YYYY');
      else {
        return moment().format('DD/MM/YYYY');
      }
    },

    formatDateEN (date) {
      if (date)
        return moment(String(date)).format('YYYY-MM-DD');
      else
        return moment().format('YYYY-MM-DD');
    },

    showSnackbar (msg) {
      // Get the snackbar DIV
      var x = document.getElementById("snackbar");

      // Add the "show" class to DIV
      x.className = "show";
      x.innerHTML = msg;

      // After 3 seconds, remove the show class from DIV
      setTimeout(function(){ x.className = x.className.replace("show", ""); }, 1500);
    },

  }

}
export default Utils;
