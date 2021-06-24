//= ===================final solution ================
import { mount, createLocalVue } from "@vue/test-utils";
import bootstrapVue, { BModal } from "bootstrap-vue";
import Vuex from "vuex";
import axios from "axios";
import Stages from "@/pages/stages.vue";

import questionnaires from "@/data/test-data/questionnaires.json";
import Modal from "@/components/Modal/Modal.vue";

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(bootstrapVue);

jest.mock("axios");

describe("stages.vue", () => {
  let getters, store, state, actions, mutations;
  const metadata = JSON.parse('{"fields":["date","time"]}');
  const stageList = [
    {
      key: "xAZiPrZhQbtjMDbn",
      name: "senior developer",
      type: "interview",
      metadata,
    },
  ];
  beforeEach(() => {
    state = {
      questionnaireList: null,
      stageList,
    };
    getters = {
      getQuestionnaireList: jest.fn(() => {
        return state.questionnaireList;
      }),
      getStageList: jest.fn(() => {
        return state.stageList;
      }),
    };
    actions = {
      questionnaireList: jest.fn(({ commit }) => {
        commit("setQuestionnaireList", questionnaires.data);
      }),
      stageList: jest.fn(({ commit }) => {
        commit("setStageList", stageList);
      }),
      stagesTableRowDelete: jest.fn(),
      stagesTableAddNewRow: jest.fn(),
    };
    mutations = {
      setQuestionnaireList: jest.fn(() => {
        state.questionnaireList = questionnaires.data;
      }),
      setStageList: jest.fn(() => {
        state.stageList = stageList;
      }),
    };
    store = new Vuex.Store({
      state,
      getters,
      actions,
      mutations,
    });
  });

  window.confirm = jest.fn(() => true);

  it("renders stages.vue page", () => {
    const wrapper = mount(Stages, { store, localVue });
    expect(wrapper.find("div.container-fluid").exists()).toBe(true);
  });

  it("open modal add modal", async () => {
    const wrapper = mount(Stages, { store, localVue });

    const button = wrapper.find("button[id=add]");
    button.trigger("click");
    await wrapper.vm.$nextTick();

    expect(wrapper.find(".indicate-block").exists()).toBe(true);
  });

  it("open modal edit onclick button reside within bootstrap table", async () => {
    const wrapper = mount(Stages, {
      localVue,
      store,
      mocks: {
        $axios: axios,
      },
      data() {
        return {
          isLoading: false,
        };
      },
    });

    // const wrapper = mount(Stages, { store, localVue })

    const button = wrapper
      .findAll(".data-table-stages tbody tr td")
      .at(2)
      .findAll("div button");
    button.at(0).trigger("click");
    await wrapper.vm.$nextTick();

    expect(wrapper.find(".indicate-block").exists()).toBe(true);
  });

  it("open modal delete onclick button reside within bootstrap table", async () => {
    const wrapper = mount(Stages, {
      localVue,
      store,
      mocks: {
        $axios: axios,
      },
      data() {
        return {
          isLoading: false,
          deletePayload: {
            action: "stagesTableRowDelete",
            key: "xAZiPrZhQbtjMDbn",
            item_name: "Stage1",
          },
        };
      },
    });

    const button = wrapper
      .findAll(".data-table-stages tbody tr td")
      .at(2)
      .findAll("div button");
    button.at(1).trigger("click");
    await wrapper.vm.$nextTick();

    wrapper.find("#delete-confirm").findAll("button").at(1).trigger("click");
    expect(wrapper.find("#delete-confirm").findAll("button").at(1).text()).toBe(
      "Delete"
    );
    await wrapper.vm.$nextTick();

    expect(actions.stagesTableRowDelete).toHaveBeenCalled();
  });

  it("submit data in modal", async () => {
    const wrapperModal = mount(Modal, {
      store,
      localVue,
      attachToDocument: true,

      propsData: {
        data: {
          action: "add",
          key: "",
          name: "",
          type: "",
          metadata: "",
        },
      },
    });

    wrapperModal.findAll("input").at(0).setValue("developer");
    wrapperModal.findAll("input").at(1).setValue("date");

    const options = wrapperModal
      .find("form div fieldset div div  select")
      .findAll("option");
    await options.at(2).setSelected();

    expect(wrapperModal.findAll("input").at(0).element.value).toBe("developer");
    expect(wrapperModal.find("option:checked").element.value).toBe("code");
    expect(wrapperModal.findAll("input").at(1).element.value).toBe("date");

    wrapperModal.find("form").trigger("submit");

    expect(actions.stagesTableAddNewRow).toHaveBeenCalled();
  });

  it("questionnaire options are shown", async () => {
    const wrapper = mount(Stages, {
      store,
      localVue,
    });
    await wrapper.vm.$store.dispatch("questionnaireList");

    const button = wrapper.find("button[id=add]");
    button.trigger("click");
    await wrapper.vm.$nextTick();

    expect(wrapper.find(".indicate-block").exists()).toBe(true);

    const typeDropDown = wrapper.find("#type").findAll("option");
    expect(typeDropDown.exists()).toBe(true);
    expect(wrapper.findComponent(BModal).exists()).toBe(true);
    await typeDropDown.at(1).setSelected();

    expect(wrapper.findAll("select#type > option").length).toBe(4);
    wrapper.findAll("select#type > option").at(1).element.selected = true;
    wrapper.find("select#type").trigger("change");
    expect(wrapper.vm.groupID).not.toBe("");

    expect(wrapper.findAll("select#questionnaire > option").length).toBe(
      questionnaires.data.length + 1
    );
    const questionnaireDropDown = wrapper
      .find("#questionnaire")
      .findAll("option");
    expect(questionnaireDropDown.exists()).toBe(true);
  });

  it("validation errors are shown", async () => {
    const wrapperModal = mount(Modal, {
      store,
      localVue,
      attachToDocument: true,

      propsData: {
        data: {
          action: "add",
          key: "",
          name: "",
          type: "",
          metadata: "",
        },
      },
    });

    expect(wrapperModal.findAll("input").at(0).element.value).toBe("");
    expect(wrapperModal.findAll("input").at(1).element.value).toBe("");

    wrapperModal.find("form").trigger("submit");

    await wrapperModal.vm.$nextTick();
    expect(wrapperModal.findAll("i").at(0).text()).toBe("Name is required.");

    expect(actions.stagesTableAddNewRow).toHaveBeenCalledTimes(0);
  });
});
