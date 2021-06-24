<template>
  <li class="timeline-item">
    <div class="timeline-badge warning">
      <b-icon icon="clock-history"></b-icon>
    </div>
    <div class="timeline-panel">
      <div class="timeline-heading">
        <h6 class="timeline-title">
          <b>{{ item.actor }}</b> has started <b>{{ item.stage_name }}</b>
        </h6>
        <div class="timeline-panel-controls">
          <div class="controls"></div>
          <div class="timestamp">
            <small
              v-b-tooltip.hover
              class="text-muted"
              :title="item.created_at | format"
              >{{ item.created_at | time }}</small
            >
          </div>
        </div>
      </div>
      <div class="timeline-body"></div>
    </div>
  </li>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      default: null,
    },
  },
};
</script>

<style scoped src="@/assets/timeline.css"></style>
