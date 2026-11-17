import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import axios from 'axios';

export const useNotificationStore = defineStore('notification', () => {
  const notifications = ref([
    {
      id: 1,
      type: 'achievement',
      title: '🏆 Verifiable Credential Issued!',
      message: 'Congratulations! Your certificate for "Python for AI & Data Science" has been cryptographically signed and registered.',
      action_url: '/verify/GELME-CERT-2026-X892K',
      action_label: 'View Credential',
      read_at: null,
      created_at: new Date(Date.now() - 1000 * 60 * 60 * 2).toISOString(),
    },
    {
      id: 2,
      type: 'classroom',
      title: '🌐 Upcoming Live Immersion Session',
      message: 'Your 25-minute 1-on-1 Spanish immersion with Carlos Méndez is scheduled for tomorrow at 10:00 AM UTC.',
      action_url: '/classroom/spanish-immersion',
      action_label: 'Classroom Hub',
      read_at: null,
      created_at: new Date(Date.now() - 1000 * 60 * 60 * 5).toISOString(),
    },
    {
      id: 3,
      type: 'qa',
      title: '💬 Instructor Answered Your Question',
      message: 'Dr. Chen replied to your discussion thread: "Handling state hydration across Pinia SSR stores".',
      action_url: '/learn/full-stack-web-development-masterclass',
      action_label: 'Open Discussion',
      read_at: null,
      created_at: new Date(Date.now() - 1000 * 60 * 60 * 24).toISOString(),
    },
    {
      id: 4,
      type: 'announcement',
      title: '📢 New Deep Learning Module Released',
      message: 'Prof. Andrew Ng has uploaded 4 new hands-on lectures on Attention Mechanisms and Transformers.',
      action_url: '/courses/generative-ai-and-large-language-models',
      action_label: 'Explore Syllabus',
      read_at: new Date(Date.now() - 1000 * 60 * 60 * 48).toISOString(),
      created_at: new Date(Date.now() - 1000 * 60 * 60 * 48).toISOString(),
    },
  ]);

  const isDropdownOpen = ref(false);
  const loading = ref(false);

  const unreadCount = computed(() => {
    return notifications.value.filter(n => !n.read_at).length;
  });

  async function fetchNotifications() {
    loading.value = true;
    try {
      const token = localStorage.getItem('gelme_auth_token');
      const headers = token ? { Authorization: `Bearer ${token}` } : {};
      const res = await axios.get('/api/notifications', { headers });
      if (res.data?.data && Array.isArray(res.data.data)) {
        notifications.value = res.data.data;
      }
    } catch (e) {
      // Keep initial client list as fallback
    } finally {
      loading.value = false;
    }
  }

  async function markAsRead(id) {
    const item = notifications.value.find(n => n.id === id);
    if (item) {
      item.read_at = new Date().toISOString();
    }
    try {
      const token = localStorage.getItem('gelme_auth_token');
      const headers = token ? { Authorization: `Bearer ${token}` } : {};
      await axios.patch(`/api/notifications/${id}/read`, {}, { headers });
    } catch (e) {
      // Optimistic update retained
    }
  }

  async function markAllAsRead() {
    const now = new Date().toISOString();
    notifications.value.forEach(n => {
      n.read_at = now;
    });
    try {
      const token = localStorage.getItem('gelme_auth_token');
      const headers = token ? { Authorization: `Bearer ${token}` } : {};
      await axios.post('/api/notifications/mark-all-read', {}, { headers });
    } catch (e) {
      // Optimistic update retained
    }
  }

  async function deleteNotification(id) {
    const idx = notifications.value.findIndex(n => n.id === id);
    if (idx !== -1) {
      notifications.value.splice(idx, 1);
    }
    try {
      const token = localStorage.getItem('gelme_auth_token');
      const headers = token ? { Authorization: `Bearer ${token}` } : {};
      await axios.delete(`/api/notifications/${id}`, { headers });
    } catch (e) {
      // Handled
    }
  }

  function addNotification(notification) {
    notifications.value.unshift({
      id: Date.now(),
      created_at: new Date().toISOString(),
      read_at: null,
      ...notification,
    });
  }

  return {
    notifications,
    isDropdownOpen,
    unreadCount,
    loading,
    fetchNotifications,
    markAsRead,
    markAllAsRead,
    deleteNotification,
    addNotification,
  };
});
