<template>
  <div>
    <div v-if="isDevelopment">
      <h2 class="text-center">Login</h2>
      <b-modal
        id="token-modal"
        ok-variant="success"
        cancel-variant="danger"
        button-size="sm"
        centered
        :static="true"
        title="Enter token to login"
        @ok="submitToken"
        @hidden="loginToken = ''"
      >
        <b-form-input
          id="input-live-help"
          v-model="loginToken"
          placeholder="Enter token here"
        ></b-form-input>
        <b-form-input
          id="group"
          v-model="group"
          placeholder="Enter group (ex. management, hiring) use comma"
        ></b-form-input>
      </b-modal>
      <b-card class="mx-auto w-25 p-2 text-center">
        <b-button id="login-btn" v-b-modal.token-modal variant="primary"
          >Login</b-button
        >
      </b-card>
    </div>
    <div v-if="!isDevelopment">
      <h2 class="text-center mt-4">{{ state }}</h2>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      loginToken: "",
      group: "",
      isDevelopment: false,
      state: "Logging...",
    };
  },

  mounted() {
    // Web is running in procution or stagging mode
    if (process.env.MODE !== "development" && process.env.MODE !== "testing") {
      // Prefill email with return response by OAuth
      this.isDevelopment = false;
      this.fetchToken();
    } else this.isDevelopment = true;
  },

  methods: {
    async fetchToken() {
      try {
        const res = await axios.get(`/dex-oauth2/auth`);
        const token = res.headers["x-auth-request-access-token"];
        const user = await axios.get(`/api/v1/groups`, {
          headers: {
            "x-auth-token": token,
          },
        });
        this.group = user.data.data.groups;
        this.loginToken = token;
        this.state = "Logging Successful!";
        this.submitToken();
      } catch (e) {
        this.$toasted.show("Something went wrong, Please realod your page!", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
        this.state = "Page will be reload in 5 seconds!";
        setTimeout(() => window.location.reload(), 5000);
      }
    },

    submitToken() {
      this.loginToken &&
        this.group &&
        this.$store
          .dispatch("headerToken", {
            token: this.loginToken,
            group: this.group,
          })
          .then(() => {
            this.$store.dispatch("user").then(() => {
              if (this.$store.getters.getToken) {
                this.$router.push("/");
              }
            });
          });
    },
  },
};
</script>
