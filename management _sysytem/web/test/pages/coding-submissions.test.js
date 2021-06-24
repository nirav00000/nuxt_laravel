import { shallowMount, createLocalVue, RouterLinkStub } from "@vue/test-utils";
import bootstrapVue from "bootstrap-vue";
import axios from "axios";
import CodingSubmission from "@/pages/coding-submissions/_id.vue";
import CodeCollapse from "@/components/CodeCollapse.vue";

const localVue = createLocalVue();
localVue.use(bootstrapVue);
jest.mock("axios");

describe("coding submission", () => {
  const $route = { params: { id: "D1PG23" } };
  it("should show no submission found", () => {
    axios.get.mockImplementationOnce(() =>
    /* eslint-disable */
      Promise.reject({
        response: {
          data: {
            success: false,
          },
        },
      })
    );
    const wrapper = shallowMount(CodingSubmission, {
      localVue,
      mocks: {
        $route,
        $axios: axios,
      },
    });
    expect(wrapper.text()).toContain("No submission found");
  });

  it("should show not submission test crawled", async () => {
    axios.get.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          data: {
            candidate: {
              key: "AABBCC",
              name: "John F Canady",
            },
            candidacy: {
              key: 'BBCCDD', 
              position: "Software Engineer",
              final_status: "active",
            },
            challenge: {
              title: "abc",
              description: "def",
            },
            code: {
              code: "abc",
              language: "python",
            },
            tests: {
              result: {
                crawled: false,
              },
            },
          },
        },
      })
    );
    const wrapper = shallowMount(CodingSubmission, {
      localVue,
      mocks: {
        $route,
        $axios: axios,
      },
      stubs: {
        NuxtLink: RouterLinkStub,
      },
    });
    await wrapper.vm.$nextTick();
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain(
      "Submittion found, But not crawled result!"
    );
  });

  it("should show submission", async () => {
    axios.get.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          data: {
            candidate: {
              key: "AABBCC",
              name: "John F Canady",
            },
            candidacy: {
              key: 'BBCCDD', 
              position: "Software Engineer",
              final_status: "active",
            },
            challenge: {
              title: "abc",
              description: "# def",
            },
            code: {
              code: "abc",
              language: "python",
            },
            tests: {
              result: {
                crawled: true,
                results: [
                  {
                    matches: true,
                    actual: "Hello",
                    expected: "Hello",
                    hasError: false,
                    errorMessage: "",
                    message: "program works correctly!",
                    outOfResources: false,
                  },
                  {
                    matches: false,
                    actual: "Hello",
                    expected: "Cool",
                    hasError: false,
                    errorMessage: "",
                    message: "expected Hello, but received Cool",
                    outOfResources: false,
                  },
                  {
                    matches: false,
                    actual: "Hello",
                    expected: "Cool",
                    hasError: true,
                    errorMessage: "Error ...",
                    message: "expected Hello, but received Cool",
                    outOfResources: false,
                  },
                  {
                    matches: false,
                    actual: "Hello",
                    expected: "Cool",
                    hasError: false,
                    errorMessage: "Error ...",
                    message: "expected Hello, but received Cool",
                    outOfResources: true,
                  },
                ],
                total_tests: 5,
                passed_tests: 1,
              },
            },
          },
        },
      })
    );
    const wrapper = shallowMount(CodingSubmission, {
      localVue,
      mocks: {
        $route,
        $axios: axios,
      },
      stubs: {
        NuxtLink: RouterLinkStub,
        CodeCollapse,
      },
    });
    await wrapper.vm.$nextTick();
    await wrapper.vm.$nextTick();
    wrapper.find(".imprs-container-main-hdr").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.find(".imprs-container-main-data")).toBeTruthy();
  });
});
