import { createLocalVue, mount } from "@vue/test-utils";
import bootstrapVue from "bootstrap-vue";
import Vuex from "vuex";
import Appheader from "@/components/AppHeader.vue";

const localVue = createLocalVue();
localVue.use(bootstrapVue);
localVue.use(Vuex);

describe("AppHeader.vue", () => {
  let getters, actions, store;
  const $router = {
    push: jest.fn(),
  };
  beforeEach(() => {
    getters = {
      getUser: jest.fn(),
      getToken: jest.fn(),
    };
    actions = {
      user: jest.fn(),
      logOut: jest.fn(),
    };
    store = new Vuex.Store({
      getters,
      actions,
    });
  });
  it("render appheader component", () => {
    const wrapper = mount(Appheader, {
      store,
      localVue,
    });
    expect(wrapper.vm).toBeTruthy();
    expect(wrapper.findComponent({ name: "b-navbar" }).exists()).toBe(true);
    expect(getters.getToken).toHaveBeenCalled();
  });
  it("render navbar have correct route path", () => {
    const wrapper = mount(Appheader, {
      store,
      localVue,
    });
    expect(wrapper.findAll("a").at(1).attributes().href).toBe("/candidacy");
    expect(wrapper.findAll("a").at(2).attributes().href).toBe("/stages");
    expect(wrapper.findAll("a").at(3).attributes().href).toBe(
      "/coding-challenges"
    );
  });

  it("logout button works", async () => {
    const wrapper = mount(Appheader, {
      store,
      localVue,
      mocks: {
        $router,
      },
    });
    const logoutBtn = wrapper.find("#logout-btn");
    expect(logoutBtn.exists()).toBe(true);
    logoutBtn.trigger("click");
    await wrapper.vm.$nextTick();
    expect(actions.logOut).toHaveBeenCalled();
    await wrapper.vm.$nextTick();
    await expect($router.push).toHaveBeenCalled();
  });

  it("should display logged in user's name", () => {
    getters.getUser.mockReturnValue({
      user_name: "ABC",
    });
    const wrapper = mount(Appheader, {
      store,
      localVue,
    });
    expect(getters.getUser).toHaveBeenCalled();
    expect(wrapper.find("#em-username").text()).toBe("Hello, ABC");
  });

  it("should display User if getUser doesn't contain data", () => {
    const wrapper = mount(Appheader, {
      store,
      localVue,
    });
    expect(getters.getUser).toHaveBeenCalled();
    expect(wrapper.find("#em-username").text()).toBe("Hello, User");
  });
});
