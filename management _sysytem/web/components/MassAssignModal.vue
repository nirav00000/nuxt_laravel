<template>
  <div class="indicate-block">
    <b-modal
      id="modal-mass-assign"
      ref="assignStageModal"
      title="Assign Stage"
      :static="true"
      hide-footer
      hide-header
    >
      <b-overlay :show="isLoading" rounded="sm">
        <b-card
          v-if="!disableComponent"
          title="Assign Stage"
          sub-title="Assign stage to candidate."
          class="mb-4"
        >
          <hr />
          <b-form @submit.stop.prevent="onStageAssign">
            <b-row>
              <b-col cols="2" align="left">
                <label class="font-weight-bold">Stage:</label></b-col
              >
              <b-col cols="10">
                <b-form-select
                  id="stages"
                  v-model="stageSelected"
                  :options="stagesInModal"
                  required
                >
                  <template #first>
                    <b-form-select-option :value="null" disabled
                      >-- Please select a stage --</b-form-select-option
                    >
                  </template>
                </b-form-select>
              </b-col>
              <div class="w-100"><br /></div>
              <b-col cols="2" align="left">
                <label class="font-weight-bold">Assignee:</label></b-col
              >
              <b-col cols="10">
                <b-form-select
                  id="assignee"
                  v-model="assigneeSelected"
                  required
                  :options="assigneesInModal"
                >
                  <template #first>
                    <b-form-select-option :value="null" disabled
                      >-- Please select an assignee --</b-form-select-option
                    >
                  </template>
                </b-form-select>
              </b-col>
              <div class="w-100"><br /></div>
              <b-col cols="12">
                <Fields
                  v-for="(field, index) in fields"
                  :key="index"
                  v-model="field.value"
                  :field="field"
                />
              </b-col>
              <b-col cols="12" align="right"
                ><b-button
                  id="assignStage"
                  type="submit"
                  variant="success"
                  @click="onStageAssign"
                  >ASSIGN</b-button
                ></b-col
              >
            </b-row>
          </b-form>
        </b-card>
      </b-overlay>
    </b-modal>
  </div>
</template>

<script>
export default {
  props: {
    stages: {
      type: Array,
      default: null,
    },
    assignees: {
      type: Array,
      default: null,
    },
    candidacies: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      stageSelected: null,
      assigneeSelected: null,
      fieldValue: null,
      stagesInModal: this.stages,
      assigneesInModal: this.assignees,
    };
  },
  computed: {
    isLoading() {
      return this.$store.getters.getSpinner;
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
    onStageAssign() {
      const candidaciesArray = [];

      Object.keys(this.candidacies).map((key) => [
        candidaciesArray.push(this.candidacies[key]),
      ]);

      console.log(candidaciesArray);

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

        this.$refs.assignStageModal.hide();

        const stageKey = this.stageSelected;
        const valuesToBePassed = [];
        valuesToBePassed.push(payload);
        valuesToBePassed.push(stageKey);

        this.$emit("click", valuesToBePassed);
        this.emptyFields();
        this.$refs.assignStageModal.hide();
      }
    },

    emptyFields() {
      this.stageSelected = null;
      this.assigneeSelected = null;
    },
  },
};
</script>
