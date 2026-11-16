<template>
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 gap-4">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2.5 flex-shrink-0 group">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-emerald-500 flex items-center justify-center text-white font-black text-xl shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
            G
          </div>
          <div>
            <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-800 bg-clip-text text-transparent">
              GELME
            </span>
            <span class="hidden md:inline-block ml-1.5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 rounded-full border border-indigo-200">
              Super Platform
            </span>
          </div>
        </router-link>

        <!-- Categories & Search Bar (Udemy / Coursera style) -->
        <div class="hidden lg:flex items-center gap-3 flex-1 max-w-xl mx-4">
          <router-link to="/courses" class="text-xs font-bold text-slate-700 hover:text-indigo-600 px-3 py-2 rounded-lg hover:bg-slate-50 transition-colors flex items-center gap-1 uppercase tracking-wider">
            <span>Explore</span>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
          </router-link>

          <div class="relative w-full">
            <input
              ref="searchInputRef"
              v-model="searchQuery"
              @keyup.enter="handleSearch"
              type="text"
              placeholder="Search courses, specializations, degrees, or live languages..."
              class="w-full pl-10 pr-9 py-2 text-xs bg-slate-100 hover:bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-500 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all text-slate-800 placeholder-slate-400"
            />
            <button
              type="button"
              @click="handleSearch"
              class="w-7 h-7 text-slate-400 hover:text-indigo-600 absolute left-2 top-1.5 flex items-center justify-center transition-colors"
              title="Search"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </button>
            <button
              v-if="searchQuery"
              type="button"
              @click="searchQuery = ''; handleSearch();"
              class="w-5 h-5 text-slate-400 hover:text-slate-600 absolute right-2.5 top-2.5 flex items-center justify-center rounded-full hover:bg-slate-200 text-xs transition-colors"
              title="Clear search"
            >
              ✕
            </button>
          </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex items-center gap-1 sm:gap-2.5">
          <!-- Mobile Search Toggle -->
          <button
            @click="mobileSearchOpen = !mobileSearchOpen"
            class="lg:hidden p-2 text-slate-700 hover:text-indigo-600 hover:bg-slate-50 rounded-xl transition-colors"
            title="Toggle search"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>
          <!-- Online Global Classroom Badge Link -->
          <router-link
            to="/global-classroom"
            class="relative inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 rounded-lg transition-colors shadow-sm"
          >
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="hidden sm:inline">Online Global Classroom</span>
            <span class="sm:hidden">OGC Live</span>
          </router-link>

          <router-link
            to="/courses"
            class="text-xs font-bold text-slate-700 hover:text-slate-900 px-3 py-2 rounded-lg hover:bg-slate-50 transition-colors hidden md:block"
          >
            Courses
          </router-link>

          <router-link
            to="/dashboard"
            class="text-xs font-bold text-slate-700 hover:text-slate-900 px-3 py-2 rounded-lg hover:bg-slate-50 transition-colors hidden sm:block"
          >
            My Learning
          </router-link>

          <router-link
            to="/careers"
            class="text-xs font-bold text-slate-700 hover:text-slate-900 px-3 py-2 rounded-lg hover:bg-slate-50 transition-colors hidden xl:block"
          >
            Careers
          </router-link>

          <router-link
            to="/verify"
            class="text-xs font-bold text-slate-700 hover:text-slate-900 px-3 py-2 rounded-lg hover:bg-slate-50 transition-colors hidden lg:block"
          >
            Verify Credential
          </router-link>

          <router-link
            to="/instructor"
            class="text-xs font-bold text-indigo-600 hover:text-indigo-700 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-colors hidden xl:block"
          >
            Instructor Studio
          </router-link>

          <router-link
            v-if="authStore.isAdmin"
            to="/admin/users"
            class="text-xs font-bold text-purple-600 hover:text-purple-700 px-3 py-2 rounded-lg hover:bg-purple-50 transition-colors hidden xl:block"
          >
            Admin Console
          </router-link>

          <!-- Wishlist Heart Trigger (Udemy style) -->
          <router-link
            to="/dashboard"
            class="relative p-2 text-slate-700 hover:text-rose-600 hover:bg-slate-50 rounded-xl transition-colors"
            title="Wishlist"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span
              v-if="courseStore.wishlist.length > 0"
              class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white rounded-full text-[9px] font-black flex items-center justify-center shadow-md"
            >
              {{ courseStore.wishlist.length }}
            </span>
          </router-link>

          <!-- Shopping Cart Trigger (Udemy style) -->
          <button
            @click="cartStore.isCartOpen = true"
            class="relative p-2 text-slate-700 hover:text-indigo-600 hover:bg-slate-50 rounded-xl transition-colors"
            title="Shopping Cart"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span
              v-if="cartStore.items.length > 0"
              class="absolute -top-1 -right-1 w-5 h-5 bg-indigo-600 text-white rounded-full text-[10px] font-black flex items-center justify-center animate-bounce shadow-md"
            >
              {{ cartStore.items.length }}
            </span>
          </button>

          <!-- User Profile / Auth State -->
          <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
            <template v-if="authStore.isAuthenticated">
              <router-link to="/dashboard" class="flex items-center gap-2 group" title="Dashboard">
                <img
                  :src="authStore.user?.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80'"
                  alt="Avatar"
                  class="w-8 h-8 rounded-full object-cover ring-2 ring-indigo-500/30 group-hover:ring-indigo-600 transition-all"
                />
                <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 hidden xl:inline">
                  {{ authStore.user?.name }}
                </span>
              </router-link>
              <button
                @click="handleLogout"
                class="text-[11px] font-bold text-slate-500 hover:text-rose-600 px-2 py-1 rounded hover:bg-slate-100 transition-colors hidden sm:block"
                title="Sign Out"
              >
                Sign Out
              </button>
            </template>
            <template v-else>
              <div class="flex items-center gap-1.5 sm:gap-2">
                <router-link
                  to="/login"
                  class="px-2.5 py-1.5 sm:px-3.5 sm:py-2 text-xs font-bold text-slate-700 hover:text-indigo-600 hover:bg-slate-100 rounded-xl transition-all border border-slate-200 whitespace-nowrap"
                >
                  Sign In
                </router-link>
                <router-link
                  to="/register"
                  class="px-3 py-1.5 sm:px-4 sm:py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-indigo-500/20 whitespace-nowrap"
                >
                  Join Free
                </router-link>
              </div>
            </template>
          </div>
        </nav>
      </div>

      <!-- Mobile Expandable Search Bar Drawer -->
      <div v-if="mobileSearchOpen" class="lg:hidden pb-3 pt-2 border-t border-slate-100">
        <div class="relative w-full">
          <input
            v-model="searchQuery"
            @keyup.enter="handleSearch(); mobileSearchOpen = false;"
            type="text"
            placeholder="Search courses, skills, degrees, or live languages..."
            class="w-full pl-10 pr-9 py-2 text-xs bg-slate-100 focus:bg-white border border-slate-200 focus:border-indigo-500 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500/20 text-slate-800"
          />
          <button
            type="button"
            @click="handleSearch(); mobileSearchOpen = false;"
            class="absolute left-3 top-2.5 text-slate-400 hover:text-indigo-600"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>
          <button
            v-if="searchQuery"
            type="button"
            @click="searchQuery = ''; handleSearch();"
            class="absolute right-3 top-2 text-slate-400 hover:text-slate-600 text-xs"
          >
            ✕
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useCartStore } from '@/stores/cart';
import { useCourseStore } from '@/stores/courses';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const cartStore = useCartStore();
const courseStore = useCourseStore();
const searchQuery = ref(route.query.search || '');
const searchInputRef = ref(null);
const mobileSearchOpen = ref(false);

watch(
  () => route.query.search,
  (newSearch) => {
    if (newSearch !== undefined) {
      searchQuery.value = newSearch || '';
    }
  }
);

function handleSearch() {
  if (searchQuery.value.trim()) {
    router.push({ path: '/courses', query: { search: searchQuery.value.trim() } });
  } else {
    router.push('/courses');
  }
}

async function handleLogout() {
  await authStore.logout();
  router.push('/login');
}

function handleGlobalKeydown(e) {
  const isInputActive = ['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName) || document.activeElement?.isContentEditable;

  // Pressing '/' or 'Ctrl+K' / 'Cmd+K' focuses the search input
  if ((e.key === '/' && !isInputActive) || ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k')) {
    e.preventDefault();
    if (searchInputRef.value) {
      searchInputRef.value.focus();
      searchInputRef.value.select();
    }
  }

  // Pressing 'Escape' blurs search input or closes cart drawer
  if (e.key === 'Escape') {
    if (cartStore.isCartOpen) {
      cartStore.isCartOpen = false;
    } else if (searchInputRef.value && document.activeElement === searchInputRef.value) {
      searchInputRef.value.blur();
    }
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleGlobalKeydown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleGlobalKeydown);
});
</script>
