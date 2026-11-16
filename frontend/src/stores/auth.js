import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import axios from 'axios';

export const useAuthStore = defineStore('auth', () => {
  const savedToken = localStorage.getItem('gelme_auth_token');
  const savedUser = localStorage.getItem('gelme_user');

  const token = ref(savedToken || null);
  const user = ref(savedUser ? JSON.parse(savedUser) : null);
  const isAuthenticated = computed(() => !!token.value && !!user.value);
  const isInstructor = computed(() => user.value?.role === 'instructor' || user.value?.role === 'admin');
  const isAdmin = computed(() => user.value?.role === 'admin');

  // Configure Axios default Bearer header
  if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
  }

  function setSession(userData, tokenValue) {
    user.value = userData;
    token.value = tokenValue;
    if (tokenValue) {
      localStorage.setItem('gelme_auth_token', tokenValue);
      axios.defaults.headers.common['Authorization'] = `Bearer ${tokenValue}`;
    } else {
      localStorage.removeItem('gelme_auth_token');
      delete axios.defaults.headers.common['Authorization'];
    }

    if (userData) {
      localStorage.setItem('gelme_user', JSON.stringify(userData));
    } else {
      localStorage.removeItem('gelme_user');
    }
  }

  async function login(email, password) {
    try {
      const res = await axios.post('/api/auth/login', { email, password });
      setSession(res.data.user, res.data.token);
      return { success: true, user: res.data.user };
    } catch (e) {
      const msg = e.response?.data?.message || 'Invalid email or password.';
      return { success: false, message: msg };
    }
  }

  async function register({ name, email, password, role = 'student' }) {
    try {
      const res = await axios.post('/api/auth/register', { name, email, password, role });
      setSession(res.data.user, res.data.token);
      return { success: true, user: res.data.user };
    } catch (e) {
      const msg = e.response?.data?.message || 'Registration failed.';
      return { success: false, message: msg };
    }
  }

  async function fetchMe() {
    if (!token.value) return null;
    try {
      const res = await axios.get('/api/auth/me');
      if (res.data.user) {
        user.value = res.data.user;
        localStorage.setItem('gelme_user', JSON.stringify(res.data.user));
      }
      return user.value;
    } catch (e) {
      if (e.response?.status === 401) {
        logout();
      }
      return null;
    }
  }

  async function logout() {
    try {
      if (token.value) {
        await axios.post('/api/auth/logout').catch(() => {});
      }
    } finally {
      setSession(null, null);
    }
  }

  return {
    user,
    token,
    isAuthenticated,
    isInstructor,
    isAdmin,
    login,
    register,
    fetchMe,
    logout,
  };
});
