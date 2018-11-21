<template>
  <form>
    <b-row class="mx-0">
      <div class="col-12 px-0">
        <label for="nameInput">Descripción:</label>
        <b-form-input id="nameInput"
                      class="d-inline-block px-1"
                      type="text"
                      v-model="entradas.name"
                      required>
        </b-form-input>
      </div>
    </b-row>
    <b-row class="mx-0">
      <div class="col-sm-6 pl-0">
        <label for="cantidadInput">Cantidad:</label>
        <b-form-input id="cantidadInput"
                      class="d-inline-block px-1 text-right"
                      type="number"
                      v-model="entradas.amount"
                      required>
        </b-form-input>
      </div>
      <div class="col-sm-6 pr-0">
        <label for="precioInput">Precio:</label>
        <b-form-input id="precioInput"
                      class="d-inline-block px-1 text-right"
                      type="number"
                      v-model="entradas.price"
                      required>
        </b-form-input>
      </div>
    </b-row>
    <hr/>
    <b-row class="mx-0">
      <div class="col-12 px-0">
        <b-btn class="float-right" variant="default" @click="onCancel">Cancelar</b-btn>
        <b-btn class="float-right mr-2" variant="primary" @click="onSave">Aceptar</b-btn>
      </div>
    </b-row>
  </form>
</template>

<script>
  export default {
    props: [ 'festivalId', 'onCancel', 'onSubmit', 'isNewCoste', 'data'],
    data () {
      return {
        entradas: {
          id: 0,
          name: '',
          amount: 0,
          price: 0,
        },
      }
    },
    created: function () {
      console.log("FichaCosteEntradasComponent created");
      if( !this.isNewCoste ) {
        this.entradas.id = this.data.id;
        this.entradas.name = this.data.name;
        this.entradas.amount = this.data.amount;
        this.entradas.price = this.data.price;
      }
    },
    methods: {
      onSave() {
        this.onSubmit(this.entradas);
      }
    }
  }
</script>
