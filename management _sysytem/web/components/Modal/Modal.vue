<template>
  <div class="indicate-block">
    <b-modal
      id="modal-prevent-closing"
      ref="modal"
      title="Submit Stage Detail"
      :static="true"
      @ok="handleOk"
      @hidden="handleHide"
    >
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <div ref="indicate">
          <b-form-group
            label="Name"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
            <b-form-input
              id="name"
              v-model="dataComponent.name"
              class="name"
              name="name"
              required
            ></b-form-input>
            <i v-if="errors.name" class="text-danger">{{ errors.name }}</i>
          </b-form-group>

          <b-form-group label="Type">
            <div>
              <b-form-select
                id="type"
                v-model="dataComponent.type"
                :options="options"
                name="type"
                required
                @change="setSelectedType"
              ></b-form-select>
              <!--// 3. option.text to show //option.value passed to v-model & @change(option.value)  -->
            </div>
            <i v-if="errors.type" class="text-danger">{{ errors.type }}</i>
          </b-form-group>

          <b-form-group
            v-if="dataComponent.type === 'questionnaire'"
            label="Questionnaire"
          >
            <div>
              <b-form-select
                id="questionnaire"
                v-model="dataComponent.questionnaire_key"
                :options="questionnaireOptions"
                name="questionnaire"
                required
                @change="setSelectedQuestionnaire"
              ></b-form-select
              ><!--// 3. same as above field-->
            </div>
            <i v-if="errors.questionnaire" class="text-danger">{{
              errors.questionnaire
            }}</i>
          </b-form-group>

          <b-form-group
            label="Fields"
            label-for="metadata-input"
            invalid-feedback="Metadata is required "
          >
            <b-form-input
              id="type"
              v-model="dataComponent.metadata"
              class="fields"
              name="fields"
            ></b-form-input>
          </b-form-group>
        </div>
      </b-form>
    </b-modal>
  </div>
</template>

<script>
export default {
  props: {
    // 2.data for edit/add stage,, action=add in add stage(other empty)
    data: {
      type: Object,
      default: null,
      name: "",
      questionnaire_key: "",
    },
  },

  data() {
    return {
      dataComponent: this.data, // 2.props are taken into vatiable for accessing values
      options: [
        { value: null, text: "--Please select a type--" },
        { value: "questionnaire", text: "questionnaire" },
        { value: "code", text: "code" },
        { value: "interview", text: "interview" },
      ],
      errors: {
        name: "",
        type: "",
        questionnaire: "",
      },
    };
  },

  computed: {
    questionnaireOptions() {
      const questionnaires = this.$store.getters.getQuestionnaireList;
      const options = [
        { value: null, text: "--Please select a questionnaire--" },
      ];
      questionnaires.forEach((element) => {
        const stage = { value: element.key, text: element.name };
        options.push(stage);
      });

      return options;
    },
  },
  methods: {
    checkForm() {
      this.errors = {};

      if (!this.dataComponent.name) {
        this.errors.name = "Name is required.";
      }

      if (!this.dataComponent.type) {
        this.errors.type = "Type is required.";
      }

      if (
        this.dataComponent.type === "questionnaire" &&
        !this.dataComponent.questionnaire_key
      ) {
        this.errors.questionnaire = "Questionnaire field is required.";
      }

      if (!Object.keys(this.errors).length) {
        return true;
      } else {
        return false;
      }
    },
    handleHide() {
      this.errors = {};
    },
    setSelectedType(type) {
      this.dataComponent.type = type;
    },
    setSelectedQuestionnaire(questionnaire) {
      this.dataComponent.questionnaire_key = questionnaire;
    },
    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.handleSubmit();
    },
    handleSubmit() {
      if (!this.checkForm()) {
        return;
      }

      const action = this.dataComponent.action;

      if (action === "add") {
        this.$store.dispatch("stagesTableAddNewRow", this.dataComponent);
      } else {
        this.$store.dispatch("stagesTableEditRow", this.dataComponent);
      }

      // Hide the modal manually
      this.$nextTick(() => {
        this.$bvModal.hide("modal-prevent-closing");
      });
    },
  },
};
</script>
