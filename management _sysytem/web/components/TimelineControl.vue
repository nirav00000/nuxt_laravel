<template>
  <a href="#">
    <i :class="control.icon_class"></i>
  </a>
</template>

<script>
export default {
  name: "TimelineControl",
  props: {
    control: {
      type: Array,
      default: null,
    },
  },
};
</script>
