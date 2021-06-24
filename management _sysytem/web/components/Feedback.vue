<template>
  <div class="feedback-card" @click="fetchFeedback">
    <div class="feedback-card-header">
      <div class="px-2">
        <b class="actor">{{ feedbackActor | capitalize }}</b>
        gives verdict
        <b
          :class="
            feedbackVerdict === 'yes'
              ? 'yes'
              : feedbackVerdict === 'no'
              ? 'no'
              : 'maybe'
          "
          >{{ feedbackVerdict | capitalize }}</b
        >
      </div>
      <div class="px-2">
        <div v-if="!isVisible">
          <svg
            _ngcontent-tif-c11=""
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
          >
            <path _ngcontent-tif-c11="" fill="none" d="M0 0h24v24H0V0z"></path>
            <path _ngcontent-tif-c11="" d="M7 10l5 5 5-5H7z"></path>
          </svg>
        </div>
        <div v-else>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 0 24 24"
            width="24px"
            fill="#000000"
          >
            <path d="M0 0h24v24H0V0z" fill="none" />
            <path d="M7 14l5-5 5 5H7z" />
          </svg>
        </div>
      </div>
    </div>
    <div v-if="isVisible" class="feedback-card-main px-2">
      <div v-if="isLoading">
        <b-skeleton width="85%"></b-skeleton>
      </div>
      <div v-else>
        <!-- eslint-disable -->
        <div v-html="data"></div>
      </div>
    </div>
  </div>
</template>

<script>
import marked from "marked";

export default {
  name: "Feedback",
  props: {
    feedbackKey: {
      type: String,
      default: "",
    },
    feedbackActor: {
      type: String,
      default: "",
    },
    feedbackVerdict: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      isVisible: false,
      isLoading: true,
      data: null,
    };
  },
  methods: {
    /**
     * Fetch feedback when click, if already fetched then return
     */
    async fetchFeedback() {
      this.isVisible = !this.isVisible;

      if (this.data) return;

      try {
        let response = await this.$axios({
          methods: "get",
          url: "/api/v1/feedback/" + this.feedbackKey,
        });

        response = response.data;

        if (response.success) {
          this.data = marked(response.data.description);
          this.isLoading = false;
        }
      } catch (e) {}
    },
  },
};
</script>
<style scoped>
.feedback-card {
  width: 100%;
  padding: 12px;
  box-shadow: 0 1px 3px 0 hsla(0, 0%, 0%, 0.2);
  border-radius: 4px;
  border: 1px solid #00000008;
  cursor: pointer;
  margin-top: 12px;
}
.feedback-card-header {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.feedback-card-main {
  margin-top: 8px;
}
.actor {
  color: #193f5f;
}
.verdict {
  color: #20639b;
}
.yes {
  color: #059669;
}
.no {
  color: #dc2626;
}
.maybe {
  color: #4b5563;
}
</style>
