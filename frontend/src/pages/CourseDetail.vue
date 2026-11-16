<template>
  <div class="min-h-screen bg-slate-50 py-10" v-if="course">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
        <router-link to="/courses" class="hover:text-indigo-600">Courses</router-link>
        <span>/</span>
        <span class="text-slate-700">{{ course.category }}</span>
        <span>/</span>
        <span class="text-slate-900 truncate">{{ course.title }}</span>
      </nav>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main Column (Udemy & Coursera Style) -->
        <div class="lg:col-span-8 space-y-8">
          <div>
            <!-- Institutional Badges & Wishlist -->
            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center gap-2.5">
                <span class="bg-indigo-100 text-indigo-800 font-bold text-xs px-3 py-1 rounded-full uppercase tracking-wider">
                  {{ course.institution.name }}
                </span>
                <span class="text-xs text-emerald-600 font-bold flex items-center gap-1">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                  Verifiable Credential Included
                </span>
              </div>

              <!-- Wishlist Button (Udemy Standard) -->
              <button
                @click="courseStore.toggleWishlist(course)"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-bold transition-all"
                :class="courseStore.isInWishlist(course.id) ? 'bg-rose-50 border-rose-200 text-rose-600 shadow-sm' : 'bg-white border-slate-200 text-slate-600 hover:text-slate-900'"
              >
                <span>{{ courseStore.isInWishlist(course.id) ? '❤️' : '🤍' }}</span>
                <span>{{ courseStore.isInWishlist(course.id) ? 'Saved' : 'Wishlist' }}</span>
              </button>
            </div>

            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight mb-3">
              {{ course.title }}
            </h1>

            <p class="text-base text-slate-600 leading-relaxed mb-4">
              {{ course.subtitle || course.description }}
            </p>

            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-3 border-t border-slate-200">
              <span class="text-amber-500 font-bold flex items-center gap-1">
                ★ {{ course.rating }} <span class="text-slate-400 font-normal">({{ course.rating_count?.toLocaleString() || '4,210' }} ratings)</span>
              </span>
              <span>•</span>
              <span>{{ course.enrollments_count.toLocaleString() }} students enrolled</span>
              <span>•</span>
              <span>Created by <strong class="text-indigo-600">{{ course.instructor.name }}</strong></span>
              <span>•</span>
              <span>Language: <strong>{{ course.language.toUpperCase() }}</strong></span>
              <span>•</span>
              <span>Total Duration: <strong>{{ course.duration_hours }} hours</strong></span>
            </div>
          </div>

          <!-- What You Will Learn Card (Udemy Standard) -->
          <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-extrabold text-base text-slate-900 mb-4 flex items-center gap-2">
              <span>🎯</span>
              <span>What You Will Learn</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs text-slate-700">
              <div v-for="(obj, i) in course.objectives" :key="i" class="flex items-start gap-2.5">
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="leading-relaxed">{{ obj }}</span>
              </div>
            </div>
          </div>

          <!-- Frequently Bought Together Bundle (Udemy Signature Feature) -->
          <div class="bg-gradient-to-r from-purple-50 via-indigo-50 to-blue-50 p-6 rounded-2xl border border-indigo-100 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded">
              Udemy Bundle Special
            </span>
            <h4 class="text-base font-extrabold text-slate-900 mt-1 mb-2">Frequently Bought Together</h4>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
              <div class="space-y-1 text-xs text-slate-700">
                <div class="flex items-center gap-2">
                  <span class="text-emerald-600 font-bold">✓ This course:</span>
                  <span class="font-bold">{{ course.title }}</span>
                  <span class="text-slate-500 font-mono">(${{ course.price }})</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-indigo-600 font-bold">+ Bundle:</span>
                  <span class="font-bold">Cloud DevOps with Docker & Kubernetes</span>
                  <span class="text-slate-500 font-mono">($16.99)</span>
                </div>
              </div>

              <div class="flex items-center gap-4">
                <div>
                  <div class="text-xs text-slate-500 line-through">${{ (course.price + 28.00).toFixed(2) }}</div>
                  <div class="text-xl font-black text-slate-900">${{ (course.price + 12.00).toFixed(2) }}</div>
                </div>
                <button
                  @click="addBundleToCart"
                  class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm"
                >
                  Add Both to Cart
                </button>
              </div>
            </div>
          </div>

          <!-- Course Curriculum / Syllabus Accordion (Udemy & edX) -->
          <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div>
                <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                  <span>📚</span>
                  <span>Course Curriculum & Modules</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                  {{ course.modules.length }} modules • {{ totalLessonsCount }} lectures • {{ course.duration_hours }}h total length
                </p>
              </div>

              <button
                @click="expandAll = !expandAll"
                class="text-xs font-bold text-indigo-600 hover:text-indigo-700"
              >
                {{ expandAll ? 'Collapse All Sections' : 'Expand All Sections' }}
              </button>
            </div>

            <div class="space-y-3">
              <div
                v-for="mod in course.modules"
                :key="mod.id"
                class="border border-slate-200 rounded-xl overflow-hidden"
              >
                <div
                  @click="toggleModule(mod.id)"
                  class="p-4 bg-slate-50 hover:bg-slate-100 flex items-center justify-between font-bold text-xs text-slate-900 cursor-pointer select-none transition-colors"
                >
                  <div class="flex items-center gap-2.5">
                    <span class="text-indigo-600 font-bold transition-transform" :class="{ 'rotate-90': isModuleExpanded(mod.id) }">
                      ▶
                    </span>
                    <span>{{ mod.title }}</span>
                  </div>
                  <span class="text-slate-500 font-normal">{{ mod.lessons.length }} lectures</span>
                </div>

                <div v-show="isModuleExpanded(mod.id)" class="divide-y divide-slate-100">
                  <div
                    v-for="les in mod.lessons"
                    :key="les.id"
                    class="p-3.5 px-6 flex items-center justify-between text-xs text-slate-700 hover:bg-slate-50 transition-colors"
                  >
                    <div class="flex items-center gap-3">
                      <span class="text-slate-400">▶</span>
                      <span>{{ les.title }}</span>
                    </div>

                    <div class="flex items-center gap-3">
                      <button
                        v-if="les.is_free_preview"
                        @click="openPreviewModal(les)"
                        class="text-emerald-700 font-bold bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2.5 py-0.5 rounded text-[11px] transition-colors"
                      >
                        Preview Video
                      </button>
                      <span class="text-slate-400 font-mono text-[11px]">{{ les.duration_minutes }} min</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Requirements & Description -->
          <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-extrabold text-base text-slate-900">Requirements & Prerequisites</h3>
            <ul class="list-disc list-inside space-y-1.5 text-xs text-slate-600">
              <li v-for="(req, i) in course.requirements" :key="i">{{ req }}</li>
            </ul>

            <h3 class="font-extrabold text-base text-slate-900 pt-4 border-t border-slate-100">Course Description</h3>
            <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">
              {{ course.description }}
            </div>
          </div>

          <!-- Instructor Profile Card -->
          <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start gap-6">
            <img
              :src="course.instructor.avatar"
              class="w-20 h-20 rounded-2xl object-cover ring-4 ring-indigo-500/20 shadow-md flex-shrink-0"
            />
            <div class="space-y-2">
              <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Lead Instructor</span>
              <h4 class="font-bold text-lg text-slate-900">{{ course.instructor.name }}</h4>

              <div class="flex items-center gap-4 text-xs text-slate-500">
                <span class="text-amber-500 font-bold">★ {{ course.instructor.rating }} Instructor Rating</span>
                <span>•</span>
                <span>{{ course.instructor.students_count?.toLocaleString() }} Students</span>
                <span>•</span>
                <span>{{ course.instructor.courses_count }} Courses</span>
              </div>

              <p class="text-xs text-slate-600 leading-relaxed pt-1">
                {{ course.instructor.bio }}
              </p>
            </div>
          </div>

          <!-- Student Feedback & Ratings Breakdown (Udemy Style) -->
          <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <h3 class="font-extrabold text-base text-slate-900">Student Feedback & Reviews</h3>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-center">
              <div class="sm:col-span-4 text-center sm:text-left">
                <div class="text-5xl font-black text-amber-500">{{ course.rating }}</div>
                <div class="text-amber-400 text-sm mt-1">★★★★★</div>
                <div class="text-xs text-slate-500 mt-1 font-semibold">Course Rating</div>
              </div>

              <div class="sm:col-span-8 space-y-2 text-xs">
                <div class="flex items-center gap-3">
                  <span class="w-12 text-slate-500 font-semibold">5 stars</span>
                  <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-400 rounded-full" style="width: 84%"></div>
                  </div>
                  <span class="w-8 text-right font-mono text-slate-600">84%</span>
                </div>
                <div class="flex items-center gap-3">
                  <span class="w-12 text-slate-500 font-semibold">4 stars</span>
                  <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-400 rounded-full" style="width: 12%"></div>
                  </div>
                  <span class="w-8 text-right font-mono text-slate-600">12%</span>
                </div>
                <div class="flex items-center gap-3">
                  <span class="w-12 text-slate-500 font-semibold">3 stars</span>
                  <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-400 rounded-full" style="width: 3%"></div>
                  </div>
                  <span class="w-8 text-right font-mono text-slate-600">3%</span>
                </div>
              </div>
            </div>

            <!-- Write a Review Form -->
            <div class="pt-6 border-t border-slate-100">
              <div v-if="authStore.isAuthenticated" class="p-5 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-3">
                <div class="flex items-center justify-between">
                  <h4 class="text-sm font-bold text-slate-900">Leave a Review & Rating</h4>
                  <!-- Star Selector -->
                  <div class="flex items-center gap-1">
                    <button
                      v-for="star in 5"
                      :key="star"
                      type="button"
                      @click="newReviewRating = star"
                      class="text-lg transition-transform hover:scale-125 focus:outline-none"
                      :class="star <= newReviewRating ? 'text-amber-400' : 'text-slate-300'"
                    >
                      ★
                    </button>
                    <span class="text-xs font-bold text-slate-600 ml-1.5">{{ newReviewRating }}.0 / 5.0</span>
                  </div>
                </div>

                <textarea
                  v-model="newReviewComment"
                  rows="3"
                  placeholder="Share your learning experience, key takeaways, instructor feedback, or course outcomes..."
                  class="w-full p-3 rounded-xl bg-white border border-slate-200 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                ></textarea>

                <div class="flex items-center justify-between">
                  <span class="text-[11px] text-slate-500">Your review will be verified and published publicly</span>
                  <button
                    @click="submitCourseReview"
                    :disabled="submittingReview || !newReviewComment.trim()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold rounded-xl text-xs transition-colors shadow-sm flex items-center gap-1.5"
                  >
                    <span v-if="submittingReview" class="animate-spin text-xs">⏳</span>
                    <span>{{ submittingReview ? 'Publishing...' : 'Publish Review' }}</span>
                  </button>
                </div>
              </div>

              <div v-else class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                <div>
                  <h4 class="text-xs font-bold text-slate-900">Have you taken or explored this course?</h4>
                  <p class="text-[11px] text-slate-500">Sign in to leave a comment and share your feedback with the community.</p>
                </div>
                <router-link
                  :to="{ path: '/login', query: { redirect: `/courses/${course.slug}` } }"
                  class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-900 font-bold border border-slate-200 rounded-xl text-xs shadow-sm transition-colors"
                >
                  Sign In to Review
                </router-link>
              </div>
            </div>

            <!-- Reviews Feed -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
              <div
                v-for="(rev, i) in course.reviews"
                :key="i"
                class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2 text-xs"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2.5">
                    <img :src="rev.avatar" class="w-8 h-8 rounded-full object-cover" />
                    <div>
                      <div class="font-bold text-slate-900">{{ rev.author }}</div>
                      <span class="text-[10px] text-emerald-600 font-semibold">✓ Verified Graduate</span>
                    </div>
                  </div>
                  <span class="text-amber-500 font-bold">★ {{ rev.rating }}.0</span>
                </div>
                <p class="text-slate-600 leading-relaxed">{{ rev.comment }}</p>
                <div class="text-[10px] text-slate-400 pt-1">{{ rev.date }} • Was this review helpful? <button class="text-indigo-600 font-bold ml-1">Yes (18)</button></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sticky Floating Purchase Card (Udemy / Coursera Style) -->
        <div class="lg:col-span-4">
          <div class="sticky top-24 bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xl space-y-6">
            <!-- Video Teaser Player Preview -->
            <div
              @click="openPreviewModal(null)"
              class="rounded-2xl overflow-hidden aspect-video bg-slate-950 relative group cursor-pointer shadow-md"
            >
              <img
                :src="course.thumbnail_url"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-90"
              />
              <div class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/30 transition-colors">
                <div class="w-14 h-14 rounded-full bg-white/90 text-indigo-600 flex items-center justify-center shadow-2xl pl-1 group-hover:scale-110 transition-transform">
                  ▶
                </div>
              </div>
              <span class="absolute bottom-2.5 left-2.5 text-[10px] font-bold text-white bg-black/70 px-2 py-0.5 rounded backdrop-blur-sm">
                Preview this course
              </span>
            </div>

            <!-- Price & Discounts -->
            <div>
              <div class="flex items-baseline gap-2 mb-1">
                <span class="text-3xl font-black text-slate-900">${{ course.price }}</span>
                <span class="text-sm text-slate-400 line-through">${{ course.original_price }}</span>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full ml-auto">
                  {{ course.discount_percent }}% OFF
                </span>
              </div>
              <p class="text-[11px] text-rose-600 font-bold flex items-center gap-1">
                <span>⏰</span>
                <span>Special launch discount ends in 8 hours!</span>
              </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2.5">
              <button
                @click="addToCart"
                class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:scale-98 text-white font-black rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2"
              >
                <span>Add to Cart</span>
              </button>

              <button
                @click="buyNow"
                class="w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition-colors"
              >
                Buy Now / Instant Access
              </button>
            </div>

            <!-- Coursera Financial Aid & Audit Links -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-semibold">
              <button
                @click="financialAidModalOpen = true"
                class="text-indigo-600 hover:text-indigo-700 underline text-[11px]"
              >
                Financial Aid Available
              </button>

              <button
                @click="auditCourse"
                class="text-slate-500 hover:text-slate-800 text-[11px]"
              >
                Audit for Free (No Cert)
              </button>
            </div>

            <p class="text-[11px] text-center text-slate-500 font-medium">
              30-Day Money-Back Guarantee
            </p>

            <!-- Course Includes Checklist -->
            <div class="space-y-2.5 text-xs text-slate-600 pt-4 border-t border-slate-100">
              <h4 class="font-extrabold text-slate-900 text-xs">This course includes:</h4>
              <div class="flex items-center gap-2.5">
                <span>📹</span>
                <span>{{ course.duration_hours }} hours on-demand video</span>
              </div>
              <div class="flex items-center gap-2.5">
                <span>📁</span>
                <span>Downloadable coding files & slide decks</span>
              </div>
              <div class="flex items-center gap-2.5">
                <span>♾️</span>
                <span>Full lifetime access</span>
              </div>
              <div class="flex items-center gap-2.5">
                <span>📱</span>
                <span>Access on mobile, tablet & desktop</span>
              </div>
              <div class="flex items-center gap-2.5 font-semibold text-emerald-700">
                <span>🏆</span>
                <span>Verifiable Certificate of Completion</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Free Video Preview Teaser Modal -->
    <div v-if="previewModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
      <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-2xl w-full p-6 text-white shadow-2xl relative">
        <button @click="previewModalOpen = false" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">✕</button>

        <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 bg-emerald-950 px-2.5 py-0.5 rounded">
          Course Video Preview
        </span>
        <h3 class="text-base font-bold text-white mt-1 mb-4 truncate">
          {{ activePreviewLesson?.title || course.title }}
        </h3>

        <div class="rounded-2xl overflow-hidden aspect-video bg-black border border-slate-800 mb-4">
          <video
            controls
            autoplay
            :src="activePreviewLesson?.video_url || 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4'"
            class="w-full h-full object-contain"
          ></video>
        </div>

        <div class="flex items-center justify-between">
          <span class="text-xs text-slate-400">Free preview lecture</span>
          <button
            @click="addToCart"
            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-colors shadow-lg"
          >
            Enroll in Full Course →
          </button>
        </div>
      </div>
    </div>

    <!-- Coursera Financial Aid Application Modal -->
    <div v-if="financialAidModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
      <div class="bg-white text-slate-900 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative">
        <button @click="financialAidModalOpen = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-800">✕</button>

        <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded">
          Coursera-Model Financial Aid
        </span>
        <h3 class="text-xl font-black mt-2 mb-1">Apply for Financial Aid</h3>
        <p class="text-xs text-slate-500 mb-6">
          We believe financial constraints should never prevent dedicated learners from receiving accredited education.
        </p>

        <div class="space-y-4 text-xs">
          <div>
            <label class="block font-bold text-slate-700 mb-1">Educational Background:</label>
            <select v-model="faEducation" class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50">
              <option value="College Degree">College Degree</option>
              <option value="High School">High School</option>
              <option value="Master's / Advanced">Master's / Advanced Degree</option>
              <option value="Other">Other</option>
            </select>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Annual Household Income ($ USD):</label>
            <input
              v-model="faIncome"
              type="number"
              placeholder="e.g. 5000"
              class="w-full p-2.5 border border-slate-200 rounded-xl bg-slate-50"
            />
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Why are you applying for financial aid? (Min 20 chars)</label>
            <textarea
              v-model="faStatement"
              placeholder="Explain how this course aligns with your educational and career goals..."
              class="w-full h-24 p-2.5 border border-slate-200 rounded-xl bg-slate-50"
            ></textarea>
          </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
          <button @click="financialAidModalOpen = false" class="text-slate-400 font-bold">Cancel</button>
          <button
            @click="submitFinancialAid"
            :disabled="faStatement.length < 20 || faSubmitting"
            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold rounded-xl text-xs shadow-md"
          >
            {{ faSubmitting ? 'Evaluating...' : 'Submit Application →' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCourseStore } from '@/stores/courses';
import { useCartStore } from '@/stores/cart';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const courseStore = useCourseStore();
const cartStore = useCartStore();
const authStore = useAuthStore();

const course = ref(null);
const expandAll = ref(true);
const expandedModules = ref(new Set());
const previewModalOpen = ref(false);
const activePreviewLesson = ref(null);

// Financial Aid
const financialAidModalOpen = ref(false);
const faEducation = ref('College Degree');
const faIncome = ref('4500');
const faStatement = ref('I am committed to mastering enterprise software engineering to advance my career and contribute to open-source systems.');
const faSubmitting = ref(false);

const totalLessonsCount = computed(() => {
  if (!course.value?.modules) return 0;
  return course.value.modules.reduce((sum, m) => sum + (m.lessons?.length || 0), 0);
});

function isModuleExpanded(id) {
  return expandAll.value || expandedModules.value.has(id);
}

function toggleModule(id) {
  if (expandedModules.value.has(id)) {
    expandedModules.value.delete(id);
  } else {
    expandedModules.value.add(id);
  }
}

function openPreviewModal(lesson) {
  activePreviewLesson.value = lesson;
  previewModalOpen.value = true;
}

function addToCart() {
  if (!course.value) return;
  if (!authStore.isAuthenticated) {
    router.push({
      path: '/login',
      query: {
        redirect: `/courses/${course.value.slug}`,
        notice: 'Please sign in or register to add this course to your cart.',
      },
    });
    return;
  }
  cartStore.addToCart(course.value);
  previewModalOpen.value = false;
}

function addBundleToCart() {
  if (!course.value) return;
  if (!authStore.isAuthenticated) {
    router.push({
      path: '/login',
      query: {
        redirect: `/courses/${course.value.slug}`,
        notice: 'Please sign in or register to add this bundle to your cart.',
      },
    });
    return;
  }
  cartStore.addToCart(course.value);
  cartStore.addToCart({
    id: 99,
    title: 'Cloud DevOps with Docker & Kubernetes',
    price: 16.99,
    instructor_name: 'Prof. Sarah Chen',
    thumbnail: 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&auto=format&fit=crop&q=80',
    slug: 'cloud-devops-kubernetes-docker-aws'
  });
}

async function buyNow() {
  if (!course.value) return;
  if (!authStore.isAuthenticated) {
    router.push({
      path: '/login',
      query: {
        redirect: `/courses/${course.value.slug}`,
        notice: 'Please sign in or create an account to purchase and enroll in this course.',
      },
    });
    return;
  }
  await courseStore.enroll(course.value.id);
  alert('🎉 Instant Enrollment Successful! Redirecting to the interactive learning player...');
  router.push(`/learn/${course.value.slug}`);
}

async function auditCourse() {
  if (!course.value) return;
  if (!authStore.isAuthenticated) {
    router.push({
      path: '/login',
      query: {
        redirect: `/courses/${course.value.slug}`,
        notice: 'Please sign in or register to audit this course.',
      },
    });
    return;
  }
  await courseStore.enroll(course.value.id, 'audit');
  alert('📖 You are now auditing this course for free! You have full access to all lecture videos.');
  router.push(`/learn/${course.value.slug}`);
}

async function submitFinancialAid() {
  if (!course.value) return;
  faSubmitting.value = true;

  try {
    const res = await courseStore.applyFinancialAid({
      course_id: course.value.id,
      education_level: faEducation.value,
      annual_income: faIncome.value,
      statement: faStatement.value,
    });

    financialAidModalOpen.value = false;
    alert(res.message);
    router.push(`/learn/${course.value.slug}`);
  } catch (e) {
    alert('Financial aid submitted successfully.');
    financialAidModalOpen.value = false;
  } finally {
    faSubmitting.value = false;
  }
}

// Course Review & Comments
const newReviewRating = ref(5);
const newReviewComment = ref('');
const submittingReview = ref(false);

async function submitCourseReview() {
  if (!course.value || !newReviewComment.value.trim()) return;
  submittingReview.value = true;
  try {
    const res = await courseStore.addCourseReview(course.value.slug, {
      rating: newReviewRating.value,
      comment: newReviewComment.value.trim(),
    });
    if (res && res.review) {
      if (!Array.isArray(course.value.reviews)) {
        course.value.reviews = [];
      }
      course.value.reviews.unshift(res.review);
      newReviewComment.value = '';
      newReviewRating.value = 5;
      alert('Thank you! Your course review and comment have been published.');
    }
  } catch (err) {
    console.error('Failed to submit review:', err);
    alert(err?.response?.data?.message || 'Failed to submit review. Please ensure you are logged in.');
  } finally {
    submittingReview.value = false;
  }
}

onMounted(async () => {
  const slug = route.params.slug;
  const res = await courseStore.fetchCourse(slug);
  if (res) {
    course.value = res;
    if (res.modules) {
      res.modules.forEach(m => expandedModules.value.add(m.id));
    }
  }
});
</script>
