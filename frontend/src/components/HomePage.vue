<template>
  <div>
    <div class="banner">
      <img src="@/assets/mybooks_banner.jpg" alt="MyBooks Banner" class="w-100 banner-img" />
      <div class="banner-overlay">
        <h1 class="text-white text-center">Welcome to MyBooks</h1>
      </div>
    </div>

    <div class="container py-5 text-center">
      <h2 class="mb-4">Explore Our Library</h2>
      <router-link to="/books" class="btn btn-primary btn-lg">Browse Books</router-link>
    </div>
  </div>
</template>

<script>
import '@/assets/main.css';

export default {
  name: 'HomePage'
}
</script>