<template>
  <div class="container-fluid">
    <div class="py-1 my-1">
      <CandidacyTable
        :items="candidacyList"
        :stages="stages"
        :assignees="assignees"
      />
    </div>
  </div>
</template>

<script>
export default {
  layout: "admin",

  async asyncData({ store, query }) {
    await store.dispatch("candidacyList", query);
    await store.dispatch("stageList");
    await store.dispatch("assigneeList");
  },
  data() {
    return {};
  },

  head() {
    return {
      title: "Candidacies - Apricot",
    };
  },
  computed: {
    candidacyList() {
      if (this.$store.getters.getCandidacyList) {
        return this.$store.getters.getCandidacyList;
      } else {
        return null;
      }
    },
    stages() {
      const _stages = [];
      this.$store.getters.getStageList &&
        this.$store.getters.getStageList.forEach((element) => {
          const stage = { value: element.key, text: element.name };
          _stages.push(stage);
        });

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

      return _assignee;
    },
  },
  watchQuery: true,

  methods: {},
};
</script>
