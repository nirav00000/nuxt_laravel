<template>
  <div>
    <div v-if="!isRegestered" data-test="title" class="container col-md-6">
      <h1 class="text-primary text-center">Registration Page</h1>
      <form class="font-weight-bold" @submit.prevent="registerCandidacy">
        <div class="form-group">
          <label for="name">Name: </label>
          <input
            id="name"
            v-model="formData.name"
            class="form-control"
            type="text"
            name="name"
            placeholder="Enter your name"
            required
          />
          <i v-if="response.data.name" class="text-danger">{{
            response.data.name[0]
          }}</i>
        </div>
        <div class="form-group">
          <label for="email">Email: </label>
          <input
            id="email"
            v-model="formData.email"
            class="form-control"
            type="email"
            name="email"
            placeholder="Enter your email"
            required
            :disabled="!isDevelopment"
          />
          <i v-if="response.data.email" class="text-danger">{{
            response.data.email[0]
          }}</i>
        </div>
        <div class="form-group">
          <label for="contact_no">Contact: </label>
          <input
            v-model="formData.metadata.contact_no"
            class="form-control"
            type="text"
            name="contact_no"
            placeholder="Enter your contact no."
          />
        </div>
        <div class="form-group">
          <label for="education">Education: </label>
          <input
            v-model="formData.metadata.education"
            class="form-control"
            type="text"
            name="education"
            placeholder="Enter your education"
          />
        </div>
        <div class="form-group">
          <label for="college">College: </label>
          <input
            v-model="formData.metadata.college"
            class="form-control"
            type="text"
            name="college"
            placeholder="Enter your college"
          />
        </div>

        <div class="form-group">
          <label for="experience">Experience: </label>
          <input
            v-model="formData.metadata.experience"
            class="form-control"
            type="number"
            name="experience"
            placeholder="Enter your experience (in months)(in integer)"
          />
        </div>

        <div class="form-group">
          <label for="last_company">Last Company: </label>
          <input
            v-model="formData.metadata.last_company"
            class="form-control"
            type="text"
            name="last_company"
            placeholder="Enter your last company"
          />
        </div>
        <div class="form-group">
          <label for="position">Position: </label>
          <input
            id="position"
            v-model="formData.position"
            class="form-control"
            type="text"
            name="position"
            placeholder="Enter position you are applying for"
            required
          />
          <i v-if="response.data.position" class="text-danger">{{
            response.data.position[0]
          }}</i>
        </div>
        <button
          id="submit"
          type="submit"
          class="btn btn-primary"
          :disabled="isLoading"
        >
          Submit
        </button>
      </form>
    </div>
    <div v-if="isRegestered">
      <div class="w-100 h-100 d-block">
        <div class="text-center" style="margin-top: 72px">
          <h2>Your Registration successful!</h2>
          <p>You have regestred with us successfully.</p>
          <button class="btn btn-primary" @click="reRegister">
            Re-Register
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      formData: {
        name: "",
        email: "Loading...",
        position: "",
        metadata: {},
      },
      response: { message: "", data: "" },
      accessToken: "",
      isLoading: true,
      isDevelopment: false,
      isRegestered: false,
    };
  },

  mounted() {
    // Web is running in procdution or staging
    if (process.env.MODE !== "development" && process.env.MODE !== "testing") {
      // Prefill email with return response by OAuth
      this.isDevelopment = false;
      axios
        .get(`/oauth2/auth`)
        .then((res) => {
          this.formData.email = res.headers["x-auth-request-email"];
          this.accessToken = res.headers["x-auth-request-access-token"];
        })
        .catch(() => {
          window.location.reload();
        });
    } else {
      this.isDevelopment = true;
      this.formData.email = "";
    }

    this.isLoading = false;
  },

  methods: {
    registerCandidacy() {
      this.isLoading = true;
      this.response.message = "";
      this.response.data = "";
      axios
        .post("/api/v1/candidacies", this.formData, {
          headers: {
            "x-access-token": this.accessToken || "something!",
          },
        })
        .then(() => {
          this.isRegestered = true;
          this.formData.name = "";
          this.formData.email = "";
          this.formData.position = "";
          this.formData.metadata = {};
          this.$toasted.show("Successfully Registered!", {
            theme: "toasted-primary",
            type: "success",
            position: "bottom-right",
            duration: 5000,
          });
        })
        .catch((error) => {
          if (typeof error.response.data === "string") {
            this.response.message = "Unknown Error"; // frontend errors
          } else if (
            error.response &&
            error.response.data &&
            error.response.data.errors
          ) {
            // validation error
            this.response.message = "Invalid data inserted!!";
            this.response.data =
              error &&
              error.response &&
              error.response.data &&
              error.response.data.errors;
          } else {
            this.response.message = error.response.data.message; // general backend errors
          }

          this.$toasted.show(
            this.response.message + " |  status_code:" + error.response.status,
            {
              theme: "toasted-primary",
              type: "error",
              position: "bottom-right",
              duration: 5000,
            }
          );
        });
      this.isLoading = false;
    },

    reRegister() {
      // App in development mode, so not need to signout with google
      if (this.isDevelopment) {
        this.isRegestered = false;
      } else {
        // App in either prod, stage, so need to signout by send request at backend
        axios({ method: "GET", url: "/oauth2/sign_out" })
          .then(() => {})
          .catch(() => {
            window.location.reload();
          });
      }
    },
  },
};
</script>
