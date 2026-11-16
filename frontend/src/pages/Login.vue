<template>
  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 shadow-2xl text-slate-900">
      <div class="text-center mb-6">
        <router-link to="/" class="inline-flex items-center gap-2 mb-3 group">
          <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-black text-xl flex items-center justify-center shadow-lg shadow-indigo-600/30 group-hover:scale-105 transition-transform">
            G
          </div>
        </router-link>
        <h2 class="text-2xl font-black text-slate-900">
          {{ isRegisterMode ? 'Join GELME Global Consortium' : 'Welcome Back to GELME' }}
        </h2>
        <p class="text-xs text-slate-500 mt-1">
          {{ isRegisterMode ? 'Create your authenticated student or instructor account' : 'Sign in to access your courses, certificates, and classrooms' }}
        </p>

        <!-- Mode Toggle Tabs -->
        <div class="flex bg-slate-100 p-1 rounded-xl mt-4 text-xs font-bold">
          <button
            type="button"
            @click="isRegisterMode = false; errorMessage = '';"
            class="flex-1 py-2 rounded-lg transition-all"
            :class="!isRegisterMode ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
          >
            Sign In
          </button>
          <button
            type="button"
            @click="isRegisterMode = true; errorMessage = '';"
            class="flex-1 py-2 rounded-lg transition-all"
            :class="isRegisterMode ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
          >
            Create Account
          </button>
        </div>
      </div>

      <!-- Error / Status Notice -->
      <div v-if="errorMessage" class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl flex items-center gap-2">
        <span>⚠️</span>
        <span>{{ errorMessage }}</span>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <!-- Name (Register Only) -->
        <div v-if="isRegisterMode">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Legal Name</label>
          <input
            v-model="name"
            type="text"
            required
            placeholder="e.g. Dr. Jane Doe"
            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium"
          />
        </div>

        <!-- Email -->
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
          <input
            v-model="email"
            type="email"
            required
            placeholder="name@gelme.global"
            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium"
          />
        </div>

        <!-- Role Selector (Register Only) -->
        <div v-if="isRegisterMode">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Account Role</label>
          <div class="grid grid-cols-2 gap-2">
            <button
              type="button"
              @click="role = 'student'"
              class="p-2.5 rounded-xl border text-xs font-bold flex items-center justify-center gap-1.5 transition-all"
              :class="role === 'student' ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-slate-200 text-slate-600'"
            >
              <span>🎓</span> Learner / Student
            </button>
            <button
              type="button"
              @click="role = 'instructor'"
              class="p-2.5 rounded-xl border text-xs font-bold flex items-center justify-center gap-1.5 transition-all"
              :class="role === 'instructor' ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-slate-200 text-slate-600'"
            >
              <span>🧑‍🏫</span> Instructor / Creator
            </button>
          </div>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
          <input
            v-model="password"
            type="password"
            required
            minlength="6"
            placeholder="••••••••"
            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium"
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition-all mt-2 disabled:opacity-50 flex items-center justify-center gap-2"
        >
          <span v-if="loading" class="animate-spin">⏳</span>
          <span>{{ isRegisterMode ? 'Complete Registration & Access' : 'Sign In & Access Learning' }}</span>
        </button>
      </form>

      <!-- Quick Demo Sign-In Buttons -->
      <div class="mt-6 pt-5 border-t border-slate-100">
        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2.5 text-center">
          1-Click Instant Demo Authentication
        </span>
        <div class="grid grid-cols-3 gap-2">
          <button
            type="button"
            @click="quickLogin('student@gelme.global', 'password123')"
            class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-[10px] font-bold transition-all text-center"
          >
            Student Demo
          </button>
          <button
            type="button"
            @click="quickLogin('sarah.chen@gelme.global', 'password123')"
            class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-[10px] font-bold transition-all text-center"
          >
            Instructor Demo
          </button>
          <button
            type="button"
            @click="quickLogin('admin@gelme.global', 'password123')"
            class="px-2.5 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-[10px] font-bold transition-all text-center"
          >
            Admin Demo
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const isRegisterMode = ref(false);
const name = ref('');
const email = ref('student@gelme.global');
const password = ref('password123');
const role = ref('student');
const loading = ref(false);
const errorMessage = ref('');

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
      if (authStore.isInstructor) {
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
</script>
