<script setup>
import { useRouter } from "vue-router";
import { useAuth } from "@/utils/auth.js";
import Notification from "./Notification.vue";
import Loading from "./Loading.vue";

const router = useRouter();
const {
  isLogin,
  formData,
  isLoading,
  error,
  success,
  toggleAuthMode,
  handleSubmit,
} = useAuth(router);
</script>

<template>
  <Loading v-if="isLoading" />
  <Notification v-if="success" :isError="false" @close="success = null">
    {{ success }}
  </Notification>
  <div class="row justify-content-center align-items-center min-vh-100 m-0">
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card shadow">
        <div class="card-body p-4">
          <div class="text-center mb-4">
            <h2 class="card-title mb-3">
              {{ isLogin ? "Login" : "Register" }}
            </h2>
            <button class="btn btn-link p-0" @click="toggleAuthMode">
              {{ isLogin ? "Need an account?" : "Already have an account?" }}
            </button>
          </div>

          <form @submit.prevent="handleSubmit">
            <div class="mb-3" v-if="!isLogin">
              <label for="name" class="form-label">Name</label>
              <input
                  type="text"
                  class="form-control"
                  id="name"
                  v-model="formData.name"
                  required
                  placeholder="Enter your name"
              />
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                  type="email"
                  class="form-control"
                  id="email"
                  v-model="formData.email"
                  required
                  placeholder="Enter your email"
              />
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input
                  type="password"
                  class="form-control"
                  id="password"
                  v-model="formData.password"
                  required
                  placeholder="Enter your password"
              />
            </div>
            <button type="submit" class="btn btn-primary w-100">
              {{ isLogin ? "Login" : "Register" }}
            </button>
          </form>

          <Notification
              v-if="error"
              :isError="true"
              @close="error = null"
              class="mt-3"
          >
            {{ error }}
          </Notification>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
