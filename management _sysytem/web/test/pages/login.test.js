import { mount, createLocalVue } from "@vue/test-utils";
import bootstrapVue, { BButton, BModal } from "bootstrap-vue";
import Vuex from "vuex";
import login from "@/pages/login.vue";

const localVue = createLocalVue();
localVue.use(bootstrapVue);
localVue.use(Vuex);

describe("login.vue", () => {
  let actions, store;
  beforeEach(() => {
    actions = {
      headerToken: jest.fn(),
      user: jest.fn(),
    };
    store = new Vuex.Store({
      actions,
    });
    process.env.MODE = "testing";
    process.env.BOOTSTRAP_VUE_NO_WARN = true;
  });
  it("render login", async () => {
    const wrapper = mount(login, {
      localVue,
      store,
    });
    await wrapper.vm.$nextTick();
    expect(wrapper.vm).toBeTruthy();
    expect(wrapper.findComponent(BModal).exists()).toBe(true);
    expect(wrapper.findComponent(BButton).exists()).toBe(true);
  });

  it("token will get set", async () => {
    const wrapper = mount(login, {
      localVue,
      store,
    });
    await wrapper.vm.$nextTick();
    const openModalBtn = wrapper.find("#login-btn");
    const tokenModal = wrapper.find("#token-modal");
    expect(openModalBtn.exists()).toBe(true);
    expect(tokenModal.exists()).toBe(true);
    expect(tokenModal.isVisible()).toBe(false);
    openModalBtn.trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.find("#token-modal").isVisible()).toBe(true);
    const submitTokenBtn = wrapper.find("#token-modal").findAll("button");
    expect(submitTokenBtn.exists()).toBe(true);
    const tokenInput = wrapper.find("#input-live-help");
    expect(tokenInput.exists()).toBe(true);
    expect(tokenInput.element.tagName).toBe("INPUT");
    await tokenInput.setValue("abc");
    const groupInput = wrapper.find("#group");
    expect(groupInput.exists()).toBe(true);
    await groupInput.setValue("admin");
    await submitTokenBtn.at(2).trigger("click");
    await wrapper.vm.$nextTick();
    expect(actions.headerToken).toHaveBeenCalled();
    expect(actions.user).toHaveBeenCalled();
  });
});
