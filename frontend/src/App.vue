<template>
  <Header />
  <div class="container mt-4">
    <main>
      <RouterView />
    </main>
  </div>
</template>

<script>
import Header from "./components/partials/Header.vue";
export default {
  components: {
    Header,
  },
};
</script>
