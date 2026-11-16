import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useAuthStore = defineStore('auth', () => {
  const user = ref({
    id: 4,
    name: 'Abebe Bekele',
    email: 'student@gelme.global',
    role: 'student',
    avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80',
  });
  const token = ref('gelme_demo_token_2026');
  const isAuthenticated = ref(true);

  async function login(email, password) {
    try {
      const res = await axios.post('/api/auth/login', { email, password });
      user.value = res.data.user;
      token.value = res.data.token;
      isAuthenticated.value = true;
      return true;
    } catch (e) {
      // fallback for demo
      user.value = {
        id: 4,
        name: 'Abebe Bekele',
        email: email || 'student@gelme.global',
        role: 'student',
        avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80',
      };
      isAuthenticated.value = true;
      return true;
    }
  }

  function logout() {
    user.value = null;
    token.value = null;
    isAuthenticated.value = false;
  }

  return { user, token, isAuthenticated, login, logout };
});
