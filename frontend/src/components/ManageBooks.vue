<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { getAuthToken } from "@/utils/auth";

const books = ref([]);
const title = ref("");
const author = ref("");
const error = ref(null);
const success = ref(null);

const loadBooks = async () => {
  try {
    const response = await axios.get("http://localhost/books");
    books.value = response.data;
  } catch (err) {
    error.value = "Failed to load books";
  }
};

const addBook = async () => {
  error.value = null;
  success.value = null;

  try {
    const token = getAuthToken();
    const response = await axios.post(
        "http://localhost/books",
        {
          title: title.value,
          author: author.value,
        },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
    );
    books.value.unshift(response.data);
    success.value = "Book added successfully";
    title.value = "";
    author.value = "";
  } catch (err) {
    error.value = "Failed to add book";
    console.error(err);
  }
};

const deleteBook = async (bookId) => {
  error.value = null;
  success.value = null;

  try {
    const token = getAuthToken();
    await axios.delete(`http://localhost/books/${bookId}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    books.value = books.value.filter((b) => b.id !== bookId);
    success.value = "Book deleted successfully";
  } catch (err) {
    error.value = "Failed to delete book";
    console.error(err);
  }
};

onMounted(loadBooks);
</script>

<template>
  <div class="container py-4">
    <h2 class="mb-4">Manage Books</h2>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-if="success" class="alert alert-success">{{ success }}</div>

    <form @submit.prevent="addBook" class="row g-3 mb-4">
      <div class="col-md-5">
        <input
            type="text"
            v-model="title"
            placeholder="Book Title"
            class="form-control"
            required
        />
      </div>
      <div class="col-md-5">
        <input
            type="text"
            v-model="author"
            placeholder="Author"
            class="form-control"
            required
        />
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-success w-100">Add Book</button>
      </div>
    </form>

    <table class="table table-bordered">
      <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="book in books" :key="book.id">
        <td>{{ book.id }}</td>
        <td>{{ book.title }}</td>
        <td>{{ book.author }}</td>
        <td>
          <button class="btn btn-danger btn-sm" @click="deleteBook(book.id)">
            Delete
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
