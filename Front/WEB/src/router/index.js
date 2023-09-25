import { createRouter, createWebHistory } from 'vue-router';
import Welcome from '../components/Welcome.vue';
import Login from '../components/Login.vue';

const routes = [
  { path: '/', component: Welcome, name: 'home' },
  { path: '/login', component: Login, name: 'login' },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;