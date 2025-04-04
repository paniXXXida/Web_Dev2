import { createRouter, createWebHistory } from "vue-router";
import { createApp } from "vue";
import { createPinia } from "pinia";

import App from "./App.vue";
import HomePage from "./components/HomePage.vue";
import ManageBooks from "./components/ManageBooks.vue";
import BookList from "./components/BookList.vue";
// import BookComments from "./components/BookComments.vue";
import BookRequests from "./components/BookRequest.vue";
import BookPage from "./components/BookPage.vue";
import Auth from "./components/Auth.vue";
import Profile from "./components/Profile.vue";
import Admin from "./components/AdminDashboard.vue";

import { getAuthToken, setAuthToken } from "@/utils/auth";

import "./assets/main.css";

// Set token if it exists
const token = getAuthToken();
if (token) {
  setAuthToken(token);
}

const routes = [
  {
    path: "/",
    component: HomePage,
  },
  {
    path: "/books",
    component: BookList,
  },
  {
    path: "/books/:id",
    component: BookPage,
  },
  {
    path: "/bookrequest",
    component: BookRequests,
  },
  // {
  //   path: "/comments",
  //   component: BookComments,
  // },
  {
    path: "/auth",
    component: Auth,
  },
  {
    path: "/admin",
    component: Admin,
  },
  {
    path: "/admin/manage-books",
    component: ManageBooks,
  },
  {
    path: "/profile",
    component: Profile,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const app = createApp(App);
app.use(router);
app.use(createPinia());
app.mount("#app");
