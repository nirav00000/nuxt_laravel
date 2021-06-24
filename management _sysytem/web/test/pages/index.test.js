import { mount } from "@vue/test-utils";
import bootstrapVue, { BJumbotron, BButton } from "bootstrap-vue";
import Index from "@/pages/index.vue";
import { localVue } from "@/data/test-data/test-modules.js";

localVue.use(bootstrapVue);
const componentData = {
  localVue,
};

const wrapper = mount(Index, componentData);

describe("index.vue", () => {
  it("render html", () => {
    expect(wrapper.findComponent(BJumbotron).exists()).toBe(true);
    expect(wrapper.findComponent(BButton).exists()).toBe(true);
  });
});
