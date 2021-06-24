<template>
  <ul class="timeline">
    <timeline-item v-for="(item, index) in items" :key="index" :item="item" />
  </ul>
</template>
<script>
export default {
  props: {
    items: {
      type: Array,
      default: null,
    },
  },
};
</script>
<style src="@/assets/timeline.css"></style>
