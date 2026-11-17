<template>
  <div class="relative min-h-screen w-full flex flex-col justify-between overflow-x-hidden bg-slate-950 text-white selection:bg-indigo-500 selection:text-white">
    <!-- Full-Page WebGL2 Topography Interactive Background -->
    <div class="fixed inset-0 w-screen h-screen overflow-hidden z-0">
      <Topography
        color="#4f7cff"
        :lineCount="22"
        :lineWidth="1.2"
        :elevation="1"
        :foreground="0.2"
        :tilt="32"
        :speed="0.25"
        direction="forward"
        :glow="0.6"
        :depthOfField="0.4"
        :fog="0.35"
        hoverEffect="lift"
        clickEffect="pulse"
        :majorLines="true"
        :scale="1"
        :detail="0.35"
        :morph="0.2"
        :brightness="1"
        markers="none"
        :glitch="0"
        :opacity="1"
        :mouseInteraction="true"
        :mouseRadius="1"
        :mouseStrength="0.6"
        :trail="0.3"
        :parallax="0.5"
        :intro="true"
        :lightMode="false"
        class="w-full h-full"
      />
    </div>

    <!-- Radial Vignette Overlay for Crisp Contrast -->
    <div class="fixed inset-0 pointer-events-none bg-radial-gradient from-transparent via-slate-950/40 to-slate-950/80 z-[1]"></div>

    <!-- Main Content Container Above Canvas -->
    <div class="relative z-10 flex-1">
      <!-- Header Banner -->
      <section class="py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800/80 bg-slate-950/60 backdrop-blur-md text-center">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/15 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-4">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
          <span>24/7 Global Learner Support & Multi-Disciplinary Teams</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight mb-3 drop-shadow-md">
          Dedicated Supporting Teams & Care
        </h1>
        <p class="text-sm text-slate-300 max-w-2xl mx-auto drop-shadow-sm leading-relaxed">
          Need academic guidance, video classroom debugging, financial aid review, or corporate partnerships? Connect directly with our specialized supporting teams.
        </p>
      </section>

      <!-- Specialized Supporting Teams Showcase -->
      <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
        <div class="text-center sm:text-left mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
          <div>
            <h2 class="text-xl font-black text-white flex items-center gap-2">
              <span>👥</span>
              <span>Our Supporting Teams</span>
            </h2>
            <p class="text-xs text-slate-400">Click any supporting team below to direct your message directly to their team lead.</p>
          </div>
          <span class="text-xs text-emerald-400 font-mono flex items-center gap-1.5 bg-emerald-950/80 px-3 py-1 rounded-full border border-emerald-800">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>All 4 Teams Live & Active</span>
          </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div
            v-for="team in supportingTeams"
            :key="team.id"
            @click="selectTeam(team)"
            class="p-5 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between"
            :class="selectedTeamId === team.id
              ? 'bg-indigo-950/80 border-indigo-500 ring-2 ring-indigo-500/30 shadow-xl'
              : 'bg-slate-900/80 border-slate-800 hover:border-slate-700 hover:bg-slate-850'"
          >
            <div>
              <div class="flex items-center justify-between mb-3">
                <span class="text-2xl">{{ team.icon }}</span>
                <span class="text-[10px] font-mono px-2 py-0.5 rounded-full" :class="team.badgeClass">
                  {{ team.sla }}
                </span>
              </div>
              <h3 class="text-sm font-bold text-white mb-1 leading-snug">{{ team.name }}</h3>
              <p class="text-[11px] text-slate-300 leading-relaxed mb-3">{{ team.description }}</p>
            </div>

            <div class="pt-3 border-t border-slate-800/80 text-[10px] text-slate-400 flex items-center justify-between">
              <span>Lead: <strong>{{ team.lead }}</strong></span>
              <span class="text-indigo-400 font-bold" v-if="selectedTeamId === team.id">✓ Selected</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Contact Form & Hubs -->
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Form Card -->
        <div class="p-8 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800 shadow-2xl">
          <div class="mb-6">
            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-400 bg-indigo-950/80 px-2.5 py-0.5 rounded-full border border-indigo-800">
              Routing To: {{ currentTeamName }}
            </span>
            <h2 class="text-xl font-black text-white mt-2">Send Message to Supporting Team</h2>
            <p class="text-xs text-slate-400 mt-1">Our team replies within the targeted SLA.</p>
          </div>

          <!-- Success Alert -->
          <div v-if="submitted" class="p-4 bg-emerald-950/70 border border-emerald-700 text-emerald-300 text-xs rounded-2xl mb-6 space-y-1 shadow-md">
            <div class="flex items-center gap-2 font-bold">
              <span>✓</span>
              <span>Ticket #GELME-{{ ticketId }} Routed Successfully!</span>
            </div>
            <p class="text-[11px] text-emerald-200">
              Your inquiry has been delivered directly to the <strong>{{ currentTeamName }}</strong>. Average response: {{ currentTeamSla }}.
            </p>
          </div>

          <form @submit.prevent="handleSubmit" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Your Full Name</label>
              <input
                v-model="name"
                type="text"
                required
                placeholder="e.g. Dr. Jane Smith"
                class="w-full p-3 bg-slate-950/70 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Email Address</label>
              <input
                v-model="email"
                type="email"
                required
                placeholder="name@university.edu"
                class="w-full p-3 bg-slate-950/70 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Target Supporting Team</label>
              <select
                v-model="selectedTeamId"
                class="w-full p-3 bg-slate-950/70 border border-slate-700/80 rounded-xl text-white focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors"
              >
                <option v-for="team in supportingTeams" :key="team.id" :value="team.id">
                  {{ team.icon }} {{ team.name }} ({{ team.sla }})
                </option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Message / Request Details</label>
              <textarea
                v-model="message"
                rows="4"
                required
                placeholder="Describe your request or question in detail..."
                class="w-full p-3 bg-slate-950/70 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors"
              ></textarea>
            </div>

            <button
              type="submit"
              class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]"
            >
              <span>Transmit Ticket to Supporting Team →</span>
            </button>
          </form>
        </div>

        <!-- Contact Info & Global Hubs -->
        <div class="space-y-6">
          <div class="p-6 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800 shadow-xl hover:border-slate-700 transition-all">
            <div class="w-10 h-10 rounded-2xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center text-xl mb-3 shadow-md">
              📍
            </div>
            <h3 class="text-base font-bold text-white mb-1">Consortium Headquarters</h3>
            <p class="text-xs text-slate-300 leading-relaxed">
              GELME Education Tower, Bole Sub-City, Addis Ababa, Ethiopia & Stanford Research Park, Palo Alto, CA 94304, USA.
            </p>
          </div>

          <div class="p-6 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800 shadow-xl hover:border-slate-700 transition-all">
            <div class="w-10 h-10 rounded-2xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center text-xl mb-3 shadow-md">
              ✉️
            </div>
            <h3 class="text-base font-bold text-white mb-1">Direct Team Dispatch</h3>
            <p class="text-xs text-slate-300 leading-relaxed">
              <strong class="text-white">Academic Advisory:</strong> academic@gelme.global<br />
              <strong class="text-white">Video & Cloud Systems:</strong> engineering@gelme.global<br />
              <strong class="text-white">Native Language Coaches:</strong> immersion@gelme.global<br />
              <strong class="text-white">Employer Network:</strong> enterprise@gelme.global
            </p>
          </div>

          <div class="p-6 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800 shadow-xl hover:border-slate-700 transition-all">
            <div class="w-10 h-10 rounded-2xl bg-purple-600/20 text-purple-400 flex items-center justify-center text-xl mb-3 shadow-md">
              ⚡
            </div>
            <h3 class="text-base font-bold text-white mb-1">24/7 AI Companion & Instant Debugging</h3>
            <p class="text-xs text-slate-300 leading-relaxed">
              For instant homework explanations, quiz preparation, and live code debugging, you can also query the 24/7 AI Tutor widget in the lower right corner.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Topography from '@/components/common/Topography';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

const supportingTeams = [
  {
    id: 'academic',
    name: 'Academic Advisory & Degrees',
    lead: 'Dr. Jane Doe',
    sla: '< 45 min',
    icon: '🎓',
    badgeClass: 'bg-indigo-950 text-indigo-400 border border-indigo-800',
    description: 'Stanford & Coursera university course counseling, financial aid reviews, and CEU credits.'
  },
  {
    id: 'technical',
    name: 'Video & Cloud Engineering',
    lead: 'Alex Rivera',
    sla: '< 15 min',
    icon: '💻',
    badgeClass: 'bg-emerald-950 text-emerald-400 border border-emerald-800',
    description: 'WebRTC video classroom debugging, microphone latency, streaming playback, and developer APIs.'
  },
  {
    id: 'immersion',
    name: 'Global Classroom Coaches',
    lead: 'Carlos Méndez & Selamawit T.',
    sla: '< 30 min',
    icon: '🌐',
    badgeClass: 'bg-teal-950 text-teal-400 border border-teal-800',
    description: '25-minute native immersion slots, CEFR diagnostic level matching, and language tutoring.'
  },
  {
    id: 'career',
    name: 'Career & Corporate Liaison',
    lead: 'Marcus Vance',
    sla: '< 2 hours',
    icon: '💼',
    badgeClass: 'bg-amber-950 text-amber-400 border border-amber-800',
    description: 'Recruiter verification of cryptographic certificates, portfolio reviews, and corporate seats.'
  }
];

const selectedTeamId = ref('academic');
const name = ref(authStore.user?.name || '');
const email = ref(authStore.user?.email || '');
const message = ref('');
const submitted = ref(false);
const ticketId = ref(Math.floor(Math.random() * 90000 + 10000));

const currentTeam = computed(() => supportingTeams.find(t => t.id === selectedTeamId.value) || supportingTeams[0]);
const currentTeamName = computed(() => currentTeam.value.name);
const currentTeamSla = computed(() => currentTeam.value.sla);

function selectTeam(team) {
  selectedTeamId.value = team.id;
}

function handleSubmit() {
  submitted.value = true;
  ticketId.value = Math.floor(Math.random() * 90000 + 10000);
  message.value = '';
}

onMounted(() => {
  if (authStore.user) {
    if (!name.value) name.value = authStore.user.name || '';
    if (!email.value) email.value = authStore.user.email || '';
  }
});
</script>
