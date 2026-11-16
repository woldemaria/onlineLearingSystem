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
      const res = await axios.get(`/api/classrooms/${slug}`);
      activeRoom.value = res.data.classroom;
      return res.data.classroom;
    } catch (e) {
      console.error('Failed to fetch room:', e);
      return null;
    } finally {
      loading.value = false;
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
      });

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
    fetchClassrooms,
    fetchRoom,
    fetchPackages,
    submitPlacementTest,
    bookRoom,
  };
});
