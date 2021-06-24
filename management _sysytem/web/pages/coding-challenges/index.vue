<template>
  <div class="container-fluid">
    <div class="mt-4">
      <div
        class="d-flex flex-row justify-content-between align-items-center px=2 flex-wrap"
      >
        <div style="font-size: 22px; font-weight: bold">
          Coding Challenges
          {{ !isLoading ? " - " + pageMeta.total : null }}
        </div>
        <div>
          <nuxt-link
            to="/coding-challenges/new"
            type="button"
            class="btn btn-primary d-flex flex-row justify-content-between align-items-center"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              fill="currentColor"
              class="bi bi-file-plus-fill"
              viewBox="0 0 16 16"
            >
              <path
                d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM8.5 6v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 1 0z"
              /></svg
            >&nbsp;&nbsp;&nbsp;
            <span class="font-weight-bold">Add Challenge</span>
          </nuxt-link>
        </div>
      </div>
    </div>
    <div v-if="isLoading" class="loading">
      <div
        class="w-100"
        style="height: 300px; display: grid; place-items: center"
      >
        Loading...
      </div>
    </div>
    <div v-if="!isLoading && isEmptyChallenges">
      <div
        class="w-100"
        style="
          height: 200px;
          display: grid;
          place-items: center;
          margin: 100px 0;
        "
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
        No challenges
      </div>
    </div>
    <div v-if="!isLoading && !isEmptyChallenges">
      <div v-for="challenge in challenges" :key="challenge.key" class="my-4">
        <div
          class="card px-4 py-2 shadow-sm d-flex flex-row justify-content-between align-items-center"
        >
          <div>
            <div
              class="font-weight-bold"
              style="font-size: 16px; color: #0000009e"
            >
              {{ challenge.title }}
            </div>
          </div>
          <div>
            <nuxt-link
              :to="{ path: '/coding-challenges/' + challenge.key }"
              class="btn"
              title="Edit"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-pen"
                viewBox="0 0 16 16"
              >
                <path
                  d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"
                />
              </svg>
            </nuxt-link>
            <button
              id="deleteChallenge"
              class="btn"
              title="Delete"
              @click="deleteChallenge(challenge.key)"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-trash"
                viewBox="0 0 16 16"
              >
                <path
                  fill="red"
                  d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
                />
                <path
                  fill-rule="evenodd"
                  d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"
                  fill="red"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div v-if="hasPrev || hasNext" style="margin: 12px 0">
        <div
          id="pagination"
          class="d-flex justify-content-center align-items-center"
        >
          <button
            class="btn btn-primary"
            :disabled="!hasPrev"
            @click="prevPage"
          >
            Prev</button
          >&nbsp;&nbsp;
          <div>
            {{ currentPage }}
          </div>
          &nbsp;&nbsp;
          <button
            class="btn btn-primary next-btn"
            :disabled="!hasNext"
            @click="nextPage"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CodingChallengeMain",
  layout: "admin",
  data() {
    return {
      isLoading: true,
      challenges: [],
      isEmptyChallenges: true,
      pageMeta: null,
      currentPage: 1,
      hasPrev: false,
      hasNext: false,
    };
  },

  head() {
    return {
      title: "Coding Challenges - Apricot",
    };
  },

  mounted() {
    this.fetchChallenges(this.currentPage);
  },

  methods: {
    prevPage() {
      this.currentPage -= 1;
      this.fetchChallenges(this.currentPage);
    },
    nextPage() {
      this.currentPage += 1;
      this.fetchChallenges(this.currentPage);
    },
    async fetchChallenges(page = 1) {
      this.isLoading = true;

      try {
        // Fetch challenges
        let challenges = await this.$axios(
          `/api/v1/coding-challenges/?page=${page}`
        );
        challenges = challenges.data;

        // Challenges retrived successfully
        if (challenges.success) {
          this.challenges = challenges.data.challenges;

          // No coding challenges and current page is 1
          if (challenges.data.challenges.length <= 0 && this.currentPage <= 1)
            this.isEmptyChallenges = true;
          // No challenges in page > 1
          else if (challenges.data.challenges.length <= 0) this.prevPage();
          // Empty challenges in page 1
          else this.isEmptyChallenges = false;

          this.pageMeta = challenges.data.meta;

          // Analyse meta and set pagination accodingly
          // Next page button
          if (!this.pageMeta.next_page_url) this.hasNext = false;
          else this.hasNext = true;

          // Prev page button
          if (!this.pageMeta.prev_page_url) this.hasPrev = false;
          else this.hasPrev = true;

          this.isLoading = false;
        }
      } catch (e) {}
    },
    /**
     * Delete the challenge
     */
    async deleteChallenge(id) {
      const index = this.challenges.findIndex((i) => i.key === id);
      const res = confirm(
        `Are sure do you want to delete ${this.challenges[index].title} problem?`
      );

      if (res) {
        this.isLoading = true;

        try {
          let response = await this.$axios.delete(
            `/api/v1/coding-challenges/${id}`
          );
          response = response.data;

          if (response.success) {
            // Challenge deleted
            this.fetchChallenges(this.currentPage);
            this.$toasted.show("Challenge deleted", {
              theme: "toasted-primary",
              type: "success",
              position: "bottom-right",
              duration: 5000,
            });
          }
        } catch (e) {}
      }
    },
  },
};
</script>
