<template>
  <form method="post" enctype="multipart/form-data">
    <label :for="inputID" class="custom-file-upload">
        <i class="fas fa-cloud-upload-alt"></i> Subir informe al Parte de <span style="text-transform:capitalize">{{ tipo }}</span>
    </label>
    <p class="small text-gray">Formatos admitidos: .doc, .docx, .xls, .xlsx, .pdf, .rtf</p>
    <input
      :id="inputID"
      accept=".doc,.docx,.xls,.xlsx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/msexcel,application/rtf"
      name="informe"
      type="file"
      v-on:change="onChange($event.target.name, $event.target.files)"
    />
  </form>
</template>

<script>
    import { mapActions } from 'vuex';
    import Utils from '../../../utils/utils.js';

    export default {
        mixins: [ Utils ],
        props: [ 'tipo', 'setInformes', 'uploadInforme' ],
        data() {
          return {
            inputID: "file-upload-",
          }
        },
        created() {
          this.inputID += this.tipo;
        },
        methods: {
          onChange(fieldName, fileList) {
            if (!fileList.length) return;

            const formData = new FormData();

            // append the files to FormData
            Array
              .from(Array(fileList.length).keys())
              .map(x => {
                formData.append(fieldName, fileList[x], fileList[x].name);
              });

            this.uploadInforme(formData)
              .then( (res) => {
                this.setInformes()
                  .then( (informes) => {
                    this.showSnackbar("Informe subido");
                  })
                  .catch( (err) => {
                    console.log("[setInformes] ERROR: " + JSON.stringify(err));
                    this.showSnackbar("[setInformes] ERROR");
                  });
              })
              .catch( (err) => {
                  console.log("[uploadInforme] ERROR: " + JSON.stringify(err));
                  this.showSnackbar("[uploadInforme] ERROR");
              });
          },
        }
    };
</script>

<style scoped>
  input[type="file"] {
    display: none;
  }
  .custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
  }
</style>
