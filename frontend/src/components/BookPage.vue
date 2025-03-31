<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";

const book = ref(null);
const error = ref(null);
const loading = ref(true);
const route = useRoute();

onMounted(async () => {
  try {
    const response = await axios.get(`http://localhost/books/${route.params.id}`);
    book.value = response.data;
  } catch (err) {
    console.error(err);
    error.value = "Failed to load book";
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <div class="container py-5">
    <div v-if="loading">Loading book...</div>
    <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-else-if="book" class="card">
      <div class="card-body">
        <h2 class="card-title">{{ book.title }}</h2>
        <p class="card-subtitle mb-2 text-muted">Author: {{ book.author }}</p>
        <p class="mt-3"><strong>ID:</strong> {{ book.id }}</p>
      </div>
    </div>
    <div v-else class="alert alert-warning">Book not found</div>
  </div>
</template>

<style scoped></style>
