<template>
  <b-modal ref="PLENModalDialog"
           id="PLENModalDialog"
           :title="title"
           @ok="onClickOk"
           @cancel="onClickCancel"
           :ok-title="title_ok"
           ok-variant="danger"
           :cancel-title="title_cancel"
           cancel-variant="secondary">
    <div v-html="dialog"></div>
  </b-modal>
</template>

<script>
  export default {
    props: [ 'title', 'dialog', 'okTitle', 'cancelTitle', 'okFunction', 'cancelFunction'],
    data() {
      return {
        onClickCancel: this.cancelFunction,
        onClickOk: this.okFunction,
        title_cancel: this.cancelTitle,
        title_ok: this.okTitle,
      }
    },
    updated() {
      this.onClickCancel = this.cancelFunction;
      this.onClickOk = this.okFunction;
      this.title_cancel = this.cancelTitle;
      this.title_ok = this.okTitle;
    }
  }
</script>
