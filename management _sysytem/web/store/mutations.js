export default {
  setCandidacyList(state, { data }) {
    state.candidacyList = data;
  },
  setStageList(state, { data }) {
    state.stageList = data.stages;
  },
  loading(state, data) {
    state.loading = data;
  },
  spinner(state, data) {
    state.spinner = data;
  },
  setCandidacy(state, { data }) {
    state.candidacy = data;
  },
  setCandidate(state, { data }) {
    state.candidate = data;
  },
  setAssigneeList(state, { data }) {
    state.assigneeList = data;
  },
  setError(state, data) {
    state.error = data;
  },
  setHistoryList(state, { data }) {
    state.historyList = data;
  },
  setToken(state, token) {
    state.token = token;
  },
  revokeToken(state) {
    state.token = null;
    state.groups = null;
  },
  setUser(state, { data }) {
    state.user = data;
  },
  setGroups(state, groups) {
    state.groups = groups;
  },
  setQuestionnaireList(state, { data }) {
    state.questionnaireList = data;
  },
};
