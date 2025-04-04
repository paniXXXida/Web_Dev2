<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { API_ENDPOINTS } from '@/config';
import { currentUser } from '@/utils/auth';

const bookRequests = ref([]);
const isLoading = ref(true);
const error = ref(null);

const fetchRequests = async () => {
  try {
    const res = await axios.get(`${API_ENDPOINTS.bookRequests}/user`);
    bookRequests.value = res.data;
  } catch (err) {
    error.value = err.response?.data?.error || 'Failed to load requests';
  } finally {
    isLoading.value = false;
  }
};

const cancelRequest = async (id) => {
  try {
    await axios.delete(`${API_ENDPOINTS.bookRequests}/${id}`);
    bookRequests.value = bookRequests.value.filter((req) => req.id !== id);
  } catch (err) {
    alert('Failed to cancel request');
  }
};

onMounted(fetchRequests);
</script>

<template>
  <div class="container mt-4">
    <h2 class="mb-3">Your Book Requests</h2>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-else-if="bookRequests.length === 0" class="alert alert-info">
      You have no book requests.
    </div>
    <div v-else>
      <table class="table">
        <thead>
        <tr>
          <th>Book ID</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="req in bookRequests" :key="req.id">
          <td>{{ req.book_id }}</td>
          <td>{{ req.status }}</td>
          <td>
            <button
                class="btn btn-sm btn-danger"
                @click="cancelRequest(req.id)"
                v-if="req.status === 'pending'"
            >
              Cancel
            </button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
