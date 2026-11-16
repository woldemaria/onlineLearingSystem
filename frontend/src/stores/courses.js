import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useCourseStore = defineStore('courses', () => {
  const courses = ref([]);
  const currentCourse = ref(null);
  const categories = ref([]);
  const myEnrollments = ref([]);
  const wishlist = ref(JSON.parse(localStorage.getItem('gelme_wishlist') || '[]'));
  const loading = ref(false);

  async function fetchCourses(filters = {}) {
    loading.value = true;
    try {
      const params = new URLSearchParams(filters).toString();
      const res = await axios.get(`/api/courses?${params}`);
      courses.value = res.data.data;
    } catch (e) {
      console.error('Failed to fetch courses:', e);
    } finally {
      loading.value = false;
    }
  }

  async function fetchCourse(slug) {
    loading.value = true;
    try {
      const res = await axios.get(`/api/courses/${slug}`);
      currentCourse.value = res.data.course;
      return res.data.course;
    } catch (e) {
      console.error('Failed to fetch course details:', e);
      return null;
    } finally {
      loading.value = false;
    }
  }

  async function fetchCategories() {
    try {
      const res = await axios.get('/api/categories');
      categories.value = res.data.categories;
    } catch (e) {
      console.error('Failed to fetch categories:', e);
    }
  }

  async function fetchMyLearning() {
    try {
      const res = await axios.get('/api/my-learning');
      myEnrollments.value = res.data.enrollments;
    } catch (e) {
      console.error('Failed to fetch my learning:', e);
    }
  }

  async function enroll(courseId, type = 'self') {
    try {
      const res = await axios.post(`/api/courses/${courseId}/enroll`, { type });
      await fetchMyLearning();
      return res.data;
    } catch (e) {
      console.error('Failed to enroll:', e);
    }
  }

  async function completeLesson(lessonId) {
    try {
      const res = await axios.post(`/api/lessons/${lessonId}/complete`);
      await fetchMyLearning();
      return res.data;
    } catch (e) {
      console.error('Failed to complete lesson:', e);
    }
  }

  async function applyFinancialAid(payload) {
    try {
      const res = await axios.post('/api/financial-aid', payload);
      await fetchMyLearning();
      return res.data;
    } catch (e) {
      console.error('Financial aid failed:', e);
      throw e;
    }
  }

  async function submitAssignment(payload) {
    try {
      const res = await axios.post('/api/assignments/submit', payload);
      return res.data;
    } catch (e) {
      console.error('Assignment submission failed:', e);
      throw e;
    }
  }

  function toggleWishlist(course) {
    const idx = wishlist.value.findIndex(c => c.id === course.id);
    if (idx !== -1) {
      wishlist.value.splice(idx, 1);
    } else {
      wishlist.value.push({
        id: course.id,
        title: course.title,
        slug: course.slug,
        price: course.price,
        instructor: course.instructor_name || course.instructor?.name || 'Instructor',
        thumbnail: course.thumbnail_url,
      });
    }
    localStorage.setItem('gelme_wishlist', JSON.stringify(wishlist.value));
  }

  function isInWishlist(courseId) {
    return wishlist.value.some(c => c.id === courseId);
  }

  return {
    courses,
    currentCourse,
    categories,
    myEnrollments,
    wishlist,
    loading,
    fetchCourses,
    fetchCourse,
    fetchCategories,
    fetchMyLearning,
    enroll,
    completeLesson,
    applyFinancialAid,
    submitAssignment,
    toggleWishlist,
    isInWishlist,
  };
});
