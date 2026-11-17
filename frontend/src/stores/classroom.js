import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useClassroomStore = defineStore('classroom', () => {
  const classrooms = ref([]);
  const activeRoom = ref(null);
  const packages = ref([]);
  const myBookings = ref(JSON.parse(localStorage.getItem('gelme_classroom_bookings') || '[]'));
  const placementResult = ref(JSON.parse(localStorage.getItem('gelme_placement_result') || 'null'));
  const loading = ref(false);

  // Live session state
  const isJoined = ref(false);
  const liveRoster = ref([]);
  const liveAttendance = ref(null);
  const userAttendance = ref(null);
  const chatMessages = ref([]);
  const liveReactions = ref([]);
  const handRaised = ref(false);

  function getAuthHeaders() {
    const token = localStorage.getItem('gelme_auth_token') || localStorage.getItem('token') || localStorage.getItem('auth_token') || localStorage.getItem('access_token');
    return token ? { Authorization: `Bearer ${token}` } : {};
  }

  async function fetchClassrooms(filters = {}) {
    loading.value = true;
    try {
      const params = new URLSearchParams(filters).toString();
      const res = await axios.get(`/api/classrooms?${params}`);
      classrooms.value = res.data.classrooms;
    } catch (e) {
      console.error('Failed to fetch classrooms:', e);
    } finally {
      loading.value = false;
    }
  }

  async function fetchRoom(slug) {
    loading.value = true;
    try {
      const res = await axios.get(`/api/classrooms/${slug}`, { headers: getAuthHeaders() });
      activeRoom.value = res.data.classroom;
      return res.data.classroom;
    } catch (e) {
      console.error('Failed to fetch room:', e);
      return null;
    } finally {
      loading.value = false;
    }
  }

  async function joinRoom(slug) {
    try {
      const res = await axios.post(`/api/classrooms/${slug}/join`, {}, { headers: getAuthHeaders() });
      isJoined.value = true;
      liveRoster.value = res.data.roster || [];
      userAttendance.value = res.data.attendance || null;
      return res.data;
    } catch (e) {
      console.error('Failed to join classroom:', e);
      throw e;
    }
  }

  async function leaveRoom(slug) {
    try {
      const res = await axios.post(`/api/classrooms/${slug}/leave`, {}, { headers: getAuthHeaders() });
      isJoined.value = false;
      return res.data;
    } catch (e) {
      console.error('Failed to leave classroom:', e);
    }
  }

  async function fetchAttendance(slug) {
    try {
      const res = await axios.get(`/api/classrooms/${slug}/attendance`, { headers: getAuthHeaders() });
      liveAttendance.value = res.data;
      if (res.data.user_attendance) {
        userAttendance.value = res.data.user_attendance;
      }
      return res.data;
    } catch (e) {
      console.error('Failed to fetch attendance:', e);
    }
  }

  async function fetchChat(slug) {
    try {
      const res = await axios.get(`/api/classrooms/${slug}/chat`, { headers: getAuthHeaders() });
      chatMessages.value = res.data.messages || [];
      return chatMessages.value;
    } catch (e) {
      console.error('Failed to fetch classroom chat:', e);
    }
  }

  async function sendChatMessage(slug, text) {
    try {
      const res = await axios.post(`/api/classrooms/${slug}/chat`, { text }, { headers: getAuthHeaders() });
      if (res.data.message) {
        chatMessages.value.push(res.data.message);
      }
      if (res.data.instructor_reply) {
        chatMessages.value.push(res.data.instructor_reply);
      }
      if (res.data.peer_reply) {
        chatMessages.value.push(res.data.peer_reply);
      }
      return res.data;
    } catch (e) {
      console.error('Failed to send classroom chat:', e);
      throw e;
    }
  }

  async function sendReaction(slug, emoji) {
    try {
      const res = await axios.post(`/api/classrooms/${slug}/reactions`, { emoji }, { headers: getAuthHeaders() });
      if (res.data.reaction) {
        liveReactions.value.push(res.data.reaction);
      }
      return res.data;
    } catch (e) {
      console.error('Failed to send reaction:', e);
    }
  }

  async function fetchReactions(slug) {
    try {
      const res = await axios.get(`/api/classrooms/${slug}/reactions`, { headers: getAuthHeaders() });
      if (res.data.reactions) {
        liveReactions.value = res.data.reactions;
      }
      return liveReactions.value;
    } catch (e) {
      console.error('Failed to fetch reactions:', e);
    }
  }

  async function toggleHandRaise(slug) {
    try {
      const res = await axios.post(`/api/classrooms/${slug}/hand-raise`, {}, { headers: getAuthHeaders() });
      handRaised.value = res.data.hand_raised;
      return res.data;
    } catch (e) {
      console.error('Failed to toggle hand raise:', e);
    }
  }

  async function updateMediaState(slug, payload) {
    try {
      const res = await axios.post(`/api/classrooms/${slug}/media-state`, payload, { headers: getAuthHeaders() });
      return res.data;
    } catch (e) {
      console.error('Failed to update media state:', e);
    }
  }

  async function fetchPackages() {
    try {
      const res = await axios.get('/api/packages');
      packages.value = res.data.packages;
      return res.data.packages;
    } catch (e) {
      console.error('Failed to fetch immersion packages:', e);
    }
  }

  async function submitPlacementTest(answers, language = 'Spanish') {
    try {
      const res = await axios.post('/api/placement-test', { answers, language });
      placementResult.value = res.data;
      localStorage.setItem('gelme_placement_result', JSON.stringify(res.data));
      return res.data;
    } catch (e) {
      console.error('Placement test failed:', e);
      throw e;
    }
  }

  async function bookRoom(classroomId, bookingDetails = {}) {
    try {
      const res = await axios.post('/api/classrooms/book', {
        classroom_id: classroomId,
        ...bookingDetails,
      }, { headers: getAuthHeaders() });

      const found = classrooms.value.find(c => c.id === classroomId);
      if (found) {
        myBookings.value.push({
          id: classroomId,
          title: found.title,
          slug: found.slug,
          language: found.immersion_language,
          instructor: found.instructor.name,
          slot: bookingDetails.preferred_time || 'Today at 2:30 PM UTC',
          format: bookingDetails.format || '25-Min 1-on-1 Native Immersion',
          bookedAt: new Date().toLocaleTimeString(),
        });
        localStorage.setItem('gelme_classroom_bookings', JSON.stringify(myBookings.value));
      }

      return res.data;
    } catch (e) {
      console.error('Failed to book room:', e);
    }
  }

  return {
    classrooms,
    activeRoom,
    packages,
    myBookings,
    placementResult,
    loading,
    isJoined,
    liveRoster,
    liveAttendance,
    userAttendance,
    chatMessages,
    liveReactions,
    handRaised,
    fetchClassrooms,
    fetchRoom,
    joinRoom,
    leaveRoom,
    fetchAttendance,
    fetchChat,
    sendChatMessage,
    sendReaction,
    fetchReactions,
    toggleHandRaise,
    updateMediaState,
    fetchPackages,
    submitPlacementTest,
    bookRoom,
  };
});
