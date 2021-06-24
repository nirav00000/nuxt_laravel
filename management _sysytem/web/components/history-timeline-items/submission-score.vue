<template>
  <div id="submission-eye-view">
    <div class="timeline-title">
      <b>{{ actor }} </b> has completed <b>{{ stageName }}</b>
      <div class="pt-2">
        View
        <a
          :href="'/coding-submissions/' + submissionKey"
          target="_blank"
          rel="noreferrer"
          >Submission</a
        >
      </div>
      <div v-if="isFetched" class="pt-2">
        <div v-if="!isCrawled">
          Result:
          <b
            >Submission is pending (User submitted their code, but pending to
            run test cases against with their code).</b
          >
        </div>
        <div v-else>
          Result:
          <b
            >{{ passedTests }} test cases passed out of {{ totalTests }} test
            cases.</b
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SubmissionScore",
  props: {
    actor: {
      type: String,
      default: null,
    },
    stageName: {
      type: String,
      default: null,
    },
    submissionKey: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      totalTests: null,
      passedTests: null,
      isCrawled: false,
      isFetched: false,
    };
  },
  mounted() {
    this.$axios
      .get(`/api/v1/coding-submissions/${this.submissionKey}`)
      .then((res) => {
        const result = res.data;

        if (result.success) {
          if (result.data.tests.result.crawled) {
            this.isCrawled = true;
            this.passedTests = result.data.tests.passed_tests;
            this.totalTests = result.data.tests.total_tests;
          } else {
            this.isCrawled = false;
          }

          this.isFetched = true;
        }
      })
      .catch(() => {
        this.$toasted.show("Something went wrong, Please reload your page!", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
      });
  },
};
</script>
