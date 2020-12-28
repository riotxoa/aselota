<template>
  <div>
    <table class="border w-100">
      <tr style="border-bottom:2px solid gray;">
        <th colspan="3" class="text-uppercase text-center px-3">
          Informes
        </th>
      </tr>
      <tr v-for="(informe, index) in informes" v-bind:key="index" style="border-bottom: 1px solid lightgray;">
        <td style="width:95px;">
          <div v-if="false === editIndex" class="d-inline-block cursor-pointer" title="Descargar informe" style="color:cadetblue" @click.stop="onDownloadInforme(index)"><i class="fas fa-download mx-2"></i></div>
          <div v-if="false === editIndex" class="d-inline-block cursor-pointer" title="Editar informe" style="color:slategray" @click.stop="onEditInforme(index)"><i class="far fa-edit mx-0"></i></div>
          <div v-if="false === editIndex" class="d-inline-block cursor-pointer" title="Borrar informe" style="color:red" @click.stop="onDelInforme(index)"><i class="far fa-trash-alt mx-2"></i></div>
        </td>
        <td class="py-1 px-3">
          <b-row>
            <b-col cols="3" class="font-weight-bold px-0">Descripción:</b-col>
            <b-col v-if="index === editIndex" cols="9">
              <b-row>
                <b-col cols="10" class="pr-0">
                  <b-form-textarea class="mb-1" v-model="informe.desc" placeholder="Introduce una descripción" size="sm" maxlength="120" rows="2" autofocus></b-form-textarea>
                </b-col>
                <b-col cols="1" class="px-0 text-center">
                  <i class="fas fa-check" v-on:click="onClickSaveEdit" title="Guardar Descripción" style="color:green; cursor:pointer;"></i>
                </b-col>
                <b-col cols="1" class="px-0 text-left">
                  <i class="fas fa-times" v-on:click="onClickCancelEdit" title="Cancelar" style="color:red; cursor:pointer;"></i>
                </b-col>
              </b-row>
            </b-col>
            <b-col v-else cols="9">{{ informe.desc ? informe.desc : '---' }}</b-col>
          </b-row>
          <b-row>
            <b-col cols="3" class="font-weight-bold px-0">Fichero:</b-col>
            <b-col cols="9">{{ informe.name }}</b-col>
          </b-row>
        </td>
        <td class="py-1 px-3">
          {{ formatFecha(informe.created_at) }}
        </td>
      </tr>
    </table>
    <p v-if="informes.length == 0" class="border py-2 small text-center text-gray">No hay informes</p>

  </div>
</template>

<script>
    import Utils from '../../../utils/utils.js';

    export default {
        name: 'PartesTablaInformes',
        props: [ 'tipo', 'informes', 'delInforme', 'setInformes', 'updateInforme' ],
        mixins:[ Utils ],
        data() {
          return {
            editIndex: false,
          }
        },
        methods: {
          deleteInforme(id) {
            this.delInforme(id).then( () => {
              this.setInformes().then( () => {
                this.showSnackbar("INFORME BORRADO");
              });
            });
          },
          formatFecha(fecha) {
            return this.formatDateES(fecha);
          },
          onClickCancelEdit() {
            this.editIndex = false;
          },
          onClickSaveEdit() {
            this.updateInforme(this.informes[this.editIndex]).then( (res) => {
              this.setInformes().then( (informes) => {
                this.editIndex = false;
                this.showSnackbar("Informe actualizado");
              });
            });
          },
          onDelInforme(index) {
            var msg = "Se va a borrar el siguiente informe:\n\n - Nombre: " + this.informes[index].name + "\n - Fecha subida: " + this.formatDateES(this.informes[index].created_at) + "\n\n¿Desea continuar?";
            if( confirm(msg) ) {
              this.deleteInforme(this.informes[index].id);
            }

          },
          onDownloadInforme(index) {
            window.open('/www/med2/p_' + this.tipo + '/informe/' + this.informes[index].id + '/download');
          },
          onEditInforme(index) {
            this.editIndex = (this.editIndex ? false : index);
          }
        }
    };
</script>

<style scoped>

</style>
