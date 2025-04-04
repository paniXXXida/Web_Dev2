<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import { API_ENDPOINTS } from "@/config";
import { currentUser } from "@/utils/auth";

const route = useRoute();
const book = ref(null);
const loading = ref(true);
const error = ref(null);
const comments = ref([]);
const newComment = ref("");
const newRating = ref("");

const fetchBook = async () => {
  try {
    const response = await axios.get(`${API_ENDPOINTS.books}/${route.params.id}`);
    book.value = response.data;
  } catch (err) {
    console.error(err);
    error.value = "Failed to load book details.";
  }
};

const fetchComments = async () => {
  try {
    const response = await axios.get(`${API_ENDPOINTS.comments}/book/${route.params.id}`);
    comments.value = response.data;
  } catch (err) {
    console.error("Failed to fetch comments", err);
  }
};

const postComment = async () => {
  if (!newComment.value.trim()) return;
  try {
    await axios.post(API_ENDPOINTS.comments, {
      book_id: route.params.id,
      comment: newComment.value,
      rating: newRating.value || null,
    });
    newComment.value = "";
    newRating.value = "";
    await fetchComments();
  } catch (err) {
    console.error("Failed to post comment", err);
  }
};

onMounted(async () => {
  await fetchBook();
  await fetchComments();
  loading.value = false;
});
</script>

<template>
  <div class="container mt-4">
    <div v-if="loading">Loading book details...</div>
    <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-else-if="book">
      <div class="card p-4 mb-4 shadow-sm">
        <h2>{{ book.title }}</h2>
        <p><strong>Author:</strong> {{ book.author }}</p>
        <p><strong>Description:</strong> {{ book.description || "No description." }}</p>
      </div>

      <div class="comments-section">
        <h4 class="mb-3">Comments</h4>

        <div v-if="comments.length === 0" class="text-muted mb-3">
          No comments yet. Be the first!
        </div>

        <ul class="list-group mb-4">
          <li
              v-for="comment in comments"
              :key="comment.id"
              class="list-group-item d-flex justify-content-between align-items-start"
          >
            <div>
              <strong>{{ comment.user_name }}</strong><br />
              <template v-if="comment.rating">
                ⭐ {{ comment.rating }}/5
              </template>
              <div>{{ comment.comment }}</div>
            </div>
          </li>
        </ul>

        <div v-if="currentUser" class="comment-form">
          <textarea
              class="form-control mb-2"
              v-model="newComment"
              placeholder="Write your comment..."
              rows="3"
          ></textarea>

          <label for="rating" class="form-label">Rate the book:</label>
          <select
              id="rating"
              v-model="newRating"
              class="form-select mb-3"
          >
            <option disabled value="">Select rating</option>
            <option v-for="n in 5" :key="n" :value="n">{{ n }} / 5</option>
          </select>

          <button class="btn btn-primary" @click="postComment">Post Comment</button>
        </div>

        <div v-else class="text-muted">Log in to leave a comment.</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card {
  border-radius: 12px;
  background-color: #f9f9f9;
}
.comments-section {
  margin-top: 2rem;
}
.list-group-item {
  border-radius: 8px;
  margin-bottom: 0.5rem;
}
</style>
