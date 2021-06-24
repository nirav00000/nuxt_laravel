import { createLocalVue, mount, config } from "@vue/test-utils";
import Vuex from "vuex";
import bootstrapVue, { BCard, BootstrapVueIcons, BModal } from "bootstrap-vue";
import History from "@/pages/candidacy/_id/index.vue";
import Timeline from "@/components/Timeline.vue";
import TimelineControl from "@/components/TimelineControl.vue";
import TimelineItem from "@/components/TimelineItem.vue";
import Fields from "@/components/Fields.vue";
import candidacy from "@/data/test-data/candidacy.json";
import histories from "@/data/test-data/history.json";
import stages from "@/data/test-data/stages.json";
import assignees from "@/data/test-data/assignee.json";
import filters from "@/plugins/filters.js";

config.stubs["client-only"] = { template: "<div><slot /></div>" };

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(filters);
localVue.use(bootstrapVue);
localVue.use(BootstrapVueIcons);

describe("candidacy/_id/index.vue", () => {
  process.env.BOOTSTRAP_VUE_NO_WARN = true;
  let getters, store, state, actions, mutations;
  const $route = { params: { id: "J5dh0JHCzLw4GTcK" } };
  const route = { params: { id: "J5dh0JHCzLw4GTcK" } };
  beforeEach(() => {
    state = {
      stageList: null,
      assigneeList: null,
      candidacy: null,
      historyList: null,
    };
    getters = {
      getStageList: jest.fn(() => {
        return state.stageList;
      }),
      getAssigneeList: jest.fn(() => {
        return state.assigneeList;
      }),
      getHistoryList: jest.fn(() => {
        return state.historyList;
      }),
      getCandidacy: jest.fn(() => {
        return state.candidacy;
      }),
      isAdmin: jest.fn(() => {
        return true;
      }),
    };
    actions = {
      completeStage: jest.fn(),
      closeCandidacy: jest.fn(),
      addFeedback: jest.fn(),
      candidacy: jest.fn(({ commit }) => {
        commit("setCandidacy", candidacy);
      }),
      stageAssignment: jest.fn(),
      stageList: jest.fn(({ commit }) => {
        commit("setStageList");
      }),
      assigneeList: jest.fn(({ commit }) => {
        commit("setAssigneeList", assignees.data);
      }),
      historyList: jest.fn(({ commit }) => {
        commit("setHistoryList", histories.data);
      }),
    };
    mutations = {
      setCandidacy: jest.fn(() => {
        state.candidacy = candidacy;
      }),
      setHistoryList: jest.fn(() => {
        state.historyList = histories.data.data;
      }),
      setStageList: jest.fn(() => {
        state.stageList = stages.data.stages;
      }),
      setAssigneeList: jest.fn(() => {
        state.assigneeList = assignees.data.data;
      }),
    };
    store = new Vuex.Store({
      state,
      getters,
      actions,
      mutations,
    });
  });

  it("render history", async () => {
    const wrapper = mount(History, {
      store,
      stubs: {
        Timeline,
        TimelineItem,
        TimelineControl,
        Fields,
      },
      localVue,
      mocks: {
        $route,
      },
    });
    expect(wrapper.vm).toBeTruthy();
    await wrapper.vm.$nextTick();
    expect(wrapper.findComponent(BCard).exists()).toBe(true);
  });

  it("asyncdata call all action methods", async () => {
    const wrapper = mount(History, {
      store,
      stubs: {
        Timeline,
        TimelineItem,
        TimelineControl,
        Fields,
      },
      localVue,
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    expect(actions.candidacy).toHaveBeenCalled();
    expect(actions.stageList).toHaveBeenCalled();
    expect(actions.assigneeList).toHaveBeenCalled();
    expect(actions.historyList).toHaveBeenCalled();
  });

  it("render stage list if stage data is available for that candidacy", async () => {
    const wrapper = mount(History, {
      store,
      stubs: {
        Timeline,
        TimelineItem,
        TimelineControl,
        Fields,
      },
      localVue,
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    expect(wrapper.find(".list-group").exists()).toBe(true);
    expect(wrapper.findAll(".list-group-item")).toHaveLength(
      Object.keys(wrapper.vm.candidacy.metadata.stages).length
    );
  });

  it("render history componenet if history data is available", async () => {
    const wrapper = mount(History, {
      store,
      stubs: {
        Timeline,
        TimelineItem,
        TimelineControl,
        Fields,
      },
      localVue,
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    expect(wrapper.findComponent(Timeline).exists()).toBe(true);
    expect(wrapper.findAllComponents(TimelineItem)).toHaveLength(
      wrapper.vm.histories.length
    );
  });

  it("complete stage works", async () => {
    const wrapper = mount(History, {
      store,
      stubs: {
        Timeline,
        TimelineItem,
        TimelineControl,
        Fields,
      },
      localVue,
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    const openModalBtn = wrapper.find(".list-group").findAll("svg").at(0);
    const reasonModal = wrapper.find("#close-stage-modal");
    expect(openModalBtn.exists()).toBe(true);
    expect(reasonModal.exists()).toBe(true);
    expect(reasonModal.isVisible()).toBe(false);
    openModalBtn.trigger("click");
    await wrapper.vm.$nextTick();
    expect(reasonModal.isVisible()).toBe(true);
    const closeStageBtn = wrapper.find("#close-stage-modal").findAll("button");
    expect(closeStageBtn.exists()).toBe(true);
    await closeStageBtn.at(2).trigger("click");
    await wrapper.vm.$nextTick();
    expect(actions.completeStage).toHaveBeenCalled();
  });

  it("stage assignment", async () => {
    const wrapper = mount(History, {
      store,
      stubs: {
        Timeline,
        TimelineItem,
        TimelineControl,
        Fields,
      },
      localVue,
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });

    const stageAssignmentButton = wrapper.find("#assign-stage");
    const modal = wrapper.findComponent({ ref: "assignStageModal" });
    expect(modal.vm.isVisible).toEqual(false);
    stageAssignmentButton.trigger("click");
    await wrapper.vm.$nextTick();
    expect(modal.vm.isVisible).toEqual(true);
  });

  it("feedback submission", async () => {
    const wrapper = mount(History, {
      store,
      stubs: {
        Timeline,
        TimelineItem,
        TimelineControl,
        Fields,
        MDEditor: { template: "<div id='feedbackMDEitor'></div>" },
      },
      localVue,
      attachTo: document.body,
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    const submitFeedback = wrapper.find("#submit-feedback");
    const modal = wrapper.findComponent({ ref: "addFeedbackModal" });
    expect(modal.vm.isVisible).toEqual(false);
    submitFeedback.trigger("click");
    await wrapper.vm.$nextTick();
    expect(modal.vm.isVisible).toEqual(true);
    const description = document.querySelector("#feedbackMDEitor");
    const verdict = document.querySelector("#radioVerdict");
    expect(verdict).toBeTruthy();
    expect(description).toBeTruthy();
  });

  it("close candidacy", async () => {
    const wrapper = mount(History, {
      store,
      stubs: {
        Timeline,
        TimelineItem,
        TimelineControl,
        Fields,
      },
      localVue,
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });

    const closeBtn = wrapper.find("#close-candidacy-btn");
    const reasonModal = wrapper.find("#status-modal");
    const reasonInput = wrapper.find("#input-live-help-close-candidacy");
    expect(closeBtn.exists()).toBe(true);
    expect(closeBtn.element.tagName).toBe("BUTTON");
    expect(reasonModal.exists()).toBe(true);
    expect(reasonModal.isVisible()).toBe(false);
    await closeBtn.trigger("click");
    await wrapper.vm.$nextTick();
    expect(reasonModal.isVisible()).toBe(true);
    expect(reasonInput.exists()).toBe(true);
    expect(reasonInput.element.tagName).toBe("TEXTAREA");
    reasonInput.setValue("Failed");
    expect(wrapper.findComponent(BModal).exists()).toBe(true);
    await wrapper
      .find("#status-modal")
      .findAll("button")
      .at(1)
      .trigger("click");
    await wrapper.vm.$nextTick();
    expect(actions.closeCandidacy).toHaveBeenCalled();
  });

  // It should render feedbacks
  it("should render feedback", async () => {
    const wrapper = mount(History, {
      store,
      stubs: {
        Timeline,
        TimelineItem,
        TimelineControl,
        Fields,
      },
      localVue,
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain("Feedbacks");
  });

  it("Resumes are shown in details", async () => {
    const wrapper = mount(History, {
      store,
      stubs: {
        Timeline,
        TimelineItem,
        TimelineControl,
        Fields,
      },
      localVue,
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });

    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain("Resumes:");
    expect(wrapper.text()).toContain("https://resume1.com");
    expect(wrapper.text()).toContain("https://resume2.com");
  });
});
