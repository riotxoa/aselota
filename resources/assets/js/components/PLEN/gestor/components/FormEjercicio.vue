<template>
  <div>
    <b-form v-if="show" @submit="onSubmit" @reset="onReset">
      <b-row>
        <b-col cols="12">
          <b-form-group label="Nombre:" label-for="nameEjercicio">
            <b-form-input
              id="nameEjercicio"
              v-model="form.name"
              maxLength="50"
              required
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" md="5" class="pr-0">
          <b-form-group label="Tipo Ejercicio:" label-for="tipoEjercicio">
            <b-form-select
              id="tipoEjercicio"
              v-model="form.tipo_ejercicio_id"
              :options="tipos"
              v-on:input="onInputTipoEjercicio"
              required
            ></b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="5" class="px-1">
          <b-form-group label="Subtipo Ejercicio:" label-for="subtipoEjercicio">
            <b-form-select
              id="subtipoEjercicio"
              v-model="form.subtipo_ejercicio_id"
              :options="subtipos"
              v-on:input="onInputSubtipoEjercicio"
              required
            ></b-form-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="2" class="pl-0">
          <b-form-group label="Orden:" label-for="orderEjercicio">
            <b-form-input
              id="orderEjercicio"
              v-model="form.order"
              type="number"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6">
          <b-row>
            <b-col cols="12">
              <b-form-group label="Imagen:" label-for="imagenEjercicio">
                <b-form-file
                  id="imagenEjercicio"
                  plain
                  v-model="form.imagen"
                  :state="Boolean(form.imagen)"
                  accept=".jpg, .jpeg, .gif, .png"
                  placeholder="Elija una imagen o arrástrela aquí..."
                  drop-placeholder="Arrastre un fichero aquí..."
                  v-on:change="onFileChange">
                </b-form-file>
                <small>Formatos admitidos: *.jpg, *.jpeg, *.gif, *.png</small>
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <img :src="form.imagen" height="200px" class="img-responsive" />
            </b-col>
          </b-row>
        </b-col>
        <b-col cols="6">
          <b-row>
            <b-col cols="12" class="mb-3">
              <b-form-group label="Vídeo:" label-for="videoEjercicio">
                <b-form-input
                  id="videoEjercicio"
                  v-model="form.video"
                  maxLength="500"
                ></b-form-input>
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <div style="height:200px" v-html="getVideoFrame(form.video)" />
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      <b-row class="mt-3">
        <b-col cols="12" md="8">
          <b-form-group label="Descripción:" label-for="descEjercicio">
            <b-form-textarea
              id="descEjercicio"
              v-model="form.desc"
              maxLength="1500"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Material:" label-for="materialEjercicio">
            <b-form-textarea
              id="materialEjercicio"
              v-model="form.material"
              maxLength="1500"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
        </b-col>
      </b-row>
      <hr>
      <div class="float-right">
        <b-button type="submit" variant="primary">Guardar</b-button>
        <b-button type="reset" variant="default">Cancelar</b-button>
      </div>
    </b-form>
    <b-card v-if="false" class="mt-3" header="Form Data Result">
      <pre class="m-0">{{ form }}</pre>
    </b-card>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import _ from 'lodash';
  import Utils from '../../../utils/utils.js';

  export default {
    props: ['new'],
    mixins: [Utils],
    data() {
      return {
        tipos: [],
        subtipos: [],
        show: true
      }
    },
    computed: {
      ...mapState({
        ejercicios: state => state.plen.ejercicios,
        form: state => state.plen.ejercicio,
        subtipos_ejercicio: state => state.plen.subtipos_ejercicio,
        tipos_ejercicio: state => state.plen.tipos_ejercicio
      }),
    },
    watch: {
      form(newVal, oldVal) {
        if( this.new && !newVal.fileName.length ) {
          // Trick to reset/clear native browser form validation state
          this.show = false
          this.$nextTick(() => {
            this.show = true
          })
        }
      }
    },
    created() {
      this.initTiposEjercicio();
      this.initSubtiposEjercicio();
    },
    methods: {
      ...mapActions({
        resetEjercicio: 'plen/resetEjercicio',
        saveEjercicio: 'plen/saveEjercicio',
        setEjercicio: 'plen/setEjercicio',
        setEjercicioImagen: 'plen/setEjercicioImagen',
      }),
      closeModal() {
        this.$root.$emit('bv::hide::modal', 'editEjercicioModal');
      },
      createFile(file) {
          let reader = new FileReader();
          let vm = this;
          reader.onload = (e) => {
            const imagen = {
              image: e.target.result,
              file: file,
              fileName: file.name,
            }
            this.setEjercicioImagen(imagen);
          };
          reader.readAsDataURL(file);
      },
      initSubtiposEjercicio( tipo_id = false ) {
        this.resetSubtiposEjercicio();
        this.subtipos_ejercicio.map( (val, key) => {
          if( !tipo_id || tipo_id === val.plen_tipos_ejercicio_id ) {
            this.subtipos.push({ value: val.id, text: val.desc });
          }
        });
      },
      initTiposEjercicio() {
        this.resetTiposEjercicio();
        this.tipos_ejercicio.map( (val, key) => {
          this.tipos.push({ value: val.id, text: val.desc });
        });
      },
      onFileChange(e) {
          let files = e.target.files || e.dataTransfer.files;
          if (!files.length)
              return;
          this.createFile(files[0]);
      },
      onInputTipoEjercicio(val) {
        var subtipo = _.find(this.subtipos_ejercicio, { id: this.form.subtipo_ejercicio_id });

        this.form.tipo_ejercicio_id = val;

        if( subtipo && subtipo.plen_tipos_ejercicio_id != val ) {
          this.form.subtipo_ejercicio_id = null;
        }
        this.initSubtiposEjercicio(val);
      },
      onInputSubtipoEjercicio(val) {
        var subtipo = _.find(this.subtipos_ejercicio, { id: val });

        this.form.subtipo_ejercicio_id = val;

        if( subtipo && this.form.tipo_ejercicio_id == null ) {
          this.form.tipo_ejercicio_id = subtipo.plen_tipos_ejercicio_id;
        }
      },
      onSubmit(event) {
        event.preventDefault();

        this.saveEjercicio(this.form).then( () => {
          this.showSnackbar("Ejercicio GUARDADO");
          this.closeModal();
        });
      },
      onReset(event) {
        event.preventDefault()
        // Reset our form values
        this.resetEjercicio();

        // Trick to reset/clear native browser form validation state
        this.show = false
        this.$nextTick(() => {
          this.show = true
        })
        this.$emit('cancel');
        this.closeModal();
      },
      resetSubtiposEjercicio() {
        this.subtipos = [{ text: 'Seleccionar Subtipo', value: null }];
      },
      resetTiposEjercicio() {
        this.tipos = [{ text: 'Seleccionar Tipo', value: null }];
      },
    }
  }
</script>

<style scoped>
  .video-frame {
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
  }
</style>
