<template>
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-12">
              <div class="card">
                  <div class="card-header bg-primary">
                    <div class="row">
                      <div class="col-sm-8 col-md-9 col-lg-10 text-white">
                        Editar Perfil de Usuario
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <b-form @submit="onSubmit" @reset="onReset" v-if="show">
                      <b-row>
                        <div class="col-md-2">
                          <img :src="image" class="img-responsive" style="width:100%;">
                        </div>
                        <div class="col-md-10">
                          <div class="row">
                            <b-form-group label="Nombre:"
                                          label-for="nameInput"
                                          class="col-sm-10 col-md-7">
                              <b-form-input id="nameInput"
                                            type="text"
                                            v-model="user.name"
                                            maxlength="50"
                                            required
                                            placeholder="Nombre">
                              </b-form-input>
                            </b-form-group>
                            <b-form-group label="Correo electrónico:"
                                          label-for="emailInput"
                                          class="col-sm-10 col-md-7">
                              <b-form-input id="emailInput"
                                            type="email"
                                            v-model="user.email"
                                            maxlength="50"
                                            required
                                            placeholder="user@example.com">
                              </b-form-input>
                            </b-form-group>
                            <b-form-group label="Contraseña:"
                                          label-for="pwdInput"
                                          class="col-sm-10 col-md-7">
                              <b-form-input id="pwdInput"
                                            type="password"
                                            v-model="user.password"
                                            maxlength="50">
                              </b-form-input>
                            </b-form-group>
                            <b-form-group label="Fotografía"
                                          class="col-sm-10 col-md-7">
                              <b-form-file class="mt-3"
                                           v-on:change="onPhotoChange"
                                           accept=".jpg"
                                           plain>
                              </b-form-file>
                            </b-form-group>
                          </div>
                          <hr/>
                          <b-button type="submit" variant="primary">Guardar</b-button>
                          <b-button variant="default" @click="onCancel">Cancelar</b-button>
                          <b-button type="reset" variant="danger" class="float-right">Reset</b-button>
                        </div>
                      </b-row>
                    </b-form>
                  </div>
              </div>
          </div>
      </div>
      <div id="snackbar"></div>
  </div>
</template>

<script>
  const goBack = () => {
    window.location.href = '/';
  }
  const showSnackbar = (msg) => {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";
    x.innerHTML = msg;

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); goBack(); }, 500);
  }
  export default {
    props: ['userId', 'userName', 'userEmail', 'userAvatar'],
    data () {
      return {
        user: {
          id: this.userId,
          name: this.userName,
          email: this.userEmail,
          password: '',
          fotoName: null,
        },
        file: null,
        image: this.userAvatar,
        show: true
      }
    },
    methods: {
      onPhotoChange(e) {
          let files = e.target.files || e.dataTransfer.files;
          if (!files.length)
              return;
          this.createImage(files[0]);
      },
      createImage(file) {
          let reader = new FileReader();
          let vm = this;
          reader.onload = (e) => {
            vm.image = e.target.result;
            this.file = file;
            this.user.fotoName = file.name;
          };
          reader.readAsDataURL(file);
      },
      onSubmit (evt) {
        evt.preventDefault();

        const config = { headers: { 'Content-Type': 'multipart/form-data' } };

        let uri = '/www/users';
        let data = new FormData();

        data.append('user', JSON.stringify(this.user));
        if(this.file)
          data.append('photo', this.file);

        this.axios.post(uri + '/' + this.user.id + '/update', data, config)
          .then((response) => {
            showSnackbar("Perfil actualizado");
          })
          .catch((error) => {
            console.log(error);
            showSnackbar("Se ha producido un ERROR");
          });
      },
      onReset (evt) {
        evt.preventDefault();
        /* Reset our form values */
        this.user.name = this.userName;
        this.user.email = this.userEmail;
        this.user.password = null;
        this.user.fotoName = null;
        this.file = null;
        this.image = this.userAvatar;
        /* Trick to reset/clear native browser form validation state */
        this.show = false;
        this.$nextTick(() => { this.show = true });
      },
      onCancel (evt) {
        goBack();
      }
    }
  }
</script>
