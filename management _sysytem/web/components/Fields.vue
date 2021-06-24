<template>
  <div v-if="field.key">
    <b-form-group :label="field.key" :label-for="field.key">
      <!-- <b-form-datepicker
        v-if="field === 'date'"
        :id="index"
        class="mb-2"
      ></b-form-datepicker> -->
      <b-form-input
        v-if="field"
        :id="field.key"
        :value="field && field.value"
        required
        @input="$emit('input', $event)"
      ></b-form-input>
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: "Fields",
  props: {
    field: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {};
  },
  computed: {},
  mounted() {},
  methods: {},
};
</script>
