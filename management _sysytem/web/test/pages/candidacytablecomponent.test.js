import { createLocalVue, mount } from "@vue/test-utils";
import Vuex from "vuex";
import bootstrapVue from "bootstrap-vue";
import Candidacytable from "@/components/CandidacyTable.vue";

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(bootstrapVue);

describe("Candidacytable.vue", () => {
  let getters, store, state;
  const $route = {
    query: { page: 1 },
  };
  const candidacies = {
    candidacy: [
      {
        key: "gZjotgxXPXwdPGkz",
        candidate: {
          key: "dwwTbPtj0Q1l6wo1",
          name: "Dr. Benjamin Thompson",
          email: "bechtelar.isaac@example.com",
          metadata: {
            contact_no: "1-291-720-4889 x9137",
            education: "Education TEST BE",
            college: "Collge TEST",
            experience: 9,
            last_company: "Wilkinson-Murazik",
          },
        },
      },
    ],
    meta: {
      current_page: 1,
      first_page_url: "http://localhost/api/v1/candidacies?&page=1",
      from: 1,
      last_page: 2,
      last_page_url: "http://localhost/api/v1/candidacies?&page=2",
      next_page_url: "http://localhost/api/v1/candidacies?&page=2",
      path: "http://localhost/api/v1/candidacies?",
      per_page: 15,
      prev_page_url: null,
      to: 15,
      total: 26,
    },
  };
  beforeEach(() => {
    state = {
      spinner: false,
    };
    getters = {
      getSpinner: jest.fn(),
    };

    store = new Vuex.Store({
      state,
      getters,
    });
  });

  it("render html", () => {
    const wrapper = mount(Candidacytable, {
      store,
      localVue,
      mocks: {
        $route,
      },
    });

    expect(wrapper.find("form").exists());
    expect(wrapper.find("table").exists());
    expect(wrapper.find("b-pagination").exists());
  });

  it("props items have candidacy data", () => {
    const wrapper = mount(Candidacytable, {
      store,
      localVue,
      mocks: {
        $route,
      },
      propsData: {
        items: candidacies,
      },
    });

    expect(wrapper.props("items")).toStrictEqual({
      candidacy: [
        {
          key: "gZjotgxXPXwdPGkz",
          candidate: {
            key: "dwwTbPtj0Q1l6wo1",
            name: "Dr. Benjamin Thompson",
            email: "bechtelar.isaac@example.com",
            metadata: {
              contact_no: "1-291-720-4889 x9137",
              education: "Education TEST BE",
              college: "Collge TEST",
              experience: 9,
              last_company: "Wilkinson-Murazik",
            },
          },
        },
      ],
      meta: {
        current_page: 1,
        first_page_url: "http://localhost/api/v1/candidacies?&page=1",
        from: 1,
        last_page: 2,
        last_page_url: "http://localhost/api/v1/candidacies?&page=2",
        next_page_url: "http://localhost/api/v1/candidacies?&page=2",
        path: "http://localhost/api/v1/candidacies?",
        per_page: 15,
        prev_page_url: null,
        to: 15,
        total: 26,
      },
    });
  });
});
