<template>
  <div class="container-fluid">
    <div class="mt-4">
      <div
        class="d-flex flex-row justify-content-between align-items-center px=2 flex-wrap"
      >
        <div style="font-size: 24px">Update Coding Challenge</div>
        <div v-if="!isLoading && !isNotFound">
          <button
            id="updateChallenge"
            class="btn btn-primary"
            @click="updateChallenge"
          >
            Update
          </button>
        </div>
      </div>
    </div>
    <div v-if="isLoading">
      <div
        class="w-100"
        style="height: 300px; display: grid; place-items: center"
      >
        Loading...
      </div>
    </div>

    <div v-if="!isLoading && isNotFound">
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
        Challenge not exists!
      </div>
    </div>

    <div v-if="!isLoading && !isNotFound">
      <div style="margin: 18px 0">
        <div>
          <div class="field">
            <label
              for="title"
              class="font-weight-bold position-relative"
              style="top: 4px"
              >Challenge Title</label
            >
            <input
              id="title"
              v-model="title"
              type="text"
              class="form-control"
              placeholder="Coding challenge title"
              autocomplete="off"
            />
            <small class="form-text text-muted"
              >Example: Two number sum, Find two largest elements in an
              array</small
            >
          </div>

          <div class="field mt-4">
            <label
              for="title"
              class="font-weight-bold position-relative"
              style="top: 4px"
              >Challenge Description</label
            >

            <MDEditor
              ref="md-editor"
              :options="editorOptions"
              :initial-value="editorInitalValue"
            />

            <small class="form-text text-muted"
              >Write Coding Challenge description in Markdown</small
            >
          </div>

          <!-- Tests -->
          <div
            class="field mt-4 d-flex justify-content-between align-items-center"
          >
            <label
              for="title"
              class="font-weight-bold position-relative"
              style="top: 4px"
              >Challenge Tests</label
            >
            <button
              id="addTest"
              class="btn btn-primary"
              :disabled="isAdding"
              @click="addTest"
            >
              Add
            </button>
          </div>

          <!-- List coding challenge test -->
          <div class="tests">
            <div v-for="(test, index) in tests" :key="index" class="my-4">
              <div
                class="card px-3 py-2 shadow-sm d-flex flex-row justify-content-between align-items-center"
              >
                <div>
                  <div
                    class="font-weight-bold"
                    style="font-size: 16px; color: #0000009e"
                  >
                    Test #{{ index + 1 }}
                  </div>
                </div>
                <div class="d-flex">
                  <button
                    id="editTest"
                    class="btn"
                    title="Edit"
                    @click="editTest(index)"
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
                  </button>
                  <button
                    id="deleteTest"
                    class="btn"
                    title="Delete"
                    @click="deleteTest(index)"
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
          </div>
          <!-- Add challenge test -->
          <div v-if="isAdding" class="block mt-2">
            <div class="card">
              <div
                class="hdr d-flex justify-content-between align-items-center"
              >
                <div class="pl-3">
                  Test
                  <span class="font-weight-bold">
                    #{{ tests.length + 1 }}
                  </span>
                </div>
                <div>
                  <button
                    id="discardTest"
                    class="btn btn-secondary"
                    @click="discardTest"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="currentColor"
                      class="bi bi-x"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"
                      />
                    </svg>
                  </button>
                  <button id="saveTest" class="btn btn-info" @click="saveTest">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="currentColor"
                      class="bi bi-check2"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"
                      />
                    </svg>
                  </button>
                </div>
              </div>
              <div class="form-group pl-3 pr-3">
                <div>
                  <label
                    for="stdin"
                    class="font-weight-bold position-relative"
                    style="top: 4px"
                    >Inputs</label
                  >
                  <textarea
                    id="stdin"
                    v-model="newInputs"
                    class="form-control"
                    rows="3"
                  ></textarea>
                  <small class="form-text text-muted"
                    >Standard Inputs is a stream from which program read its
                    input data</small
                  >
                </div>
                <div class="mt-2">
                  <label
                    for="stdout"
                    class="font-weight-bold position-relative"
                    style="top: 4px"
                    >Expected Output</label
                  >
                  <textarea
                    id="stdout"
                    v-model="newOutputs"
                    class="form-control"
                    rows="3"
                  ></textarea>
                  <small class="form-text text-muted"
                    >Program should return output for above standard
                    input</small
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Edit Test model -->
    <div>
      <b-modal ref="editTestModel" hide-footer :title="editTestTitle">
        <div>
          <div class="form-group pl-3 pr-3">
            <div>
              <label
                for="stdin"
                class="font-weight-bold position-relative"
                style="top: 4px"
                >Inputs</label
              >
              <textarea
                id="stdin"
                v-model="editTestInputs"
                class="form-control"
                rows="3"
              ></textarea>
              <small class="form-text text-muted"
                >Standard Inputs is a stream from which program read its input
                data</small
              >
            </div>
            <div class="mt-2">
              <label
                for="stdin"
                class="font-weight-bold position-relative"
                style="top: 4px"
                >Expected Output</label
              >
              <textarea
                id="stdin"
                v-model="editTestOutput"
                class="form-control"
                rows="3"
              ></textarea>
              <small class="form-text text-muted"
                >Program should return output for above standard input</small
              >
            </div>
            <footer style="margin-left: auto">
              <b-button
                block
                class="mt-3 btn btn-secondary"
                @click="discardEditTest"
                >Discard</b-button
              >
              <b-button block class="mt-3 btn btn-info" @click="updateEditTest"
                >Update</b-button
              >
            </footer>
          </div>
        </div>
      </b-modal>
    </div>
  </div>
</template>

<script>
export default {
  layout: "admin",
  data() {
    return {
      isLoading: true,
      isNotFound: false,
      title: "",
      editorInitalValue: "",
      tests: [],
      editorOptions: {
        hideModeSwitch: true,
      },
      isAdding: false,
      newInputs: "", // New prefix means new test
      newOutputs: "",
      editTestNumber: "", // Edit prefix means model state
      editTestTitle: "",
      editTestInputs: "",
      editTestOutput: "",
    };
  },

  head() {
    return {
      title:
        !this.isNotFound && !this.isLoading
          ? `Edit ${this.title} Coding Challenge - Apricot`
          : this.isNotFound && !this.isLoading
          ? "Invalid Coding Challenge - Apricot"
          : "Loading Coding Challenge - Apricot",
    };
  },

  mounted() {
    this.isLoading = true;
    const challengeId = this.$route.params.id;
    this.fetchChallenge(challengeId);
  },

  methods: {
    /**
     * Fetch challnge by id
     */
    async fetchChallenge(id) {
      try {
        let response = await this.$axios.get(`/api/v1/coding-challenges/${id}`);
        response = response.data;

        if (response.success) {
          // Record is found
          this.isNotFound = false;
          this.title = response.data.title;
          this.editorInitalValue = response.data.description;
          this.tests = JSON.parse(response.data.tests);
          this.isLoading = false;
        } else {
          // Invalid params and not record
          this.isNotFound = true;
          this.isLoading = false;
        }
      } catch (e) {
        this.isLoading = false;
        this.isNotFound = true;
      }
    },
    /**
     * Validate and update a coding challenge, redirect to coding challenge lists
     */
    async updateChallenge() {
      this.isLoading = true;
      const title = this.title;
      const description = this.$refs["md-editor"].invoke("getMarkdown");
      const tests = this.tests;

      if (!title)
        this.$toasted.show("Please give coding challenge title", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
      else if (!description)
        this.$toasted.show("Please give coding challenge description", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
      else if (tests.length <= 0)
        this.$toasted.show("Please give at least one coding challenge test", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
      else {
        // Every things perfect
        try {
          let response = await this.$axios.put(
            `/api/v1/coding-challenges/${this.$route.params.id}`,
            {
              title,
              description,
              tests,
            }
          );
          response = response.data;

          if (response.success) {
            this.$toasted.show("Coding challenge updated!", {
              theme: "toasted-primary",
              type: "success",
              position: "bottom-right",
              duration: 5000,
            });
            this.$router.push("/coding-challenges");
          }
        } catch (e) {}
      }

      this.isLoading = false;
    },
    /**
     * When Add challenge adding, Add button is disable
     */
    addTest() {
      this.isAdding = true;
    },
    /**
     * Add a test to tests and clear state
     */
    saveTest() {
      if (this.newInputs && this.newOutputs) {
        this.tests.push({ inputs: this.newInputs, output: this.newOutputs });
        this.newInputs = "";
        this.newOutputs = "";
        this.isAdding = false;
      } else {
        // Either inputs or stdout not supplied
        this.$toasted.show("Please enter valid test inputs and output", {
          theme: "toasted-primary",
          type: "success",
          position: "bottom-right",
          duration: 5000,
        });
      }
    },
    /**
     * User edit particular test and put update state of model to selected input tests
     */
    editTest(index) {
      this.editTestNumber = index;
      this.editTestTitle = `Edit Test #${index + 1}`;
      this.$refs.editTestModel.show();
      this.editTestInputs = this.tests[index].inputs;
      this.editTestOutput = this.tests[index].output;
    },
    /**
     * Hide edit test model
     */
    discardEditTest() {
      this.$refs.editTestModel.hide();
    },
    /**
     * Update test of challenge
     */
    updateEditTest() {
      this.tests[this.editTestNumber].inputs = this.editTestInputs;
      this.tests[this.editTestNumber].output = this.editTestOutput;
      this.$refs.editTestModel.hide();
    },
    /**
     * User mistake click on Add test button and discard
     */
    discardTest() {
      // Clear test case input, expected output, discard
      this.newInputs = "";
      this.newOutputs = "";
      this.isAdding = false;
    },
    /**
     * Delete test from tests array
     */
    deleteTest(index) {
      // We sent ordianl index
      this.tests.splice(index, 1);
    },
  },
};
</script>
