var Nav = {

  methods: {

    goBack () {
      window.history.length > 1
        ? this.$router.go(-1)
        : this.$router.push('/')
    },

    goRoot () {
      window.location.href = '/';
    },

  }

}
export default Nav;
