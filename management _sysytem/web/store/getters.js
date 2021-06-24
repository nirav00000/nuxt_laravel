export default {
  getStages(state) {
    return state.stages;
  },
  getCandidacyList: (state) => {
    return state.candidacyList;
  },
  getStageList: (state) => {
    return state && state.stageList ? state.stageList : null;
  },
  getLoading: (state) => {
    return state.loading;
  },
  getSpinner: (state) => {
    return state.spinner;
  },
  getCandidacy: (state) => {
    return state.candidacy;
  },
  getCandidate: (state) => {
    return state.candidate;
  },
  getAssigneeList: (state) => {
    return state.assigneeList;
  },
  getError: (state) => {
    return state.error;
  },
  getHistoryList: (state) => {
    return state.historyList;
  },
  getToken: (state) => {
    return state.token;
  },
  getUser: (state) => {
    return state.user;
  },
  getGroups: (state) => {
    return state.groups;
  },
  isAdmin: (state) => {
    // In stagging inside group set array
    if (Array.isArray(state.groups))
      return state.groups.includes(process.env.LDAP_ADMIN_GROUP_NAME);
    else
      return state.groups
        .split(",")
        .includes(process.env.LDAP_ADMIN_GROUP_NAME);
  },
  getQuestionnaireList: (state) => {
    return state.questionnaireList;
  },
};
