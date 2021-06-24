import { mount, createLocalVue } from "@vue/test-utils";
import bootstrapVue from "bootstrap-vue";
import Appfooter from "@/components/AppFooter.vue";

const localVue = createLocalVue();
localVue.use(bootstrapVue);
const wrapper = mount(Appfooter, {
  localVue,
});

describe("Appfooter.vue", () => {
  it("render appfooter conponenet", () => {
    expect(wrapper.vm).toBeTruthy();
    expect(wrapper.findComponent({ name: "b-navbar" }).exists()).toBe(true);
  });
});
