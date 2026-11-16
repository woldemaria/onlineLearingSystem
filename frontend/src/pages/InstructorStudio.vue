<template>
  <div class="min-h-screen bg-slate-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-8 border-b border-slate-800 mb-8 gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 bg-indigo-950 px-3 py-1 rounded-full border border-indigo-800">
            Instructor Studio & Creator Suite
          </span>
          <h1 class="text-3xl font-black text-white mt-2">Teach & Earn on GELME</h1>
          <p class="text-xs text-slate-400 mt-1">
            Publish Udemy-style bootcamps, Coursera university degrees, and Online Global Classroom 25-minute live immersion slots.
          </p>
        </div>

        <button
          @click="showCreateModal = true"
          class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2"
        >
          <span>+</span>
          <span>Create New Course or Live Classroom</span>
        </button>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
        <div class="p-6 bg-slate-800/80 border border-slate-700 rounded-2xl">
          <div class="text-xs text-slate-400 font-medium">Total Active Students</div>
          <div class="text-2xl font-black text-white mt-1">{{ stats?.total_students?.toLocaleString() || '289,450' }}</div>
          <div class="text-[11px] text-emerald-400 mt-1">{{ stats?.monthly_growth || '+24.6%' }} this month</div>
        </div>

        <div class="p-6 bg-slate-800/80 border border-slate-700 rounded-2xl">
          <div class="text-xs text-slate-400 font-medium">Total Platform Revenue</div>
          <div class="text-2xl font-black text-emerald-400 mt-1">{{ stats?.total_earnings || '$121,500.00' }}</div>
          <div class="text-[11px] text-slate-400 mt-1">Stripe & Direct Payouts</div>
        </div>

        <div class="p-6 bg-slate-800/80 border border-slate-700 rounded-2xl">
          <div class="text-xs text-slate-400 font-medium">Instructor Rating</div>
          <div class="text-2xl font-black text-amber-400 mt-1">★ {{ stats?.average_rating || 4.94 }}</div>
          <div class="text-[11px] text-slate-400 mt-1">Across 18,400+ student reviews</div>
        </div>

        <div class="p-6 bg-slate-800/80 border border-slate-700 rounded-2xl">
          <div class="text-xs text-slate-400 font-medium">Live Immersion Rooms</div>
          <div class="text-2xl font-black text-white mt-1">{{ stats?.upcoming_live_sessions || 8 }} Active Rooms</div>
          <div class="text-[11px] text-indigo-400 mt-1">Online Global Classroom Hub</div>
        </div>
      </div>

      <!-- Recent Enrollments Feed -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <div class="lg:col-span-2 bg-slate-800/60 border border-slate-700 rounded-2xl p-6 shadow-xl">
          <h3 class="font-extrabold text-base text-white mb-4">Published Courses & Curricula</h3>

          <div class="divide-y divide-slate-700">
            <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <span class="text-[10px] font-bold text-indigo-400 bg-indigo-950 px-2 py-0.5 rounded">ON-DEMAND BOOTCAMP</span>
                <h4 class="font-bold text-sm text-white mt-1">Complete Full-Stack Web Development Bootcamp 2026</h4>
                <p class="text-xs text-slate-400">84,210 enrolled • $14.99 • 3 modules</p>
              </div>
              <div class="flex items-center gap-2">
                <router-link to="/learn/complete-full-stack-web-development-bootcamp" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 rounded-lg text-xs font-semibold">
                  Preview Player
                </router-link>
              </div>
            </div>

            <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded">ONLINE GLOBAL CLASSROOM</span>
                <h4 class="font-bold text-sm text-white mt-1">Conversational Spanish: 25-Minute Live Native Immersion</h4>
                <p class="text-xs text-slate-400">18,200 enrolled • WebRTC Live Hub • 8/12 seats booked</p>
              </div>
              <div class="flex items-center gap-2">
                <router-link to="/classroom/spanish-travel-daily-immersion" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 rounded-lg text-xs font-bold text-slate-950">
                  Launch Live Room
                </router-link>
              </div>
            </div>

            <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <span class="text-[10px] font-bold text-blue-400 bg-blue-950 px-2 py-0.5 rounded">COURSERA SPECIALIZATION</span>
                <h4 class="font-bold text-sm text-white mt-1">Advanced AI & Deep Learning Specialization</h4>
                <p class="text-xs text-slate-400">38,900 enrolled • Stanford / MITx • Graded Quizzes</p>
              </div>
              <div class="flex items-center gap-2">
                <router-link to="/courses/advanced-ai-deep-learning-specialization" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 rounded-lg text-xs font-semibold">
                  Course Page
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Real-Time Enrollment Stream -->
        <div class="bg-slate-800/60 border border-slate-700 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
          <div>
            <h3 class="font-extrabold text-base text-white mb-4">Recent Enrollments</h3>
            <div class="space-y-3">
              <div
                v-for="(enr, idx) in (recentList || [])"
                :key="idx"
                class="p-3 bg-slate-900/60 border border-slate-800 rounded-xl text-xs flex items-center justify-between"
              >
                <div>
                  <div class="font-bold text-white">{{ enr.student }}</div>
                  <div class="text-[11px] text-slate-400 truncate max-w-[170px]">{{ enr.course }}</div>
                  <div class="text-[10px] text-indigo-400 mt-0.5">{{ enr.time }}</div>
                </div>
                <span class="font-mono font-bold text-emerald-400 text-sm">{{ enr.amount }}</span>
              </div>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-700 text-center">
            <span class="text-[11px] text-slate-400">Total 24h Payout: <strong class="text-white">$1,894.20</strong></span>
          </div>
        </div>
      </div>

      <!-- Create Course / Classroom Modal -->
      <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 text-white shadow-2xl relative">
          <button @click="showCreateModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-white">✕</button>

          <h3 class="text-xl font-black mb-1">Create Course or 25-Min Live Room</h3>
          <p class="text-xs text-slate-400 mb-6">Launch your masterclass or schedule high-impact live native immersion slots.</p>

          <div class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-300 mb-1">Course / Session Title:</label>
              <input
                v-model="newTitle"
                type="text"
                placeholder="e.g. Master Vue 3 Architecture & Production Deployment"
                class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-indigo-500"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-300 mb-1">Type / Delivery Format:</label>
              <select
                v-model="newFormat"
                class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none"
              >
                <option value="self_paced">On-Demand Video Course (Udemy Marketplace)</option>
                <option value="cohort_based">University Cohort Specialization (Coursera / edX)</option>
                <option value="live_virtual">Live 25-Min Native Immersion (Online Global Classroom)</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-300 mb-1">Price ($ USD):</label>
              <input
                v-model="newPrice"
                type="number"
                placeholder="19.99"
                class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none"
              />
            </div>
          </div>

          <div class="mt-8 pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
            <button @click="showCreateModal = false" class="px-4 py-2 text-slate-400 hover:text-white text-xs font-bold">
              Cancel
            </button>
            <button
              @click="submitCreate"
              class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-colors shadow-lg"
            >
              Publish to GELME Catalog →
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const stats = ref(null);
const recentList = ref([]);
const showCreateModal = ref(false);
const newTitle = ref('');
const newFormat = ref('self_paced');
const newPrice = ref('19.99');

function submitCreate() {
  if (!newTitle.value.trim()) return;
  alert(`🎉 "${newTitle.value}" has been submitted and published to the GELME Global Catalog!`);
  showCreateModal.value = false;
  newTitle.value = '';
}

onMounted(async () => {
  try {
    const res = await axios.get('/api/instructor/stats');
    stats.value = res.data.stats;
    recentList.value = res.data.recent_enrollments;
  } catch (e) {
    console.error('Failed to load instructor stats:', e);
  }
});
</script>
