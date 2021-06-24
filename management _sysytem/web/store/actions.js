// import histories from "../data/test-data/history.json";
// import { $route } from "~/data/test-data/test-modules";
// import assignees from "../data/test-data/assignee.json";

import axios from "axios";
import Vue from "vue";

function showToast(message, type) {
  Vue.toasted.show(message, {
    theme: "toasted-primary",
    type,
    position: "bottom-right",
    duration: 5000,
  });
}

export default {
  candidacyList({ commit }, query) {
    commit("spinner", true);

    return this.$axios({
      method: "get",
      url: this.getters.isAdmin
        ? "/api/v1/admin/candidacies"
        : "/api/v1/candidacies",
      params: query,
    })
      .then((response) => {
        commit("setCandidacyList", response.data);
      })
      .catch(() => {
        commit("setCandidacyList", {});
      });
  },

  stageList({ commit }) {
    return this.$axios({
      method: "get",
      url: "api/v1/stages",
    })
      .then((response) => {
        commit("setStageList", response.data);
      })
      .catch(() => {
        commit("setStageList", {});
      });
  },
  async questionnaireList({ commit }) {
    await this.$axios({
      method: "get",
      url: "api/v1/questionnaires",
    }).then((response) => {
      commit("setQuestionnaireList", response.data);
    });
  },
  async stagesTableRowDelete(context, data) {
    const response = await this.$axios
      .$delete("api/v1/stages/" + data)
      .then((response) => {
        showToast(response.message, "success");
        context.dispatch("stageList");
      });

    return response;
  },

  async stagesTableAddNewRow(context, data) {
    const obj = {};
    const str = data.metadata;
    let arrayOfString = [];
    arrayOfString = str.split(",");
    obj.fields = arrayOfString;
    // obj.actual = str;
    const response = await this.$axios
      .$post("api/v1/stages", {
        name: data.name,
        type: data.type,
        metadata: obj,
        questionnaire_key: data.questionnaire_key,
      })
      .then(() => {
        context.dispatch("stageList");
      });

    return response;
  },

  async stagesTableEditRow(context, data) {
    const obj = {};
    const str = data.metadata;
    let arrayOfString = [];
    arrayOfString = str.split(",");
    obj.fields = arrayOfString;
    // obj.actual = str;

    const response = await this.$axios
      .$put("api/v1/stages/" + data.key, {
        name: data.name,
        type: data.type,
        metadata: obj,
        questionnaire_key: data.questionnaire_key,
      })
      .then((response) => {
        showToast(response.message, "success");
        context.dispatch("stageList");
      });

    return response;
  },

  async assigneeList({ commit }) {
    await this.$axios({
      method: "get",
      url: "api/v1/users",
    }).then((response) => {
      commit("setAssigneeList", response.data);
    });
  },

  async historyList({ commit }, key) {
    await this.$axios({
      method: "get",
      url: this.getters.isAdmin
        ? "/api/v1/admin/candidacy_histories/" + key
        : "/api/v1/candidacy_histories/" + key,
    }).then((response) => {
      commit("setHistoryList", response.data);
    });
  },

  async stageAssignment(
    { commit, dispatch },
    { payload, candidacyKey, stageKey }
  ) {
    commit("spinner", true);
    await this.$axios({
      method: "post",
      url:
        "api/v1/admin/candidacies/" + candidacyKey + "/assignStage/" + stageKey,
      data: payload,
    }).then(() => {
      dispatch("historyList", candidacyKey);
      dispatch("candidacy", candidacyKey);
    });
  },

  async addFeedback({ dispatch }, { payload, key }) {
    await this.$axios({
      method: "post",
      url: "api/v1/candidacies/" + key + "/feedback",
      data: payload,
    }).then(() => {
      dispatch("historyList", key);
    });
  },

  async candidacy({ commit }, key) {
    await this.$axios({
      method: "get",
      url: "api/v1/candidacies/" + key,
    }).then((response) => {
      commit("setCandidacy", response.data);
    });
  },

  async candidate({ commit }, key) {
    await this.$axios({
      method: "get",
      url: "api/v1/candidates/" + key,
    }).then((response) => {
      commit("setCandidate", response.data);
    });
  },

  async updateCandidate({ commit }, { payload, key }) {
    commit("spinner", true);
    await this.$axios({
      method: "put",
      url: "api/v1/candidates/" + key,
      data: payload,
    }).then(() => {
      this._vm.$toasted.show("Candidate details updated successfully", {
        theme: "toasted-primary",
        type: "success",
        position: "bottom-right",
        duration: 5000,
      });
      this.$router.back();
    });
  },

  async closeCandidacy({ dispatch }, { payload, key }) {
    await this.$axios({
      method: "post",
      url: "/api/v1/admin/candidacies/" + key + "/close",
      data: payload,
    }).then(() => {
      dispatch("candidacy", key);
      dispatch("historyList", key);
      this._vm.$toasted.show("Candidacy closed successfully", {
        theme: "toasted-primary",
        type: "success",
        position: "bottom-right",
        duration: 5000,
      });
    });
  },

  async completeStage({ dispatch }, { payload, key }) {
    await this.$axios({
      method: "post",
      url: this.getters.isAdmin
        ? "/api/v1/admin/candidacies/" + key + "/closeStage"
        : "/api/v1/candidacies/" + key + "/closeStage",
      data: payload,
    }).then(() => {
      dispatch("candidacy", key);
      dispatch("historyList", key);
      this._vm.$toasted.show("Stage Completed successfully", {
        theme: "toasted-primary",
        type: "success",
        position: "bottom-right",
        duration: 5000,
      });
    });
  },

  headerToken({ commit }, auth) {
    commit("setToken", auth.token);
    commit("setGroups", auth.group);
  },

  async logOut({ commit }) {
    await this.$axios({
      method: "post",
      url: "api/v1/logout",
    }).then(() => {
      if (
        process.env.MODE !== "development" &&
        process.env.MODE !== "testing"
      ) {
        this.$cookies.remove("apricot");
        axios({ method: "GET", url: "/dex-oauth2/sign_out" })
          .then(() => commit("revokeToken"))
          .catch(() => {
            commit("revokeToken");
            window.location.reload();
          });
      } else {
        this.$cookies.remove("apricot");
        commit("revokeToken");
      }
    });
  },

  async user({ commit }) {
    await this.$axios({
      method: "post",
      url: "api/v1/users/me",
    }).then((response) => {
      commit("setUser", response.data);
    });
  },
};
