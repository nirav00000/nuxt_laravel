import {
  createLocalVue,
  mount,
  shallowMount,
  RouterLinkStub,
} from "@vue/test-utils";
import Vuex from "vuex";
import bootstrapVue, { BPagination } from "bootstrap-vue";
import Candidacy from "@/pages/candidacy/index.vue";
import Candidacytable from "@/components/CandidacyTable.vue";
import candidacies from "@/data/test-data/candidacies.json";

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(bootstrapVue);

describe("candidacy/index.vue", () => {
  let getters, store, state, actions, mutations;
  const $route = { query: { page: 1 } };
  const $router = {
    push: jest.fn((x) => {
      return x;
    }),
    replace: jest.fn((x) => {
      return x.query;
    }),
  };
  const query = { page: 1 };
  beforeEach(() => {
    state = {
      candidacyList: null,
    };
    getters = {
      getCandidacyList: jest.fn(() => {
        return state.candidacyList;
      }),
    };
    actions = {
      candidacyList: jest.fn(({ commit }) => {
        commit("setCandidacyList", candidacies);
      }),
    };
    mutations = {
      setCandidacyList: jest.fn(() => {
        state.candidacyList = candidacies;
      }),
    };
    store = new Vuex.Store({
      state,
      getters,
      actions,
      mutations,
    });
  });

  it("render candidacy", async () => {
    const wrapper = mount(Candidacy, {
      store,
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        CandidacyTable: Candidacytable,
      },
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      query,
    });
    expect(wrapper.vm).toBeTruthy();
    expect(wrapper.findComponent(Candidacytable).exists()).toBe(true);
    expect(getters.getCandidacyList).toHaveBeenCalled();
    expect(actions.candidacyList).toHaveBeenCalled();
  });

  it("page have candidacylist data", async () => {
    const wrapper = shallowMount(Candidacy, {
      store,
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        CandidacyTable: Candidacytable,
      },
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      query,
    });
    expect(wrapper.vm.candidacyList).toBe(candidacies);
  });

  it("candidacy page sent correct data to candidacytable component", async () => {
    const wrapper = mount(Candidacy, {
      store,
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        CandidacyTable: Candidacytable,
      },
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      query,
    });
    expect(wrapper.find("tbody").findAll("tr")).toHaveLength(
      wrapper.vm.candidacyList.candidacies.length
    );
  });

  it("render search", async () => {
    const wrapper = mount(Candidacy, {
      store,
      localVue,
      mocks: {
        $route,
      },
      stubs: {
        NuxtLink: RouterLinkStub,
        CandidacyTable: Candidacytable,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      query,
    });
    expect(wrapper.find("form").exists()).toBe(true);
    expect(wrapper.find("input[type=search]").exists()).toBe(true);
    expect(wrapper.find("select").findAll("option").exists()).toBe(true);
    expect(wrapper.find("button[type=submit]").exists()).toBe(true);
    expect(wrapper.find("button[type=reset]").exists()).toBe(true);
  });

 
  it("render pagination", async () => {
    const wrapper = mount(Candidacy, {
      store,
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        CandidacyTable: Candidacytable,
      },
      mocks: {
        $route,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      query,
    });
    expect(wrapper.findComponent(BPagination).exists()).toBe(true);
  });

  it("pagination is working", async () => {
    const wrapper = mount(Candidacy, {
      store,
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        CandidacyTable: Candidacytable,
      },
      mocks: {
        $route,
        $router,
      },
    });
    await wrapper.vm.$options.asyncData({
      store,
      query,
    });
    expect(
      wrapper
        .findComponent(BPagination)
        .findAll("button[role=menuitemradio]")
        .exists()
    ).toBe(true);
    expect(
      wrapper.findComponent(BPagination).findAll("button[role=menuitemradio]")
    ).toHaveLength(wrapper.vm.candidacyList.meta.last_page);
    const pagination = wrapper.findComponent(BPagination).findAll("button");
    pagination.at(2).trigger("click");
    await wrapper.vm.$nextTick();
    expect($router.replace).toHaveBeenCalled();
  });
});
