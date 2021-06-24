import { shallowMount, RouterLinkStub, createLocalVue } from "@vue/test-utils";
import axios from "axios";
import bootstrapVue from "bootstrap-vue";
import CodingChallenge from "@/pages/coding-challenges/index.vue";
import CodingChallengeNew from "@/pages/coding-challenges/new.vue";
import CodingChallengeUpdate from "@/pages/coding-challenges/_id.vue";

const localVue = createLocalVue();
jest.mock("axios");
localVue.use(bootstrapVue);

describe("coding challenges", () => {
  it("should render loading initally", () => {
    const wrapper = shallowMount(CodingChallenge, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
      },
      mocks: {
        $axios: axios,
      },
    });
    expect(wrapper.text()).toContain("Loading...");
  });

  it("should hide loading when empty data is loaded and show no challeges", () => {
    const wrapper = shallowMount(CodingChallenge, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
      },
      mocks: {
        $axios: axios,
      },
      data() {
        return {
          isLoading: false,
          isEmptyChallenges: true,
          pageMeta: {
            total: 0,
          },
        };
      },
    });
    expect(wrapper.text()).toContain("No challenges");
  });

  it("should render challenges", () => {
    const wrapper = shallowMount(CodingChallenge, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
      },
      mocks: {
        $axios: axios,
      },
      data() {
        return {
          isLoading: false,
          isEmptyChallenges: false,
          challenges: [
            {
              key: "NX72OMQ",
              title: "Three number sum",
              description: "some description",
            },
          ],
          pageMeta: {
            current_page: 1,
            first_page_url:
              "http://localhost:8000/api/v1/coding-challenges?page=1",
            from: 1,
            last_page: 1,
            last_page_url:
              "http://localhost:8000/api/v1/coding-challenges?page=1",
            next_page_url: null,
            path: "http://localhost:8000/api/v1/coding-challenges",
            per_page: 10,
            prev_page_url: null,
            to: 1,
            total: 1,
          },
        };
      },
    });
    expect(wrapper.element.textContent).toContain("Three number sum");
  });

  it("should delete challenges", async () => {
    const wrapper = shallowMount(CodingChallenge, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
      },
      mocks: {
        $axios: axios,
      },
      data() {
        return {
          isLoading: false,
          isEmptyChallenges: false,
          challenges: [
            {
              key: "NX72OMQ",
              title: "Three number sum",
              description: "some description",
            },
          ],
          pageMeta: {
            current_page: 1,
            first_page_url:
              "http://localhost:8000/api/v1/coding-challenges?page=1",
            from: 1,
            last_page: 1,
            last_page_url:
              "http://localhost:8000/api/v1/coding-challenges?page=1",
            next_page_url: null,
            path: "http://localhost:8000/api/v1/coding-challenges",
            per_page: 10,
            prev_page_url: null,
            to: 1,
            total: 1,
          },
        };
      },
    });
    window.confirm = jest.fn().mockImplementation(() => true);
    axios.delete.mockResolvedValue(
      Promise.resolve({
        data: {
          success: true,
        },
      })
    );
    axios.get.mockResolvedValue(
      Promise.resolve({
        data: {
          success: true,
          data: {
            challenges: [],
            pageMeta: {
              current_page: 1,
              first_page_url:
                "http://localhost:8000/api/v1/coding-challenges?page=1",
              from: 1,
              last_page: 1,
              last_page_url:
                "http://localhost:8000/api/v1/coding-challenges?page=1",
              next_page_url: null,
              path: "http://localhost:8000/api/v1/coding-challenges",
              per_page: 10,
              prev_page_url: null,
              to: 1,
              total: 0,
            },
          },
        },
      })
    );
    wrapper.find("#deleteChallenge").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.element.textContent).not.toContain("Three number sum");
  });

  it("should show pagination buttons when challenges graterthan 10", () => {
    const wrapper = shallowMount(CodingChallenge, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
      },
      mocks: {
        $axios: axios,
      },
      data() {
        return {
          isLoading: false,
          isEmptyChallenges: false,
          currentPage: 1,
          hasPrev: false,
          hasNext: true,
          challenges: [
            {
              key: "1",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "2",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "3",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "4",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "5",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "6",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "7",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "8",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "9",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "10",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "11",
              title: "Three number sum",
              description: "some description",
            },
          ],
          pageMeta: {
            current_page: 1,
            first_page_url:
              "http://localhost:8000/api/v1/coding-challenges?page=1",
            from: 1,
            last_page: 10,
            last_page_url:
              "http://localhost:8000/api/v1/coding-challenges?page=1",
            next_page_url:
              "http://localhost:8000/api/v1/coding-challenges/?page=2",
            path: "http://localhost:8000/api/v1/coding-challenges",
            per_page: 10,
            prev_page_url: null,
            to: 10,
            total: 11,
          },
        };
      },
    });

    expect(wrapper.text()).toContain("Next");
  });

  it("should show pagination prev button when challenges graterthan 10", () => {
    const wrapper = shallowMount(CodingChallenge, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
      },
      mocks: {
        $axios: axios,
      },
      data() {
        return {
          isLoading: false,
          isEmptyChallenges: false,
          currentPage: 2,
          hasPrev: true,
          hasNext: false,
          challenges: [
            {
              key: "1",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "2",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "3",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "4",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "5",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "6",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "7",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "8",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "9",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "10",
              title: "Three number sum",
              description: "some description",
            },
            {
              key: "11",
              title: "Three number sum",
              description: "some description",
            },
          ],
          pageMeta: {
            current_page: 2,
            first_page_url:
              "http://localhost:8000/api/v1/coding-challenges?page=2",
            from: 1,
            last_page: 10,
            last_page_url:
              "http://localhost:8000/api/v1/coding-challenges?page=2",
            next_page_url: null,
            path: "http://localhost:8000/api/v1/coding-challenges",
            per_page: 10,
            prev_page_url:
              "http://localhost:8000/api/v1/coding-challenges/?page=1",
            to: 10,
            total: 11,
          },
        };
      },
    });
    expect(wrapper.text()).toContain("Prev");
  });

  it("should render add challenge button", () => {
    const wrapper = shallowMount(CodingChallenge, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
      },
      mocks: {
        $axios: axios,
      },
    });
    expect(wrapper.text()).toContain("Add Challenge");
  });
});

// URL/coding-challenges/new
describe("create a new coding challenge", () => {
  it("should render add coding header and save button", async () => {
    const MDEditorStub = {
      render: () => {},
      methods: {
        invoke: (any) => "# Header" + any,
      },
    };
    const wrapper = shallowMount(CodingChallengeNew, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: MDEditorStub,
      },
      mocks: {
        $axios: axios,
      },
    });
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain("Add Coding Challenge");
    expect(wrapper.text()).toContain("Save");
  });
  it("should render title, description, and add test buttons", async () => {
    const wrapper = shallowMount(CodingChallengeNew, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: { template: "<div id='description'></div>" },
      },
      mocks: {
        $axios: axios,
      },
    });
    await wrapper.vm.$nextTick();
    expect(wrapper.find("#title").exists()).toBeTruthy();
    expect(wrapper.find("#description").exists()).toBeTruthy();
    expect(wrapper.find("#addTest").exists()).toBeTruthy();
  });
  it("should be disable button add test when already is entering", async () => {
    const MDEditorStub = {
      render: () => {},
      methods: {
        invoke: (any) => "# Header" + any,
      },
    };
    const wrapper = shallowMount(CodingChallengeNew, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: MDEditorStub,
      },
      mocks: {
        $axios: axios,
      },
    });
    await wrapper.vm.$nextTick();
    wrapper.find("#addTest").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.find("#addTest").attributes("disabled")).toBeTruthy();
  });
  it("should add test context is show", async () => {
    const MDEditorStub = {
      render: () => {},
      methods: {
        invoke: (any) => "# Header" + any,
      },
    };
    const wrapper = shallowMount(CodingChallengeNew, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: MDEditorStub,
      },
      mocks: {
        $axios: axios,
      },
    });
    await wrapper.vm.$nextTick();
    wrapper.find("#addTest").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.find("#add-test-popup").exists()).toBeTruthy();
  });

  it("should create add a test", async () => {
    global.alert = jest.fn();
    const MDEditorStub = {
      render: () => {},
      methods: {
        invoke: (any) => "# Header" + any,
      },
      mocks: {
        $axios: axios,
      },
    };
    const wrapper = shallowMount(CodingChallengeNew, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: MDEditorStub,
      },
      mocks: {
        $axios: axios,
      },
    });
    await wrapper.vm.$nextTick();
    wrapper.find("#addTest").trigger("click");
    await wrapper.vm.$nextTick();
    wrapper.find("#stdin").setValue("input");
    wrapper.find("#stdout").setValue("output");
    wrapper.find("#saveTest").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain("Test #1");
  });

  it("should able to discard a test", async () => {
    global.alert = jest.fn();
    const MDEditorStub = {
      render: () => {},
      methods: {
        invoke: (any) => "# Write something" + any,
      },
    };
    const wrapper = shallowMount(CodingChallengeNew, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: MDEditorStub,
      },
      mocks: {
        $axios: axios,
      },
    });
    await wrapper.vm.$nextTick();
    wrapper.find("#addTest").trigger("click");
    await wrapper.vm.$nextTick();
    wrapper.find("#stdin").setValue("input");
    wrapper.find("#stdout").setValue("output");
    wrapper.find("#discardTest").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).not.toContain("Test #1");
  });

  it("should add challenge", async () => {
    global.alert = jest.fn();
    const MDEditorStub = {
      render: () => {},
      methods: {
        invoke: (any) => "# Header" + any,
      },
    };
    const wrapper = shallowMount(CodingChallengeNew, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: MDEditorStub,
      },
      mocks: {
        $axios: axios,
      },
      data() {
        return {
          tests: [{ inputs: "Input", output: "Hello" }],
        };
      },
    });
    axios.post.mockImplementationOnce(() => {
      Promise.resolve({
        data: {
          success: true,
        },
      });
    });
    await wrapper.vm.$nextTick();
    wrapper.find("#title").setValue("Three number sum");
    wrapper.find("#addChallenge").trigger("click");
    expect(global.alert).toHaveBeenCalledTimes(0);
  });
});

describe("Edit coding challenge", () => {
  it("should render created coding challenge", () => {
    const $route = { params: { id: "D1PG23" } };
    axios.get.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          data: {
            title: "Three number sum",
            description: "# Some desc",
            tests: JSON.stringify([{ inputs: "hello", output: "cool" }]),
          },
        },
      })
    );
    const wrapper = shallowMount(CodingChallengeUpdate, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: { template: "<div id='description'></div>" },
      },
      mocks: {
        $route,
        $axios: axios,
      },
    });

    expect(wrapper.text()).not.toContain("Challenge not exists!");
  });

  it("should not render not created coding challenge", async () => {
    const $route = { params: { id: "YWER23" } };
    axios.get.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: false,
          data: {},
        },
      })
    );
    const wrapper = shallowMount(CodingChallengeUpdate, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: { template: "<div id='description'></div>" },
      },
      mocks: {
        $route,
        $axios: axios,
      },
    });
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain("Challenge not exists!");
  });

  it("add test", async () => {
    const $route = { params: { id: "D1PG23" } };
    axios.get.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          data: {
            title: "Three number sum",
            description: "# Some desc",
            tests: JSON.stringify([{ inputs: "hello", output: "cool" }]),
          },
        },
      })
    );
    const wrapper = shallowMount(CodingChallengeUpdate, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: { template: "<div id='description'></div>" },
      },
      mocks: {
        $route,
        $axios: axios,
      },
    });

    await wrapper.vm.$nextTick();
    wrapper.find("#addTest").trigger("click");
    await wrapper.vm.$nextTick();
    wrapper.find("#stdin").setValue("1");
    wrapper.find("#stdout").setValue("2");
    wrapper.find("#saveTest").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain("Test #2");
  });

  it("should discard test", async () => {
    const $route = { params: { id: "D1PG23" } };
    axios.get.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          data: {
            title: "Three number sum",
            description: "# Some desc",
            tests: JSON.stringify([{ inputs: "hello", output: "cool" }]),
          },
        },
      })
    );
    const wrapper = shallowMount(CodingChallengeUpdate, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: { template: "<div id='description'></div>" },
      },
      mocks: {
        $route,
        $axios: axios,
      },
    });

    await wrapper.vm.$nextTick();
    wrapper.find("#addTest").trigger("click");
    await wrapper.vm.$nextTick();
    wrapper.find("#discardTest").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).not.toContain("Test #2");
  });

  it("should delete test", async () => {
    const $route = { params: { id: "D1PG23" } };
    axios.get.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          data: {
            title: "Three number sum",
            description: "# Some desc",
            tests: JSON.stringify([{ inputs: "hello", output: "cool" }]),
          },
        },
      })
    );

    const wrapper = shallowMount(CodingChallengeUpdate, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: { template: "<div id='description'></div>" },
      },
      mocks: {
        $route,
        $axios: axios,
      },
    });

    await wrapper.vm.$nextTick();
    wrapper.find("#deleteTest").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).not.toContain("Test #1");
  });

  it("should able edit test", async () => {
    const $route = { params: { id: "D1PG23" } };
    axios.get.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          data: {
            title: "Three number sum",
            description: "# Some desc",
            tests: JSON.stringify([{ inputs: "hello", output: "cool" }]),
          },
        },
      })
    );

    const wrapper = shallowMount(CodingChallengeUpdate, {
      localVue,
      stubs: {
        NuxtLink: RouterLinkStub,
        MDEditor: { template: "<div id='description'></div>" },
      },
      mocks: {
        $route,
        $axios: axios,
      },
    });
    wrapper.vm.$refs.editTestModel.show = jest.fn(() => {});
    wrapper.vm.$refs.editTestModel.hide = jest.fn(() => {});
    await wrapper.vm.$nextTick();
    wrapper.find("#editTest").trigger("click");
    expect(wrapper.find("#stdin")).toBeTruthy();
  });
});
