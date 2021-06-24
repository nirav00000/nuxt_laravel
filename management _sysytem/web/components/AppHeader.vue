<template>
  <div>
    <b-navbar fixed toggleable="md" type="dark" variant="dark">
      <b-navbar-brand href="/">Apricot</b-navbar-brand>

      <b-navbar-toggle
        v-if="isLoggedIn"
        target="nav-collapse"
      ></b-navbar-toggle>

      <b-collapse v-if="isLoggedIn" id="nav-collapse" is-nav>
        <b-navbar-nav>
          <b-nav-item to="/candidacy" prefetch>Candidacy</b-nav-item>
          <b-nav-item to="/stages" prefetch>Stages</b-nav-item>
          <b-nav-item to="/coding-challenges" prefetch
            >Coding Challenges</b-nav-item
          >
        </b-navbar-nav>

        <!-- Right aligned nav items -->
        <b-navbar-nav v-if="isLoggedIn" class="ml-auto">
          <b-nav-item-dropdown right>
            <!-- Using 'button-content' slot -->
            <template #button-content>
              <em id="em-username">Hello, {{ user }}</em>
            </template>
            <b-dropdown-item v-if="isLoggedIn" id="logout-btn" @click="logOut"
              >Log Out</b-dropdown-item
            >
            <b-dropdown-item v-else href="/login">Log In</b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>

<script>
// import jwt from "jsonwebtoken";

export default {
  async fetch() {
    if (this.isLoggedIn) {
      await this.$store.dispatch("user");
    }
  },
  computed: {
    isLoggedIn() {
      return this.$store.getters.getToken !== null;
    },
    user() {
      if (this.$store.getters.getUser) {
        return this.$store.getters.getUser.user_name;
      } else {
        return "User";
      }
    },
  },
  methods: {
    logOut() {
      this.$store.dispatch("logOut").then(() => {
        if (!this.$store.getters.getToken) {
          this.$router.push("/login");
        }
      });
    },
  },
};
</script>

<style>
.nuxt-link-active {
  color: #fff;
  font-weight: bold;
  text-decoration: none;
}

.navbar-dark .navbar-nav .nav-link,
.navbar-dark .navbar-nav .nav-link:hover,
.navbar-dark .navbar-nav .nav-link:focus {
  color: #fff;
}
</style>
