<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { getAuthToken } from "@/utils/auth";

const books = ref([]);
const loading = ref(true);
const error = ref(null);
const requestSuccess = ref(null);
const router = useRouter();

onMounted(async () => {
  try {
    const response = await axios.get("http://localhost/books");
    books.value = response.data;
  } catch (err) {
    error.value = "Failed to load books";
  } finally {
    loading.value = false;
  }
});

const requestBook = async (bookId) => {
  try {
    const token = getAuthToken();
    await axios.post(
        "http://localhost/book-requests",
        { book_id: bookId },
        { headers: { Authorization: `Bearer ${token}` } }
    );
    requestSuccess.value = bookId;
  } catch (err) {
    console.error(err);
    error.value = "Failed to request book";
  }
};

const goToBookPage = (bookId) => {
  router.push(`/books/${bookId}`);
};
</script>

<template>
  <div class="container py-4">
    <h2 class="mb-4">Available Books</h2>
    <div v-if="loading">Loading books...</div>
    <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-else>
      <div
          v-for="book in books"
          :key="book.id"
          class="card mb-3 cursor-pointer"
          @click="goToBookPage(book.id)"
      >
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title mb-1">{{ book.title }}</h5>
            <p class="mb-0"><strong>Author:</strong> {{ book.author }}</p>
          </div>
          <button
              class="btn btn-outline-primary"
              @click.stop="requestBook(book.id)"
              :disabled="requestSuccess === book.id"
          >
            {{ requestSuccess === book.id ? "Requested" : "Request" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>
