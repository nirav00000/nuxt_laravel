<template>
  <div class="container-fluid">
    <div class="mt-4">
      <!-- Header -->
      <div
        v-if="!isLoading && isFound"
        class="d-flex flex-row justify-content-between align-items-center px=2 flex-wrap"
      >
        <div style="font-size: 22px; font-weight: bold">
          {{ submitterName }}'s
          <span style="font-weight: normal">Submission&nbsp;-&nbsp;</span>
          <span v-if="isCrawled" style="font-weight: normal">
            (<b>{{ passedTestCases }}/{{ totalTestCases }}</b> Test cases
            Passed)
          </span>
        </div>
        <div>
          <nuxt-link
            :to="`/candidacy/${candidacyKey}`"
            type="button"
            class="btn btn-primary d-flex flex-row justify-content-between align-items-center"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              fill="currentColor"
              class="bi bi-person-fill"
              viewBox="0 0 16 16"
            >
              <path
                d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"
              />
            </svg>
            &nbsp;&nbsp;&nbsp;
            <span class="font-weight-bold">Profile</span>
          </nuxt-link>
        </div>
      </div>
      <!-- Loader -->
      <div>
        <div v-if="isLoading" class="loading">
          <div
            class="w-100"
            style="height: 600px; display: grid; place-items: center"
          >
            <b-spinner label="Loading..."></b-spinner>
          </div>
        </div>
      </div>
      <!-- Invalid submission -->
      <div>
        <div v-if="!isLoading && !isFound">
          <div
            class="w-100 d-flex justify-content-center align-items-center"
            style="height: 400px"
          >
            <svg
              id="b21613c9-2bf0-4d37-bef0-3b193d34fc5d"
              data-name="Layer 1"
              xmlns="http://www.w3.org/2000/svg"
              width="144px"
              height="144px"
              viewBox="0 0 647.63626 632.17383"
            >
              <path
                d="M687.3279,276.08691H512.81813a15.01828,15.01828,0,0,0-15,15v387.85l-2,.61005-42.81006,13.11a8.00676,8.00676,0,0,1-9.98974-5.31L315.678,271.39691a8.00313,8.00313,0,0,1,5.31006-9.99l65.97022-20.2,191.25-58.54,65.96972-20.2a7.98927,7.98927,0,0,1,9.99024,5.3l32.5498,106.32Z"
                transform="translate(-276.18187 -133.91309)"
                fill="#f2f2f2"
              />
              <path
                d="M725.408,274.08691l-39.23-128.14a16.99368,16.99368,0,0,0-21.23-11.28l-92.75,28.39L380.95827,221.60693l-92.75,28.4a17.0152,17.0152,0,0,0-11.28028,21.23l134.08008,437.93a17.02661,17.02661,0,0,0,16.26026,12.03,16.78926,16.78926,0,0,0,4.96972-.75l63.58008-19.46,2-.62v-2.09l-2,.61-64.16992,19.65a15.01489,15.01489,0,0,1-18.73-9.95l-134.06983-437.94a14.97935,14.97935,0,0,1,9.94971-18.73l92.75-28.4,191.24024-58.54,92.75-28.4a15.15551,15.15551,0,0,1,4.40966-.66,15.01461,15.01461,0,0,1,14.32032,10.61l39.0498,127.56.62012,2h2.08008Z"
                transform="translate(-276.18187 -133.91309)"
                fill="#3f3d56"
              />
              <path
                d="M398.86279,261.73389a9.0157,9.0157,0,0,1-8.61133-6.3667l-12.88037-42.07178a8.99884,8.99884,0,0,1,5.9712-11.24023l175.939-53.86377a9.00867,9.00867,0,0,1,11.24072,5.9707l12.88037,42.07227a9.01029,9.01029,0,0,1-5.9707,11.24072L401.49219,261.33887A8.976,8.976,0,0,1,398.86279,261.73389Z"
                transform="translate(-276.18187 -133.91309)"
                fill="#000000"
              />
              <circle cx="190.15351" cy="24.95465" r="20" fill="#000000" />
              <circle cx="190.15351" cy="24.95465" r="12.66462" fill="#fff" />
              <path
                d="M878.81836,716.08691h-338a8.50981,8.50981,0,0,1-8.5-8.5v-405a8.50951,8.50951,0,0,1,8.5-8.5h338a8.50982,8.50982,0,0,1,8.5,8.5v405A8.51013,8.51013,0,0,1,878.81836,716.08691Z"
                transform="translate(-276.18187 -133.91309)"
                fill="#e6e6e6"
              />
              <path
                d="M723.31813,274.08691h-210.5a17.02411,17.02411,0,0,0-17,17v407.8l2-.61v-407.19a15.01828,15.01828,0,0,1,15-15H723.93825Zm183.5,0h-394a17.02411,17.02411,0,0,0-17,17v458a17.0241,17.0241,0,0,0,17,17h394a17.0241,17.0241,0,0,0,17-17v-458A17.02411,17.02411,0,0,0,906.81813,274.08691Zm15,475a15.01828,15.01828,0,0,1-15,15h-394a15.01828,15.01828,0,0,1-15-15v-458a15.01828,15.01828,0,0,1,15-15h394a15.01828,15.01828,0,0,1,15,15Z"
                transform="translate(-276.18187 -133.91309)"
                fill="#3f3d56"
              />
              <path
                d="M801.81836,318.08691h-184a9.01015,9.01015,0,0,1-9-9v-44a9.01016,9.01016,0,0,1,9-9h184a9.01016,9.01016,0,0,1,9,9v44A9.01015,9.01015,0,0,1,801.81836,318.08691Z"
                transform="translate(-276.18187 -133.91309)"
                fill="#000000"
              />
              <circle cx="433.63626" cy="105.17383" r="20" fill="#000000" />
              <circle cx="433.63626" cy="105.17383" r="12.18187" fill="#fff" />
            </svg>
            <h5 style="margin-top: 22px; padding-left: 22px">
              No submission found
            </h5>
          </div>
        </div>
      </div>
      <!-- Submittion found, not crawled yet -->
      <div>
        <div v-if="!isLoading && isFound && !isCrawled">
          <div
            class="w-100 d-flex justify-content-center align-items-center"
            style="height: 400px"
          >
            <img src="@/assets/not_crawl.svg" alt="Result not crawled, yet!" />
            <h5 style="margin-top: 22px; padding-left: 42px">
              Submittion found, But not crawled result!
            </h5>
          </div>
        </div>
      </div>
      <!-- Main -->
      <div v-if="!isLoading && isFound && isCrawled">
        <div class="mt-4">
          <div class="code">
            <CodeCollapse is-code="true" :data="code" />
            <div v-for="(result, index) in results" :key="`result-${index}`">
              <CodeCollapse is-test="true" :data="result" :index="index" />
            </div>
            <CodeCollapse is-challenge="true" :data="challenge" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CodeCollapse from "@/components/CodeCollapse";

export default {
  components: {
    CodeCollapse,
  },
  layout: "admin",
  data() {
    return {
      isLoading: false,
      submittionId: this.$route.params.id,
      isFound: false,
      submitterName: "",
      isCrawled: false,
      totalTestCases: 10,
      passedTestCases: 3,
      candidacyKey: "",
      code: {
        code: "",
        language: "",
      },
      challenge: {
        title: "",
        description: "",
      },
      results: [],
    };
  },
  mounted() {
    this.isLoading = true;
    this.fetchSubmission();
  },
  methods: {
    async fetchSubmission() {
      this.isLoading = true;

      try {
        let submission = await this.$axios.get(
          `/api/v1/coding-submissions/${this.submittionId}`
        );
        submission = submission.data;

        if (submission.success) {
          // Candidate portion
          this.candidacyKey = submission.data.candidacy.key;
          this.submitterName = submission.data.candidate.name;
          // Challege portion
          this.challenge.title = submission.data.challenge.title;
          this.challenge.description = submission.data.challenge.description;
          // Code portion
          this.code.code = submission.data.code.code;
          this.code.language = submission.data.code.language;

          // Test portion
          // Test Crawled
          if (submission.data.tests.result.crawled) {
            this.isCrawled = true;
            this.results = submission.data.tests.result.results;
            this.totalTestCases = submission.data.tests.total_tests;
            this.passedTestCases = submission.data.tests.passed_tests;
          } else {
            // Crwaling not started, half completed
            this.isCrawled = false;
          }

          this.isFound = true;
        }
      } catch (e) {
        const response = e.response?.data;

        if (response) {
          // No Record found
          if (!response.success) {
            this.isFound = false;
          }
        }
      }

      this.isLoading = false;
    },
  },
};
</script>
