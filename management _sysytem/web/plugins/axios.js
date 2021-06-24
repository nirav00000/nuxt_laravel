/* eslint-disable no-console */
import Vue from "vue";

function showToast(message, type) {
  Vue.toasted.show(message, {
    theme: "toasted-primary",
    type,
    position: "bottom-right",
    duration: 5000,
  });
}

export default function ({ $axios, store, app, redirect, $config }) {
  app.router.beforeEach((to, from, next) => {
    if (!process.server) {
      // window && window.$nuxt && window.$nuxt.$root.$loading.start();
    }

    console.log(to.name);

    if (from.name) {
      store.state.source &&
        store.state.source.cancel &&
        store.state.source.cancel("Operation canceled by the user.");
    }

    next();
  });

  app.router.afterEach(() => {
    if (!process.server) {
      // window && window.$nuxt && window.$nuxt.$root.$loading.finish();
    }
  });

  $axios.onRequest((config) => {
    config.headers.common["x-auth-token"] = store.state.token;
    config.headers.common.group = store.state.groups;

    store.commit("loading", true);
  });

  $axios.onResponse((response) => {
    store.commit("loading", false);

    if (process.server) {
      console.log(
        `[${response && response.status}] ${
          response && response.request && response.request.path
        }`
      );
      console.log(`  `);
    }

    store.commit("spinner", false);

    process.client &&
      response.status === 201 &&
      showToast(response.data.message, "success");
  });

  $axios.onError((error) => {
    store.commit("spinner", false);
    // if ($axios.isCancel(thrown)) {
    //   console.log("Request canceled", thrown.message);
    // } else {
    //   // handle error
    // }

    // // register error in sentry
    if ($config.MODE !== "development") {
      if (error && !error.response) {
        // app.$sentry.captureException(new Error(JSON.stringify(error)));
      }
    }

    if (process.server) {
      console.log(
        `[${error && error.response && error.response.status}] ${
          error &&
          error.response &&
          error.response.request &&
          error.response.request.path
        }`
      );
      console.log(error && error.response && error.response.data);
      console.log(`  `);
    }

    if (error.toString().includes("Error: timeout")) {
      store
        .dispatch(
          "error",
          "Something went wrong, can't process your request right now"
        )
        .then(() => {})
        .catch((e) => {
          return e;
        });
    }

    if (error.toString().includes("Error: Network Error")) {
      store
        .dispatch(
          "error",
          "Looks like you are currently offline. Please try again later."
        )
        .then(() => {})
        .catch((e) => {
          return e;
        });
    }

    if (error && typeof error === "object") {
      if (error.message !== "Operation canceled by the user.") {
        const errorData = JSON.stringify(
          error &&
            error.response &&
            error.response.data &&
            (error.response.data.reason || error.response.data.error)
        );

        console.log(errorData);

        // if (
        //   errorData !== '"Tenant does not exist"' &&
        //   !errorData.includes("about operation")
        // ) {
        // process.client &&
        //   errorData.length > 2 &&
        //   Vue.toasted.show(errorData, {
        //     theme: "bubble",
        //     position: "top-right",
        //     duration: 15000,
        //   });

        // redirect user to login screen if token expired
        const status = error && error.response && error.response.status;

        if (status === 401) {
          // if (process.server) {
          store.commit("revokeToken");
          // app.$cookies.remove("pandio");
          // app.$cookies.remove("active_account");
          app.$cookies.remove("token");
          redirect("/login");
          // }
        }
        // }
      }
    } else {
      process.client && showToast(error.message, "error");
    }
  });

  $axios.onRequestError((error) => {
    process.client && showToast(error.message, "error");
  });

  $axios.onResponseError((error) => {
    if ($axios.isCancel(error)) {
      console.log("Request canceled");
    } else if (error.response.status === 401) {
      process.client &&
        showToast("Authentication failed, please try to login again", "error");
    } else if (error.response.status === 400) {
      process.client && showToast(error.response.data.message, "error");
    } else {
      process.client && showToast(error.response.data.message, "error");
    }
  });

  $axios.interceptors.request.use(
    (request) => {
      // console.log(request);

      const source = Object.assign({}, {}, store.state.source);
      request.cancelToken = source.token;

      return request;
    },
    (error) => {
      // console.log(error);

      return Promise.reject(error);
    }
  );

  // $axios.interceptors.response.use(
  //   (response) => {
  //     // console.log(response);

  //     // Edit response config
  //     return response;
  //   },
  //   (error) => {
  //     // console.log(error);

  //     return Promise.reject(error);
  //   }
  // );
}
