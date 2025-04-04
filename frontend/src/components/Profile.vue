<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { API_ENDPOINTS } from "@/config";
import { currentUser, fetchUser, getAuthToken } from "@/utils/auth";

const requests = ref([]);
const loading = ref(true);
const error = ref(null);

const loadRequests = async () => {
  try {
    const response = await axios.get(API_ENDPOINTS.bookRequests + "/user", {
      headers: {
        Authorization: `Bearer ${getAuthToken()}`,
      },
    });
    requests.value = response.data;
  } catch (err) {
    console.error("Failed to load your requests:", err);
    error.value = "Failed to load your requests.";
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  await fetchUser();
  await loadRequests();
});

const cancelRequest = async (requestId) => {
  console.log("Clicked cancel for request:", requestId); // <- это ключ
  try {
    const response = await axios.put(
        `${API_ENDPOINTS.bookRequests}/${requestId}/cancel`,
        {},
        {
          headers: {
            Authorization: `Bearer ${getAuthToken()}`,
          },
        }
    );
    console.log("Cancel response:", response.data);
    await loadRequests();
  } catch (err) {
    console.error("Failed to cancel request:", err);
  }
};

</script>

<template>
  <div class="container mt-4">
    <h2 class="mb-4">Profile</h2>

    <div v-if="currentUser" class="mb-4">
      <p><strong>Name:</strong> {{ currentUser.name }}</p>
      <p><strong>Email:</strong> {{ currentUser.email }}</p>
    </div>

    <div>
      <h4>Your Book Requests</h4>
      <div v-if="loading">Loading requests...</div>
      <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
      <div v-else-if="requests.length === 0">No requests found.</div>
      <ul class="list-group">
        <li
            v-for="request in requests"
            :key="request.id"
            class="list-group-item d-flex justify-content-between align-items-center"
        >
          <div>
            <strong>{{ request.book_title }}</strong><br />
            <small>Status: {{ request.status }}</small>
          </div>
          <button
              v-if="request.status === 'pending'"
              class="btn btn-danger btn-sm"
              @click="cancelRequest(request.id)"
          >
            Cancel
          </button>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>
.list-group-item {
  margin-bottom: 0.5rem;
}
</style>
