<template>
  <div>

    <b-form @submit="onSubmit" @reset="onReset" v-if="show">

      <b-row class="mb-3 bg-danger py-3">
        <div class="col-12 text-white"><strong>{{ pelotariAlias }}</strong></div>
      </b-row>

      <b-row>
        <div class="col-md-12">
          <b-row>
            <b-form-group label="Identificador:"
                          label-for="identificadorInput"
                          class="col-sm-4">
              <b-form-input id="identificadorInput"
                            class="text-left"
                            type="text"
                            v-model="header.name"
                            maxlength="50">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Fecha Inicio:"
                          label-for="fecha_iniInput"
                          class="col-sm-4">
              <b-form-input id="fecha_iniInput"
                            type="date"
                            v-model="header.fecha_ini"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
            <b-form-group label="Fecha Fin:"
                          label-for="fecha_finInput"
                          class="col-sm-4">
              <b-form-input id="fecha_finInput"
                            type="date"
                            v-model="header.fecha_fin"
                            required
                            placeholder="dd/mm/yyyy">
              </b-form-input>
            </b-form-group>
          </b-row>
          <b-row style="border-top:1px solid lightgray; padding-top:1rem;">
            <b-form-group label="Contrato Deportivo"
                          class="col-sm-8">
              <b-row>
                <b-form-file class="mt-0 col-sm-10"
                             v-on:change="onDocChange"
                             accept=".doc, .docx, .pdf, .rtf"
                             plain>
                </b-form-file>
              </b-row>
            </b-form-group>
            <b-form-group v-if="edit && header.file"
                          label="Contrato Deportivo"
                          label-for="fileInput"
                          class="col-sm-4"
                          style="color:transparent;">
              <b-button block size="md" variant="success" class="mt-0 font-weight-bold" v-on:click="downloadContrato()" title="Descargar contrato deportivo"><span class="icon voyager-download mr-2"></span>{{ header.file }}</b-button>
            </b-form-group>
          </b-row>
          <b-row style="border-top:1px solid lightgray; padding-top:1rem;">
            <b-form-group label="Contrato Derechos de Imagen"
                          class="col-sm-8">
              <b-row>
                <b-form-file class="mt-0 col-sm-10"
                             v-on:change="onDocDerechosChange"
                             accept=".doc, .docx, .pdf, .rtf"
                             plain>
                </b-form-file>
              </b-row>
            </b-form-group>
            <b-form-group v-if="edit && header.file_derechos"
                          label="Contrato Derechos de Imagen"
                          label-for="fileDerechosInput"
                          class="col-sm-4"
                          style="color:transparent;">
              <b-button block size="md" variant="success" class="mt-0 font-weight-bold" v-on:click="downloadContratoDerechos()" title="Descargar contrato derechos de imagen"><span class="icon voyager-download mr-2"></span>{{ header.file_derechos }}</b-button>
            </b-form-group>
          </b-row>

        </div>
      </b-row>

      <hr/>

      <b-button type="submit" variant="primary">Guardar</b-button>
      <b-button variant="default" @click="onCancel">Cancelar</b-button>
      <b-button v-if="!edit" type="reset" variant="danger" class="float-right mr-1">Reset</b-button>

    </b-form>

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
    props: ['pelotariId', 'pelotariAlias', 'onCancel', 'getHeaderRow', 'isNewHeader', 'formatAmount'],
    data () {
      return {
        header: {
          id: null,
          pelotari_id: null,
          name: '',
          file: null,
          file_derechos: null,
          fecha_ini: null,
          fecha_fin: null,
          created_at: null,
          updated_at: null,
        },
        edit: false,
        show: true,
        goBack: () => {
          this.onCancel();
        }
      }
    },
    created: function() {
      console.log("FichaHeaderComponent created");

      this.header.pelotari_id = this.pelotariId;

      if( this.isNewHeader() ) {
        this.edit = false;
      } else {
        this.edit = true;

        var rowHeader = this.getHeaderRow();

        this.header.id = rowHeader.id;
        this.header.name = rowHeader.name;
        this.header.fecha_ini = rowHeader.fecha_ini;
        this.header.fecha_fin = rowHeader.fecha_fin;
        this.header.file = (rowHeader.file ? rowHeader.file.replace('/storage/contratos/', '') : '');
        this.header.file_derechos = (rowHeader.file_derechos ? rowHeader.file_derechos.replace('/storage/contratos/', '') : '');
      }
    },
    methods: {
      onDocChange (e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
            return;
        this.createFile(files[0]);
      },
      downloadContrato () {
        window.open('/www/contratos/header/' + this.header.id + '/download');
      },
      createFile (file) {
          let reader = new FileReader();
          let vm = this;
          reader.onload = (e) => {
            vm.header.contrato = e.target.result;
            vm.header.fileName = file.name;
            vm.header.file = file;
          };
          reader.readAsDataURL(file);
      },
      onDocDerechosChange (e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
            return;
        this.createFileDerechos(files[0]);
      },
      downloadContratoDerechos () {
        window.open('/www/contratos/header/' + this.header.id + '/derechos/download');
      },
      createFileDerechos (file) {
          let reader = new FileReader();
          let vm = this;
          reader.onload = (e) => {
            vm.header.contrato_derechos = e.target.result;
            vm.header.fileDerechosName = file.name;
            vm.header.file_derechos = file;
          };
          reader.readAsDataURL(file);
      },
      onSubmit (evt) {
        evt.preventDefault();

        const config = { headers: { 'Content-Type': 'multipart/form-data' } };

        let uri = '/www/contratos/header';
        let data = new FormData();

        data.append('form', JSON.stringify(this.header));
        if(this.header.file)
          data.append('file', this.header.file);
        if(this.header.file_derechos)
          data.append('file_derechos', this.header.file_derechos);

        if(this.edit) {
          this.axios.post(uri + '/' + this.header.id + '/update', data, config)
            .then((response) => {
              showSnackbar("Contrato actualizado");
              this.goBack();
            })
            .catch((error) => {
              console.log(error);
              showSnackbar("Se ha producido un ERROR");
            });
        } else {
          this.axios.post(uri, data, config)
            .then((response) => {
              showSnackbar("Contrato creado");
              this.goBack();
            })
            .catch((error) => {
              console.log(error);
              showSnackbar("Se ha producido un ERROR");
            });
        }
      },
      onReset (evt) {
        evt.preventDefault();
        /* Reset our form values */
        this.header.fecha_ini = null;
        this.header.fecha_fin = null;
        this.header.file = null;
        this.header.file_derechos = null;
        this.header.name = '';
      },
    }
  }
</script>
