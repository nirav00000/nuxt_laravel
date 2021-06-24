import { createLocalVue, mount } from "@vue/test-utils";
import Vuex from "vuex";
import bootstrapVue from "bootstrap-vue";
import UpdateCandidate from "@/pages/candidacy/_id/edit/index.vue";
import candidate from "@/data/test-data/candidate.json";
import candidacy from "@/data/test-data/candidacy.json";

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(bootstrapVue);

describe("update-candidate", () => {
  let getters, store, actions, mutations, state;
  const $route = { params: { id: "J5dh0JHCzLw4GTcK" } };
  const route = { params: { id: "J5dh0JHCzLw4GTcK" } };
  const $router = { back: jest.fn() };
  beforeEach(() => {
    state = {
      candidate: null,
      candidacy: null,
    };
    getters = {
      getCandidate: jest.fn(() => {
        return state.candidate;
      }),
      getCandidacy: jest.fn(() => {
        return state.candidacy; // candidacy comes frome here
      }),
    };
    actions = {
      candidate: jest.fn(({ commit }) => {
        commit("setCandidate", candidate.data);
      }),
      updateCandidate: jest.fn(),
      candidacy: jest.fn(({ commit }) => {
        commit("setCandidacy", candidacy);
      }),
    };
    mutations = {
      setCandidate: jest.fn(() => {
        state.candidate = candidate.data;
      }),
      setCandidacy: jest.fn(() => {
        state.candidacy = candidacy;
      }),
    };
    store = new Vuex.Store({
      state,
      getters,
      actions,
      mutations,
    });
  });

  it("render update-candidate", async () => {
    const wrapper = mount(UpdateCandidate, {
      store,
      localVue,
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    expect(wrapper.vm).toBeTruthy();
    // store test
    expect(actions.candidate).toHaveBeenCalled();
    expect(mutations.setCandidate).toHaveBeenCalled();
    expect(getters.getCandidate).toHaveBeenCalled();
    // page test
    expect(wrapper.find("form").exists()).toBe(true);
    expect(wrapper.find("#input-name").exists()).toBe(true);
    expect(wrapper.find("#input-email").exists()).toBe(true);
    expect(wrapper.find("#input-contact").exists()).toBe(true);
    expect(wrapper.find("#input-education").exists()).toBe(true);
    expect(wrapper.find("#input-experience").exists()).toBe(true);
    expect(wrapper.find("#input-collage").exists()).toBe(true);
    expect(wrapper.find("#input-company").exists()).toBe(true);
    expect(wrapper.find("#btn-update").exists()).toBe(true);
    expect(wrapper.find("#btn-cancel").exists()).toBe(true);
    expect(wrapper.find("#resume-url").exists()).toBe(true);
  });

  it("should already have data in respective field", async () => {
    const wrapper = mount(UpdateCandidate, {
      store,
      localVue,
      mocks: {
        $route,
      },
    });
    const data = await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    wrapper.setData(data);
    await wrapper.vm.$nextTick();
    expect(wrapper.find("#input-name").element.value).toBe("Freda Goodwin");
    expect(wrapper.find("#input-email").element.value).toBe(
      "mohr.ethelyn@example.com"
    );
    expect(wrapper.find("#input-contact").element.value).toBe("4658185411");
    expect(wrapper.find("#input-education").element.value).toBe(
      "Education TEST BE"
    );
    expect(wrapper.find("#input-experience").element.value).toBe("94");
    expect(wrapper.find("#input-collage").element.value).toBe("Collge TEST");
    expect(wrapper.find("#input-company").element.value).toBe(
      "Johnston, Cassin and McDermott"
    );
  });

  it("update functionality should work", async () => {
    const wrapper = mount(UpdateCandidate, {
      store,
      localVue,
      mocks: {
        $route,
      },
    });
    const data = await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    wrapper.setData(data);
    await wrapper.vm.$nextTick();
    wrapper.find("#input-name").setValue("Abc");
    wrapper.find("#input-email").setValue("Abc@gmail.com");
    wrapper.find("#input-contact").setValue("9988667755");
    wrapper.find("#input-education").setValue("BE");
    wrapper.find("#input-experience").setValue("12");
    wrapper.find("#input-collage").setValue("XYZ");
    wrapper.find("#input-company").setValue("");
    wrapper
      .find("#resume-url")
      .setValue("https://resumes.com/43e3dq33434e/MyResume.pdf");
    await wrapper.find("#btn-update").trigger("submit");
    expect(actions.updateCandidate).toHaveBeenCalled();
  });

  it("cancel button should word", async () => {
    const wrapper = mount(UpdateCandidate, {
      store,
      localVue,
      mocks: {
        $route,
        $router,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    await wrapper.find("#btn-cancel").trigger("click");
    await wrapper.vm.$nextTick();
    expect($router.back).toHaveBeenCalled();
  });

  it("if metadata is null should assign empty object to it", async () => {
    const wrapper = mount(UpdateCandidate, {
      store,
      localVue,
      mocks: {
        $route,
      },
    });
    getters.getCandidate.mockReturnValue({
      candidate: {
        key: "nnml6rrLuOY6hRAm",
        name: "Freda Goodwin",
        email: "mohr.ethelyn@example.com",
        metadata: null,
      },
    });
    const data = await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    wrapper.setData(data);
    expect(wrapper.find("#input-contact").element.value).toBe("");
    expect(wrapper.find("#input-education").element.value).toBe("");
    expect(wrapper.find("#input-experience").element.value).toBe("");
    expect(wrapper.find("#input-collage").element.value).toBe("");
    expect(wrapper.find("#input-company").element.value).toBe("");
  });

  it("update should not work if required filed is not given", async () => {
    const wrapper = mount(UpdateCandidate, {
      store,
      localVue,
      mocks: {
        $route,
      },
    });
    const data = await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    wrapper.setData(data);
    await wrapper.vm.$nextTick();
    wrapper.find("#input-name").setValue("");
    wrapper.find("#input-email").setValue("");
    await wrapper.find("#btn-update").trigger("submit");
    expect(actions.updateCandidate).not.toHaveBeenCalled();
  });

  it("page should render although getters does not have data", async () => {
    const wrapper = mount(UpdateCandidate, {
      store,
      localVue,
      mocks: {
        $route,
      },
    });
    getters.getCandidate.mockReturnValue();
    const data = await wrapper.vm.$options.asyncData({
      store,
      route,
    });
    expect(data).toBe();
    expect(wrapper.find("form").exists()).toBe(true);
    expect(wrapper.find("#input-name").exists()).toBe(true);
    expect(wrapper.find("#input-email").exists()).toBe(true);
    expect(wrapper.find("#input-contact").exists()).toBe(true);
    expect(wrapper.find("#input-education").exists()).toBe(true);
    expect(wrapper.find("#input-experience").exists()).toBe(true);
    expect(wrapper.find("#input-collage").exists()).toBe(true);
    expect(wrapper.find("#input-company").exists()).toBe(true);
    expect(wrapper.find("#btn-update").exists()).toBe(true);
    expect(wrapper.find("#btn-cancel").exists()).toBe(true);
  });
});
