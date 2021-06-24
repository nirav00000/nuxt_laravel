import { shallowMount, createLocalVue } from "@vue/test-utils";
import bootstrapVue from "bootstrap-vue";
import axios from "axios";
import Register from "@/pages/register.vue";

const localVue = createLocalVue();
localVue.use(bootstrapVue);
jest.mock("axios");

describe("Register", () => {
  beforeEach(() => {
    process.env.MODE = "testing";
    process.env.BOOTSTRAP_VUE_NO_WARN = true;
  });
  // form is shown
  it("should render form", () => {
    const wrapper = shallowMount(Register, {
      localVue,
      mocks: {
        $toasted: {
          show: jest.fn((data) => data),
        },
      },
    });
    expect(wrapper.find("form")).toBeTruthy();
  });

  it("should submit form successfully", async () => {
    const wrapper = shallowMount(Register, {
      localVue,
      mocks: {
        $toasted: {
          show: jest.fn((data) => data),
        },
      },
    });
    await wrapper.vm.$nextTick();
    await wrapper.find("#email").setValue("someone@example.com");
    await wrapper.find("#name").setValue("someone");
    await wrapper.find("#position").setValue("SE");
    axios.post.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          is_valid: true,
          is_started: false,
        },
      })
    );
    await wrapper.find("form").trigger("submit");
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain("Your Registration successful!");
  });

  it("should show missing error of missing fileds", async () => {
    const wrapper = shallowMount(Register, {
      localVue,
      mocks: {
        $toasted: {
          show: jest.fn((data) => data),
        },
      },
    });
    await wrapper.vm.$nextTick();

    await wrapper.find("#email").setValue("someone");

    const err = new Error();
    err.response = {};
    err.response.data = {
      message: "The given data was invalid.",
      errors: {
        name: ["The name field is required."],
      },
    };

    axios.post.mockImplementationOnce(() => Promise.reject(err));

    await wrapper.find("form").trigger("submit");
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain("The name field is required");
  });
});
