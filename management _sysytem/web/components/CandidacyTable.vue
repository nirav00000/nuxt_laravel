<template>
  <b-container fluid>
    <MassAssignModal
      :candidacies="selected"
      :stages="stages"
      :assignees="assignees"
      @click="emitBacKDataFromChild($event)"
    />

    <b-col lg="11">
      <form @submit.prevent="getFilteredData">
        <b-form-group
          label="Search :"
          label-for="filter-input"
          label-cols="2"
          align="right"
          label-size="md"
        >
          <b-input-group size="md">
            <b-form-input
              id="filter-input"
              v-model="filter"
              type="search"
              required
              placeholder="Type to Search"
            ></b-form-input>

            <b-input-group-append>
              <b-form-select
                id="filterSelected"
                v-model="filterSelected"
                required
                :options="searchFileds"
              >
                <template #first>
                  <b-form-select-option :value="null" disabled selected
                    >-- Please select an option --</b-form-select-option
                  >
                </template>
              </b-form-select>
              <b-button variant="outline-primary" type="submit"
                >Search</b-button
              >
              <b-button type="reset" variant="outline-dark" @click="onClear"
                >Clear</b-button
              >
              <b-button
                id="add"
                v-b-modal.modal-mass-assign
                variant="outline-danger"
                @click="isChecked"
                >MassAssign</b-button
              >
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
      </form>
    </b-col>
    <b-table
      responsive
      hover
      :busy="isLoading"
      :filter-included-field="filterSelected"
      :current-page="1"
      :per-page="items ? items.meta.per_page : null"
      head-variant="light"
      show-empty
      :items="items ? items.candidacies : null"
      :fields="fields"
      style="cursor: pointer"
      @row-clicked="openCandidacy"
    >
      <template #table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>
      <template #cell(check)="row">
        <b-form-group>
          <input
            key="row.item.key"
            type="checkbox"
            @click="checkData(row.item.key)"
          />
        </b-form-group>
      </template>
    </b-table>

    <b-row class="justify-content-center m-4">
      <b-pagination
        v-model="currentPage"
        align="fill"
        :total-rows="items ? items.meta.total : null"
        :per-page="items ? items.meta.per_page : null"
        size="sm"
        class="col-2 float-right"
        variant="info"
      ></b-pagination>
    </b-row>
  </b-container>
</template>

<script>
import MassAssignModal from "@/components/MassAssignModal";

export default {
  name: "CandidacyTable",
  components: {
    MassAssignModal,
  },
  props: {
    items: {
      type: Object,
      default: null,
    },
    stages: {
      type: Array,
      default: null,
    },
    assignees: {
      type: Array,
      default: null,
    },
  },
  data() {
    return {
      fields: [
        { key: "check", label: "Check" },
        { key: "candidate.name", label: "Name" },
        { key: "position", label: "Position" },
        { key: "final_status", label: "Status" },
      ],
      searchFileds: [
        { value: "candidate_name", text: "Name" },
        { value: "position", text: "Position" },
        { value: "final_status", text: "Status" },
      ],

      sortDirection: "",
      page: 1,
      filter: "",
      filterSelected: null,
      candidacyArray: [],
      selected: [],
      valueFromStageAssignModel: null,
    };
  },
  computed: {
    searchValidation: {
      get() {
        return null;
      },
      set() {},
    },
    radioValidation: {
      get() {
        return null;
      },
      set() {},
    },
    isLoading() {
      return this.$store.getters.getSpinner;
    },
    currentPage: {
      get() {
        return this.$route.query.page ? this.$route.query.page : 1;
      },
      set(value) {
        this.page = value;

        if (this.$route.query) {
          this.$router.replace({
            query: { ...this.$route.query, page: value },
          });
        } else {
          const _query = { page: value };
          this.$router.push({ query: _query });
        }
      },
    },
  },
  methods: {
    checkData(key) {
      if (this.selected.includes(key)) {
        this.selected.splice(
          this.selected.findIndex((v) => v === key),
          1
        );
      } else {
        this.selected.push(key);
      }

      this.selected = JSON.parse(JSON.stringify(this.selected));
    },

    emptyArray() {
      this.selected = [];
    },

    isChecked() {
      if (!this.selected) {
      }
    },

    emitBacKDataFromChild(data) {
      const payload = data[Object.keys(data)[0]];
      const stageKey = data[Object.keys(data)[1]];
      const candidaciesArray = this.selected;
      const promiseArray = [];

      for (let key in candidaciesArray) {
        const candidacyKey = candidaciesArray[key];

        key = new Promise(() => {
          this.$store.dispatch("stageAssignment", {
            payload,
            candidacyKey,
            stageKey,
          });
        });

        promiseArray.push(key);
      }

      Promise.all(promiseArray)
        .then(() => {
          console.log("stage assigned successfully");
        })
        .catch(() => {
          console.log("error is there");
        });

      this.emptyArray();
    },

    getFilteredData() {
      const _query = {};
      this.filterSelected &&
        this.filter &&
        (_query[this.filterSelected] = this.filter);

      if (JSON.stringify(this.$route.query) !== JSON.stringify(_query)) {
        this.$router.replace({ path: this.$route.path, query: _query });
      }
    },
    onClear() {
      this.$router.push({ path: this.$route.path, query: null });
      this.filter = "";
      this.filterSelected = null;
    },
    openCandidacy(record) {
      this.$router.push(`/candidacy/${record.key}`);
    },
  },
};
</script>
