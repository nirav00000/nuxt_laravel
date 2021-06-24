import { shallowMount, createLocalVue } from "@vue/test-utils";
import bootstrapVue from "bootstrap-vue";
import axios from "axios";
import CodingSession from "@/pages/coding-sessions/_id.vue";

const localVue = createLocalVue();
localVue.use(bootstrapVue);
jest.mock("axios");

describe("coding session", () => {
  beforeEach(() => {
    process.env.MODE = "testing";
  });

  it("should show instruction page when not started", async () => {
    axios.post.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          is_valid: true,
          is_started: false,
        },
      })
    );

    const $route = { params: { id: "D1PG23" } };
    const wrapper = shallowMount(CodingSession, {
      localVue,
      mocks: {
        $route,
        $toasted: {
          show(message) {
            return message;
          },
        },
      },
    });
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain("Welcome.");
  });

  it("should start challenge when press start", async () => {
    axios.post.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          is_valid: true,
          is_started: false,
        },
      })
    );
    const MonacoEditor = {
      render: () => {},
      methods: {
        invoke: (any) => "# Header" + any,
      },
    };
    const $route = { params: { id: "D1PG23" } };
    const wrapper = shallowMount(CodingSession, {
      localVue,
      mocks: {
        $route,
        $toasted: {
          show(message) {
            return message;
          },
        },
      },
      stubs: {
        MonacoEditor,
      },
    });
    await wrapper.vm.$nextTick();
    expect(wrapper.text()).toContain("Welcome.");
    axios.post.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          is_valid: true,
          is_started: true,
          is_submitted: false,
          playground: {
            code: "",
            language: "",
          },
          data: {
            title: "abc",
            description: "abc",
            inputs: "abc",
            output: "abc",
          },
        },
      })
    );
    wrapper.find("#startCoding").trigger("click");
    await wrapper.vm.$nextTick();
    await wrapper.vm.$nextTick();
    axios.post.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          is_valid: true,
          is_started: true,
          is_submitted: false,
          playground: {
            code: "",
            language: "",
          },
          data: {
            title: "abc",
            description: "abc",
            inputs: "abc",
            output: "abc",
          },
        },
      })
    );
    await wrapper.vm.fetchChallenge();
    expect(wrapper.text()).toContain("Apricot");
  });

  it("should show started coding challenge and submit when click on submit", async () => {
    const $route = { params: { id: "D1PG23" } };
    const MonacoEditor = {
      render: () => {},
      methods: {
        invoke: (any) => "# Header" + any,
      },
    };
    const wrapper = shallowMount(CodingSession, {
      localVue,
      data() {
        return {
          isLoading: false,
          isStartedCoding: true,
          isValidSession: true,
          challegeTitle: "abc",
          challengeInstructions: "def",
          inputs: "abc",
          output: "abc",
          code: "code...",
          language: "python",
        };
      },
      stubs: {
        MonacoEditor,
      },
      mounted() {},
      mocks: {
        $route,
        $toasted: {
          show(message) {
            return message;
          },
        },
      },
    });

    axios.post.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          is_valid: true,
          is_started: true,
          is_submitted: true,
          playground: {
            code: "",
            language: "",
          },
          data: {
            title: "abc",
            description: "abc",
            inputs: "abc",
            output: "abc",
          },
        },
      })
    );
    wrapper.find("#submit").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.$data.isSubmitted).toBeTruthy();
  });

  it("should show started coding challenge and run when click on run, result out of resource", async () => {
    const $route = { params: { id: "D1PG23" } };
    const MonacoEditor = {
      render: () => {},
      methods: {
        invoke: (any) => "# Header" + any,
      },
    };
    const wrapper = shallowMount(CodingSession, {
      localVue,
      data() {
        return {
          isLoading: false,
          isStartedCoding: true,
          isValidSession: true,
          challegeTitle: "abc",
          challengeInstructions: "def",
          inputs: "abc",
          output: "abc",
          code: "code...",
          language: "python",
        };
      },
      stubs: {
        MonacoEditor,
      },
      mounted() {},
      mocks: {
        $route,
        $toasted: {
          show(message) {
            return message;
          },
        },
      },
    });

    axios.post.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          is_expired: false,
          is_valid: true,
          is_started: true,
          is_submitted: false,
          playground: {
            code: "hello",
            language: "python",
          },
          data: {
            rce: {
              matches: false,
              hasError: false,
              outOfResources: true,
            },
          },
        },
      })
    );
    wrapper.find("#run").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.$data.err).toBeTruthy();
  });

  it("should show started coding challenge and run when click on run, result error", async () => {
    const $route = { params: { id: "D1PG23" } };
    const MonacoEditor = {
      render: () => {},
      methods: {
        invoke: (any) => "# Header" + any,
      },
    };
    const wrapper = shallowMount(CodingSession, {
      localVue,
      data() {
        return {
          isLoading: false,
          isStartedCoding: true,
          isValidSession: true,
          challegeTitle: "abc",
          challengeInstructions: "def",
          inputs: "abc",
          output: "abc",
          code: "code...",
          language: "python",
        };
      },
      stubs: {
        MonacoEditor,
      },
      mounted() {},
      mocks: {
        $route,
        $toasted: {
          show(message) {
            return message;
          },
        },
      },
    });

    axios.post.mockImplementationOnce(() =>
      Promise.resolve({
        data: {
          success: true,
          is_expired: false,
          is_valid: true,
          is_started: true,
          is_submitted: false,
          playground: {
            code: "hello",
            language: "python",
          },
          data: {
            rce: {
              matches: false,
              hasError: true,
              errorMessage: "Something went wrong",
              outOfResources: false,
            },
          },
        },
      })
    );
    wrapper.find("#run").trigger("click");
    await wrapper.vm.$nextTick();
    expect(wrapper.vm.$data.err).toBeTruthy();
  });
});
