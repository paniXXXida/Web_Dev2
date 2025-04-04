<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { getAuthToken } from "@/utils/auth";

const users = ref([]);
const requests = ref([]);
const error = ref(null);
const loading = ref(true);
const statusUpdateMessage = ref(null);

// Add Book Form
const newBook = ref({
  title: "",
  author: "",
  description: "",
});
const bookAddMessage = ref(null);

const fetchData = async () => {
  try {
    const token = getAuthToken();
    const headers = {
      Authorization: `Bearer ${token}`,
    };

    const [usersRes, requestsRes] = await Promise.all([
      axios.get("http://localhost/admin/users", { headers }),
      axios.get("http://localhost/admin/book-requests", { headers }),
    ]);

    users.value = usersRes.data;

    const rawRequests = Array.isArray(requestsRes.data)
        ? requestsRes.data
        : requestsRes.data.data ?? [];

    requests.value = rawRequests.map((r) => ({
      id: r.id ?? r.request_id,
      book_title: r.book_title ?? `Book #${r.book_id}`,
      ...r,
    }));
  } catch (err) {
    console.error(err);
    error.value = "Failed to load admin data";
  } finally {
    loading.value = false;
  }
};

const updateRequestStatus = async (requestId, status) => {
  try {
    const token = getAuthToken();
    await axios.put(
        `http://localhost/admin/book-requests/${requestId}`,
        { status },
        { headers: { Authorization: `Bearer ${token}` } }
    );
    statusUpdateMessage.value = `Request #${requestId} updated to ${status}`;
    await fetchData();
  } catch (err) {
    console.error(err);
    error.value = "Failed to update request status";
  }
};

const addBook = async () => {
  try {
    const token = getAuthToken();
    await axios.post(
        "http://localhost/admin/books",
        { ...newBook.value },
        { headers: { Authorization: `Bearer ${token}` } }
    );
    bookAddMessage.value = "Book added successfully!";
    newBook.value = { title: "", author: "", description: "" };
    await fetchData();
  } catch (err) {
    console.error(err);
    bookAddMessage.value = "Failed to add book.";
  }
};

onMounted(fetchData);
</script>

<template>
  <div class="container py-5">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div v-if="loading">Loading admin data...</div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-if="statusUpdateMessage" class="alert alert-success">{{ statusUpdateMessage }}</div>

    <h4 class="mt-4">Users</h4>
    <table class="table">
      <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="user in users" :key="user.id">
        <td>{{ user.id }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.role }}</td>
      </tr>
      </tbody>
    </table>

    <h4 class="mt-5">Book Requests</h4>
    <table class="table">
      <thead>
      <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Book Title</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="req in requests" :key="req.id">
        <td>{{ req.id }}</td>
        <td>{{ req.user_id }}</td>
        <td>{{ req.book_title }}</td>
        <td>{{ req.status }}</td>
        <td>
          <template v-if="req.status === 'pending'">
            <button
                class="btn btn-success btn-sm me-2"
                @click="updateRequestStatus(req.id, 'approved')"
            >
              Approve
            </button>
            <button
                class="btn btn-danger btn-sm"
                @click="updateRequestStatus(req.id, 'rejected')"
            >
              Reject
            </button>
          </template>
          <template v-else>
            <span class="text-muted">No actions</span>
          </template>
        </td>
      </tr>
      </tbody>
    </table>

    <h4 class="mt-5">Add New Book</h4>
    <form @submit.prevent="addBook" class="mb-4">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input v-model="newBook.title" type="text" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Author</label>
        <input v-model="newBook.author" type="text" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea v-model="newBook.description" class="form-control" rows="3"></textarea>
      </div>
      <button class="btn btn-primary">Add Book</button>
    </form>
    <div v-if="bookAddMessage" class="alert alert-info">{{ bookAddMessage }}</div>
  </div>
</template>

<style scoped>
.table th,
.table td {
  vertical-align: middle;
}
</style>
