<template>
  <div class="indicate-block">
    <b-modal
      id="delete-confirm"
      ref="delete-modal"
      :static="true"
      ok-variant="danger"
      ok-title="Delete"
      ok-only
      centered
      header="header"
      title="Confirm Delete"
      @ok="handleOk"
    >
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <div ref="indicate">
          <b-form-group>
            <!-- eslint-disable -->
            <label v-html="placeholder"></label>
          </b-form-group>
        </div>
      </b-form>
    </b-modal>
  </div>
</template>
<script>
export default {
  props: {
    data: {
      type: Object,
      default: null,
      action: "",
      key: "",
      item_name: "",
    },
  },
  data() {
    return {};
  },
  computed: {
    placeholder() {
      const text = this.data.item_name.replace(/'.*'/, (x) => {
        return x.bold();
      });

      return "Are you sure, you want to delete " + text + " ?";
    },
  },
  methods: {
    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.handleSubmit();
    },
    handleSubmit() {
      this.$store.dispatch(this.data.action, this.data.key);
      this.$nextTick(() => {
        this.$bvModal.hide("delete-confirm");
      });
    },
  },
};
</script>
