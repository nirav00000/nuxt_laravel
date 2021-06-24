<template>
  <div class="page-container">
    <AppHeader class="sticky-top" />
    <Nuxt class="content-wrap" />
    <AppFooter />
  </div>
</template>
<script>
export default {
  middleware: "auth",
};
</script>

<style>
.page-container {
  position: relative;
  min-height: 100vh;
}

.content-wrap {
  padding-bottom: 2.5rem;
}
</style>
