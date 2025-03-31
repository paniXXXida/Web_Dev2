<script>
export default {};
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <RouterLink to="/" class="navbar-brand">MyBooks</RouterLink>
      <div class="navbar-nav ms-auto">
        <RouterLink class="nav-item nav-link" to="/books">Books</RouterLink>
        <RouterLink class="nav-item nav-link" to="/auth">Login/Register</RouterLink>
        <RouterLink class="nav-item nav-link" to="/profile">Profile</RouterLink>
      </div>
    </div>
  </nav>
  <div class="container mt-4">
    <main>
      <RouterView />
    </main>
  </div>
</template>

<style scoped>
.navbar-nav .nav-link {
  margin-left: 1rem;
}
</style>
