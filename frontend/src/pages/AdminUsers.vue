<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 pb-20">
    <!-- Interactive Topography Header Hero with React Bits Component -->
    <div class="relative bg-slate-950 border-b border-slate-800 overflow-hidden">
      <!-- Topography WebGL2 Canvas Background -->
      <div class="absolute inset-0 opacity-40 pointer-events-auto">
        <Topography
          color="#6366f1"
          :lineCount="24"
          :lineWidth="1.2"
          :elevation="1.1"
          :foreground="0.2"
          :tilt="35"
          :speed="0.2"
          direction="forward"
          :glow="0.6"
          :depthOfField="0.35"
          :fog="0.3"
          hoverEffect="lift"
          clickEffect="pulse"
          :mouseInteraction="true"
        />
      </div>

      <!-- Hero Content Overlay -->
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-semibold mb-3 border border-indigo-500/30">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              GELME Central Admin Console
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
              User Registration & Role Governance
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm max-w-2xl mt-1 leading-relaxed">
              Manage platform learners, certified instructors, institutional privileges, and registration verifications.
            </p>
          </div>

          <div class="flex items-center gap-3">
            <button
              @click="loadData"
              class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-white rounded-xl text-xs font-bold border border-slate-700 transition-all flex items-center gap-2"
            >
              <span>🔄</span> Refresh Registry
            </button>
            <router-link
              to="/instructor"
              class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold shadow-lg transition-all"
            >
              Instructor Studio →
            </router-link>
          </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800 shadow-lg">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Registered</span>
            <div class="text-2xl font-black text-white mt-1">{{ stats?.total_users || 0 }}</div>
            <span class="text-[10px] text-emerald-400 mt-0.5 block">100% Verified Profiles</span>
          </div>

          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800 shadow-lg">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Enrolled Learners</span>
            <div class="text-2xl font-black text-indigo-400 mt-1">{{ stats?.total_students || 0 }}</div>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Active Student IDs</span>
          </div>

          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800 shadow-lg">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Educators & Faculty</span>
            <div class="text-2xl font-black text-purple-400 mt-1">{{ stats?.total_instructors || 0 }}</div>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Course Creators & Native Tutors</span>
          </div>

          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800 shadow-lg">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Active Accounts</span>
            <div class="text-2xl font-black text-emerald-400 mt-1">{{ stats?.active_users || 0 }}</div>
            <span class="text-[10px] text-emerald-400 mt-0.5 block">Operational & Compliant</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Admin Controls & Registry Table -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
      <!-- Authorization Notice if not Admin -->
      <div
        v-if="!authStore.isAdmin"
        class="mb-6 p-4 bg-purple-950/40 border border-purple-500/40 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4"
      >
        <div class="flex items-center gap-3">
          <span class="text-2xl">🛡️</span>
          <div>
            <h4 class="text-sm font-bold text-purple-200">Admin Elevation Required</h4>
            <p class="text-xs text-slate-300">
              You are signed in as <strong class="text-white">{{ authStore.user?.email || 'Guest' }}</strong>. Switch to the Admin profile to update roles, verify instructors, and toggle user account statuses.
            </p>
          </div>
        </div>
        <button
          @click="switchToAdmin"
          class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-xl transition-all shadow-md whitespace-nowrap"
        >
          1-Click Switch to Demo Admin (Dr. Elena Rostova)
        </button>
      </div>

      <!-- Action Feedback Banner -->
      <div
        v-if="alertMessage"
        class="mb-6 p-3.5 bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 rounded-xl text-xs flex items-center justify-between"
      >
        <div class="flex items-center gap-2">
          <span>✅</span>
          <span>{{ alertMessage }}</span>
        </div>
        <button @click="alertMessage = ''" class="text-slate-400 hover:text-white">✕</button>
      </div>

      <!-- Search & Filters Bar -->
      <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 mb-6 flex flex-col sm:flex-row items-center gap-4 justify-between">
        <div class="relative w-full sm:w-80">
          <input
            v-model="searchQuery"
            @input="filterUsers"
            type="text"
            placeholder="Search by student name or email..."
            class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-indigo-500"
          />
          <svg class="w-4 h-4 text-slate-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
          <!-- Role Filter -->
          <select
            v-model="roleFilter"
            @change="filterUsers"
            class="px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none"
          >
            <option value="all">All Roles</option>
            <option value="student">Students / Learners</option>
            <option value="instructor">Instructors & Faculty</option>
            <option value="admin">Administrators</option>
          </select>

          <!-- Status Filter -->
          <select
            v-model="statusFilter"
            @change="filterUsers"
            class="px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none"
          >
            <option value="all">All Status</option>
            <option value="active">Active Only</option>
            <option value="suspended">Suspended Only</option>
          </select>
        </div>
      </div>

      <!-- Users Registry Table -->
      <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-950/80 text-slate-400 uppercase font-bold text-[10px] border-b border-slate-800">
              <tr>
                <th class="p-4">User & Identifier</th>
                <th class="p-4">Assigned Role</th>
                <th class="p-4">Registration Date</th>
                <th class="p-4">Account Status</th>
                <th class="p-4 text-right">Admin Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
              <tr
                v-for="u in usersList"
                :key="u.id"
                class="hover:bg-slate-800/40 transition-colors"
              >
                <!-- User info -->
                <td class="p-4">
                  <div class="flex items-center gap-3">
                    <img
                      :src="u.avatar"
                      alt="Avatar"
                      class="w-9 h-9 rounded-xl object-cover border border-slate-700"
                    />
                    <div>
                      <div class="font-bold text-white text-xs flex items-center gap-1.5">
                        <span>{{ u.name }}</span>
                        <span
                          v-if="u.role === 'admin'"
                          class="px-1.5 py-0.2 bg-purple-500/20 text-purple-300 text-[9px] rounded font-mono font-bold"
                        >
                          ADMIN
                        </span>
                      </div>
                      <div class="text-[11px] text-slate-400 font-mono">{{ u.email }}</div>
                      <div class="text-[10px] text-slate-500">{{ u.student_number }}</div>
                    </div>
                  </div>
                </td>

                <!-- Role Selector -->
                <td class="p-4">
                  <select
                    :value="u.role"
                    @change="handleRoleChange(u, $event.target.value)"
                    :disabled="!authStore.isAdmin"
                    class="px-2.5 py-1.5 bg-slate-950 border border-slate-700 rounded-lg text-xs font-semibold focus:outline-none transition-colors"
                    :class="{
                      'text-purple-400 border-purple-500/50': u.role === 'admin',
                      'text-indigo-400 border-indigo-500/50': u.role === 'instructor',
                      'text-emerald-400 border-emerald-500/50': u.role === 'student'
                    }"
                  >
                    <option value="student">🎓 Student</option>
                    <option value="instructor">🧑‍🏫 Instructor</option>
                    <option value="admin">⚡ Administrator</option>
                  </select>
                </td>

                <!-- Date -->
                <td class="p-4 text-slate-300">
                  <div>{{ u.created_at }}</div>
                  <div class="text-[10px] text-slate-500">{{ u.last_login }}</div>
                </td>

                <!-- Status Badge & Toggle -->
                <td class="p-4">
                  <button
                    @click="handleStatusToggle(u)"
                    :disabled="!authStore.isAdmin"
                    class="px-2.5 py-1 rounded-full text-[10px] font-bold flex items-center gap-1.5 transition-all"
                    :class="u.is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="u.is_active ? 'bg-emerald-400' : 'bg-rose-400'"></span>
                    <span>{{ u.is_active ? 'Active' : 'Suspended' }}</span>
                  </button>
                </td>

                <!-- Actions -->
                <td class="p-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button
                      v-if="u.role === 'instructor' && !u.is_verified"
                      @click="verifyInstructor(u)"
                      class="px-2.5 py-1 bg-amber-500/20 text-amber-300 hover:bg-amber-500/30 border border-amber-500/40 rounded-lg text-[10px] font-bold transition-all"
                      title="Verify Instructor"
                    >
                      ✓ Approve
                    </button>
                    <button
                      @click="deleteUser(u)"
                      :disabled="!authStore.isAdmin || u.id === authStore.user?.id"
                      class="px-2.5 py-1 bg-slate-800 hover:bg-rose-900/60 text-slate-400 hover:text-rose-200 rounded-lg text-[10px] font-bold transition-all border border-slate-700"
                      title="Delete User"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import Topography from '@/components/common/Topography.vue';

const authStore = useAuthStore();

const stats = ref(null);
const usersList = ref([]);
const rawUsers = ref([]);
const searchQuery = ref('');
const roleFilter = ref('all');
const statusFilter = ref('all');
const alertMessage = ref('');

async function loadData() {
  try {
    const statsRes = await axios.get('/api/admin/stats');
    stats.value = statsRes.data;

    const usersRes = await axios.get('/api/admin/users');
    rawUsers.value = usersRes.data.users;
    filterUsers();
  } catch (err) {
    console.error('Failed to load admin data:', err);
  }
}

function filterUsers() {
  let list = [...rawUsers.value];

  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase().trim();
    list = list.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q));
  }

  if (roleFilter.value !== 'all') {
    list = list.filter(u => u.role === roleFilter.value);
  }

  if (statusFilter.value !== 'all') {
    const isActive = statusFilter.value === 'active';
    list = list.filter(u => u.is_active === isActive);
  }

  usersList.value = list;
}

async function handleRoleChange(user, newRole) {
  try {
    const res = await axios.patch(`/api/admin/users/${user.id}/role`, { role: newRole });
    user.role = res.data.role;
    alertMessage.value = res.data.message;
    await loadData();
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to update role.');
  }
}

async function handleStatusToggle(user) {
  try {
    const newStatus = !user.is_active;
    const res = await axios.patch(`/api/admin/users/${user.id}/status`, { is_active: newStatus });
    user.is_active = res.data.is_active;
    alertMessage.value = res.data.message;
    await loadData();
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to update user status.');
  }
}

async function verifyInstructor(user) {
  try {
    const res = await axios.patch(`/api/admin/users/${user.id}/verify`);
    user.is_verified = true;
    alertMessage.value = res.data.message;
    await loadData();
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to verify instructor.');
  }
}

async function deleteUser(user) {
  if (!confirm(`Are you sure you want to delete user ${user.name} (${user.email})?`)) return;
  try {
    const res = await axios.delete(`/api/admin/users/${user.id}`);
    alertMessage.value = res.data.message;
    await loadData();
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to delete user.');
  }
}

async function switchToAdmin() {
  await authStore.login('admin@gelme.global', 'password123');
  await loadData();
}

onMounted(() => {
  loadData();
});
</script>
