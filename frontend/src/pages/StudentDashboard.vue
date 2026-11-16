<template>
  <div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Profile Header -->
      <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-10 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
        <div class="flex items-center gap-5">
          <img
            :src="authStore.user?.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80'"
            class="w-20 h-20 rounded-2xl object-cover ring-4 ring-indigo-500/20 shadow-md"
          />
          <div>
            <div class="flex items-center gap-2">
              <h1 class="text-2xl font-black text-slate-900">{{ authStore.user?.name || 'Abebe Bekele' }}</h1>
              <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2.5 py-0.5 rounded-full uppercase">
                Student Scholar
              </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Pursuing Full-Stack Engineering, Generative AI & Multilingual Native Immersion</p>
            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-600 mt-3">
              <span>📚 <strong>{{ courseStore.myEnrollments.length }}</strong> Enrolled Courses</span>
              <span>•</span>
              <span>🏆 <strong>3</strong> Verified Credentials</span>
              <span>•</span>
              <span>🌐 <strong>{{ classroomStore.myBookings.length || 2 }}</strong> Booked 25-Min Live Sessions</span>
              <span>•</span>
              <span>❤️ <strong>{{ courseStore.wishlist.length }}</strong> Saved Wishlist</span>
            </div>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-2">
          <router-link
            to="/verify/GELME-CERT-2026-X892K"
            class="px-5 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black rounded-xl text-xs flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all whitespace-nowrap"
          >
            <span>🏆</span>
            <span>View Verified Certificate</span>
          </router-link>
        </div>
      </div>

      <!-- CEFR Placement Result Diagnostic Banner (Online Global Classroom) -->
      <div
        v-if="classroomStore.placementResult"
        class="mb-8 p-6 rounded-3xl bg-gradient-to-r from-emerald-950 via-slate-900 to-teal-950 text-white border border-emerald-800 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6"
      >
        <div class="space-y-1">
          <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 bg-emerald-900 px-2 py-0.5 rounded">
            Online Global Classroom Verified CEFR Oral Level
          </span>
          <h3 class="text-xl font-black text-white">
            Current Level: {{ classroomStore.placementResult.cefr_level }} ({{ classroomStore.placementResult.language }})
          </h3>
          <p class="text-xs text-slate-300 max-w-xl">
            Matched native coach: <strong>{{ classroomStore.placementResult.recommended_teacher }}</strong>. Suggested plan: {{ classroomStore.placementResult.recommended_sessions }}.
          </p>
        </div>

        <router-link
          to="/global-classroom"
          class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl text-xs transition-colors whitespace-nowrap"
        >
          Book 25-Min Slot with Matched Coach →
        </router-link>
      </div>

      <!-- Dashboard Tabs -->
      <div class="mb-8 flex flex-wrap items-center gap-6 border-b border-slate-200 text-sm font-bold">
        <button
          @click="tab = 'courses'"
          class="pb-3 transition-colors"
          :class="tab === 'courses' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500 hover:text-slate-900'"
        >
          My Courses ({{ courseStore.myEnrollments.length }})
        </button>
        <button
          @click="tab = 'classrooms'"
          class="pb-3 transition-colors"
          :class="tab === 'classrooms' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500 hover:text-slate-900'"
        >
          Booked 25-Min Live Classrooms ({{ classroomStore.myBookings.length || 2 }})
        </button>
        <button
          @click="tab = 'credentials'"
          class="pb-3 transition-colors"
          :class="tab === 'credentials' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500 hover:text-slate-900'"
        >
          Verifiable Credentials & Badges (3)
        </button>
        <button
          @click="tab = 'wishlist'"
          class="pb-3 transition-colors"
          :class="tab === 'wishlist' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500 hover:text-slate-900'"
        >
          Udemy Wishlist ({{ courseStore.wishlist.length }})
        </button>
      </div>

      <!-- Enrolled Courses Tab -->
      <div v-if="tab === 'courses'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-if="courseStore.myEnrollments.length === 0" class="col-span-2 text-center py-16 bg-white rounded-2xl border border-slate-200">
          <p class="text-slate-600 text-sm font-bold">No active enrollments found.</p>
          <router-link to="/courses" class="mt-3 inline-block px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold">
            Explore Catalog & Enroll
          </router-link>
        </div>

        <div
          v-for="enr in courseStore.myEnrollments"
          :key="enr.id"
          class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow"
        >
          <div>
            <div class="relative aspect-video bg-slate-900 overflow-hidden">
              <img :src="enr.thumbnail_url" class="w-full h-full object-cover" />
              <span
                class="absolute top-3 right-3 text-white font-bold text-[10px] px-2.5 py-0.5 rounded-full uppercase"
                :class="enr.progress_percent >= 100 ? 'bg-emerald-600' : 'bg-indigo-600'"
              >
                {{ enr.progress_percent >= 100 ? 'Completed 100% ✓' : 'In Progress' }}
              </span>
            </div>

            <div class="p-6">
              <span class="text-xs font-bold text-indigo-600 uppercase">{{ enr.institution }}</span>
              <h3 class="font-extrabold text-base text-slate-900 mt-1 mb-3">{{ enr.title }}</h3>

              <!-- Progress bar -->
              <div class="space-y-1.5 mb-4">
                <div class="flex items-center justify-between text-xs text-slate-600">
                  <span>Course Progress:</span>
                  <strong class="font-mono">{{ enr.progress_percent }}%</strong>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                  <div
                    class="h-full rounded-full transition-all duration-500"
                    :class="enr.progress_percent >= 100 ? 'bg-emerald-500' : 'bg-indigo-600'"
                    :style="{ width: `${enr.progress_percent}%` }"
                  ></div>
                </div>
              </div>
            </div>
          </div>

          <div class="p-6 pt-0 flex items-center gap-3">
            <router-link
              :to="`/learn/${enr.slug}`"
              class="flex-1 text-center py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-colors"
            >
              Resume Learning →
            </router-link>

            <router-link
              v-if="enr.progress_percent >= 100"
              to="/verify/GELME-CERT-2026-X892K"
              class="px-4 py-3 bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold rounded-xl text-xs transition-colors"
            >
              Certificate 🏆
            </router-link>
          </div>
        </div>
      </div>

      <!-- Booked Classrooms Tab (Online Global Classroom) -->
      <div v-if="tab === 'classrooms'" class="space-y-4">
        <div
          v-for="room in (classroomStore.myBookings.length > 0 ? classroomStore.myBookings : defaultBookings)"
          :key="room.id"
          class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
        >
          <div>
            <div class="flex items-center gap-2 mb-1.5">
              <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-xs font-bold font-mono">
                {{ room.language || 'Spanish (Español)' }}
              </span>
              <span class="text-xs text-slate-500 font-semibold">{{ room.format || '25-Min Native Immersion' }}</span>
            </div>
            <h4 class="font-extrabold text-base text-slate-900">{{ room.title }}</h4>
            <p class="text-xs text-slate-500 mt-1">
              Native Teacher: <strong class="text-slate-800">{{ room.instructor }}</strong> • Scheduled: <span class="text-emerald-700 font-mono font-bold">{{ room.slot || 'Today at 2:30 PM UTC' }}</span>
            </p>
          </div>

          <div class="flex items-center gap-3 w-full sm:w-auto">
            <router-link
              :to="`/classroom/${room.slug}`"
              class="w-full sm:w-auto text-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs transition-colors shadow-md shadow-emerald-600/20"
            >
              Launch Live Class Studio →
            </router-link>
          </div>
        </div>
      </div>

      <!-- Verifiable Credentials Tab (Coursera / edX) -->
      <div v-if="tab === 'credentials'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="cert in credentialsList"
          :key="cert.code"
          class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between space-y-4"
        >
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded">
                Verified Credential
              </span>
              <span class="text-[10px] text-slate-400 font-mono">{{ cert.code }}</span>
            </div>

            <h4 class="font-extrabold text-sm text-slate-900 leading-snug mb-1">{{ cert.title }}</h4>
            <p class="text-xs text-slate-500 mb-3">{{ cert.issuer }}</p>

            <div class="text-[11px] text-slate-600 space-y-1 pt-2 border-t border-slate-100">
              <div>Grade Achieved: <strong class="text-emerald-700">{{ cert.grade }}</strong></div>
              <div>Academic Credits: <strong>{{ cert.credits }}</strong></div>
            </div>
          </div>

          <router-link
            :to="`/verify/${cert.code}`"
            class="w-full block text-center py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition-colors"
          >
            Verify Credential Ledger →
          </router-link>
        </div>
      </div>

      <!-- Wishlist Tab (Udemy Standard) -->
      <div v-if="tab === 'wishlist'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-if="courseStore.wishlist.length === 0" class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-200">
          <div class="text-3xl mb-2">🤍</div>
          <p class="text-slate-700 text-sm font-bold">Your wishlist is currently empty.</p>
          <p class="text-xs text-slate-400 mt-1">Click the heart icon on any course to save it for later.</p>
          <router-link to="/courses" class="mt-3 inline-block px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold">
            Browse Courses
          </router-link>
        </div>

        <div
          v-for="item in courseStore.wishlist"
          :key="item.id"
          class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm flex flex-col justify-between"
        >
          <div>
            <img :src="item.thumbnail" class="w-full aspect-video object-cover" />
            <div class="p-5">
              <h4 class="font-bold text-sm text-slate-900 line-clamp-2 mb-2">{{ item.title }}</h4>
              <p class="text-xs text-slate-500 mb-3">By {{ item.instructor }}</p>
              <div class="text-base font-black text-slate-900">${{ item.price }}</div>
            </div>
          </div>

          <div class="p-5 pt-0 flex items-center gap-2">
            <button
              @click="cartStore.addToCart(item)"
              class="flex-1 py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-colors"
            >
              Move to Cart 🛒
            </button>
            <button
              @click="courseStore.toggleWishlist(item)"
              class="p-2 border border-slate-200 rounded-xl hover:bg-slate-100 text-xs"
              title="Remove from Wishlist"
            >
              ✕
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useCourseStore } from '@/stores/courses';
import { useClassroomStore } from '@/stores/classroom';
import { useCartStore } from '@/stores/cart';

const authStore = useAuthStore();
const courseStore = useCourseStore();
const classroomStore = useClassroomStore();
const cartStore = useCartStore();

const tab = ref('courses');

const defaultBookings = [
  {
    id: 1,
    title: 'Live 25-Min Conversational Spanish: Travel & Daily Situations',
    slug: 'spanish-travel-daily-immersion',
    language: 'Spanish (Español)',
    instructor: 'Carlos Méndez (Bogotá)',
    format: '25-Min 1-on-1 Native Immersion',
    slot: 'Today at 02:30 PM UTC'
  },
  {
    id: 2,
    title: 'Global Classroom: Amharic & Ethiopian Heritage Immersion',
    slug: 'amharic-culture-heritage-exchange',
    language: 'Amharic (አማርኛ)',
    instructor: 'Selamawit Tadesse (Addis Ababa)',
    format: '25-Min Native Immersion',
    slot: 'Tomorrow at 11:30 AM UTC'
  }
];

const credentialsList = [
  {
    code: 'GELME-CERT-2026-X892K',
    title: 'Full-Stack Web Development & Cloud Architecture Professional Certificate',
    issuer: 'Stanford Online & GELME Consortium',
    grade: '98.5%',
    credits: '4.5 CEU'
  },
  {
    code: 'STANFORD-AI-2026-9901',
    title: 'Advanced AI & Deep Learning Master Specialization Credential',
    issuer: 'MITx MicroMasters Consortium',
    grade: '99.2%',
    credits: '6.0 CEU'
  },
  {
    code: 'OGC-SPANISH-2026-7741',
    title: 'Online Global Classroom CEFR B2 Conversational Spanish Certificate',
    issuer: 'Online Global Classroom Network',
    grade: '96.0%',
    credits: '2.5 CEU'
  }
];

onMounted(() => {
  courseStore.fetchMyLearning();
  classroomStore.fetchClassrooms();
});
</script>
