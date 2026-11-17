<template>
  <div class="min-h-screen bg-slate-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Authorization Status Alert if not instructor -->
      <div
        v-if="!authStore.isInstructor"
        class="mb-8 p-5 bg-gradient-to-r from-amber-500/10 via-slate-800 to-indigo-950/30 border border-amber-500/30 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4"
      >
        <div class="flex items-center gap-3.5">
          <span class="text-3xl">🧑‍🏫</span>
          <div>
            <h3 class="text-sm font-bold text-amber-300">
              {{ authStore.isAuthenticated ? 'Instructor Verification Required' : 'Instructor Sign In Required' }}
            </h3>
            <p class="text-xs text-slate-300 mt-0.5">
              <template v-if="authStore.isAuthenticated">
                Signed in as <strong class="text-white">{{ authStore.user?.name }}</strong> ({{ authStore.user?.email }}). Activate your verified instructor status below to upload video lectures, attach PDF syllabi, and receive revenue payouts.
              </template>
              <template v-else>
                You are currently viewing as a guest. Sign in with an instructor profile or switch to a demo educator account to create courses.
              </template>
            </p>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
          <button
            v-if="authStore.isAuthenticated"
            @click="activateInstructor"
            :disabled="activating"
            class="px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 text-xs font-black rounded-xl transition-all shadow-md flex items-center gap-1.5 whitespace-nowrap"
          >
            <span v-if="activating" class="animate-spin">⏳</span>
            <span>{{ activating ? 'Activating...' : '✨ 1-Click Upgrade to Certified Instructor' }}</span>
          </button>
          <router-link
            v-else
            to="/login?redirect=/instructor"
            class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-black rounded-xl transition-all shadow-md whitespace-nowrap"
          >
            Sign In / Join as Instructor →
          </router-link>

          <button
            @click="switchToInstructor"
            class="px-3.5 py-2.5 bg-slate-800 hover:bg-slate-700 text-amber-300 text-xs font-bold rounded-xl border border-amber-500/30 transition-all whitespace-nowrap"
          >
            Demo: Prof. Sarah Chen
          </button>
        </div>
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
          @click="handleOpenCreateModal"
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
        <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-3xl w-full p-6 sm:p-8 text-white shadow-2xl relative my-8 max-h-[90vh] flex flex-col">
          <button @click="showCreateModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">✕</button>

          <div class="flex-shrink-0">
            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-400 bg-indigo-950 px-2.5 py-0.5 rounded-full border border-indigo-800">
              Publish New Masterclass
            </span>
            <h3 class="text-xl font-black mt-2 mb-1">Multi-Lesson Course & Syllabus Studio</h3>
            <p class="text-xs text-slate-400 mb-4">Structure your multi-module curriculum, upload HD video lectures, and attach downloadable PDF study guides.</p>

            <!-- Upload Status / Error Banner -->
            <div v-if="uploadError" class="mb-4 p-3 bg-rose-500/20 border border-rose-500 text-rose-300 text-xs rounded-xl flex items-center gap-2">
              <span>⚠️</span>
              <span>{{ uploadError }}</span>
            </div>
          </div>

          <div class="space-y-4 text-xs overflow-y-auto pr-1 flex-1">
            <div>
              <label class="block font-bold text-slate-300 mb-1">Course Title *</label>
              <input
                v-model="newTitle"
                type="text"
                required
                placeholder="e.g. Production Cloud Architecture & Microservices"
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

            <!-- Curriculum Builder Mode Switcher -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-800">
              <span class="font-bold text-sm text-white">Course Curriculum & Syllabus</span>
              <div class="flex items-center gap-2 bg-slate-800 p-1 rounded-xl border border-slate-700">
                <button
                  type="button"
                  @click="curriculumMode = 'multi'"
                  class="px-3 py-1 rounded-lg text-xs font-bold transition-all"
                  :class="curriculumMode === 'multi' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'"
                >
                  Multi-Lesson Syllabus
                </button>
                <button
                  type="button"
                  @click="curriculumMode = 'quick'"
                  class="px-3 py-1 rounded-lg text-xs font-bold transition-all"
                  :class="curriculumMode === 'quick' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'"
                >
                  Quick Single Upload
                </button>
              </div>
            </div>

            <!-- Multi-Lesson Syllabus Builder View -->
            <div v-if="curriculumMode === 'multi'" class="space-y-4">
              <div
                v-for="(mod, mIdx) in modules"
                :key="mIdx"
                class="p-4 bg-slate-800/60 rounded-2xl border border-slate-700 space-y-3"
              >
                <div class="flex items-center justify-between gap-3">
                  <div class="flex-1 flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-indigo-950 text-indigo-400 text-[10px] font-mono font-bold">
                      Module {{ mIdx + 1 }}
                    </span>
                    <input
                      v-model="mod.title"
                      type="text"
                      class="flex-1 p-1.5 bg-slate-900 border border-slate-700 rounded-lg text-xs text-white font-bold"
                      placeholder="e.g. Module 1: System Foundations"
                    />
                  </div>
                  <button
                    v-if="modules.length > 1"
                    type="button"
                    @click="removeModule(mIdx)"
                    class="text-rose-400 hover:text-rose-300 text-xs px-2 py-1 bg-rose-950/50 rounded-lg border border-rose-800"
                  >
                    Delete Module
                  </button>
                </div>

                <!-- Lessons in Module -->
                <div class="pl-3 border-l-2 border-indigo-500/40 space-y-3">
                  <div
                    v-for="(les, lIdx) in mod.lessons"
                    :key="lIdx"
                    class="p-3 bg-slate-900/90 rounded-xl border border-slate-800 space-y-2.5"
                  >
                    <div class="flex items-center justify-between gap-2">
                      <div class="flex-1 flex items-center gap-2">
                        <span class="text-[11px] font-mono text-slate-400 font-bold">
                          {{ mIdx + 1 }}.{{ lIdx + 1 }}
                        </span>
                        <input
                          v-model="les.title"
                          type="text"
                          class="flex-1 p-1.5 bg-slate-800 border border-slate-700 rounded-lg text-xs text-white"
                          placeholder="Lesson Title, e.g. Architecture Walkthrough"
                        />
                        <div class="flex items-center gap-1 text-[11px] text-slate-400 flex-shrink-0">
                          <input
                            v-model.number="les.duration_minutes"
                            type="number"
                            min="1"
                            class="w-14 p-1.5 bg-slate-800 border border-slate-700 rounded-lg text-xs text-white text-center"
                          />
                          <span>min</span>
                        </div>
                      </div>
                      <button
                        v-if="mod.lessons.length > 1"
                        type="button"
                        @click="removeLesson(mIdx, lIdx)"
                        class="text-slate-500 hover:text-rose-400 text-xs px-1.5"
                      >
                        ✕
                      </button>
                    </div>

                    <!-- Uploads for this lesson: Video & PDF -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                      <!-- Lesson Video Upload -->
                      <div class="p-2 bg-slate-950/60 rounded-lg border border-slate-800">
                        <div class="flex items-center justify-between mb-1">
                          <span class="text-indigo-300 font-bold">🎥 Video Lecture</span>
                          <span v-if="les.video_url" class="text-emerald-400 text-[10px]">✓ Loaded</span>
                        </div>
                        <input
                          type="file"
                          accept="video/mp4,video/webm,video/quicktime"
                          @change="e => uploadLessonMedia(e, mIdx, lIdx, 'video')"
                          class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:bg-indigo-600 file:text-white cursor-pointer"
                        />
                        <div v-if="les.video_name" class="mt-1 truncate font-mono text-[10px] text-slate-400">
                          {{ les.video_name }}
                        </div>
                      </div>

                      <!-- Lesson Study Material Upload (PDF, Slides, Code, Notes) -->
                      <div class="p-2 bg-slate-950/60 rounded-lg border border-slate-800">
                        <div class="flex items-center justify-between mb-1">
                          <span class="text-emerald-300 font-bold">📄 Study Material / Slides / Code</span>
                          <span v-if="les.pdf_url" class="text-emerald-400 text-[10px]">✓ Loaded</span>
                        </div>
                        <input
                          type="file"
                          accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.tar,.gz,.png,.jpg,.jpeg,.mp3,.md"
                          @change="e => uploadLessonMedia(e, mIdx, lIdx, 'document')"
                          class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:bg-emerald-600 file:text-white cursor-pointer"
                        />
                        <div v-if="les.pdf_name" class="mt-1 truncate font-mono text-[10px] text-slate-400">
                          {{ les.pdf_name }}
                        </div>
                      </div>
                    </div>
                  </div>

                  <button
                    type="button"
                    @click="addLesson(mIdx)"
                    class="w-full py-1.5 border border-dashed border-indigo-500/40 rounded-xl text-indigo-400 hover:bg-indigo-950/40 text-xs font-bold transition-all"
                  >
                    + Add Lesson to Module {{ mIdx + 1 }}
                  </button>
                </div>
              </div>

              <button
                type="button"
                @click="addModule"
                class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold border border-slate-700 transition-all flex items-center justify-center gap-2"
              >
                <span>➕</span>
                <span>Add Another Curriculum Module</span>
              </button>
            </div>

            <!-- Quick Single Upload View (Legacy fallback) -->
            <div v-else class="space-y-4">
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

              <!-- Study Material Upload Dropzone -->
              <div class="p-3.5 bg-slate-800/80 rounded-2xl border border-slate-700">
                <label class="block font-bold text-slate-200 mb-1 flex items-center justify-between">
                  <span>📄 Lesson Materials, Slides, Code or Notes (.pdf, .pptx, .docx, .zip)</span>
                  <span v-if="pdfUploadedUrl" class="text-emerald-400 text-[10px] font-bold">✓ Ready</span>
                </label>

                <div class="flex items-center gap-3 mt-2">
                  <input
                    type="file"
                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.tar,.gz,.png,.jpg,.jpeg,.mp3,.md"
                    @change="handlePdfFile"
                    class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 cursor-pointer"
                  />
                </div>

                <!-- Uploading indicator -->
                <div v-if="pdfUploading" class="mt-2 text-emerald-400 text-[11px] flex items-center gap-2">
                  <span class="animate-spin">⏳</span>
                  <span>Uploading study material to secure storage...</span>
                </div>

                <div v-if="pdfUploadedUrl" class="mt-2 p-2 bg-slate-900 rounded-lg text-[11px] text-slate-300 flex items-center justify-between">
                  <span class="truncate font-mono text-emerald-400">{{ pdfFileName || 'Uploaded PDF' }} ({{ pdfFileSize }})</span>
                  <a :href="pdfUploadedUrl" target="_blank" class="text-indigo-400 hover:underline flex-shrink-0">View PDF</a>
                </div>
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
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
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
const curriculumMode = ref('multi');

// Multi-lesson syllabus state
const modules = ref([
  {
    title: 'Module 1: Foundations & Architecture',
    lessons: [
      {
        title: '1.1 System Architecture Overview',
        duration_minutes: 20,
        video_url: '',
        video_name: '',
        pdf_url: '',
        pdf_name: '',
      },
      {
        title: '1.2 Hands-on Implementation Lab',
        duration_minutes: 35,
        video_url: '',
        video_name: '',
        pdf_url: '',
        pdf_name: '',
      },
    ],
  },
]);

function addModule() {
  const nextNum = modules.value.length + 1;
  modules.value.push({
    title: `Module ${nextNum}: Advanced Mastery`,
    lessons: [
      {
        title: `${nextNum}.1 Deep Dive Lecture`,
        duration_minutes: 25,
        video_url: '',
        video_name: '',
        pdf_url: '',
        pdf_name: '',
      },
    ],
  });
}

function removeModule(mIdx) {
  if (modules.value.length > 1) {
    modules.value.splice(mIdx, 1);
  }
}

function addLesson(mIdx) {
  const nextNum = modules.value[mIdx].lessons.length + 1;
  modules.value[mIdx].lessons.push({
    title: `Lesson ${mIdx + 1}.${nextNum}`,
    duration_minutes: 20,
    video_url: '',
    video_name: '',
    pdf_url: '',
    pdf_name: '',
  });
}

function removeLesson(mIdx, lIdx) {
  if (modules.value[mIdx].lessons.length > 1) {
    modules.value[mIdx].lessons.splice(lIdx, 1);
  }
}

function getAuthHeaders(extra = {}) {
  const token = authStore.token || localStorage.getItem('gelme_auth_token');
  const headers = { ...extra };
  if (token) {
    headers['Authorization'] = `Bearer ${token}`;
  }
  return headers;
}

async function uploadLessonMedia(e, mIdx, lIdx, type) {
  const file = e.target.files[0];
  if (!file) return;

  uploadError.value = '';
  try {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', type);

    const res = await axios.post('/api/instructor/upload', formData, {
      headers: getAuthHeaders({ 'Content-Type': 'multipart/form-data' }),
    });

    const targetLesson = modules.value[mIdx].lessons[lIdx];
    if (type === 'video') {
      targetLesson.video_url = res.data.url;
      targetLesson.video_name = res.data.file_name;
    } else {
      targetLesson.pdf_url = res.data.url;
      targetLesson.pdf_name = res.data.file_name;
    }
  } catch (err) {
    uploadError.value = err.response?.data?.message || `Failed to upload ${type}.`;
  }
}

// Single / Quick upload state
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
const activating = ref(false);
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
      headers: getAuthHeaders({ 'Content-Type': 'multipart/form-data' }),
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
      headers: getAuthHeaders({ 'Content-Type': 'multipart/form-data' }),
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
    };

    if (curriculumMode.value === 'multi') {
      payload.modules = modules.value.map(m => ({
        title: m.title,
        lessons: m.lessons.map(l => ({
          title: l.title,
          duration_minutes: l.duration_minutes || 20,
          video_url: l.video_url || null,
          pdf_url: l.pdf_url || null,
          pdf_name: l.pdf_name || 'Study Material.pdf',
        })),
      }));
      // First video as preview video if available
      const firstVid = payload.modules[0]?.lessons[0]?.video_url;
      if (firstVid) payload.video_url = firstVid;
    } else {
      payload.video_url = videoUploadedUrl.value || 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4';
      payload.pdf_url = pdfUploadedUrl.value || null;
      payload.pdf_name = pdfFileName.value || 'Course_Guide.pdf';
    }

    const res = await axios.post('/api/instructor/courses', payload, {
      headers: getAuthHeaders(),
    });
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
    const res = await axios.get('/api/instructor/courses', {
      headers: getAuthHeaders(),
    });
    coursesList.value = res.data.courses;
  } catch (err) {
    console.error('Failed to load instructor courses:', err);
  }
}

async function activateInstructor() {
  activating.value = true;
  try {
    const res = await axios.post('/api/instructor/activate', {}, {
      headers: getAuthHeaders(),
    });
    if (res.data?.user) {
      authStore.setSession(res.data.user, authStore.token);
    } else if (authStore.user) {
      authStore.user.role = 'instructor';
      localStorage.setItem('gelme_user', JSON.stringify(authStore.user));
    }
    alert(`🎉 Success! Your account is now verified as a Certified Instructor.`);
    await loadCourses();
  } catch (err) {
    alert('Failed to activate instructor profile: ' + (err.response?.data?.message || err.message));
  } finally {
    activating.value = false;
  }
}

async function switchToInstructor() {
  await authStore.login('sarah.chen@gelme.global', 'password123');
  await loadCourses();
}

function handleOpenCreateModal() {
  if (!authStore.isAuthenticated) {
    router.push({
      path: '/login',
      query: {
        redirect: '/instructor',
        notice: 'Please sign in or register with an instructor account to upload courses.'
      }
    });
    return;
  }
  if (!authStore.isInstructor) {
    if (confirm('An instructor profile is required to upload course videos and publish syllabi. Would you like to activate your instructor status now?')) {
      activateInstructor();
    }
    return;
  }
  showCreateModal.value = true;
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
