<template>
  <div class="m-auto w-75">
    <b-card class="m-3">
      <b-card-title class="pb-3 font-weight-bold border-bottom border-primary"
        >Update Candidacy Details</b-card-title
      >
      <b-overlay :show="isLoading" rounded="sm">
        <b-form @submit.prevent="editCandidateDetails">
          <b-form-group
            id="input-group-edit-details"
            label="Name:"
            label-for="input-name"
          >
            <b-form-input
              id="input-name"
              v-model="form.name"
              type="text"
              placeholder="Enter name"
              @input="errors.name = ''"
            ></b-form-input>
            <i v-if="errors.name" class="text-danger">{{ errors.name }}</i>
          </b-form-group>
          <b-form-group
            id="input-group-edit-details"
            label="Email:"
            label-for="input-email"
          >
            <b-form-input
              id="input-email"
              v-model="form.email"
              placeholder="Enter email"
              @input="errors.email = ''"
            ></b-form-input>
            <i v-if="errors.email" class="text-danger">{{ errors.email }}</i>
          </b-form-group>
          <b-form-group
            id="input-group-edit-details"
            label="Contact:"
            label-for="input-contact"
          >
            <b-form-input
              id="input-contact"
              v-model="form.metadata.contact_no"
              type="number"
              placeholder="Enter contact"
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-edit-details"
            label="Education:"
            label-for="input-education"
          >
            <b-form-input
              id="input-education"
              v-model="form.metadata.education"
              placeholder="Enter education"
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-edit-details"
            label="College:"
            label-for="input-collage"
          >
            <b-form-input
              id="input-collage"
              v-model="form.metadata.college"
              placeholder="Enter college"
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-edit-details"
            label="Experience:"
            label-for="input-experience"
          >
            <b-form-input
              id="input-experience"
              v-model="form.metadata.experience"
              type="number"
              placeholder="Enter experience (in months)(in integer)"
              min="0"
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="input-group-edit-details"
            label="Last Company:"
            label-for="input-company"
          >
            <b-form-input
              id="input-company"
              v-model="form.metadata.last_company"
              placeholder="Enter company"
            ></b-form-input>
          </b-form-group>
          <b-form-group id="input-group-edit-details" label="Link New Resume :">
            <b-form-input
              id="resume-url"
              v-model="form.resume"
              type="url"
              placeholder="Enter URL of resume(optional)"
            ></b-form-input>
          </b-form-group>
          <b-button id="btn-update" type="submit" variant="success"
            >Update</b-button
          >
          <b-button
            id="btn-cancel"
            type="button"
            variant="secondary"
            @click="goToHistoryPage"
            >Cancel</b-button
          >
          <!-- this variable is for testing -->
        </b-form>
      </b-overlay>
    </b-card>
  </div>
</template>

<script>
import Vue from "vue";

export default {
  layout: "admin",
  async asyncData({ store, route }) {
    const form = await store.dispatch("candidate", route.params.id).then(() => {
      if (store.getters.getCandidate) {
        const _candidate = store.getters.getCandidate.candidate;

        if (_candidate.metadata === null) {
          _candidate.metadata = {};
        }

        return _candidate;
      }
    });

    if (form) {
      return { form };
    }
  },
  data() {
    return {
      form: {
        name: "",
        email: "",
        metadata: {},
        resume: "",
      },
      errors: {
        name: "",
        email: "",
      },
    };
  },
  computed: {
    isLoading() {
      return this.$store.getters.getSpinner;
    },
    candidacy() {
      return this.$store.getters.getCandidacy
        ? this.$store.getters.getCandidacy
        : {};
    },
  },

  methods: {
    checkForm() {
      this.errors = {};

      if (!this.form.name) {
        this.errors.name = "Name is required.";
      }

      if (!this.form.email) {
        this.errors.email = "Email is required.";
      }

      if (!this.validateEmail(this.form.email)) {
        this.errors.email = "Please enter a valid email.";
      }

      if (!Object.keys(this.errors).length) {
        return true;
      } else {
        return false;
      }
    },
    validateEmail(email) {
      const re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

      return re.test(email.toLowerCase());
    },
    editCandidateDetails() {
      if (!this.checkForm()) {
        Vue.toasted.show("Please enter correct details", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });

        return;
      }

      if (this.form && this.form.name && this.form.email) {
        for (const key in this.form.metadata) {
          if (this.form.metadata[key] === "") {
            delete this.form.metadata[key];
          }
        }

        const payload = this.form;
        payload.candidacy_key = this.candidacy.key;
        payload.candidacy_resume = this.form.resume;
        const key = this.$route.params.id;
        this.$store.dispatch("updateCandidate", { payload, key });
      }
    },
    goToHistoryPage() {
      this.$router.back();
    },
  },
};
</script>
