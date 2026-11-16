<template>
  <div class="min-h-screen bg-slate-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Authorization Status Alert if not instructor -->
      <div
        v-if="!authStore.isInstructor"
        class="mb-8 p-4 bg-amber-500/10 border border-amber-500/30 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4"
      >
        <div class="flex items-center gap-3">
          <span class="text-2xl">🔐</span>
          <div>
            <h3 class="text-sm font-bold text-amber-300">Instructor Account Required</h3>
            <p class="text-xs text-slate-300">
              You are currently logged in as a learner (<strong class="text-white">{{ authStore.user?.email || 'Guest' }}</strong>). Switch to an instructor profile to upload course videos and publish curricula.
            </p>
          </div>
        </div>
        <button
          @click="switchToInstructor"
          class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black rounded-xl transition-all shadow-md whitespace-nowrap"
        >
          1-Click Switch to Demo Instructor (Prof. Sarah Chen)
        </button>
      </div>

      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-8 border-b border-slate-800 mb-8 gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 bg-indigo-950 px-3 py-1 rounded-full border border-indigo-800">
            Instructor Studio & Creator Suite
          </span>
          <h1 class="text-3xl font-black text-white mt-2">Teach & Earn on GELME</h1>
          <p class="text-xs text-slate-400 mt-1">
            Publish Udemy-style video bootcamps, attach PDF syllabi, and host Online Global Classroom 25-minute live immersion slots.
          </p>
        </div>

        <button
          @click="showCreateModal = true"
          class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2"
        >
          <span>+</span>
          <span>Upload Course & PDF Resources</span>
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

      <!-- Published Courses & Recent Enrollments -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <div class="lg:col-span-2 bg-slate-800/60 border border-slate-700 rounded-2xl p-6 shadow-xl">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-base text-white">Published Courses & Curricula</h3>
            <span class="text-xs text-slate-400 font-mono">{{ coursesList.length }} Total</span>
          </div>

          <div class="divide-y divide-slate-700">
            <div
              v-for="c in coursesList"
              :key="c.id"
              class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
            >
              <div>
                <span class="text-[10px] font-bold text-indigo-400 bg-indigo-950 px-2 py-0.5 rounded uppercase">
                  {{ c.type ? c.type.replace('_', ' ') : 'COURSE' }}
                </span>
                <h4 class="font-bold text-sm text-white mt-1">{{ c.title }}</h4>
                <p class="text-xs text-slate-400">
                  {{ c.enrollments_count?.toLocaleString() || 1 }} enrolled • ${{ c.price }} • {{ c.modules_count || 1 }} module • Active
                </p>
              </div>
              <div class="flex items-center gap-2">
                <router-link
                  :to="'/learn/' + c.slug"
                  class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 rounded-lg text-xs font-semibold transition-colors"
                >
                  Preview Player
                </router-link>
                <router-link
                  :to="'/courses/' + c.slug"
                  class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-xs font-semibold text-white transition-colors"
                >
                  Public Page
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Real-Time Enrollment Stream -->
        <div class="bg-slate-800/60 border border-slate-700 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-4">
              <h3 class="font-extrabold text-base text-white">Live Enrollment Stream</h3>
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
            </div>
            <div class="space-y-3">
              <div
                v-for="(enrollment, idx) in recentList"
                :key="idx"
                class="p-3 bg-slate-900/60 rounded-xl border border-slate-700/60 text-xs"
              >
                <div class="flex items-center justify-between font-bold text-slate-200">
                  <span>{{ enrollment.student }}</span>
                  <span class="text-emerald-400">{{ enrollment.amount }}</span>
                </div>
                <div class="text-[11px] text-slate-400 truncate mt-0.5">{{ enrollment.course }}</div>
                <div class="text-[10px] text-slate-500 mt-1 font-mono">{{ enrollment.time }}</div>
              </div>
            </div>
          </div>

          <div class="mt-6 pt-4 border-t border-slate-700/60 flex items-center justify-between">
            <span class="text-xs font-semibold text-emerald-400">Instant Automated Payouts</span>
            <span class="text-[11px] text-slate-400">Total 24h Payout: <strong class="text-white">$1,894.20</strong></span>
          </div>
        </div>
      </div>

      <!-- Create Course / Upload Video & PDF Modal -->
      <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md overflow-y-auto">
        <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-xl w-full p-6 sm:p-8 text-white shadow-2xl relative my-8">
          <button @click="showCreateModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">✕</button>

          <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-400 bg-indigo-950 px-2.5 py-0.5 rounded-full border border-indigo-800">
            Publish New Masterclass
          </span>
          <h3 class="text-xl font-black mt-2 mb-1">Upload Course Video & PDF Study Materials</h3>
          <p class="text-xs text-slate-400 mb-6">Create your curriculum, upload your HD video lecture, and attach downloadable PDF study guides.</p>

          <!-- Upload Status / Error Banner -->
          <div v-if="uploadError" class="mb-4 p-3 bg-rose-500/20 border border-rose-500 text-rose-300 text-xs rounded-xl flex items-center gap-2">
            <span>⚠️</span>
            <span>{{ uploadError }}</span>
          </div>

          <div class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-300 mb-1">Course Title *</label>
              <input
                v-model="newTitle"
                type="text"
                required
                placeholder="e.g. Docker & Kubernetes Production Cloud Engineering"
                class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none focus:ring-1 focus:ring-indigo-500"
              />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block font-bold text-slate-300 mb-1">Type / Delivery Format</label>
                <select
                  v-model="newFormat"
                  class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none"
                >
                  <option value="self_paced">On-Demand Video (Udemy Style)</option>
                  <option value="cohort_based">Cohort Degree (Coursera / edX)</option>
                  <option value="live_virtual">Live 25-Min Native Immersion</option>
                </select>
              </div>

              <div>
                <label class="block font-bold text-slate-300 mb-1">Price ($ USD) *</label>
                <input
                  v-model="newPrice"
                  type="number"
                  step="0.01"
                  placeholder="19.99"
                  class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none"
                />
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-300 mb-1">Description / Curriculum Overview</label>
              <textarea
                v-model="newDescription"
                rows="2"
                placeholder="Key concepts, architecture patterns, and hands-on projects covered..."
                class="w-full p-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none"
              ></textarea>
            </div>

            <!-- Video Upload Dropzone -->
            <div class="p-3.5 bg-slate-800/80 rounded-2xl border border-slate-700">
              <label class="block font-bold text-slate-200 mb-1 flex items-center justify-between">
                <span>🎥 Course Lesson Video (.mp4, .webm, .mov)</span>
                <span v-if="videoUploadedUrl" class="text-emerald-400 text-[10px] font-bold">✓ Ready</span>
              </label>

              <div class="flex items-center gap-3 mt-2">
                <input
                  type="file"
                  accept="video/mp4,video/webm,video/quicktime,video/x-m4v"
                  @change="handleVideoFile"
                  class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer"
                />
              </div>

              <!-- Uploading indicator -->
              <div v-if="videoUploading" class="mt-2 text-indigo-400 text-[11px] flex items-center gap-2">
                <span class="animate-spin">⏳</span>
                <span>Uploading video file to secure media storage...</span>
              </div>

              <div v-if="videoUploadedUrl" class="mt-2 p-2 bg-slate-900 rounded-lg text-[11px] text-slate-300 flex items-center justify-between">
                <span class="truncate font-mono text-emerald-400">{{ videoFileName || 'Uploaded Video' }} ({{ videoFileSize }})</span>
                <a :href="videoUploadedUrl" target="_blank" class="text-indigo-400 hover:underline flex-shrink-0">Preview</a>
              </div>
            </div>

            <!-- PDF Material Upload Dropzone -->
            <div class="p-3.5 bg-slate-800/80 rounded-2xl border border-slate-700">
              <label class="block font-bold text-slate-200 mb-1 flex items-center justify-between">
                <span>📄 Lesson PDF Guide / Study Slides (.pdf)</span>
                <span v-if="pdfUploadedUrl" class="text-emerald-400 text-[10px] font-bold">✓ Ready</span>
              </label>

              <div class="flex items-center gap-3 mt-2">
                <input
                  type="file"
                  accept="application/pdf"
                  @change="handlePdfFile"
                  class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 cursor-pointer"
                />
              </div>

              <!-- Uploading indicator -->
              <div v-if="pdfUploading" class="mt-2 text-emerald-400 text-[11px] flex items-center gap-2">
                <span class="animate-spin">⏳</span>
                <span>Uploading PDF document...</span>
              </div>

              <div v-if="pdfUploadedUrl" class="mt-2 p-2 bg-slate-900 rounded-lg text-[11px] text-slate-300 flex items-center justify-between">
                <span class="truncate font-mono text-emerald-400">{{ pdfFileName || 'Uploaded PDF' }} ({{ pdfFileSize }})</span>
                <a :href="pdfUploadedUrl" target="_blank" class="text-indigo-400 hover:underline flex-shrink-0">View PDF</a>
              </div>
            </div>
          </div>

          <div class="mt-8 pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
            <button
              @click="showCreateModal = false"
              class="px-4 py-2 text-slate-400 hover:text-white text-xs font-bold"
            >
              Cancel
            </button>
            <button
              @click="submitCreate"
              :disabled="publishing || videoUploading || pdfUploading"
              class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-colors shadow-lg disabled:opacity-50 flex items-center gap-2"
            >
              <span v-if="publishing" class="animate-spin">⏳</span>
              <span>{{ publishing ? 'Publishing Course...' : 'Publish to GELME Catalog →' }}</span>
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
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

const stats = ref(null);
const recentList = ref([]);
const coursesList = ref([]);
const showCreateModal = ref(false);

// Form fields
const newTitle = ref('');
const newFormat = ref('self_paced');
const newPrice = ref('19.99');
const newDescription = ref('');

// Video state
const videoUploading = ref(false);
const videoUploadedUrl = ref('');
const videoFileName = ref('');
const videoFileSize = ref('');

// PDF state
const pdfUploading = ref(false);
const pdfUploadedUrl = ref('');
const pdfFileName = ref('');
const pdfFileSize = ref('');

const publishing = ref(false);
const uploadError = ref('');

async function handleVideoFile(e) {
  const file = e.target.files[0];
  if (!file) return;

  videoUploading.value = true;
  uploadError.value = '';
  try {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', 'video');

    const res = await axios.post('/api/instructor/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });

    videoUploadedUrl.value = res.data.url;
    videoFileName.value = res.data.file_name;
    videoFileSize.value = res.data.size_formatted;
  } catch (err) {
    uploadError.value = err.response?.data?.message || 'Failed to upload video file.';
  } finally {
    videoUploading.value = false;
  }
}

async function handlePdfFile(e) {
  const file = e.target.files[0];
  if (!file) return;

  pdfUploading.value = true;
  uploadError.value = '';
  try {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', 'document');

    const res = await axios.post('/api/instructor/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });

    pdfUploadedUrl.value = res.data.url;
    pdfFileName.value = res.data.file_name;
    pdfFileSize.value = res.data.size_formatted;
  } catch (err) {
    uploadError.value = err.response?.data?.message || 'Failed to upload PDF document.';
  } finally {
    pdfUploading.value = false;
  }
}

async function submitCreate() {
  if (!newTitle.value.trim()) {
    uploadError.value = 'Please provide a course title.';
    return;
  }

  publishing.value = true;
  uploadError.value = '';

  try {
    const payload = {
      title: newTitle.value.trim(),
      type: newFormat.value,
      price: parseFloat(newPrice.value) || 0,
      description: newDescription.value.trim(),
      video_url: videoUploadedUrl.value || 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
      pdf_url: pdfUploadedUrl.value || null,
      pdf_name: pdfFileName.value || 'Course_Guide.pdf',
    };

    const res = await axios.post('/api/instructor/courses', payload);
    alert(`🎉 "${res.data.course.title}" published successfully to GELME!`);

    showCreateModal.value = false;
    newTitle.value = '';
    newDescription.value = '';
    videoUploadedUrl.value = '';
    pdfUploadedUrl.value = '';

    await loadCourses();
  } catch (err) {
    uploadError.value = err.response?.data?.message || 'Failed to publish course.';
  } finally {
    publishing.value = false;
  }
}

async function loadCourses() {
  try {
    const res = await axios.get('/api/instructor/courses');
    coursesList.value = res.data.courses;
  } catch (err) {
    console.error('Failed to load instructor courses:', err);
  }
}

async function switchToInstructor() {
  await authStore.login('sarah.chen@gelme.global', 'password123');
  await loadCourses();
}

onMounted(async () => {
  try {
    const res = await axios.get('/api/instructor/stats');
    stats.value = res.data.stats;
    recentList.value = res.data.recent_enrollments;
  } catch (e) {
    console.error('Failed to load instructor stats:', e);
  }

  await loadCourses();
});
</script>
