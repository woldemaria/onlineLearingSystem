<template>
  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 shadow-2xl text-slate-900">
      <div class="text-center mb-6">
        <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-black text-xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-indigo-600/30">
          G
        </div>
        <h2 class="text-2xl font-black text-slate-900">Welcome to GELME</h2>
        <p class="text-xs text-slate-500 mt-1">Sign in to your global learning account</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
          <input
            v-model="email"
            type="email"
            required
            placeholder="student@gelme.global"
            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
          <input
            v-model="password"
            type="password"
            required
            placeholder="••••••••"
            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
        </div>

        <button
          type="submit"
          class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition-all mt-2"
        >
          Sign In & Access Learning
        </button>
      </form>

      <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
        Demo accounts: <strong class="text-slate-700">student@gelme.global</strong> or <strong class="text-slate-700">admin@gelme.global</strong> (password: password123)
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
const email = ref('student@gelme.global');
const password = ref('password123');

async function handleSubmit() {
  await authStore.login(email.value, password.value);
  router.push('/dashboard');
}
</script>
