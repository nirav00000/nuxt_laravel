<template>
  <div class="container-fluid">
    <div class="hdr w-100 d-flex align-items-center justify-content-between">
      <div class="candidacy-name">
        {{
          candidacy &&
          candidacy.candidate &&
          candidacy.candidate.name | capitalize
        }}
      </div>
      <div class="d-flex align-items-center">
        <nuxt-link
          v-if="candidacy.candidate && isAdmin && !disableComponent"
          class="btn btn-primary"
          :to="{
            path: '/candidacy/' + candidacy.candidate.key + '/edit',
          }"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 0 24 24"
            width="24px"
            fill="#000000"
          >
            <path d="M0 0h24v24H0V0z" fill="none" />
            <path
              fill="#fff"
              d="M14.06 9.02l.92.92L5.92 19H5v-.92l9.06-9.06M17.66 3c-.25 0-.51.1-.7.29l-1.83 1.83 3.75 3.75 1.83-1.83c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.2-.2-.45-.29-.71-.29zm-3.6 3.19L3 17.25V21h3.75L17.81 9.94l-3.75-3.75z"
            /></svg
          >&nbsp;&nbsp;Edit Candidacy
        </nuxt-link>
        &nbsp;&nbsp;
        <b-button
          v-if="isAdmin && !disableComponent"
          id="close-candidacy-btn"
          v-b-modal.status-modal
          :variant="statusVariant"
          class="font-weight-bold d-flex align-items-center"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            fill="currentColor"
            class="bi bi-x-lg"
            viewBox="0 0 16 16"
          >
            <path
              d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"
            />
          </svg>
          &nbsp;&nbsp;Close Candidacy</b-button
        >
        <b v-if="disableComponent" class="px-2">Candidacy Closed</b>
      </div>
    </div>
    <b-row>
      <b-col>
        <div class="mb-4">
          <!-- Modal: Assign Stage -->
          <b-modal
            ref="assignStageModal"
            scrollable
            size="lg"
            title="Assign Stage"
            hide-footer
          >
            <b-form @submit.stop.prevent="onStageAssign">
              <b-form-group id="stages-label" label="Stages" label-for="stages">
                <b-form-select
                  id="stages"
                  v-model="stageSelected"
                  :options="stages"
                  required
                >
                  <template #first>
                    <b-form-select-option :value="null" disabled>{{
                      stages.length > 0
                        ? "-- Please select a stage --"
                        : "No Stages found"
                    }}</b-form-select-option>
                  </template>
                </b-form-select>
              </b-form-group>
              <b-form-group
                id="assignee-label"
                label="Asignees"
                label-for="assignee"
              >
                <b-form-select
                  id="assignee"
                  v-model="assigneeSelected"
                  required
                  :options="assignees"
                >
                  <template #first>
                    <b-form-select-option :value="null" disabled>{{
                      assignees.length > 0
                        ? "-- Please select an assignee --"
                        : "No Assignee found"
                    }}</b-form-select-option>
                  </template>
                </b-form-select>
              </b-form-group>
              <div>
                <Fields
                  v-for="(field, index) in fields"
                  :key="index"
                  v-model="field.value"
                  :field="field"
                />
              </div>
              <b-col cols="12 p-0" align="right"
                ><b-button id="assign-stage" type="submit" variant="success"
                  >Assign Stage</b-button
                ></b-col
              >
            </b-form>
          </b-modal>
          <!-- Modal: Add feedback -->
          <b-modal
            id="add-feedback-modal"
            ref="addFeedbackModal"
            scrollable
            size="xl"
            title="Add Feedback"
            hide-footer
          >
            <form @submit.stop.prevent="onFeedbackSubmit">
              <b-row>
                <b-col cols="2" align="left">
                  <label class="font-weight-bold">Verdict:</label></b-col
                >
                <b-col cols="10">
                  <b-form-radio-group
                    id="radioVerdict"
                    v-model="verdictSelected"
                    :options="verdicts"
                    class="mb-3"
                    value-field="item"
                    text-field="name"
                    required
                    disabled-field="notEnabled"
                  ></b-form-radio-group>
                </b-col>
                <div class="w-100"></div>
                <b-col cols="2" align="left">
                  <label class="font-weight-bold">Description:</label></b-col
                >
                <b-col cols="10">
                  <client-only>
                    <MDEditor
                      id="feedbackMDEitor"
                      ref="feedbackMDEditor"
                      :initial-value="editorInitalValue"
                      :options="editorOptions"
                    />
                  </client-only>
                </b-col>
                <div class="w-100"><br /></div>
                <b-col cols="12" align="right"
                  ><b-button
                    id="submit-feedback"
                    type="submit"
                    variant="success"
                    >Submit Feeback</b-button
                  ></b-col
                >
              </b-row>
            </form>
          </b-modal>

          <!-- Model : Submit the reason to close candidacy -->
          <b-modal
            id="status-modal"
            ok-only
            ok-variant="danger"
            ok-title="Yes, Close Candidacy"
            button-size="md"
            centered
            :static="true"
            title="Are you sure?"
            @ok="submitReason"
          >
            <b-form-textarea
              id="input-live-help-close-candidacy"
              v-model="reasonToCloseCandidacy"
              placeholder="Enter the reason"
              rows="3"
              :autofocus="true"
              max-rows="6"
            ></b-form-textarea>
          </b-modal>

          <!-- Model : Submit the reason to close stage -->
          <b-modal
            id="close-stage-modal"
            ok-variant="success"
            cancel-variant="danger"
            button-size="sm"
            centered
            :static="true"
            title="Are you sure?"
            @ok="completeStage"
            @cancel="
              {
                reasonToCloseStage = '';
              }
            "
          >
            <b-form-input
              id="input-live-help-close-stage"
              v-model="reasonToCloseStage"
              placeholder="Enter the reason"
            ></b-form-input>
            <b-form-text id="input-live-help-close-stage"
              >Enter the reason to close stage</b-form-text
            >
          </b-modal>
          <!-- Details -->
          <b-card>
            <b-card-title>
              <b-row>
                <b-col cols="6" class="w-100">Candidate Infomation</b-col>
              </b-row>
            </b-card-title>
            <b-card-sub-title> All details about candidate.</b-card-sub-title>
            <hr />
            <!-- candidacy -->
            <b-row
              v-for="(value, index) in candidacy"
              :key="'candidacy' + index"
            >
              <b-col
                v-if="
                  value !== candidacy.candidate &&
                  index !== 'key' &&
                  index !== 'metadata'
                "
                cols="3"
                align="left"
              >
                <label class="font-weight-bold"
                  >{{ index | capitalize | split }}:</label
                ></b-col
              >
              <b-col
                v-if="
                  value !== candidacy.candidate &&
                  index !== 'key' &&
                  index !== 'metadata'
                "
                cols="9"
              >
                <label>{{ value }}</label>
              </b-col>
            </b-row>
            <!-- candidate -->
            <b-row
              v-for="(value, index) in candidacy && candidacy.candidate"
              :key="'candidate' + index"
            >
              <b-col
                v-if="value !== candidacy.candidate.metadata && index !== 'key'"
                cols="3"
                align="left"
              >
                <label class="font-weight-bold"
                  >{{ index | capitalize | split }}:</label
                ></b-col
              >
              <b-col
                v-if="value !== candidacy.candidate.metadata && index !== 'key'"
                cols="9"
              >
                <label>{{ value }}</label>
              </b-col>
            </b-row>
            <!-- candidate_metadata -->
            <b-row
              v-for="(value, index) in candidacy &&
              candidacy.candidate &&
              candidacy.candidate.metadata"
              :key="'candidate_metadata' + index"
            >
              <b-col cols="3" align="left">
                <label class="font-weight-bold"
                  >{{ index | capitalize | split }}:</label
                ></b-col
              >
              <b-col cols="9">
                <label>{{ value }}</label>
              </b-col>
            </b-row>
            <!-- candidacy resumes -->
            <b-row
              v-if="
                !(
                  candidacy &&
                  candidacy.metadata &&
                  candidacy.metadata.resumes &&
                  candidacy.metadata.resumes.length
                )
              "
            >
              <b-col cols="3" class="font-weight-bold" align="left"
                >Resumes:
              </b-col>
              <b-col cols="9"
                ><i><b>N/A</b></i></b-col
              >
            </b-row>
            <b-row
              v-for="(value, index) in orderedResumes"
              :key="'resumes_' + index"
            >
              <b-col
                v-if="index == 0"
                cols="3"
                class="font-weight-bold"
                align="left"
                >Resumes:</b-col
              >
              <b-col v-else cols="3"></b-col>
              <b-col cols="9" align="left">
                <a
                  v-b-tooltip.hover
                  :href="value.link"
                  :title="value.added_at | format"
                  target="_blank"
                  >{{ index + 1 }} - {{ value.link }}</a
                >
              </b-col>
            </b-row>
          </b-card>
        </div>
      </b-col>
      <!-- Right-Bar -->
      <b-col class="col-md">
        <!-- Given Feedbacks Card -->
        <div class="custom-card">
          <div class="custom-card-header">
            <div>
              <h4>Feedbacks</h4>
              <p class="card-subtitle text-muted mb-2">
                display all given feedbacks
              </p>
            </div>
            <div>
              <b-button
                v-if="!disableComponent"
                id="submit-feedback"
                class="btn"
                @click="addFeedback"
                >Add Feedback</b-button
              >
            </div>
          </div>
          <hr />
          <div v-if="!feedbacks.length">No feedback found!</div>
          <div v-if="feedbacks.length" class="custom-card-main">
            <Feedback
              v-for="f in feedbacks"
              :key="f.metadata.feedback_key"
              :feedback-key="f.metadata.feedback_key"
              :feedback-actor="f.actor"
              :feedback-verdict="f.metadata.verdict"
            />
          </div>
        </div>
        <!-- All Assigned Stages -->
        <div class="custom-card">
          <div class="custom-card-header">
            <div>
              <h4>Stages</h4>
              <p class="card-subtitle text-muted mb-2">
                display all the assigned stages also complete active stage
              </p>
            </div>
            <div>
              <b-button
                v-if="isAdmin && !disableComponent"
                id="assign-stage"
                class="btn"
                @click="assignStage"
                >Assign Stage</b-button
              >
            </div>
          </div>
          <hr />
          <div
            v-if="
              candidacy && candidacy.metadata && candidacy.metadata.stages
                ? Object.keys(candidacy.metadata.stages) <= 0
                : true
            "
          >
            No stage is assigned to you!
          </div>
          <b-list-group
            v-if="
              candidacy &&
              candidacy.metadata &&
              candidacy.metadata.stages &&
              Object.keys(candidacy.metadata.stages).length
            "
          >
            <b-list-group-item
              v-for="(stage, index) in candidacy &&
              candidacy.metadata &&
              candidacy.metadata.stages"
              :key="index"
              variant="warning"
            >
              <b-row
                ><b-col cols="10">
                  {{ stage.stage_name | split | capitalize }}
                </b-col>
                <!-- <b-col cols="5"> {{ stage.status | split | capitalize }}</b-col> -->
                <b-col cols="2" align="right">
                  <b-icon
                    v-if="
                      stage && stage.status !== 'completed' && !disableComponent
                    "
                    icon="x-circle"
                    font-scale="1"
                    class="control"
                    @click="showCloseStageModal(stage)"
                  ></b-icon> </b-col
              ></b-row>
            </b-list-group-item>
          </b-list-group>
        </div>
      </b-col>
    </b-row>
    <!-- History -->
    <div>
      <b-card
        title="History"
        sub-title="All history of candidacy."
        class="mb-4"
      >
        <hr />
        <timeline v-if="histories.length" :items="histories"></timeline>
        <div v-else>No history</div>
      </b-card>
      <br />
    </div>
  </div>
</template>

<script>
import Feedback from "@/components/Feedback.vue";
import FeedbackTemplate from "@/static/templates/Feedback.txt";

export default {
  components: {
    Feedback,
  },
  layout: "admin",
  async asyncData({ store, route }) {
    await store.dispatch("candidacy", route.params.id);
    await store.dispatch("stageList");
    await store.dispatch("assigneeList");
    await store.dispatch("historyList", route.params.id);
  },
  data() {
    return {
      stageSelected: null,
      description: "",
      selectedStageToClose: "",
      assigneeSelected: null,
      reasonToCloseCandidacy: "",
      reasonToCloseStage: "",
      verdictSelected: null,
      fieldValue: null,
      verdicts: [
        { item: "yes", name: "Yes" },
        { item: "no", name: "No" },
        { item: "maybe", name: "Maybe" },
      ],
      editorOptions: {
        hideModeSwitch: true,
      },
      editorInitalValue: FeedbackTemplate,
    };
  },
  head() {
    return {
      title: "Candidacy Information - Apricot",
    };
  },
  computed: {
    orderedResumes() {
      if (
        this.candidacy &&
        this.candidacy.metadata &&
        this.candidacy.metadata.resumes
      ) {
        const resumes = this.candidacy.metadata.resumes;
        resumes.sort(function (a, b) {
          const c = new Date(a.added_at);
          const d = new Date(b.added_at);

          return d - c;
        });

        return resumes;
      } else {
        return [];
      }
    },
    isAdmin() {
      return this.$store.getters.isAdmin;
    },

    disableComponent() {
      return this.candidacy.final_status !== "active";
    },

    statusVariant() {
      return this.candidacy.final_status === "active"
        ? "danger"
        : "outline-dark";
    },
    isLoading() {
      return this.$store.getters.getSpinner;
    },
    candidacy() {
      return this.$store.getters.getCandidacy
        ? this.$store.getters.getCandidacy
        : {};
    },

    stages() {
      const _stages = [];
      this.$store.getters.getStageList &&
        this.$store.getters.getStageList.forEach((element) => {
          const stage = { value: element.key, text: element.name };
          _stages.push(stage);
        });

      if (_stages.length > 0) this.setFirstStage(_stages[0].value);

      return _stages;
    },
    assignees() {
      const _assignee = [];
      this.$store.getters.getAssigneeList &&
        this.$store.getters.getAssigneeList.users &&
        this.$store.getters.getAssigneeList.users.forEach((element) => {
          const assignee = { value: element.key, text: element.name };
          _assignee.push(assignee);
        });

      if (_assignee.length > 0) this.setFirstAssignee(_assignee[0].value);

      return _assignee;
    },
    histories() {
      return this.$store.getters.getHistoryList &&
        this.$store.getters.getHistoryList.histories
        ? this.$store.getters.getHistoryList.histories
        : [];
    },

    feedbacks() {
      return this.histories.filter(
        (feedback) =>
          !feedback.stage_name &&
          !feedback.status &&
          feedback.metadata.feedback_key
      );
    },

    fields() {
      if (this.$store.getters.getStageList) {
        const element = this.$store.getters.getStageList.find((obj) => {
          return obj.key === this.stageSelected;
        });

        if (element && element.metadata && element.metadata.fields) {
          const _fields = [];
          element.metadata.fields.forEach((field) => {
            _fields.push({
              key: field,
              value: "",
            });
          });

          return _fields;
        } else {
          return null;
        }
      } else {
        return [];
      }
    },
  },

  methods: {
    showCloseStageModal(stage) {
      this.$bvModal.show("close-stage-modal");
      this.selectedStageToClose = stage;
    },
    completeStage() {
      const payload = {
        stage_name: this.selectedStageToClose.stage_name,
        stage_closing_reason: this.reasonToCloseStage,
      };
      const key = this.$route.params.id;
      this.$store.dispatch("completeStage", { payload, key }).then(() => {
        this.reasonToCloseStage = "";
      });
    },
    submitReason() {
      const key = this.$route.params.id;
      const payload = {
        candidacy_closing_reason: this.reasonToCloseCandidacy,
      };
      this.$store.dispatch("closeCandidacy", { payload, key }).then(() => {
        this.reasonToCloseCandidacy = "";
      });
    },
    onStageAssign() {
      if (this.stageSelected !== null) {
        const payload = {
          assignee_key: this.assigneeSelected,
          meta: {},
        };

        if (this.fields) {
          this.fields.forEach((object) => {
            payload.meta[object.key] = object.value;
          });
        }

        const stageKey = this.stageSelected;
        const candidacyKey = this.$route.params.id;
        this.$store
          .dispatch("stageAssignment", { payload, candidacyKey, stageKey })
          .then(() => {
            this.emptyFields();
            this.$refs.assignStageModal.hide();
          })
          .catch(() => {
            this.emptyFields();
            this.$refs.assignStageModal.hide();
          });
      }
    },
    onFeedbackSubmit() {
      const payload = {
        verdict: this.verdictSelected,
        description: this.$refs.feedbackMDEditor.invoke("getMarkdown"),
      };
      const key = this.$route.params.id;
      this.$store.dispatch("addFeedback", { payload, key }).then(() => {
        this.emptyFields();
      });
      this.$refs.addFeedbackModal.hide();
    },
    emptyFields() {
      this.stageSelected = null;
      this.verdictSelected = null;
      this.description = null;
      this.assigneeSelected = null;
    },
    addFeedback() {
      this.$refs.addFeedbackModal.show();
    },
    assignStage() {
      this.$refs.assignStageModal.show();
    },
    setFirstStage(key) {
      this.stageSelected = key;
    },
    setFirstAssignee(key) {
      this.assigneeSelected = key;
    },
  },
};
</script>

<style>
.card-columns {
  column-count: 1;
}

.control:hover {
  cursor: pointer;
  color: rgb(236, 2, 2);
  text-decoration: none;
  transform: scale(1.1);
}

#close-candidacy-btn,
#submit-feedback,
#assign-stage {
  font-weight: bold;
  /* letter-spacing: 2px; */
}

#close-candidacy-btn {
  background: #ef4444;
}

#submit-feedback,
#assign-stage {
  background: #10b981;
}

.card-title:first {
  margin: 0;
}

.candidacy-name {
  font-size: 22px;
  font-weight: bold;
  padding-left: 8px;
  color: #495057;
}

.hdr {
  height: 72px;
  margin: 8px 0;
}

.feedback-card {
  width: 100%;
  padding: 12px;
  box-shadow: 0 1px 3px 0 hsla(0, 0%, 0%, 0.2);
  border-radius: 4px;
  border: 1px solid #00000008;
  cursor: pointer;
  margin-top: 12px;
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
.custom-card {
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 1px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
  margin-bottom: 12px;
  padding: 1.25rem;
}
.custom-card .custom-card-header {
  width: 100%;
  display: flex;
  height: 52px;
  align-items: center;
  justify-content: space-between;
}
.custom-card .custom-card-main {
  margin-top: 8px;
}
</style>
