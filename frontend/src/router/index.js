import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/pages/Home.vue';
import CourseCatalog from '@/pages/CourseCatalog.vue';
import CourseDetail from '@/pages/CourseDetail.vue';
import CoursePlayer from '@/pages/CoursePlayer.vue';
import GlobalClassroomHub from '@/pages/GlobalClassroomHub.vue';
import LiveVirtualClassroom from '@/pages/LiveVirtualClassroom.vue';
import StudentDashboard from '@/pages/StudentDashboard.vue';
import InstructorStudio from '@/pages/InstructorStudio.vue';
import CertificateVerification from '@/pages/CertificateVerification.vue';
import CareerResources from '@/pages/CareerResources.vue';
import AdminUsers from '@/pages/AdminUsers.vue';
import Login from '@/pages/Login.vue';
import AboutUs from '@/pages/AboutUs.vue';
import ContactUs from '@/pages/ContactUs.vue';
import PrivacyPolicy from '@/pages/PrivacyPolicy.vue';
import NotFound from '@/pages/NotFound.vue';

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/courses', name: 'CourseCatalog', component: CourseCatalog },
  { path: '/courses/:slug', name: 'CourseDetail', component: CourseDetail },
  { path: '/learn/:slug', name: 'CoursePlayer', component: CoursePlayer },
  { path: '/global-classroom', name: 'GlobalClassroomHub', component: GlobalClassroomHub },
  { path: '/classroom/:slug', name: 'LiveVirtualClassroom', component: LiveVirtualClassroom },
  { path: '/dashboard', name: 'StudentDashboard', component: StudentDashboard, meta: { requiresAuth: true } },
  { path: '/my-learning', name: 'MyLearning', component: StudentDashboard, meta: { requiresAuth: true } },
  { path: '/mylearning', redirect: '/my-learning' },
  { path: '/careers', name: 'CareerResources', component: CareerResources },
  { path: '/career-resources', redirect: '/careers' },
  { path: '/instructor', name: 'InstructorStudio', component: InstructorStudio, meta: { requiresAuth: true } },
  { path: '/admin/users', name: 'AdminUsers', component: AdminUsers, meta: { requiresAuth: true, requiresAdmin: true } },
  { path: '/verify/:code?', name: 'CertificateVerification', component: CertificateVerification },
  { path: '/about', name: 'AboutUs', component: AboutUs },
  { path: '/contact', name: 'ContactUs', component: ContactUs },
  { path: '/support', name: 'Support', component: ContactUs },
  { path: '/privacy', name: 'PrivacyPolicy', component: PrivacyPolicy },
  { path: '/privacy-policy', redirect: '/privacy' },
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Login },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 };
  },
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('gelme_auth_token') || localStorage.getItem('token') || localStorage.getItem('auth_token') || localStorage.getItem('access_token');
  const userStr = localStorage.getItem('gelme_user');
  let user = null;
  try {
    user = userStr ? JSON.parse(userStr) : null;
  } catch (e) {
    user = null;
  }
  const isAuthenticated = !!token && !!user;

  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({
      path: '/login',
      query: {
        redirect: to.fullPath,
        notice: 'Please sign in to access your My Learning dashboard.'
      }
    });
  }

  if (to.meta.requiresAdmin && user?.role !== 'admin') {
    return next({ path: '/dashboard' });
  }

  next();
});

export default router;
