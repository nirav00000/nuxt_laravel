import Toasted from "vue-toasted";
import Vuex from "vuex";
import {
  mount,
  shallowMount,
  createLocalVue,
  createWrapper,
} from "@vue/test-utils";

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(Toasted);

export { localVue, Vuex, shallowMount, mount, createWrapper };

export const $toasted = {
  success: jest.fn(),
  show: jest.fn(),
  error: jest.fn(),
  info: jest.fn(),
};

export const $router = {
  push: jest.fn((x) => x),
  go: jest.fn(),
};

export const mutations = {
  loading: jest.fn(),
  params: jest.fn(),
};

export const $cookies = {
  set: jest.fn((x) => x),
  get: jest.fn((x) => x),
  remove: jest.fn((x) => x),
};

export const $route = {
  name: "",
  params: {},
  query: {
    page: 1,
    per_page: 15,
  },
};

export const query = {
  page: 1,
  per_page: 15,
};

export const localStorageMock = {
  getItem: jest.fn(),
  setItem: jest.fn(),
  clear: jest.fn(),
};
