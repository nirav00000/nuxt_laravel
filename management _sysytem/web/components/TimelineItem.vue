<template>
  <stage-start v-if="item.status === 'started'" :item="item"></stage-start>
  <stage-assign
    v-else-if="item.status === 'created'"
    :item="item"
  ></stage-assign>
  <stage-complete
    v-else-if="item.status === 'completed'"
    :item="item"
  ></stage-complete>
  <feedback-record
    v-else-if="item.metadata && item.metadata.verdict"
    :key="item.metadata.feedback_key"
    :item="item"
  ></feedback-record>
  <candidacy-close
    v-else-if="item.metadata && item.metadata.candidacy_closing_reason"
    :item="item"
  ></candidacy-close>
</template>

<script>
import CandidacyClose from "./history-timeline-items/candidacy-close.vue";
import FeedbackRecord from "./history-timeline-items/feedback-record.vue";
import StageAssign from "./history-timeline-items/stage-assign.vue";
import StageStart from "./history-timeline-items/stage-start.vue";
import StageComplete from "./history-timeline-items/stage-complete.vue";

export default {
  components: {
    StageStart,
    StageAssign,
    StageComplete,
    FeedbackRecord,
    CandidacyClose,
  },
  props: {
    item: {
      type: Object,
      default: null,
    },
  },
};
</script>

<style scoped src="@/assets/timeline.css"></style>
