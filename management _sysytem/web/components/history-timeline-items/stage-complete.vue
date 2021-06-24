<template>
  <li class="timeline-item">
    <div class="timeline-badge success">
      <b-icon icon="check2-square"></b-icon>
    </div>
    <div class="timeline-panel">
      <div class="timeline-heading">
        <h6 class="timeline-title">
          <span
            v-if="item.metadata && item.metadata.questionnaire_submission_key"
          >
            <b>{{ item.actor }} </b> has completed
            <b>{{ item.stage_name }}</b> see
            <nuxt-link
              :to="{
                path:
                  '/candidacy/' + $route.params.id + '/questioner-submission',
              }"
            >
              submission
            </nuxt-link>
          </span>
          <SubmissionScore
            v-else-if="item.metadata && item.metadata.submission_key"
            :actor="item.actor"
            :stage-name="item.stage_name"
            :submission-key="item.metadata.submission_key"
          />
          <span v-else>
            <b>{{ item.actor }}</b> has mark <b>{{ item.stage_name }}</b> as
            completed
            <b
              v-if="
                item.metadata &&
                item.metadata.stage_closing_reason !== 'Reason not provided.'
              "
            >
              with reason {{ item.metadata.stage_closing_reason }}
            </b>
          </span>
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
import SubmissionScore from "./submission-score.vue";

export default {
  components: { SubmissionScore },
  props: {
    item: {
      type: Object,
      default: null,
    },
  },
};
</script>

<style scoped src="@/assets/timeline.css"></style>
