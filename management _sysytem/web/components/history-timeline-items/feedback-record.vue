<template>
  <li class="timeline-item">
    <div class="timeline-badge info">
      <b-icon icon="pencil-square"></b-icon>
    </div>
    <div class="timeline-panel">
      <div class="timeline-heading">
        <h6 class="timeline-title">
          <b class="actor">{{ item.actor | capitalize }}</b> gives verdict
          <b
            class="verdict"
            :class="
              item.metadata.verdict === 'yes'
                ? 'yes'
                : item.metadata.verdict === 'no'
                ? 'no'
                : 'maybe'
            "
            >{{ item.metadata.verdict | capitalize }}</b
          >
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
      <!-- <div class="timeline-body">
        <b-collapse :id="'collaps-feedback' + item.created_at">
          <div class="col">
            <b-icon icon="arrow-right" class="pr-1" />{{ feedbackDetails }}
          </div>
        </b-collapse>
      </div> -->
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
