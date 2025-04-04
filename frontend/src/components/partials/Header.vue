<script setup>
import { useRouter } from "vue-router";
import { currentUser, setAuthToken } from "@/utils/auth";

const router = useRouter();

const logout = () => {
  setAuthToken(null);
  router.push("/auth");
};
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <RouterLink to="/" class="navbar-brand">MyBooks</RouterLink>
      <div class="navbar-nav ms-auto">
        <RouterLink class="nav-item nav-link" to="/books">Books</RouterLink>

        <RouterLink
            v-if="currentUser && currentUser.role === 'admin'"
            class="nav-item nav-link"
            to="/admin"
        >
          Admin Panel
        </RouterLink>

        <RouterLink
            v-if="currentUser"
            class="nav-item nav-link"
            to="/profile"
        >
          Profile
        </RouterLink>

        <button
            v-if="currentUser"
            @click="logout"
            class="btn btn-outline-danger ms-3"
        >
          Logout
        </button>

        <RouterLink
            v-else
            class="nav-item nav-link"
            to="/auth"
        >
          Login / Register
        </RouterLink>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar-nav .nav-link {
  margin-left: 1rem;
}
</style>
