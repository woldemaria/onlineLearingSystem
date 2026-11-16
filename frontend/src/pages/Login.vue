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

    <!-- Radial Vignette Overlay for Crisp Contrast (Click-through to allow canvas interaction) -->
    <div class="fixed inset-0 pointer-events-none bg-radial-gradient from-transparent via-slate-950/40 to-slate-950/80 z-[1]"></div>

    <!-- Top Navigation Header -->
    <header class="relative z-10 max-w-7xl w-full mx-auto p-4 sm:p-6 flex items-center justify-between">
      <router-link to="/" class="flex items-center gap-3 group">
        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-indigo-400 text-white font-black text-lg flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform">
          G
        </div>
        <div>
          <span class="text-base font-black tracking-tight text-white block">GELME</span>
          <span class="text-[10px] uppercase font-bold tracking-widest text-indigo-400 block -mt-1">Super Platform</span>
        </div>
      </router-link>

      <router-link
        to="/"
        class="px-3.5 py-1.5 rounded-xl bg-slate-900/60 hover:bg-slate-850/80 backdrop-blur-md border border-slate-700/60 text-xs font-bold text-slate-300 hover:text-white transition-all flex items-center gap-2 shadow-lg"
      >
        <span>←</span>
        <span>Back to Home</span>
      </router-link>
    </header>

    <!-- Center Floating Glassmorphic Authentication Card -->
    <main class="relative z-10 w-full max-w-md mx-auto px-4 my-6 sm:my-10">
      <div class="bg-slate-900/80 backdrop-blur-2xl border border-slate-700/70 rounded-3xl p-6 sm:p-8 text-white shadow-2xl ring-1 ring-white/10 relative">
        <div class="text-center mb-6">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/15 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-3">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            Unified Consortium Gateway
          </div>
          <h2 class="text-2xl font-black text-white tracking-tight">
            {{ isRegisterMode ? 'Join GELME Global Consortium' : 'Welcome Back to GELME' }}
          </h2>
          <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
            {{ isRegisterMode ? 'Create your authenticated student or instructor account' : 'Sign in to access your courses, certificates, and classrooms' }}
          </p>

          <!-- Mode Toggle Tabs (Sign In vs Create Account) -->
          <div class="flex bg-slate-950/70 p-1 rounded-2xl mt-5 border border-slate-800 text-xs font-bold">
            <button
              type="button"
              @click="switchMode(false)"
              class="flex-1 py-2 rounded-xl transition-all"
              :class="!isRegisterMode ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white'"
            >
              Sign In
            </button>
            <button
              type="button"
              @click="switchMode(true)"
              class="flex-1 py-2 rounded-xl transition-all"
              :class="isRegisterMode ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white'"
            >
              Create Account
            </button>
          </div>
        </div>

        <!-- Error / Status Notice -->
        <div v-if="errorMessage" class="mb-4 p-3 bg-rose-950/60 border border-rose-700 text-rose-300 text-xs rounded-xl flex items-center gap-2">
          <span>⚠️</span>
          <span>{{ errorMessage }}</span>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <!-- Name (Register Only) -->
          <div v-if="isRegisterMode">
            <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Full Legal Name</label>
            <input
              v-model="name"
              type="text"
              required
              placeholder="e.g. Dr. Jane Doe"
              class="w-full px-4 py-2.5 bg-slate-950/60 border border-slate-700/80 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 font-medium transition-colors"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Email Address</label>
            <input
              v-model="email"
              type="email"
              required
              placeholder="name@gelme.global"
              class="w-full px-4 py-2.5 bg-slate-950/60 border border-slate-700/80 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 font-medium transition-colors"
            />
          </div>

          <!-- Role Selector (Register Only) -->
          <div v-if="isRegisterMode">
            <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Account Role</label>
            <div class="grid grid-cols-2 gap-2">
              <button
                type="button"
                @click="role = 'student'"
                class="p-2.5 rounded-xl border text-xs font-bold flex items-center justify-center gap-1.5 transition-all"
                :class="role === 'student' ? 'border-indigo-500 bg-indigo-600/20 text-indigo-300 shadow-sm' : 'border-slate-800 bg-slate-950/50 text-slate-400 hover:text-slate-200'"
              >
                <span>🎓</span> Learner / Student
              </button>
              <button
                type="button"
                @click="role = 'instructor'"
                class="p-2.5 rounded-xl border text-xs font-bold flex items-center justify-center gap-1.5 transition-all"
                :class="role === 'instructor' ? 'border-indigo-500 bg-indigo-600/20 text-indigo-300 shadow-sm' : 'border-slate-800 bg-slate-950/50 text-slate-400 hover:text-slate-200'"
              >
                <span>🧑‍🏫</span> Instructor / Creator
              </button>
            </div>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Password</label>
            <input
              v-model="password"
              type="password"
              required
              minlength="6"
              placeholder="••••••••"
              class="w-full px-4 py-2.5 bg-slate-950/60 border border-slate-700/80 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 font-medium transition-colors"
            />
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition-all mt-2 disabled:opacity-50 flex items-center justify-center gap-2"
          >
            <span v-if="loading" class="animate-spin">⏳</span>
            <span>{{ isRegisterMode ? 'Complete Registration & Access' : 'Sign In & Access Learning' }}</span>
          </button>
        </form>

        <!-- Quick Demo Sign-In Buttons -->
        <div class="mt-6 pt-5 border-t border-slate-800">
          <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2.5 text-center">
            1-Click Instant Demo Authentication
          </span>
          <div class="grid grid-cols-3 gap-2">
            <button
              type="button"
              @click="quickLogin('student@gelme.global', 'password123')"
              class="px-2 py-1.5 bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white rounded-lg text-[10px] font-bold border border-slate-700 transition-all text-center"
            >
              🎓 Student
            </button>
            <button
              type="button"
              @click="quickLogin('sarah.chen@gelme.global', 'password123')"
              class="px-2 py-1.5 bg-indigo-950/80 hover:bg-indigo-900 text-indigo-300 hover:text-white rounded-lg text-[10px] font-bold border border-indigo-800/80 transition-all text-center"
            >
              🧑‍🏫 Instructor
            </button>
            <button
              type="button"
              @click="quickLogin('admin@gelme.global', 'password123')"
              class="px-2 py-1.5 bg-purple-950/80 hover:bg-purple-900 text-purple-300 hover:text-white rounded-lg text-[10px] font-bold border border-purple-800/80 transition-all text-center"
            >
              👑 Admin
            </button>
          </div>
        </div>
      </div>
    </main>

    <!-- Bottom Footer Telemetry & Attribution -->
    <footer class="relative z-10 max-w-7xl w-full mx-auto p-4 sm:p-6 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-3">
      <div class="flex items-center gap-2 text-[11px]">
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        <span class="text-slate-400">Interactive 3D WebGL2 Topography Terrain Background</span>
      </div>
      <div class="text-[11px] text-slate-500">
        Move cursor across screen to sculpt terrain • Click to pulse light wave
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Topography from '@/components/common/Topography';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

const isRegisterMode = ref(route.path === '/register');
const name = ref('');
const email = ref('student@gelme.global');
const password = ref('password123');
const role = ref('student');
const loading = ref(false);
const errorMessage = ref('');

watch(
  () => route.path,
  (newPath) => {
    isRegisterMode.value = newPath === '/register';
    errorMessage.value = '';
  }
);

function switchMode(register) {
  isRegisterMode.value = register;
  errorMessage.value = '';
  router.replace(register ? '/register' : '/login');
}

async function handleSubmit() {
  loading.value = true;
  errorMessage.value = '';

  try {
    let result;
    if (isRegisterMode.value) {
      result = await authStore.register({
        name: name.value,
        email: email.value,
        password: password.value,
        role: role.value,
      });
    } else {
      result = await authStore.login(email.value, password.value);
    }

    if (result.success) {
      if (authStore.isAdmin) {
        router.push('/admin/users');
      } else if (authStore.isInstructor) {
        router.push('/instructor');
      } else {
        router.push('/dashboard');
      }
    } else {
      errorMessage.value = result.message || 'Authentication error occurred.';
    }
  } catch (err) {
    errorMessage.value = 'Failed to communicate with authentication service.';
  } finally {
    loading.value = false;
  }
}

async function quickLogin(demoEmail, demoPassword) {
  email.value = demoEmail;
  password.value = demoPassword;
  isRegisterMode.value = false;
  await handleSubmit();
}

onMounted(() => {
  if (route.path === '/register') {
    isRegisterMode.value = true;
  }
});
</script>
