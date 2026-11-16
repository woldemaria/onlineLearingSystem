<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-indigo-950 text-white pt-20 pb-28">
      <!-- Glow Gradients -->
      <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          <!-- Hero Text -->
          <div class="lg:col-span-7 space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-400/20 text-indigo-300 text-xs font-semibold uppercase tracking-wider">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              The Global Learning & Education Super Platform
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1]">
              Learn on-demand.<br />
              Earn accredited degrees.<br />
              <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400 bg-clip-text text-transparent">
                Speak fluently with live native teachers.
              </span>
            </h1>

            <p class="text-lg text-slate-300 max-w-xl leading-relaxed">
              Synthesizing the self-paced marketplace of <strong>Udemy</strong>, university specializations of <strong>Coursera</strong>, cryptographic credentials of <strong>edX</strong>, and 25-minute live interactive immersion of <strong>Online Global Classroom</strong>.
            </p>

            <!-- Search Bar CTA -->
            <div class="flex flex-col sm:flex-row gap-3 pt-2 max-w-lg">
              <div class="relative flex-1">
                <input
                  v-model="searchQuery"
                  @keyup.enter="goSearch"
                  type="text"
                  placeholder="What do you want to learn today?"
                  class="w-full px-5 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white/15 text-sm"
                />
              </div>
              <button
                @click="goSearch"
                class="px-6 py-3.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition-all text-sm whitespace-nowrap"
              >
                Search Catalog
              </button>
            </div>

            <!-- Trust / Partner Badges -->
            <div class="pt-6 border-t border-slate-800">
              <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold mb-3">Partnered with Leading Institutions & Immersion Academies</p>
              <div class="flex flex-wrap items-center gap-6 text-slate-400 font-semibold text-sm">
                <span class="flex items-center gap-1.5 text-white"><span class="w-2 h-2 rounded-full bg-red-400"></span> Stanford Online</span>
                <span class="flex items-center gap-1.5 text-white"><span class="w-2 h-2 rounded-full bg-blue-400"></span> MITx Consortium</span>
                <span class="flex items-center gap-1.5 text-emerald-300"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> Online Global Classroom</span>
                <span class="flex items-center gap-1.5 text-white"><span class="w-2 h-2 rounded-full bg-purple-400"></span> DeepMind Lab</span>
              </div>
            </div>
          </div>

          <!-- Hero Spotlight Card: Live Online Global Classroom -->
          <div class="lg:col-span-5">
            <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-2xl relative overflow-hidden backdrop-blur-xl">
              <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-5">
                <div class="flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                  <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Live Virtual Immersion</span>
                </div>
                <span class="text-xs bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 px-2.5 py-0.5 rounded-full font-mono">
                  OGC Format
                </span>
              </div>

              <!-- Spotlight Video Preview -->
              <div class="relative rounded-2xl overflow-hidden aspect-video bg-slate-950 mb-5 border border-slate-800 group">
                <img
                  src="https://images.unsplash.com/photo-1543269865-cbf427effbad?w=800&auto=format&fit=crop&q=80"
                  alt="Live Classroom"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex items-end p-4">
                  <div class="text-left">
                    <span class="text-[11px] font-bold text-emerald-400 bg-emerald-950/80 px-2 py-0.5 rounded">Native Speaker 1-on-1</span>
                    <h4 class="text-sm font-bold text-white mt-1">Live 25-Min Conversational Spanish: Travel Situations</h4>
                  </div>
                </div>
              </div>

              <div class="space-y-3 mb-6">
                <div class="flex items-center justify-between text-xs text-slate-300">
                  <span>Instructor</span>
                  <span class="font-semibold text-white">Carlos Méndez (Bogotá)</span>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-300">
                  <span>Methodology</span>
                  <span class="text-emerald-400 font-medium">25-Minute Spontaneous Conversation</span>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-300">
                  <span>Languages Available</span>
                  <span class="text-indigo-300 font-medium">14+ World Languages</span>
                </div>
              </div>

              <router-link
                to="/global-classroom"
                class="w-full block text-center py-3.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all text-sm"
              >
                Enter Online Global Classroom Hub →
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4 Pillars Section (Udemy + Coursera + edX + Online Global Classroom) -->
    <section class="py-16 bg-white border-b border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="p-6 rounded-2xl bg-purple-50/60 border border-purple-100 hover:border-purple-300 transition-all">
            <div class="w-12 h-12 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-md shadow-purple-600/20">
              🎥
            </div>
            <h3 class="font-bold text-slate-900 text-base mb-1">Udemy Marketplace Model</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              Comprehensive, self-paced video bootcamps with practical coding projects, lifetime updates, and direct instructor QA.
            </p>
          </div>

          <div class="p-6 rounded-2xl bg-blue-50/60 border border-blue-100 hover:border-blue-300 transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-md shadow-blue-600/20">
              🎓
            </div>
            <h3 class="font-bold text-slate-900 text-base mb-1">Coursera Specializations</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              Accredited university programs from Stanford and top institutes with peer-reviewed assignments and rigorous academic grading.
            </p>
          </div>

          <div class="p-6 rounded-2xl bg-slate-100/70 border border-slate-200 hover:border-slate-300 transition-all">
            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-md shadow-slate-900/20">
              📜
            </div>
            <h3 class="font-bold text-slate-900 text-base mb-1">edX Micro-Credentials</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              Verifiable certificates with tamper-proof cryptographic QR hashes, audit tracks, and career-advancing MicroMasters.
            </p>
          </div>

          <div class="p-6 rounded-2xl bg-emerald-50/60 border border-emerald-100 hover:border-emerald-300 transition-all">
            <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-md shadow-emerald-600/20">
              🌐
            </div>
            <h3 class="font-bold text-slate-900 text-base mb-1">Online Global Classroom</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              Interactive 25-minute live 1-on-1 and small-group immersion across 14+ languages conducted by native teachers.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Top Featured Courses Section -->
    <section class="py-20 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">
              Most Popular
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-2">
              Featured Global Courses & Programs
            </h2>
            <p class="text-sm text-slate-600 mt-1">Explore top-rated bootcamps, specializations, and live language tracks</p>
          </div>
          <router-link to="/courses" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 group">
            <span>Browse Full Catalog</span>
            <span class="group-hover:translate-x-1 transition-transform">→</span>
          </router-link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="course in courseStore.courses"
            :key="course.id"
            class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
          >
            <!-- Thumbnail & Badges -->
            <div>
              <div class="relative aspect-video overflow-hidden bg-slate-900">
                <img
                  :src="course.thumbnail_url"
                  :alt="course.title"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <span class="absolute top-3 left-3 bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                  {{ course.level.replace('_', ' ') }}
                </span>
                <span v-if="course.type === 'live_virtual'" class="absolute top-3 right-3 bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider flex items-center gap-1 shadow-md">
                  <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                  Live 25-Min OGC
                </span>
                <span v-else-if="course.type === 'cohort_based'" class="absolute top-3 right-3 bg-blue-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-md">
                  Specialization
                </span>
              </div>

              <!-- Content -->
              <div class="p-6">
                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                  <span class="font-medium text-indigo-600">{{ course.category }}</span>
                  <span>{{ course.duration_hours }}h total</span>
                </div>

                <h3 class="font-extrabold text-base text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug mb-2">
                  <router-link :to="`/courses/${course.slug}`">
                    {{ course.title }}
                  </router-link>
                </h3>

                <p class="text-xs text-slate-600 line-clamp-2 mb-4 leading-relaxed">
                  {{ course.subtitle || course.description }}
                </p>

                <div class="flex items-center gap-2 text-xs text-slate-500 mb-4 pb-4 border-b border-slate-100">
                  <img :src="course.instructor_avatar" class="w-5 h-5 rounded-full" alt="Avatar" />
                  <span class="font-medium text-slate-700 truncate">{{ course.instructor_name }}</span>
                  <span class="text-slate-300">•</span>
                  <span class="text-amber-500 font-bold flex items-center gap-1">
                    ★ {{ course.rating }} <span class="text-slate-400 font-normal">({{ course.reviews_count }})</span>
                  </span>
                </div>
              </div>
            </div>

            <!-- Price & Actions -->
            <div class="p-6 pt-0 flex items-center justify-between">
              <div>
                <span class="text-lg font-black text-slate-900">${{ course.price }}</span>
                <span class="text-xs text-slate-400 ml-1.5 line-through">${{ (course.price * 2.8).toFixed(2) }}</span>
              </div>

              <div class="flex items-center gap-2">
                <button
                  @click="cartStore.addToCart(course)"
                  class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-colors"
                  title="Add to Cart"
                >
                  🛒
                </button>
                <router-link
                  :to="`/courses/${course.slug}`"
                  class="px-4 py-2 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white font-bold rounded-lg text-xs transition-colors"
                >
                  View Details
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Online Global Classroom Spotlight Hub -->
    <section class="py-20 bg-emerald-950 text-white relative overflow-hidden">
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl mb-12">
          <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 bg-emerald-900/60 border border-emerald-700/60 px-3 py-1 rounded-full">
            Online Global Classroom Feature
          </span>
          <h2 class="text-3xl sm:text-4xl font-black tracking-tight mt-3">
            High-Intensity 25-Minute Live Native Language Immersion
          </h2>
          <p class="text-slate-300 text-base mt-2 leading-relaxed">
            Break language barriers naturally. Practice conversational fluency with native tutors across Spanish, English (ESL), Amharic, Mandarin, French, Japanese, and more.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
          <div
            v-for="room in classroomStore.classrooms.slice(0, 3)"
            :key="room.id"
            class="bg-emerald-900/40 border border-emerald-800/80 rounded-2xl p-6 backdrop-blur-md flex flex-col justify-between"
          >
            <div>
              <div class="flex items-center justify-between text-xs mb-3">
                <span class="font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">
                  {{ room.immersion_language }}
                </span>
                <span class="text-emerald-300 font-semibold flex items-center gap-1.5">
                  <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                  {{ room.format }}
                </span>
              </div>

              <h4 class="font-bold text-base text-white mb-2 leading-snug">{{ room.title }}</h4>
              <p class="text-xs text-emerald-100/80 line-clamp-3 leading-relaxed mb-4">
                {{ room.description }}
              </p>
            </div>

            <div class="pt-4 border-t border-emerald-800/60">
              <div class="flex items-center justify-between text-xs mb-4">
                <span class="text-emerald-200">Teacher: <strong class="text-white">{{ room.instructor.name }}</strong></span>
                <span class="text-emerald-300">{{ room.current_participants }}/{{ room.max_capacity }} booked</span>
              </div>

              <router-link
                :to="`/classroom/${room.slug}`"
                class="w-full block text-center py-2.5 px-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl text-xs transition-colors shadow-md shadow-emerald-500/20"
              >
                Join Live Video Session →
              </router-link>
            </div>
          </div>
        </div>

        <div class="text-center">
          <router-link
            to="/global-classroom"
            class="inline-flex items-center gap-2 px-6 py-3.5 bg-white text-emerald-950 font-extrabold rounded-xl hover:bg-emerald-50 transition-colors text-sm shadow-xl"
          >
            <span>Explore All 14+ Languages in Global Classroom</span>
            <span>→</span>
          </router-link>
        </div>
      </div>
    </section>

    <!-- Verifiable Credential Trust Banner (Coursera / edX) -->
    <section class="py-16 bg-white border-t border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-3xl p-8 sm:p-12 text-white flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl">
          <div class="space-y-3 max-w-xl">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 bg-indigo-950/60 px-3 py-1 rounded-full border border-indigo-800">
              edX & Coursera Quality Standard
            </span>
            <h3 class="text-2xl sm:text-3xl font-black tracking-tight">
              Instant Cryptographic Verification
            </h3>
            <p class="text-sm text-slate-300 leading-relaxed">
              Every graduate receives a tamper-proof digital credential with unique hash verification, QR authenticity checks, and direct employer sharing.
            </p>
          </div>

          <div class="flex-shrink-0 flex flex-col sm:flex-row gap-3">
            <router-link
              to="/verify"
              class="px-6 py-3.5 bg-white hover:bg-slate-100 text-slate-900 font-bold rounded-xl text-sm transition-colors shadow-md text-center"
            >
              Verify a Certificate Now
            </router-link>
            <router-link
              to="/courses"
              class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm transition-colors shadow-md text-center"
            >
              Start Learning
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useCourseStore } from '@/stores/courses';
import { useClassroomStore } from '@/stores/classroom';
import { useCartStore } from '@/stores/cart';

const router = useRouter();
const courseStore = useCourseStore();
const classroomStore = useClassroomStore();
const cartStore = useCartStore();
const searchQuery = ref('');

function goSearch() {
  if (searchQuery.value.trim()) {
    router.push({ path: '/courses', query: { search: searchQuery.value.trim() } });
  } else {
    router.push('/courses');
  }
}

onMounted(() => {
  courseStore.fetchCourses();
  classroomStore.fetchClassrooms();
});
</script>
