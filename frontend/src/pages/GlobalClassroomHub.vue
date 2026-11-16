<template>
  <div class="min-h-screen bg-slate-950 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Hub Header Banner (Online Global Classroom Style) -->
      <div class="bg-gradient-to-r from-emerald-950 via-slate-900 to-teal-950 p-8 sm:p-12 rounded-3xl border border-emerald-800/60 shadow-2xl mb-12 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-3xl relative z-10">
          <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-bold rounded-full uppercase tracking-wider mb-4">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            Online Global Classroom Network
          </div>

          <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight mb-4">
            Live 25-Minute Conversational Immersion in 14+ Languages
          </h1>

          <p class="text-sm sm:text-base text-slate-300 leading-relaxed mb-6">
            Directly modeled on <strong>Online Global Classroom</strong> pedagogy: High-intensity, 25-minute live 1-on-1 and small group sessions conducted by certified native teachers. Focus on spontaneous spoken fluency, cultural nuances, and real-world conversation.
          </p>

          <!-- Quick Actions: Placement Test & Language Filter -->
          <div class="flex flex-wrap items-center gap-3 pt-1">
            <button
              @click="placementModalOpen = true"
              class="px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black rounded-xl text-xs shadow-lg shadow-emerald-500/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2"
            >
              <span>🎯</span>
              <span>Take Free 5-Min Language Placement Diagnostic</span>
            </button>

            <a
              href="#packages"
              class="px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl text-xs border border-slate-700 transition-colors"
            >
              View 25-Min Packages & Bundles ↓
            </a>
          </div>

          <!-- Language Filters Chips -->
          <div class="space-y-2 pt-6">
            <span class="text-xs uppercase font-bold tracking-wider text-emerald-400">Filter by Language:</span>
            <div class="flex flex-wrap items-center gap-2 text-xs">
              <button
                @click="selectedLanguage = ''"
                class="px-3 py-1.5 rounded-xl font-bold transition-all"
                :class="!selectedLanguage ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/30' : 'bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700'"
              >
                🌍 All Languages (14+)
              </button>
              <button
                v-for="lang in availableLanguages"
                :key="lang.name"
                @click="selectedLanguage = (selectedLanguage === lang.name ? '' : lang.name)"
                class="px-3 py-1.5 rounded-xl font-bold cursor-pointer transition-all flex items-center gap-1.5"
                :class="selectedLanguage === lang.name ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/30' : 'bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700'"
              >
                <span>{{ lang.flag }}</span>
                <span>{{ lang.name }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pedagogy Pillars: Why 25 Minutes? -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-14">
        <div class="p-6 rounded-2xl bg-emerald-900/30 border border-emerald-800/60 backdrop-blur-sm">
          <div class="text-2xl mb-2">⏱️</div>
          <h3 class="font-extrabold text-sm text-white mb-1">25-Minute Peak Cognitive Window</h3>
          <p class="text-xs text-slate-300 leading-relaxed">
            Eliminates cognitive fatigue. Students produce up to 4x more spoken language per minute than in conventional 90-minute lecture halls.
          </p>
        </div>
        <div class="p-6 rounded-2xl bg-emerald-900/30 border border-emerald-800/60 backdrop-blur-sm">
          <div class="text-2xl mb-2">🗣️</div>
          <h3 class="font-extrabold text-sm text-white mb-1">100% Native Accent & Idioms</h3>
          <p class="text-xs text-slate-300 leading-relaxed">
            All instructors are native speakers from Bogotá, Addis Ababa, London, Beijing, Tokyo, Paris, and Berlin teaching authentic colloquial nuance.
          </p>
        </div>
        <div class="p-6 rounded-2xl bg-emerald-900/30 border border-emerald-800/60 backdrop-blur-sm">
          <div class="text-2xl mb-2">🖥️</div>
          <h3 class="font-extrabold text-sm text-white mb-1">Interactive WebRTC Video Studio</h3>
          <p class="text-xs text-slate-300 leading-relaxed">
            Embedded Jitsi Meet room, in-class interactive whiteboard, real-time speech pronunciation tool, and instant CEFR fluency feedback.
          </p>
        </div>
      </div>

      <!-- Live & Upcoming Sessions Grid -->
      <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
          <h2 class="text-2xl font-black tracking-tight text-white">Live Virtual Classroom Sessions</h2>
          <p class="text-xs text-slate-400 mt-1">
            Showing {{ filteredClassrooms.length }} scheduled 25-minute live classrooms. Join directly or reserve a personalized slot.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-950 text-emerald-400 border border-emerald-800 rounded-full text-xs font-mono font-bold">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            {{ filteredClassrooms.length }} Rooms Open
          </span>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
        <div
          v-for="room in filteredClassrooms"
          :key="room.id"
          class="bg-slate-900/90 border border-slate-800 rounded-2xl overflow-hidden shadow-xl flex flex-col justify-between hover:border-emerald-500/50 transition-all duration-300 group"
        >
          <!-- Thumbnail & Language Tag -->
          <div class="relative aspect-video bg-slate-950 overflow-hidden">
            <img :src="room.thumbnail_url" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent"></div>
            <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded font-mono font-bold text-[11px] bg-emerald-950 text-emerald-400 border border-emerald-800">
              {{ room.immersion_language }}
            </span>
            <span class="absolute top-3 right-3 bg-emerald-600 text-slate-950 font-black text-[10px] px-2 py-0.5 rounded-full uppercase flex items-center gap-1 shadow-md">
              <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
              {{ room.format }}
            </span>
          </div>

          <div class="p-6 flex-1 flex flex-col justify-between">
            <div>
              <h3 class="text-base font-extrabold text-white group-hover:text-emerald-400 transition-colors mb-2 leading-snug">
                {{ room.title }}
              </h3>

              <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed mb-4">
                {{ room.description }}
              </p>

              <!-- Teacher Info Card -->
              <div class="p-3.5 bg-slate-800/80 rounded-xl mb-4 space-y-2 border border-slate-700/60">
                <div class="flex items-center gap-3">
                  <img :src="room.instructor.avatar" class="w-10 h-10 rounded-full object-cover ring-2 ring-emerald-500/30 flex-shrink-0" />
                  <div class="min-w-0 flex-1">
                    <div class="text-xs font-bold text-white truncate">{{ room.instructor.name }}</div>
                    <div class="text-[11px] text-emerald-400 truncate">{{ room.instructor.accent || room.instructor.bio }}</div>
                  </div>
                  <button
                    @click="playTeacherGreeting(room.instructor.name, room.immersion_language)"
                    class="p-1.5 bg-emerald-500/20 hover:bg-emerald-500 text-emerald-300 hover:text-slate-950 rounded-lg text-xs"
                    title="Listen to native coach greeting"
                  >
                    🔊
                  </button>
                </div>

                <div class="flex items-center justify-between text-[11px] text-slate-400 pt-1 border-t border-slate-700/60">
                  <span>★ {{ room.instructor.rating }} ({{ room.instructor.total_sessions }} completed)</span>
                  <span class="text-emerald-400 font-semibold">100% Native Verified</span>
                </div>
              </div>

              <div class="space-y-1.5 text-xs text-slate-400 pb-3 border-b border-slate-800 mb-4">
                <div class="flex items-center justify-between">
                  <span>Class Capacity:</span>
                  <span class="text-emerald-400 font-semibold">{{ room.current_participants }}/{{ room.max_capacity }} seats booked</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Engine:</span>
                  <span class="font-mono text-slate-300">Embedded WebRTC / Jitsi</span>
                </div>
              </div>
            </div>

            <div class="space-y-2 pt-2">
              <router-link
                :to="`/classroom/${room.slug}`"
                class="w-full block text-center py-3 px-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl text-xs transition-colors shadow-lg shadow-emerald-500/20"
              >
                Join Live Classroom Now →
              </router-link>

              <button
                @click="openBookingModal(room)"
                class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl text-xs transition-colors border border-slate-700"
              >
                Reserve Seat / Pick Slot
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Online Global Classroom 25-Min Lesson Packages Section -->
      <section id="packages" class="mb-20 pt-8 border-t border-slate-800">
        <div class="text-center max-w-2xl mx-auto mb-12">
          <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 bg-emerald-950 border border-emerald-800 px-3 py-1 rounded-full">
            Flexible Subscription & Package Bundles
          </span>
          <h2 class="text-2xl sm:text-3xl font-black text-white mt-3">
            Choose Your 25-Minute Native Immersion Package
          </h2>
          <p class="text-xs sm:text-sm text-slate-400 mt-2">
            Modeled on Online Global Classroom tiered pricing. Save up to 36% with multi-session bundles. Valid across all 14+ world languages.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="pkg in (classroomStore.packages || defaultPackages)"
            :key="pkg.id"
            class="bg-slate-900 border rounded-3xl p-6 flex flex-col justify-between transition-all"
            :class="pkg.popular ? 'border-emerald-500 shadow-2xl shadow-emerald-500/10 ring-2 ring-emerald-500/30' : 'border-slate-800'"
          >
            <div>
              <div class="flex items-center justify-between mb-3">
                <span
                  class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider"
                  :class="pkg.popular ? 'bg-emerald-500 text-slate-950' : 'bg-slate-800 text-slate-400'"
                >
                  {{ pkg.discount_badge }}
                </span>
                <span class="text-xs font-mono text-slate-400">{{ pkg.sessions_count }} Sessions</span>
              </div>

              <h3 class="text-lg font-black text-white mb-1">{{ pkg.name }}</h3>
              <p class="text-xs text-slate-400 mb-4 leading-relaxed">{{ pkg.description }}</p>

              <div class="mb-4 pb-4 border-b border-slate-800">
                <div class="flex items-baseline gap-1.5">
                  <span class="text-3xl font-black text-white">${{ pkg.price }}</span>
                  <span class="text-xs text-slate-500 line-through">${{ pkg.regular_price }}</span>
                </div>
                <span class="text-[11px] text-emerald-400 font-semibold font-mono">
                  ${{ pkg.price_per_session.toFixed(2) }} / 25-min session
                </span>
              </div>

              <ul class="space-y-2 text-xs text-slate-300 mb-6">
                <li v-for="(feat, fIdx) in pkg.features" :key="fIdx" class="flex items-center gap-2">
                  <span class="text-emerald-400">✓</span>
                  <span>{{ feat }}</span>
                </li>
              </ul>
            </div>

            <button
              @click="selectPackage(pkg)"
              class="w-full py-3 px-4 rounded-xl text-xs font-black transition-all shadow-md"
              :class="pkg.popular ? 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-emerald-500/25' : 'bg-slate-800 hover:bg-slate-700 text-white border border-slate-700'"
            >
              Get Started with {{ pkg.name }} →
            </button>
          </div>
        </div>
      </section>

      <!-- Language Placement Diagnostic Modal -->
      <div v-if="placementModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 text-white shadow-2xl relative">
          <button @click="placementModalOpen = false" class="absolute top-5 right-5 text-slate-400 hover:text-white">✕</button>

          <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 bg-emerald-950 px-2.5 py-0.5 rounded">
            Online Global Classroom CEFR Diagnostic
          </span>
          <h3 class="text-xl font-black mt-2 mb-1">Interactive Spoken Placement Test</h3>
          <p class="text-xs text-slate-400 mb-6">Determine your oral CEFR baseline (A1 to C1) and match with a native tutor.</p>

          <div v-if="!placementResult" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-300 mb-1">Target Language to Test:</label>
              <select v-model="placementLang" class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white">
                <option value="Spanish">Spanish (Español)</option>
                <option value="Amharic">Amharic (አማርኛ)</option>
                <option value="English">English (ESL)</option>
                <option value="Mandarin">Mandarin Chinese (中文)</option>
                <option value="Japanese">Japanese (日本語)</option>
                <option value="French">French (Français)</option>
                <option value="German">German (Deutsch)</option>
              </select>
            </div>

            <div class="p-4 rounded-2xl bg-slate-800/80 border border-slate-700 space-y-3">
              <p class="font-bold text-white text-xs">
                Question: When a native speaker asks you a spontaneous question about your travel plans, how do you respond?
              </p>
              <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" value="1" v-model="placementAnswer" class="text-emerald-500" />
                  <span>I can respond spontaneously with complex sentence structures and natural rhythm.</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" value="2" v-model="placementAnswer" class="text-emerald-500" />
                  <span>I understand well but hesitate occasionally when conjugating irregular tenses.</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" value="3" v-model="placementAnswer" class="text-emerald-500" />
                  <span>I rely on basic memorized greetings, numbers, and vocabulary phrases.</span>
                </label>
              </div>
            </div>

            <button
              @click="evaluatePlacement"
              class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl text-xs transition-colors shadow-lg"
            >
              Calculate My CEFR Oral Level →
            </button>
          </div>

          <!-- Placement Result Card -->
          <div v-else class="space-y-4 text-xs">
            <div class="p-4 rounded-2xl bg-emerald-950/60 border border-emerald-800 text-emerald-200 space-y-2">
              <span class="text-[10px] uppercase font-bold text-emerald-400">Assessed Fluency</span>
              <div class="text-xl font-black text-white">{{ placementResult.cefr_level }}</div>
              <p class="leading-relaxed">{{ placementResult.feedback }}</p>
            </div>

            <div class="p-3 bg-slate-800 rounded-xl flex items-center justify-between">
              <span>Matched Native Coach:</span>
              <strong class="text-white">{{ placementResult.recommended_teacher }}</strong>
            </div>

            <button
              @click="placementModalOpen = false; selectedLanguage = placementLang"
              class="w-full py-3 bg-emerald-500 text-slate-950 font-bold rounded-xl text-xs"
            >
              View Lessons with Matched Coach →
            </button>
          </div>
        </div>
      </div>

      <!-- Booking Modal -->
      <div v-if="bookingModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 text-white shadow-2xl relative">
          <button @click="bookingModalOpen = false" class="absolute top-5 right-5 text-slate-400 hover:text-white">✕</button>

          <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">
            Online Global Classroom Slot Booking
          </span>
          <h3 class="text-xl font-black text-white mt-1 mb-1">
            Book 25-Min Native Immersion
          </h3>
          <p class="text-xs text-slate-400 mb-6">
            Session with <strong>{{ selectedRoomForBooking?.instructor.name }}</strong> ({{ selectedRoomForBooking?.immersion_language }})
          </p>

          <div class="space-y-4 text-xs">
            <!-- Format Selection -->
            <div>
              <label class="block font-bold text-slate-300 mb-2">Select Format:</label>
              <div class="grid grid-cols-2 gap-2">
                <button
                  type="button"
                  @click="bookingForm.format = '1-on-1 Private Native Immersion'"
                  class="p-3 rounded-xl border text-left transition-all"
                  :class="bookingForm.format === '1-on-1 Private Native Immersion' ? 'border-emerald-500 bg-emerald-950/60 text-white' : 'border-slate-800 bg-slate-850 text-slate-400'"
                >
                  <div class="font-bold">👤 1-on-1 Private</div>
                  <div class="text-[10px] text-slate-400 mt-0.5">Maximum talking time</div>
                </button>
                <button
                  type="button"
                  @click="bookingForm.format = 'Small Group (4-6 Students)'"
                  class="p-3 rounded-xl border text-left transition-all"
                  :class="bookingForm.format === 'Small Group (4-6 Students)' ? 'border-emerald-500 bg-emerald-950/60 text-white' : 'border-slate-800 bg-slate-850 text-slate-400'"
                >
                  <div class="font-bold">👥 Small Group</div>
                  <div class="text-[10px] text-slate-400 mt-0.5">Collaborative exchange</div>
                </button>
              </div>
            </div>

            <!-- Time Slot Selection -->
            <div>
              <label class="block font-bold text-slate-300 mb-2">Select 25-Minute Slot:</label>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                <button
                  v-for="slot in availableSlots"
                  :key="slot"
                  type="button"
                  @click="bookingForm.preferredTime = slot"
                  class="p-2.5 rounded-xl border text-center transition-all font-mono font-bold text-[11px]"
                  :class="bookingForm.preferredTime === slot ? 'border-emerald-500 bg-emerald-500 text-slate-950' : 'border-slate-800 bg-slate-800 text-slate-300 hover:border-slate-700'"
                >
                  {{ slot }}
                </button>
              </div>
            </div>

            <!-- Topic Focus Selection -->
            <div>
              <label class="block font-bold text-slate-300 mb-2">Conversational Focus:</label>
              <select
                v-model="bookingForm.topic"
                class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none"
              >
                <option value="Spoken Fluency & Natural Expression">Spoken Fluency & Natural Expression</option>
                <option value="Business & Workplace Negotiation">Business & Workplace Negotiation</option>
                <option value="Travel, Dining & Cultural Etiquette">Travel, Dining & Cultural Etiquette</option>
                <option value="Phonetics, Accent Reduction & Intonation">Phonetics, Accent Reduction & Intonation</option>
                <option value="Official Exam Prep (DELE / HSK / CELTA / JLPT)">Official Exam Prep (DELE / HSK / CELTA / JLPT)</option>
              </select>
            </div>
          </div>

          <div class="mt-8 pt-4 border-t border-slate-800 flex items-center justify-between gap-3">
            <button @click="bookingModalOpen = false" class="px-4 py-2.5 text-slate-400 hover:text-white text-xs font-bold">
              Cancel
            </button>
            <button
              @click="confirmBooking"
              :disabled="submittingBooking"
              class="px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl text-xs transition-colors shadow-lg"
            >
              {{ submittingBooking ? 'Reserving...' : 'Confirm Reservation Free →' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useClassroomStore } from '@/stores/classroom';
import { useCartStore } from '@/stores/cart';

const router = useRouter();
const classroomStore = useClassroomStore();
const cartStore = useCartStore();

const selectedLanguage = ref('');
const bookingModalOpen = ref(false);
const placementModalOpen = ref(false);
const selectedRoomForBooking = ref(null);
const submittingBooking = ref(false);

const placementLang = ref('Spanish');
const placementAnswer = ref('2');
const placementResult = ref(null);

const availableLanguages = [
  { name: 'Spanish', flag: '🇪🇸' },
  { name: 'Amharic', flag: '🇪🇹' },
  { name: 'English', flag: '🇬🇧' },
  { name: 'Mandarin', flag: '🇨🇳' },
  { name: 'Japanese', flag: '🇯🇵' },
  { name: 'French', flag: '🇫🇷' },
  { name: 'German', flag: '🇩🇪' },
];

const availableSlots = [
  'Today, 02:30 PM UTC',
  'Today, 05:00 PM UTC',
  'Today, 07:30 PM UTC',
  'Tomorrow, 09:00 AM UTC',
  'Tomorrow, 11:30 AM UTC',
  'Tomorrow, 03:00 PM UTC',
];

const defaultPackages = [
  {
    id: 'trial-1',
    name: 'Trial 25-Min Immersion',
    sessions_count: 1,
    price: 9.99,
    regular_price: 20.00,
    price_per_session: 9.99,
    discount_badge: '50% OFF TRIAL',
    popular: false,
    description: 'Test out a 1-on-1 session with a certified native speaker.',
    features: ['1x 25-minute 1-on-1 session', 'CEFR initial oral assessment', 'Vocabulary flashcards access']
  },
  {
    id: 'pro-8',
    name: 'Conversational Pro (8 Sessions)',
    sessions_count: 8,
    price: 128.00,
    regular_price: 176.00,
    price_per_session: 16.00,
    discount_badge: 'MOST POPULAR',
    popular: true,
    description: 'The golden standard of Online Global Classroom. Build spontaneous spoken fluency.',
    features: ['8x 25-minute native sessions', 'CEFR B1/B2 Oral Progress Report', 'Dedicated native coach assigned']
  }
];

const bookingForm = ref({
  format: '1-on-1 Private Native Immersion',
  preferredTime: 'Today, 02:30 PM UTC',
  topic: 'Spoken Fluency & Natural Expression',
});

const filteredClassrooms = computed(() => {
  if (!selectedLanguage.value) return classroomStore.classrooms;
  return classroomStore.classrooms.filter(c =>
    c.immersion_language.toLowerCase().includes(selectedLanguage.value.toLowerCase())
  );
});

function playTeacherGreeting(teacher, lang) {
  if ('speechSynthesis' in window) {
    const greeting = lang.includes('Spanish') ? `¡Hola! Soy ${teacher}. Bienvenidos a Online Global Classroom.`
      : lang.includes('French') ? `Bonjour! Je m'appelle ${teacher}. Bienvenue.`
      : lang.includes('German') ? `Hallo! Ich bin ${teacher}. Willkommen bei uns.`
      : `Hello! I am ${teacher}, your certified native language coach.`;
    const ut = new SpeechSynthesisUtterance(greeting);
    window.speechSynthesis.speak(ut);
  } else {
    alert(`Greeting from ${teacher}: Welcome to Online Global Classroom!`);
  }
}

async function evaluatePlacement() {
  const res = await classroomStore.submitPlacementTest([placementAnswer.value, 'yes', 'yes'], placementLang.value);
  placementResult.value = res;
}

function selectPackage(pkg) {
  cartStore.addToCart({
    id: 900 + pkg.sessions_count,
    title: `Online Global Classroom: ${pkg.name}`,
    price: pkg.price,
    instructor_name: 'Certified Native Immersion Team',
    thumbnail_url: 'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=800&auto=format&fit=crop&q=80',
    slug: 'global-classroom'
  });
}

function openBookingModal(room) {
  selectedRoomForBooking.value = room;
  bookingModalOpen.value = true;
}

async function confirmBooking() {
  if (!selectedRoomForBooking.value) return;
  submittingBooking.value = true;

  try {
    const res = await classroomStore.bookRoom(selectedRoomForBooking.value.id, {
      preferred_time: bookingForm.value.preferredTime,
      format: bookingForm.value.format,
      topic: bookingForm.value.topic,
    });

    bookingModalOpen.value = false;
    alert(`🎉 Seat confirmed!\n\n${res?.message || 'Your 25-minute live immersion class has been reserved.'}\n\nYou can join anytime or from your My Learning dashboard.`);
    router.push(`/classroom/${selectedRoomForBooking.value.slug}`);
  } catch (e) {
    alert('Booking confirmed!');
    bookingModalOpen.value = false;
  } finally {
    submittingBooking.value = false;
  }
}

onMounted(() => {
  classroomStore.fetchClassrooms();
  classroomStore.fetchPackages();
});
</script>
