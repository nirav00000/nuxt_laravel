<template>
  <div class="container-fluid">
    <div class="mt-4">
      <div
        class="d-flex flex-row justify-content-between align-items-center px=2 flex-wrap"
      >
        <div style="font-size: 24px">Add Coding Challenge</div>
        <div v-if="!isLoading">
          <button
            id="addChallenge"
            class="btn btn-primary"
            @click="addChallenge"
          >
            Save
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

    <div v-if="!isLoading">
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
              id="description"
              ref="md-editor"
              :initial-value="editorInitalValue"
              :options="editorOptions"
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
                  <button class="btn" title="Edit" @click="editTest(index)">
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
                  <button class="btn" title="Delete" @click="deleteTest(index)">
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
            <div id="add-test-popup" class="card">
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
      title: "",
      description: "",
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
      editorInitalValue: "# Write something",
    };
  },

  head() {
    return {
      title: "Add new coding challenge - Apricot",
    };
  },

  mounted() {
    this.isLoading = false;
  },

  methods: {
    /**
     * Validate and create a coding challenge, redirect to coding challenge lists
     */
    async addChallenge() {
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
          let response = await this.$axios.post(`/api/v1/coding-challenges`, {
            title,
            description,
            tests,
          });
          response = response.data;

          if (response.success) {
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
          type: "error",
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
