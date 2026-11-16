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
import Login from '@/pages/Login.vue';

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/courses', name: 'CourseCatalog', component: CourseCatalog },
  { path: '/courses/:slug', name: 'CourseDetail', component: CourseDetail },
  { path: '/learn/:slug', name: 'CoursePlayer', component: CoursePlayer },
  { path: '/global-classroom', name: 'GlobalClassroomHub', component: GlobalClassroomHub },
  { path: '/classroom/:slug', name: 'LiveVirtualClassroom', component: LiveVirtualClassroom },
  { path: '/dashboard', name: 'StudentDashboard', component: StudentDashboard },
  { path: '/careers', name: 'CareerResources', component: CareerResources },
  { path: '/career-resources', redirect: '/careers' },
  { path: '/instructor', name: 'InstructorStudio', component: InstructorStudio },
  { path: '/verify/:code?', name: 'CertificateVerification', component: CertificateVerification },
  { path: '/login', name: 'Login', component: Login },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 };
  },
});

export default router;
