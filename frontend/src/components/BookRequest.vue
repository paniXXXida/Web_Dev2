<template>
  <div class="container py-5">
    <h2 class="text-center mb-4">My Book Requests</h2>
    <div v-if="requests.length" class="table-responsive">
      <table class="table table-striped">
        <thead>
        <tr>
          <th>Book</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="request in requests" :key="request.id">
          <td>{{ request.book }}</td>
          <td>
            <span :class="statusClass(request.status)">{{ request.status }}</span>
          </td>
          <td>{{ formatDate(request.requested_at) }}</td>
        </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="text-center mt-5">
      <p>You have no book requests.</p>
    </div>
  </div>
</template>

<script>
import '@/assets/main.css';
import axios from 'axios';

export default {
  name: 'BookRequests',
  data() {
    return {
      requests: []
    }
  },
  async mounted() {
    try {
      const token = localStorage.getItem('token');
      const response = await axios.get('http://localhost/requests', {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
      this.requests = response.data;
    } catch (error) {
      console.error('Failed to load book requests:', error);
    }
  },
  methods: {
    formatDate(dateStr) {
      const date = new Date(dateStr);
      return date.toLocaleDateString();
    },
    statusClass(status) {
      return {
        pending: 'text-warning',
        approved: 'text-success',
        rejected: 'text-danger'
      }[status] || 'text-muted';
    }
  }
}
</script>
