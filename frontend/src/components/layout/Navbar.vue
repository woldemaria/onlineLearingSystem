<template>
  <header
    class="sticky top-0 z-40 backdrop-blur-md transition-colors duration-200"
    :class="isDarkNav ? 'bg-slate-950/80 border-b border-slate-800 text-white' : 'bg-white/95 border-b border-slate-200'"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 gap-4">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2.5 flex-shrink-0 group">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-emerald-500 flex items-center justify-center text-white font-black text-xl shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
            G
          </div>
          <div>
            <span
              class="text-xl font-extrabold tracking-tight"
              :class="isDarkNav ? 'text-white' : 'bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-800 bg-clip-text text-transparent'"
            >
              GELME
            </span>
            <span
              class="hidden md:inline-block ml-1.5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full border"
              :class="isDarkNav ? 'bg-indigo-950 text-indigo-300 border-indigo-800' : 'bg-indigo-50 text-indigo-700 border-indigo-200'"
            >
              Super Platform
            </span>
          </div>
        </router-link>

        <!-- Categories & Search Bar (Udemy / Coursera style) -->
        <div class="hidden lg:flex items-center gap-3 flex-1 max-w-xl mx-4">
          <router-link
            to="/courses"
            class="text-xs font-bold px-3 py-2 rounded-lg transition-colors flex items-center gap-1 uppercase tracking-wider"
            :class="isDarkNav ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50'"
          >
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
              class="w-full pl-10 pr-9 py-2 text-xs rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all"
              :class="isDarkNav ? 'bg-slate-900 border border-slate-700 text-white placeholder-slate-400 focus:bg-slate-900 focus:border-indigo-400' : 'bg-slate-100 hover:bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-500 text-slate-800 placeholder-slate-400'"
            />
            <button
              type="button"
              @click="handleSearch"
              class="w-7 h-7 absolute left-2 top-1.5 flex items-center justify-center transition-colors"
              :class="isDarkNav ? 'text-slate-400 hover:text-indigo-400' : 'text-slate-400 hover:text-indigo-600'"
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
              class="w-5 h-5 absolute right-2.5 top-2.5 flex items-center justify-center rounded-full text-xs transition-colors"
              :class="isDarkNav ? 'text-slate-400 hover:text-white hover:bg-slate-800' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-200'"
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
            class="lg:hidden p-2 rounded-xl transition-colors"
            :class="isDarkNav ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50'"
            title="Toggle search"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>
          <!-- Online Global Classroom Badge Link -->
          <router-link
            to="/global-classroom"
            class="relative inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg transition-colors shadow-sm"
            :class="isDarkNav ? 'text-emerald-300 bg-emerald-950/80 hover:bg-emerald-900 border border-emerald-700/60' : 'text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300'"
            title="1,400+ Learners active online now"
          >
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="hidden sm:inline">Online Global Classroom</span>
            <span class="sm:hidden">OGC Live</span>
            <span class="hidden xl:inline text-[10px] font-mono px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-300 border border-emerald-700/40">🟢 1.4k live</span>
          </router-link>

          <router-link
            to="/courses"
            class="text-xs font-bold px-3 py-2 rounded-lg transition-colors hidden md:block"
            :class="isDarkNav ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50'"
          >
            Courses
          </router-link>

          <router-link
            to="/dashboard"
            class="text-xs font-bold px-3 py-2 rounded-lg transition-colors hidden sm:block"
            :class="isDarkNav ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50'"
          >
            My Learning
          </router-link>

          <router-link
            to="/careers"
            class="text-xs font-bold px-3 py-2 rounded-lg transition-colors hidden xl:block"
            :class="isDarkNav ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50'"
          >
            Careers
          </router-link>

          <router-link
            to="/verify"
            class="text-xs font-bold px-3 py-2 rounded-lg transition-colors hidden lg:block"
            :class="isDarkNav ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50'"
          >
            Verify Credential
          </router-link>

          <router-link
            to="/about"
            class="text-xs font-bold px-3 py-2 rounded-lg transition-colors hidden 2xl:block"
            :class="isDarkNav ? 'text-indigo-400 font-extrabold hover:text-white hover:bg-slate-800' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50'"
          >
            About
          </router-link>

          <router-link
            to="/contact"
            class="text-xs font-bold px-3 py-2 rounded-lg transition-colors hidden 2xl:block"
            :class="isDarkNav ? 'text-indigo-400 font-extrabold hover:text-white hover:bg-slate-800' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50'"
          >
            Contact
          </router-link>

          <router-link
            to="/instructor"
            class="text-xs font-bold px-3 py-2 rounded-lg transition-colors hidden xl:block"
            :class="isDarkNav ? 'text-indigo-400 hover:text-indigo-300 hover:bg-slate-800' : 'text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50'"
          >
            Instructor Studio
          </router-link>

          <router-link
            v-if="authStore.isAdmin"
            to="/admin/users"
            class="text-xs font-bold px-3 py-2 rounded-lg transition-colors hidden xl:block"
            :class="isDarkNav ? 'text-purple-400 hover:text-purple-300 hover:bg-slate-800' : 'text-purple-600 hover:text-purple-700 hover:bg-purple-50'"
          >
            Admin Console
          </router-link>

          <!-- Notification Bell Trigger -->
          <div class="relative">
            <button
              @click="notificationStore.isDropdownOpen = !notificationStore.isDropdownOpen"
              class="relative p-2 rounded-xl transition-colors"
              :class="isDarkNav ? 'text-slate-300 hover:text-amber-400 hover:bg-slate-800' : 'text-slate-700 hover:text-amber-500 hover:bg-slate-50'"
              title="Notifications"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
              </svg>
              <span
                v-if="notificationStore.unreadCount > 0"
                class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 text-slate-950 rounded-full text-[9px] font-black flex items-center justify-center shadow-md animate-pulse"
              >
                {{ notificationStore.unreadCount }}
              </span>
            </button>

            <!-- Notification Dropdown Panel -->
            <div
              v-if="notificationStore.isDropdownOpen"
              class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl shadow-2xl border z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-150"
              :class="isDarkNav ? 'bg-slate-900 border-slate-700 text-white' : 'bg-white border-slate-200 text-slate-900'"
            >
              <div class="p-3.5 border-b flex items-center justify-between" :class="isDarkNav ? 'border-slate-800 bg-slate-950/60' : 'border-slate-100 bg-slate-50'">
                <div class="flex items-center gap-2">
                  <span class="font-extrabold text-xs uppercase tracking-wider">Notifications</span>
                  <span
                    v-if="notificationStore.unreadCount > 0"
                    class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500 text-slate-950"
                  >
                    {{ notificationStore.unreadCount }} new
                  </span>
                </div>
                <button
                  v-if="notificationStore.unreadCount > 0"
                  @click="notificationStore.markAllAsRead"
                  class="text-[11px] font-bold text-indigo-500 hover:text-indigo-400 hover:underline"
                >
                  Mark all read
                </button>
              </div>

              <div class="max-h-80 overflow-y-auto divide-y" :class="isDarkNav ? 'divide-slate-800' : 'divide-slate-100'">
                <div
                  v-if="notificationStore.notifications.length === 0"
                  class="p-6 text-center text-xs text-slate-500"
                >
                  No notifications right now.
                </div>
                <div
                  v-for="item in notificationStore.notifications"
                  :key="item.id"
                  class="p-3.5 text-xs transition-colors relative flex items-start gap-3"
                  :class="[
                    !item.read_at
                      ? (isDarkNav ? 'bg-slate-800/60' : 'bg-indigo-50/50')
                      : (isDarkNav ? 'hover:bg-slate-800/30' : 'hover:bg-slate-50')
                  ]"
                >
                  <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 text-sm font-bold bg-white/10 border border-white/10 shadow-sm">
                    <span v-if="item.type === 'achievement'">🏆</span>
                    <span v-else-if="item.type === 'classroom'">🌐</span>
                    <span v-else-if="item.type === 'qa'">💬</span>
                    <span v-else>📢</span>
                  </div>

                  <div class="flex-1 min-w-0 pr-4">
                    <div class="flex items-center justify-between gap-1 mb-0.5">
                      <h4 class="font-bold text-xs truncate" :class="isDarkNav ? 'text-slate-100' : 'text-slate-900'">
                        {{ item.title }}
                      </h4>
                      <span v-if="!item.read_at" class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></span>
                    </div>
                    <p class="text-[11px] leading-relaxed line-clamp-2" :class="isDarkNav ? 'text-slate-400' : 'text-slate-600'">
                      {{ item.message }}
                    </p>
                    <div class="mt-2 flex items-center justify-between">
                      <router-link
                        v-if="item.action_url"
                        :to="item.action_url"
                        @click="notificationStore.markAsRead(item.id); notificationStore.isDropdownOpen = false;"
                        class="text-[11px] font-bold text-indigo-500 hover:text-indigo-400 hover:underline"
                      >
                        {{ item.action_label || 'View Details' }} →
                      </router-link>
                      <button
                        @click="notificationStore.deleteNotification(item.id)"
                        class="text-[10px] text-slate-400 hover:text-rose-500 transition-colors ml-auto"
                        title="Dismiss"
                      >
                        ✕
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="p-2 border-t text-center" :class="isDarkNav ? 'border-slate-800 bg-slate-950/40' : 'border-slate-100 bg-slate-50'">
                <router-link
                  to="/dashboard"
                  @click="notificationStore.isDropdownOpen = false"
                  class="text-[11px] font-bold text-slate-500 hover:text-indigo-500"
                >
                  View Learning Activity Dashboard
                </router-link>
              </div>
            </div>
          </div>

          <!-- Wishlist Heart Trigger (Udemy style) -->
          <router-link
            to="/dashboard"
            class="relative p-2 rounded-xl transition-colors"
            :class="isDarkNav ? 'text-slate-300 hover:text-rose-400 hover:bg-slate-800' : 'text-slate-700 hover:text-rose-600 hover:bg-slate-50'"
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
            class="relative p-2 rounded-xl transition-colors"
            :class="isDarkNav ? 'text-slate-300 hover:text-indigo-400 hover:bg-slate-800' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50'"
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
          <div
            class="flex items-center gap-2 pl-2 border-l"
            :class="isDarkNav ? 'border-slate-800' : 'border-slate-200'"
          >
            <template v-if="authStore.isAuthenticated">
              <router-link to="/dashboard" class="flex items-center gap-2 group" title="Dashboard">
                <img
                  :src="authStore.user?.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80'"
                  alt="Avatar"
                  class="w-8 h-8 rounded-full object-cover ring-2 ring-indigo-500/30 group-hover:ring-indigo-600 transition-all"
                />
                <span
                  class="text-xs font-bold group-hover:text-indigo-400 hidden xl:inline"
                  :class="isDarkNav ? 'text-slate-200' : 'text-slate-800'"
                >
                  {{ authStore.user?.name }}
                </span>
              </router-link>
              <button
                @click="handleLogout"
                class="text-[11px] font-bold px-2 py-1 rounded transition-colors hidden sm:block"
                :class="isDarkNav ? 'text-slate-400 hover:text-rose-400 hover:bg-slate-800' : 'text-slate-500 hover:text-rose-600 hover:bg-slate-100'"
                title="Sign Out"
              >
                Sign Out
              </button>
            </template>
            <template v-else>
              <div class="flex items-center gap-1.5 sm:gap-2">
                <router-link
                  to="/login"
                  class="px-2.5 py-1.5 sm:px-3.5 sm:py-2 text-xs font-bold rounded-xl transition-all border whitespace-nowrap"
                  :class="isDarkNav ? 'text-slate-200 hover:text-white hover:bg-slate-800 border-slate-700 bg-slate-900/50' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-100 border-slate-200'"
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
      <div
        v-if="mobileSearchOpen"
        class="lg:hidden pb-3 pt-2 border-t"
        :class="isDarkNav ? 'border-slate-800' : 'border-slate-100'"
      >
        <div class="relative w-full">
          <input
            v-model="searchQuery"
            @keyup.enter="handleSearch(); mobileSearchOpen = false;"
            type="text"
            placeholder="Search courses, skills, degrees, or live languages..."
            class="w-full pl-10 pr-9 py-2 text-xs rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
            :class="isDarkNav ? 'bg-slate-900 border border-slate-700 text-white placeholder-slate-400' : 'bg-slate-100 focus:bg-white border border-slate-200 focus:border-indigo-500 text-slate-800'"
          />
          <button
            type="button"
            @click="handleSearch(); mobileSearchOpen = false;"
            class="w-7 h-7 absolute left-2 top-1.5 flex items-center justify-center"
            :class="isDarkNav ? 'text-slate-400 hover:text-indigo-400' : 'text-slate-400 hover:text-indigo-600'"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>
          <button
            v-if="searchQuery"
            type="button"
            @click="searchQuery = ''; handleSearch(); mobileSearchOpen = false;"
            class="w-5 h-5 absolute right-2.5 top-2.5 flex items-center justify-center rounded-full text-xs"
            :class="isDarkNav ? 'text-slate-400 hover:text-white' : 'text-slate-400 hover:text-slate-600'"
          >
            ✕
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useCartStore } from '@/stores/cart';
import { useCourseStore } from '@/stores/courses';
import { useNotificationStore } from '@/stores/notification';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const cartStore = useCartStore();
const courseStore = useCourseStore();
const notificationStore = useNotificationStore();
const searchQuery = ref(route.query.search || '');
const searchInputRef = ref(null);
const mobileSearchOpen = ref(false);

const isDarkNav = computed(() => {
  return route.name === 'AboutUs' || route.name === 'ContactUs';
});

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

  // Pressing 'Escape' blurs search input or closes cart/notification drawer
  if (e.key === 'Escape') {
    if (cartStore.isCartOpen) {
      cartStore.isCartOpen = false;
    } else if (notificationStore.isDropdownOpen) {
      notificationStore.isDropdownOpen = false;
    } else if (searchInputRef.value && document.activeElement === searchInputRef.value) {
      searchInputRef.value.blur();
    }
  }
}

function handleDocumentClick(e) {
  if (notificationStore.isDropdownOpen && !e.target.closest('.relative')) {
    notificationStore.isDropdownOpen = false;
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleGlobalKeydown);
  document.addEventListener('click', handleDocumentClick);
  notificationStore.fetchNotifications();
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleGlobalKeydown);
  document.removeEventListener('click', handleDocumentClick);
});
</script>
