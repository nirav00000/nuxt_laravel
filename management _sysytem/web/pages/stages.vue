<template>
  <!-- container-fluid -->

  <div class="container-fluid">
    <Modal :data="payload" />
    <DeleteConfirm :data="deletePayload" />
    <!-- container -->
    <div class="container mt-5">
      <b-button
        id="add"
        v-b-modal.modal-prevent-closing
        variant="primary"
        class="float-right font-weight-bold"
        @click="openmodal((action = 'add'))"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          viewBox="0 0 16 16"
          class="bi bi-file-plus-fill"
        >
          <path
            d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM8.5 6v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 1 0z"
          ></path>
        </svg>
        &nbsp;&nbsp;Add Stage</b-button
      ><br />

      <label class="float-left"
        ><h3>Total stages : {{ items.length }}</h3></label
      >

      <div class="mb-1 mt-1"></div>
      <div v-if="!items.length && !isLoading" class="text-center">
        <br /><br /><br /><br /><br /><br /><span
          ><h1>No stages available.</h1></span
        >
      </div>
      <div v-if="isLoading" class="text-center">
        <br /><br /><br /><br /><br /><br /><span><h3>Loading...</h3></span>
      </div>
      <div v-if="items.length && !isLoading">
        <b-table
          :items="items"
          :fields="fields"
          stacked="md"
          show-empty
          small
          class="data-table-stages"
        >
          <template #cell(action)="row">
            <b-button
              :id="'edit' + row.index"
              v-b-modal.modal-prevent-closing
              :data-qa="'edit' + row.index"
              class="btn btn-primary edit font-weight-bold"
              @click="openmodal(row.item)"
            >
              Edit
            </b-button>
            <b-button
              :id="row.index + 1000"
              v-b-modal.delete-confirm
              class="btn btn-danger font-weight-bold"
              @click="deleteRow(row.item)"
            >
              Delete
            </b-button>
          </template>
        </b-table>
      </div>
    </div>
  </div>
</template>

<script>
import Modal from "@/components/Modal/Modal";
import DeleteConfirm from "@/components/DeleteConfirm.vue";

export default {
  components: {
    Modal,
    DeleteConfirm,
  },
  layout: "admin",

  data() {
    return {
      payload: {
        action: "",
        key: "",
        name: "",
        type: "",
        metadata: "",
        questionnaire_key: "",
      },
      deletePayload: {
        action: "",
        key: "",
        item_name: "",
      },

      isLoading: true,
      fields: ["name", "type", "action"],
    };
  },

  async fetch() {
    await this.$store.dispatch("stageList");
    this.isLoading = false;
    await this.$store.dispatch("questionnaireList");
  },
  computed: {
    items() {
      return this.$store.getters.getStageList
        ? this.$store.getters.getStageList
        : {};
    },
  },
  methods: {
    deleteRow(row) {
      this.deletePayload.action = "stagesTableRowDelete";
      this.deletePayload.key = row.key;
      this.deletePayload.item_name = "'" + row.name + "'" + " Stage";
    },

    openmodal(action) {
      // 1.opens modal for add stage
      if (action === "add") {
        // payload is data passed to props
        this.payload.name = "";
        this.payload.type = null;
        this.payload.key = "";
        this.payload.metadata = "";
        this.payload.action = "add";
        this.payload.questionnaire_key = null;
      } else {
        // 1.opens modal for edit stage with old data

        let fields = action.metadata.fields;

        if (fields) {
          fields = fields.join(",");
        }

        this.payload.name = action.name;
        this.payload.type = action.type;
        this.payload.key = action.key;
        this.payload.metadata = fields;
        this.payload.questionnaire_key = action.metadata.questionnaire_key
          ? action.metadata.questionnaire_key
          : null;
        this.payload.action = "edit";
      }
    },
  },
};
</script>
