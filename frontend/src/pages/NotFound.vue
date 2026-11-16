<template>
  <div class="relative min-h-screen w-full flex flex-col justify-between overflow-x-hidden bg-slate-950 text-white selection:bg-purple-500 selection:text-white">
    <!-- Full-Screen Interactive WebGL2 Light Tunnel Background -->
    <div class="fixed inset-0 w-screen h-screen overflow-hidden z-0">
      <LightTunnel
        cableColor="#6366f1"
        pulseColor="#c084fc"
        tunnelColor="#020617"
        :tunnelOpacity="0.4"
        :speed="0.18"
        flowDirection="inward"
        :pulseSpeed="2.2"
        :pulseLength="0.32"
        :pulseBlend="0.6"
        :pulseWidth="1.2"
        :pulseDensity="0.55"
        :cableCount="64"
        :thickness="0.4"
        :glow="1.3"
        :brightness="1.1"
        :mouseInteraction="true"
        :mouseStrength="0.15"
        :clickSurge="true"
        :intro="true"
        class="w-full h-full"
      />
    </div>

    <!-- Radial Dark Vignette Overlay -->
    <div class="fixed inset-0 pointer-events-none bg-radial-gradient from-transparent via-slate-950/50 to-slate-950/90 z-[1]"></div>

    <!-- Top Navigation Header -->
    <header class="relative z-10 max-w-7xl w-full mx-auto p-4 sm:p-6 flex items-center justify-between">
      <router-link to="/" class="flex items-center gap-3 group">
        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 text-white font-black text-lg flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-105 transition-transform">
          G
        </div>
        <div>
          <span class="text-base font-black tracking-tight text-white block">GELME</span>
          <span class="text-[10px] uppercase font-bold tracking-widest text-purple-400 block -mt-1">Super Platform</span>
        </div>
      </router-link>

      <router-link
        to="/"
        class="px-4 py-2 rounded-xl bg-slate-900/70 hover:bg-slate-850/90 backdrop-blur-md border border-slate-700/60 text-xs font-bold text-slate-300 hover:text-white transition-all flex items-center gap-2 shadow-lg"
      >
        <span>←</span>
        <span>Return to Home</span>
      </router-link>
    </header>

    <!-- Center 404 Glassmorphic Cosmic Card -->
    <main class="relative z-10 w-full max-w-xl mx-auto px-4 my-auto py-8">
      <div class="bg-slate-900/80 backdrop-blur-2xl border border-slate-700/70 rounded-3xl p-6 sm:p-10 text-white shadow-2xl ring-1 ring-white/10 text-center relative overflow-hidden">
        <!-- Glow Pill Header -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-purple-500/15 border border-purple-500/30 text-purple-300 text-xs font-semibold mb-4">
          <span class="w-2 h-2 rounded-full bg-rose-400 animate-ping"></span>
          <span>HTTP 404 • Lost in Learning Hyperspace</span>
        </div>

        <!-- Big 404 Glitch/Cosmic Title -->
        <h1 class="text-6xl sm:text-7xl font-black tracking-tight bg-gradient-to-r from-indigo-400 via-purple-300 to-pink-400 bg-clip-text text-transparent mb-3">
          404
        </h1>

        <h2 class="text-xl sm:text-2xl font-black text-white mb-3">
          Educational Horizon Not Found
        </h2>

        <p class="text-xs sm:text-sm text-slate-300 max-w-md mx-auto mb-6 leading-relaxed">
          The course catalog quadrant, lecture module, or live virtual immersion slot you are searching for has drifted into deep space.
        </p>

        <!-- Direct Search on 404 Page -->
        <div class="max-w-md mx-auto mb-6">
          <div class="relative">
            <input
              v-model="searchQuery"
              @keyup.enter="handleSearch"
              type="text"
              placeholder="Search 1,200+ courses, degrees, or native languages..."
              class="w-full pl-10 pr-24 py-3 text-xs bg-slate-950/70 border border-slate-700 rounded-2xl text-white placeholder-slate-400 focus:outline-none focus:border-purple-400 focus:ring-1 focus:ring-purple-400 shadow-inner"
            />
            <span class="absolute left-3.5 top-3 text-slate-400 text-sm">🔍</span>
            <button
              @click="handleSearch"
              class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl text-xs transition-all shadow-md"
            >
              Search
            </button>
          </div>
        </div>

        <!-- Helpful Action Links Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-4 border-t border-slate-800 text-xs font-bold">
          <router-link
            to="/courses"
            class="p-3 rounded-2xl bg-slate-950/60 hover:bg-slate-800/80 border border-slate-800 text-slate-300 hover:text-white transition-all flex flex-col items-center gap-1 group"
          >
            <span class="text-lg group-hover:scale-110 transition-transform">📚</span>
            <span>Browse Courses</span>
          </router-link>

          <router-link
            to="/global-classroom"
            class="p-3 rounded-2xl bg-emerald-950/30 hover:bg-emerald-900/40 border border-emerald-800/60 text-emerald-300 hover:text-emerald-200 transition-all flex flex-col items-center gap-1 group"
          >
            <span class="text-lg group-hover:scale-110 transition-transform">🌍</span>
            <span>Live Classroom</span>
          </router-link>

          <router-link
            to="/dashboard"
            class="p-3 rounded-2xl bg-indigo-950/30 hover:bg-indigo-900/40 border border-indigo-800/60 text-indigo-300 hover:text-indigo-200 transition-all flex flex-col items-center gap-1 group"
          >
            <span class="text-lg group-hover:scale-110 transition-transform">🎓</span>
            <span>My Learning</span>
          </router-link>
        </div>
      </div>
    </main>

    <!-- Bottom Interactive Guidance & Attribution -->
    <footer class="relative z-10 max-w-7xl w-full mx-auto p-4 sm:p-6 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-3">
      <div class="flex items-center gap-2 text-[11px]">
        <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
        <span class="text-slate-400">Interactive 3D WebGL2 Light Tunnel Active</span>
      </div>
      <div class="text-[11px] text-slate-500">
        Move cursor to steer tunnel trajectory • Click anywhere to surge light pulses
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import LightTunnel from '@/components/common/LightTunnel';

const router = useRouter();
const searchQuery = ref('');

function handleSearch() {
  if (searchQuery.value.trim()) {
    router.push({ path: '/courses', query: { search: searchQuery.value.trim() } });
  } else {
    router.push('/courses');
  }
}
</script>
