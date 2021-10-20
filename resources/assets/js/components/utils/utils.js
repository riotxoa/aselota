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

    formatDateShort(date) {
      if( date ) {
        const d = new Date(date)
        const dtf = new Intl.DateTimeFormat('es', { year: 'numeric', month: 'short', day: 'numeric' })
        const [{ value: da },,{ value: mo },,{ value: ye }] = dtf.formatToParts(d)

        return `${da} ${mo}`;
      } else {
        return date;
      }
    },

    formatHour (date) {
      if (date)
        return moment(String(date)).format('HH:mm:ss');
      else
        return moment().format('HH:mm:ss');
    },

    formatFestivalID (id, date) {
      var str_id = String(id);
      while (str_id.length < (4 || 2)) {str_id = "0" + str_id;}
      return 'F' + date.substr(2,2) + '-' + str_id; // 'FXX-YYYY'
    },

    getVideoFrame(video) {
      let src = "";
      if( !video ) {
        return "";
      }
      if( video.includes("youtube") || video.includes("youtu.be") ) {
        src = video;
      }
      if( video.includes("vimeo") ) {
        src = video.replace("https://vimeo.com/", "https://player.vimeo.com/video/");
      }
      return "<iframe src='" + src + "' style='position:absolute;top:0;left:0;width:100%;height:100%;' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
    },

    showPreloader($) {
      jQuery("body").addClass("preloader");
    },

    hidePreloader() {
      setTimeout(function(){ jQuery("body").removeClass("preloader"); }, 250);
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
