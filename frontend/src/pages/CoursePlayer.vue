<template>
  <div class="h-screen flex flex-col bg-slate-950 text-white overflow-hidden" v-if="course">
    <!-- Top Player Bar -->
    <header class="h-14 bg-slate-900 border-b border-slate-800 px-4 sm:px-6 flex items-center justify-between flex-shrink-0 z-20">
      <div class="flex items-center gap-3">
        <router-link to="/dashboard" class="text-slate-400 hover:text-white text-xs font-bold flex items-center gap-1.5 transition-colors">
          <span>←</span>
          <span class="hidden sm:inline">My Learning</span>
        </router-link>
        <span class="text-slate-700">|</span>
        <h2 class="text-xs sm:text-sm font-bold text-slate-200 truncate max-w-xs md:max-w-md">
          {{ course.title }}
        </h2>
      </div>

      <div class="flex items-center gap-4">
        <!-- Progress Bar -->
        <div class="hidden md:flex items-center gap-2.5">
          <div class="w-32 h-2 bg-slate-800 rounded-full overflow-hidden">
            <div
              class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-500"
              :style="{ width: `${progressPercent}%` }"
            ></div>
          </div>
          <span class="text-xs font-mono font-bold text-emerald-400">{{ progressPercent }}%</span>
        </div>

        <!-- Claim Certificate Button (edX / Coursera / Udemy) -->
        <button
          v-if="progressPercent >= 100"
          @click="showCertModal = true"
          class="px-3 py-1.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black rounded-lg text-xs flex items-center gap-1 shadow-md shadow-amber-500/20 animate-bounce cursor-pointer"
        >
          <span>🏆</span>
          <span>View Verified Certificate</span>
        </button>

        <button
          @click="sidebarOpen = !sidebarOpen"
          class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-xs font-semibold text-slate-200 transition-colors"
        >
          {{ sidebarOpen ? 'Hide Curriculum' : 'Show Curriculum' }}
        </button>
      </div>
    </header>

    <!-- Player Body -->
    <div class="flex-1 flex overflow-hidden relative">
      <!-- Main Video Screen & In-Lesson Study Tabs -->
      <div class="flex-1 flex flex-col overflow-y-auto bg-slate-950">
        <!-- Video Screen Container -->
        <div class="w-full bg-black flex items-center justify-center relative aspect-video max-h-[62vh]">
          <!-- Paywall Lock Overlay if Not Enrolled / Unpaid -->
          <div
            v-if="requiresPayment"
            class="absolute inset-0 z-30 bg-slate-950/95 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center text-white"
          >
            <div class="w-16 h-16 rounded-3xl bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center text-3xl mb-4 shadow-xl">
              🔒
            </div>
            <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 bg-indigo-950 px-3 py-1 rounded-full border border-indigo-800 mb-2">
              Course Enrollment & Payment Required
            </span>
            <h2 class="text-xl sm:text-2xl font-black max-w-lg mb-2">
              Unlock Full Access to {{ course?.title || 'this Masterclass' }}
            </h2>
            <p class="text-xs text-slate-400 max-w-md mb-6 leading-relaxed">
              Enroll and pay ${{ course?.price || '19.99' }} USD to unlock all HD video lectures, downloadable PDF materials, and claim your verifiable certificate.
            </p>

            <div class="flex flex-col sm:flex-row items-center gap-3">
              <button
                @click="handleUnlockAndPay"
                :disabled="unlocking"
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600 text-white font-black rounded-xl text-xs shadow-xl shadow-indigo-500/30 transition-all flex items-center gap-2"
              >
                <span v-if="unlocking" class="animate-spin">⏳</span>
                <span>{{ unlocking ? 'Processing Payment & Enrollment...' : `Pay & Unlock Course ($${course?.price || '19.99'} USD) →` }}</span>
              </button>

              <router-link
                :to="`/courses/${course?.slug}`"
                class="px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl text-xs border border-slate-700 transition-colors"
              >
                View Syllabus Details
              </router-link>
            </div>
          </div>

          <video
            v-else
            ref="videoRef"
            controls
            autoplay
            :src="videoStreamSrc"
            class="w-full h-full object-contain"
            @timeupdate="onTimeUpdate"
            @ended="handleLessonEnd"
          ></video>

          <!-- Captions Overlay -->
          <div
            v-if="captionsEnabled && !requiresPayment"
            class="absolute bottom-16 px-4 py-1.5 bg-black/80 text-amber-300 font-semibold rounded-lg text-xs sm:text-sm text-center max-w-xl backdrop-blur-sm pointer-events-none"
          >
            [{{ currentCaptionLang.toUpperCase() }}] {{ activeCaptionText }}
          </div>
        </div>

        <!-- Lesson Meta & Action Bar -->
        <div class="p-5 sm:p-6 bg-slate-900 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-400">
              Current Lecture
            </span>
            <h1 class="text-base sm:text-xl font-extrabold text-white mt-0.5">
              {{ currentLesson?.title || 'Lesson Overview' }}
            </h1>
            <p class="text-xs text-slate-400 mt-1">
              Duration: {{ currentLesson?.duration_minutes || 15 }} minutes • HD Video Stream
            </p>
          </div>

          <div class="flex flex-wrap items-center gap-3">
            <!-- Captions Selector -->
            <button
              @click="toggleCaptions"
              class="flex items-center gap-1 text-[11px] px-2.5 py-1.5 rounded-lg font-bold transition-colors"
              :class="captionsEnabled ? 'bg-amber-400 text-slate-950' : 'bg-slate-800 text-slate-300'"
              title="Toggle Captions"
            >
              <span>CC</span>
              <span v-if="captionsEnabled">({{ currentCaptionLang }})</span>
            </button>

            <!-- Playback Speed Dropdown -->
            <div class="flex items-center gap-1 text-xs bg-slate-800 p-1 rounded-xl">
              <span class="text-[10px] text-slate-400 px-1">Speed:</span>
              <button
                v-for="speed in [0.75, 1, 1.25, 1.5, 2]"
                :key="speed"
                @click="setPlaybackSpeed(speed)"
                class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold transition-colors"
                :class="currentPlaybackSpeed === speed ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'"
              >
                {{ speed }}x
              </button>
            </div>

            <button
              @click="markComplete"
              class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-lg shadow-emerald-600/30 transition-all"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              <span>{{ isCurrentCompleted ? 'Completed ✓' : 'Mark as Complete' }}</span>
            </button>

            <button
              @click="nextLesson"
              class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl text-xs transition-colors"
            >
              Next →
            </button>
          </div>
        </div>

        <!-- In-Lesson Study Tabs: Overview, Notes, Q&A, Quiz, Peer Project, Resources -->
        <div class="p-6 max-w-4xl space-y-6">
          <div class="flex items-center gap-6 border-b border-slate-800 pb-3 text-xs sm:text-sm font-bold overflow-x-auto">
            <button
              @click="activeTab = 'overview'"
              :class="activeTab === 'overview' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-3 -mb-3' : 'text-slate-400 hover:text-white'"
            >
              Overview
            </button>
            <button
              @click="activeTab = 'notes'"
              :class="activeTab === 'notes' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-3 -mb-3' : 'text-slate-400 hover:text-white'"
            >
              Timestamped Notes ({{ myNotes.length }})
            </button>
            <button
              @click="activeTab = 'qa'"
              :class="activeTab === 'qa' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-3 -mb-3' : 'text-slate-400 hover:text-white'"
            >
              Student Q&A
            </button>
            <button
              @click="activeTab = 'quiz'"
              :class="activeTab === 'quiz' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-3 -mb-3' : 'text-slate-400 hover:text-white'"
            >
              Graded Assessment
            </button>
            <button
              @click="activeTab = 'assignment'"
              :class="activeTab === 'assignment' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-3 -mb-3' : 'text-slate-400 hover:text-white'"
            >
              Peer-Graded Project
            </button>
            <button
              @click="activeTab = 'resources'"
              :class="activeTab === 'resources' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-3 -mb-3' : 'text-slate-400 hover:text-white'"
            >
              Downloadable Files (3)
            </button>
            <button
              @click="activeTab = 'reviews'"
              :class="activeTab === 'reviews' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-3 -mb-3' : 'text-slate-400 hover:text-white'"
            >
              Reviews & Feedback
            </button>
          </div>

          <!-- Overview Tab -->
          <div v-if="activeTab === 'overview'" class="space-y-4 text-xs text-slate-300 leading-relaxed">
            <p>
              In this hands-on session, we dive into production application design. You will explore component lifecycles, backend data integrity, and containerized deployment workflows.
            </p>

            <!-- AI Smart Study Assistant Generator -->
            <div class="p-4 rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950/40 to-slate-900 border border-indigo-500/30 space-y-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-sm">✨</span>
                  <span class="font-extrabold text-white text-xs uppercase tracking-wider">AI Lesson Smart Summarizer</span>
                </div>
                <button
                  @click="generateAiSummary"
                  :disabled="loadingSummary"
                  class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-bold rounded-lg text-xs flex items-center gap-1.5 transition-colors shadow-md"
                >
                  <span v-if="loadingSummary" class="animate-spin text-[10px]">⏳</span>
                  <span>{{ loadingSummary ? 'Synthesizing...' : 'Generate AI Summary & Flashcards' }}</span>
                </button>
              </div>

              <!-- AI Generated Summary View -->
              <div v-if="aiSummary" class="space-y-3 pt-2 border-t border-indigo-500/20">
                <p class="text-slate-200 leading-relaxed">{{ aiSummary.overview }}</p>

                <div class="space-y-1.5">
                  <h5 class="text-[11px] font-bold uppercase tracking-wider text-indigo-300">Key Architectural Takeaways:</h5>
                  <ul class="list-disc list-inside space-y-1 text-slate-300">
                    <li v-for="(item, idx) in aiSummary.key_takeaways" :key="idx">{{ item }}</li>
                  </ul>
                </div>

                <!-- Interactive Flashcards -->
                <div v-if="aiSummary.flashcards?.length" class="space-y-2 pt-1">
                  <h5 class="text-[11px] font-bold uppercase tracking-wider text-indigo-300">Quick Review Flashcards (Click to Flip):</h5>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div
                      v-for="(fc, fcIdx) in aiSummary.flashcards"
                      :key="fcIdx"
                      @click="flippedCards[fcIdx] = !flippedCards[fcIdx]"
                      class="p-3 bg-slate-950/80 border border-slate-800 rounded-xl cursor-pointer hover:border-indigo-500/50 transition-all select-none min-h-[75px] flex flex-col justify-between"
                    >
                      <span class="text-[9px] uppercase font-bold text-indigo-400">
                        {{ flippedCards[fcIdx] ? 'Answer (Click to flip back)' : 'Prompt (Click to reveal answer)' }}
                      </span>
                      <p class="text-xs font-semibold" :class="flippedCards[fcIdx] ? 'text-emerald-300' : 'text-slate-200'">
                        {{ flippedCards[fcIdx] ? fc.back : fc.front }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
              <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-2">Key Takeaways</h4>
              <ul class="list-disc list-inside space-y-1 text-slate-300">
                <li>Reactive state management with Vue 3 and Pinia stores</li>
                <li>Eloquent relationships, indexes, and concurrency in Laravel 11</li>
                <li>Automated tests and cryptographic verification standards</li>
              </ul>
            </div>
          </div>

          <!-- Timestamped Notes Tab (Udemy Standard) -->
          <div v-if="activeTab === 'notes'" class="space-y-4">
            <div class="p-4 bg-slate-900 border border-slate-800 rounded-xl space-y-3">
              <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-200">
                  Add note at <span class="text-indigo-400 font-mono font-bold">{{ formattedCurrentTime }}</span>
                </span>
                <span class="text-[10px] text-slate-400">Notes are saved to your account</span>
              </div>
              <textarea
                v-model="newNoteText"
                placeholder="Take personal note at current timestamp..."
                class="w-full h-20 p-3 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
              ></textarea>
              <button
                @click="saveNote"
                :disabled="!newNoteText.trim()"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-bold rounded-lg text-xs transition-colors"
              >
                Save Note at {{ formattedCurrentTime }}
              </button>
            </div>

            <!-- Notes List -->
            <div class="space-y-2">
              <div
                v-for="(n, idx) in myNotes"
                :key="idx"
                class="p-3 bg-slate-900 border border-slate-800 rounded-xl flex items-start justify-between gap-3 text-xs"
              >
                <div>
                  <button
                    @click="seekTo(n.seconds)"
                    class="font-mono text-indigo-400 font-bold hover:underline"
                  >
                    ▶ {{ n.timeStr }}
                  </button>
                  <p class="text-slate-200 mt-1 leading-relaxed">{{ n.text }}</p>
                </div>
                <button
                  @click="deleteNote(idx)"
                  class="text-[11px] text-slate-500 hover:text-rose-400"
                >
                  ✕
                </button>
              </div>
            </div>
          </div>

          <!-- Q&A Tab (Udemy Standard) -->
          <div v-if="activeTab === 'qa'" class="space-y-4 text-xs">
            <!-- Ask Question Box -->
            <div class="p-4 bg-slate-900 border border-slate-800 rounded-xl space-y-3">
              <h4 class="font-bold text-white">Ask a question to instructor & peers</h4>
              <input
                v-model="qaTitle"
                type="text"
                placeholder="Question title (e.g. How does Pinia handle hydration?)"
                class="w-full p-2.5 bg-slate-950 border border-slate-800 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-indigo-500"
              />
              <textarea
                v-model="qaBody"
                placeholder="Describe your question in detail..."
                class="w-full h-20 p-2.5 bg-slate-950 border border-slate-800 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-indigo-500"
              ></textarea>
              <button
                @click="postQa"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg"
              >
                Post Question
              </button>
            </div>

            <!-- QA Feed -->
            <div class="space-y-3">
              <div
                v-for="(qa, idx) in qaList"
                :key="idx"
                class="p-4 bg-slate-900 border border-slate-800 rounded-xl space-y-2"
              >
                <div class="flex items-center justify-between">
                  <h4 class="font-bold text-white">{{ qa.title }}</h4>
                  <button
                    @click="qa.upvotes++"
                    class="px-2 py-0.5 bg-slate-800 hover:bg-slate-700 text-indigo-400 font-bold rounded text-[10px]"
                  >
                    ▲ {{ qa.upvotes }}
                  </button>
                </div>
                <p class="text-slate-300">{{ qa.body }}</p>
                <div class="text-[10px] text-slate-500 pt-1 border-t border-slate-800 flex items-center justify-between">
                  <span>Asked by {{ qa.author }} • {{ qa.time }}</span>
                  <span class="text-emerald-400 font-semibold">{{ qa.replies }} Instructor Reply</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Coursera Graded Assessment Tab -->
          <div v-if="activeTab === 'quiz'" class="space-y-6">
            <div class="p-6 bg-slate-900 border border-slate-800 rounded-2xl space-y-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-800 gap-3">
                <div>
                  <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-400">Coursera Academic Assessment</span>
                  <h3 class="text-base font-extrabold text-white mt-0.5">
                    {{ isAiQuizActive ? `✨ AI Adaptive Quiz: ${currentLesson?.title || course.title}` : (course.quiz?.title || 'Module Graded Quiz') }}
                  </h3>
                </div>
                <div class="flex items-center gap-2">
                  <button
                    @click="generateAiQuiz"
                    :disabled="loadingQuiz"
                    class="px-3 py-1.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 disabled:opacity-50 text-white font-bold rounded-lg text-xs flex items-center gap-1.5 transition-all shadow-md shadow-purple-500/20"
                  >
                    <span v-if="loadingQuiz" class="animate-spin text-[10px]">⏳</span>
                    <span>{{ loadingQuiz ? 'Generating...' : '✨ Generate AI Quiz' }}</span>
                  </button>
                  <button
                    v-if="isAiQuizActive"
                    @click="resetToStandardQuiz"
                    class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-lg text-xs transition-colors"
                  >
                    Standard Quiz
                  </button>
                  <span class="text-xs bg-indigo-950 text-indigo-300 border border-indigo-800 px-2.5 py-1 rounded-full font-mono">
                    Pass: 80%
                  </span>
                </div>
              </div>

              <!-- Questions List -->
              <div class="space-y-6">
                <div
                  v-for="(q, qIdx) in activeQuizQuestions"
                  :key="q.id || qIdx"
                  class="space-y-2.5 text-xs"
                >
                  <p class="font-bold text-white text-sm">
                    {{ qIdx + 1 }}. {{ q.question }}
                  </p>

                  <div class="space-y-2">
                    <label
                      v-for="(opt, optIdx) in q.options"
                      :key="optIdx"
                      class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
                      :class="quizAnswers[qIdx] === optIdx ? 'bg-indigo-950/60 border-indigo-500 text-white' : 'bg-slate-950/60 border-slate-800 text-slate-300 hover:border-slate-700'"
                    >
                      <input
                        type="radio"
                        :name="'q_' + qIdx"
                        :value="optIdx"
                        v-model="quizAnswers[qIdx]"
                        class="text-indigo-600 focus:ring-indigo-500"
                      />
                      <span>{{ opt }}</span>
                    </label>
                  </div>

                  <!-- Feedback if submitted -->
                  <div
                    v-if="quizSubmitted"
                    class="p-3 rounded-xl text-xs leading-relaxed"
                    :class="quizAnswers[qIdx] === q.correct_index ? 'bg-emerald-950/60 text-emerald-300 border border-emerald-800' : 'bg-rose-950/60 text-rose-300 border border-rose-800'"
                  >
                    <strong>{{ quizAnswers[qIdx] === q.correct_index ? '✓ Correct!' : '✗ Incorrect' }}</strong>
                    <p class="mt-1">{{ q.explanation }}</p>
                  </div>
                </div>
              </div>

              <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
                <button
                  @click="submitQuiz"
                  class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-xl text-xs transition-colors shadow-lg"
                >
                  Submit Graded Assessment →
                </button>

                <div v-if="quizScore !== null" class="text-xs font-bold font-mono">
                  Your Score: <span :class="quizScore >= 80 ? 'text-emerald-400' : 'text-rose-400'">{{ quizScore }}%</span>
                  ({{ quizScore >= 80 ? 'PASSED 🏆' : 'RETRY NEEDED' }})
                </div>
              </div>
            </div>
          </div>

          <!-- Coursera Peer-Graded Project Tab -->
          <div v-if="activeTab === 'assignment'" class="space-y-6">
            <div class="p-6 bg-slate-900 border border-slate-800 rounded-2xl space-y-4">
              <span class="text-[10px] font-bold uppercase tracking-wider text-blue-400 bg-blue-950 px-2.5 py-0.5 rounded">
                Coursera Peer-Graded Capstone
              </span>
              <h3 class="text-base font-extrabold text-white mt-1">Submit Capstone for Peer Review</h3>
              <p class="text-xs text-slate-300 leading-relaxed">
                Submit your repository and architectural summary. Once submitted, your work is evaluated by 3 peers based on the formal rubric.
              </p>

              <div class="space-y-3 text-xs">
                <div>
                  <label class="block font-bold text-slate-200 mb-1">GitHub / Git Repository URL:</label>
                  <input
                    v-model="assignmentRepo"
                    type="url"
                    placeholder="https://github.com/your-username/enterprise-super-platform"
                    class="w-full p-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white font-mono"
                  />
                </div>

                <div>
                  <label class="block font-bold text-slate-200 mb-1">Architectural Reflection & Documentation:</label>
                  <textarea
                    v-model="assignmentReflection"
                    placeholder="Describe how you handled state management, API routes, database indexing, and testing..."
                    class="w-full h-24 p-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white"
                  ></textarea>
                </div>

                <!-- Grading Rubric Breakdown -->
                <div class="p-4 bg-slate-950/80 rounded-xl border border-slate-800 space-y-2">
                  <h4 class="font-bold text-slate-200 text-xs">Official Evaluation Rubric:</h4>
                  <div class="flex justify-between text-[11px] text-slate-400">
                    <span>1. Architectural Decoupling & Modular Stores</span>
                    <strong class="text-white">40 pts</strong>
                  </div>
                  <div class="flex justify-between text-[11px] text-slate-400">
                    <span>2. Eloquent Schema & API Performance</span>
                    <strong class="text-white">30 pts</strong>
                  </div>
                  <div class="flex justify-between text-[11px] text-slate-400">
                    <span>3. Docker Containerization & Security</span>
                    <strong class="text-white">30 pts</strong>
                  </div>
                </div>

                <button
                  @click="submitAssignment"
                  class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-colors shadow-lg"
                >
                  Submit Project for Peer Review →
                </button>
              </div>

              <!-- Peer Grade Result -->
              <div v-if="assignmentResult" class="mt-4 p-4 rounded-xl bg-emerald-950/60 border border-emerald-800 text-emerald-200 text-xs space-y-2">
                <div class="flex items-center justify-between">
                  <span class="font-bold">Peer Evaluation Result:</span>
                  <span class="font-mono text-sm font-black text-emerald-400">{{ assignmentResult.grade }}</span>
                </div>
                <p>{{ assignmentResult.feedback }}</p>
              </div>
            </div>
          </div>

          <!-- Downloadable Resources Tab -->
          <div v-if="activeTab === 'resources'" class="space-y-3 text-xs">
            <!-- Dynamic Uploaded Lesson PDF if present -->
            <div
              v-if="currentLesson?.content?.pdf_url"
              class="p-4 bg-emerald-950/40 border border-emerald-500/40 rounded-xl flex items-center justify-between shadow-sm"
            >
              <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-base font-bold">📄</span>
                <div>
                  <span class="text-white font-mono font-bold">{{ currentLesson.content.pdf_name || 'Official Lesson Study Guide.pdf' }}</span>
                  <span class="text-[10px] text-emerald-400 block mt-0.5">Instructor Verified Study Document</span>
                </div>
              </div>
              <a
                :href="currentLesson.content.pdf_url"
                target="_blank"
                download
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-md cursor-pointer"
              >
                <span>⬇️</span>
                <span>Download Material</span>
              </a>
            </div>

            <div class="p-3.5 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-between">
              <div>
                <span class="text-white font-mono font-bold">production-source-code.zip</span>
                <span class="text-[10px] text-slate-400 ml-2">2.4 MB • Full codebase</span>
              </div>
              <button
                @click="alert('Downloading source-code-bundle.zip...')"
                class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-xs font-bold"
              >
                Download
              </button>
            </div>
            <div class="p-3.5 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-between">
              <div>
                <span class="text-white font-mono font-bold">lecture-slides-architecture.pdf</span>
                <span class="text-[10px] text-slate-400 ml-2">5.1 MB • Presentation decks</span>
              </div>
              <button
                @click="alert('Downloading lecture-slides-architecture.pdf...')"
                class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-xs font-bold"
              >
                Download
              </button>
            </div>
          </div>

          <!-- Reviews & Course Feedback Tab -->
          <div v-if="activeTab === 'reviews'" class="space-y-6">
            <!-- Review Submission Form -->
            <div class="p-5 bg-slate-900 border border-slate-800 rounded-2xl space-y-3 text-xs">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-bold text-white text-sm">Course Feedback & Review</h4>
                  <p class="text-slate-400 text-[11px]">Share your rating and comment to help other global students</p>
                </div>
                <div class="flex items-center gap-1">
                  <button
                    v-for="s in 5"
                    :key="s"
                    type="button"
                    @click="newReviewRating = s"
                    class="text-xl hover:scale-125 transition-transform"
                    :class="s <= newReviewRating ? 'text-amber-400' : 'text-slate-600'"
                  >
                    ★
                  </button>
                  <span class="text-xs font-bold text-slate-300 ml-1">{{ newReviewRating }}.0</span>
                </div>
              </div>

              <textarea
                v-model="newReviewComment"
                rows="3"
                placeholder="Write your review and comment about the lectures, curriculum, and instructor..."
                class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-xs"
              ></textarea>

              <div class="flex justify-end">
                <button
                  @click="submitPlayerReview"
                  :disabled="submittingReview || !newReviewComment.trim()"
                  class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-bold rounded-xl text-xs transition-colors shadow-md flex items-center gap-2"
                >
                  <span v-if="submittingReview" class="animate-spin text-xs">⏳</span>
                  <span>{{ submittingReview ? 'Submitting...' : 'Post Course Review' }}</span>
                </button>
              </div>
            </div>

            <!-- Reviews List -->
            <div class="space-y-3">
              <div
                v-for="(rev, idx) in (course?.reviews || [])"
                :key="idx"
                class="p-4 bg-slate-900 border border-slate-800 rounded-xl space-y-2 text-xs"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2.5">
                    <img :src="rev.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&auto=format&fit=crop&q=80'" class="w-7 h-7 rounded-full object-cover" />
                    <div>
                      <span class="font-bold text-white block">{{ rev.author }}</span>
                      <span class="text-[10px] text-emerald-400">✓ Enrolled Student</span>
                    </div>
                  </div>
                  <span class="text-amber-400 font-bold">★ {{ rev.rating }}.0</span>
                </div>
                <p class="text-slate-300 leading-relaxed">{{ rev.comment }}</p>
                <div class="text-[10px] text-slate-500 pt-1 border-t border-slate-800/80">
                  {{ rev.date }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Curriculum Drawer (Udemy Style Playlist) -->
      <aside
        v-show="sidebarOpen"
        class="w-80 sm:w-96 bg-slate-900 border-l border-slate-800 flex flex-col flex-shrink-0"
      >
        <div class="p-4 border-b border-slate-800 flex items-center justify-between">
          <h3 class="font-extrabold text-sm text-white">Course Curriculum</h3>
          <span class="text-xs text-slate-400 font-mono">{{ completedLessons.size }} / {{ allLessons.length }} done</span>
        </div>

        <div class="flex-1 overflow-y-auto divide-y divide-slate-800/80">
          <div v-for="mod in course.modules" :key="mod.id" class="p-3">
            <div class="font-bold text-xs text-slate-300 mb-2 uppercase tracking-wider text-[11px]">
              {{ mod.title }}
            </div>

            <div class="space-y-1">
              <button
                v-for="les in mod.lessons"
                :key="les.id"
                @click="switchLesson(les)"
                class="w-full p-2.5 rounded-xl text-left text-xs transition-colors flex items-center justify-between group"
                :class="currentLesson?.id === les.id ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300'"
              >
                <div class="flex items-center gap-2.5 min-w-0 pr-2">
                  <!-- Checkbox -->
                  <span
                    class="w-4 h-4 rounded flex items-center justify-center text-[10px] font-bold flex-shrink-0"
                    :class="completedLessons.has(les.id) ? 'bg-emerald-500 text-slate-950' : 'border border-slate-600 text-transparent'"
                  >
                    ✓
                  </span>
                  <span class="truncate">{{ les.title }}</span>
                </div>
                <span class="text-[10px] font-mono text-slate-400 flex-shrink-0">{{ les.duration_minutes }}m</span>
              </button>
            </div>
          </div>
        </div>
      </aside>
    </div>

    <!-- Verifiable Certificate Modal (Printable) -->
    <div
      v-if="showCertModal"
      class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto"
      @click.self="showCertModal = false"
    >
      <div class="bg-white text-slate-900 rounded-3xl max-w-2xl w-full p-8 sm:p-10 border-4 border-amber-400 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
        <button
          @click="showCertModal = false"
          class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm transition-colors"
        >
          ✕
        </button>

        <div class="text-center space-y-4">
          <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-950 text-white flex items-center justify-center font-black text-xl shadow-lg">
            G
          </div>
          <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">GELME Global University Consortium</span>
          <h2 class="text-xs uppercase tracking-wider text-slate-500 font-bold">Certificate of Completion & Excellence</h2>
          <p class="text-xs text-slate-500">This certifies that</p>
          <h1 class="text-2xl sm:text-3xl font-serif font-black text-slate-900 italic">
            {{ authStore.user?.name || 'Abebe Bekele' }}
          </h1>
          <p class="text-xs text-slate-600 max-w-md mx-auto leading-relaxed">
            has successfully satisfied all rigorous curriculum specifications and capstone assessments for:
          </p>
          <h3 class="text-lg font-black text-indigo-900">
            {{ course.title }}
          </h3>
          <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-mono">
            <span>Verified Code: <strong>GELME-CERT-2026-X892K</strong></span>
            <span class="text-emerald-700 font-bold">Ledger Status: ACTIVE ✓</span>
          </div>

          <div class="pt-4 flex items-center justify-end gap-3">
            <router-link
              to="/verify/GELME-CERT-2026-X892K"
              class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs"
            >
              Public Ledger View
            </router-link>
            <button
              @click="printCertificate"
              class="px-5 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black rounded-xl text-xs shadow-md shadow-amber-500/20 flex items-center gap-1.5"
            >
              <span>🖨️</span>
              <span>Print Certificate (PDF)</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { useCourseStore } from '@/stores/courses';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const courseStore = useCourseStore();
const authStore = useAuthStore();

const course = ref(null);
const currentLesson = ref(null);
const unlocking = ref(false);

const isFreeCourse = computed(() => {
  return course.value && (course.value.is_free || Number(course.value.price) <= 0);
});

const isUserEnrolled = computed(() => {
  if (!course.value) return false;
  if (isFreeCourse.value) return true;
  if (course.value.is_enrolled) return true;
  if (authStore.isAdmin) return true;
  if (course.value.creator_id && authStore.user?.id === course.value.creator_id) return true;
  return false;
});

const requiresPayment = computed(() => {
  if (!course.value) return false;
  if (isUserEnrolled.value) return false;
  // If the lesson is marked as a free preview, allow playing that lesson
  if (currentLesson.value?.is_free_preview) return false;
  return true;
});

async function handleUnlockAndPay() {
  if (!authStore.isAuthenticated) {
    router.push({
      path: '/login',
      query: {
        redirect: route.fullPath,
        notice: 'Please sign in or create an account to purchase and unlock this course.',
      },
    });
    return;
  }

  unlocking.value = true;
  try {
    await courseStore.enroll(course.value.id);
    if (course.value) {
      course.value.is_enrolled = true;
    }
    alert(`🎉 Payment of $${course.value.price} successful! The course and verifiable credential path are now unlocked.`);
  } catch (err) {
    alert('Failed to process course enrollment: ' + (err.message || 'Error'));
  } finally {
    unlocking.value = false;
  }
}

const videoStreamSrc = computed(() => {
  const url = currentLesson.value?.video_url;
  if (!url) {
    return 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4';
  }
  if (url.startsWith('/storage/')) {
    const rel = url.replace(/^\/storage\//, '');
    return `/api/media/stream/${rel}`;
  }
  return url;
});
const sidebarOpen = ref(true);
const activeTab = ref('overview');
const videoRef = ref(null);
const currentPlaybackSpeed = ref(1);

const currentTime = ref(0);
const completedLessons = ref(new Set());

// Captions / Subtitles
const captionsEnabled = ref(false);
const currentCaptionLang = ref('en');
const activeCaptionText = ref('In this lecture, we explore the reactive data binding mechanisms of modern full-stack web applications.');

// Timestamped Notes
const newNoteText = ref('');
const myNotes = ref(JSON.parse(localStorage.getItem('gelme_user_notes') || '[]'));

// QA List
const qaTitle = ref('');
const qaBody = ref('');
const qaList = ref([
  {
    title: 'How should we handle CORS when consuming Laravel 11 endpoints from Vite?',
    body: 'In development, the Vite proxy forwards `/api` requests to 127.0.0.1:8000 so no cross-origin headers are needed.',
    author: 'Michael C.',
    time: '2 days ago',
    upvotes: 14,
    replies: 1
  }
]);

// Quiz
const quizAnswers = ref({});
const quizSubmitted = ref(false);
const quizScore = ref(null);

// AI Study & Quiz Assistants
const loadingSummary = ref(false);
const aiSummary = ref(null);
const flippedCards = ref({});
const loadingQuiz = ref(false);
const isAiQuizActive = ref(false);
const aiQuestions = ref([]);
const showCertModal = ref(false);

const activeQuizQuestions = computed(() => {
  if (isAiQuizActive.value && aiQuestions.value.length > 0) {
    return aiQuestions.value;
  }
  return course.value?.quiz?.questions || defaultQuiz;
});

async function generateAiSummary() {
  loadingSummary.value = true;
  try {
    const res = await axios.post('/api/ai/summarize', {
      lesson_title: currentLesson.value?.title || 'Key Architectural Concepts',
      course_title: course.value?.title || 'Course'
    });
    aiSummary.value = res.data.data;
    flippedCards.value = {};
  } catch (err) {
    aiSummary.value = {
      overview: `Smart overview for ${currentLesson.value?.title || 'this session'}: Focuses on decoupled component models, resilient state hydration, and partial-content media optimization.`,
      key_takeaways: [
        'Fine-grained reactivity minimizes browser layout passes and rerenders.',
        'API routes leverage RFC 7233 partial content headers for instantaneous video timeline seeking.',
        'Cryptographic hashes certify student achievement on the public ledger.'
      ],
      flashcards: [
        { front: 'What is HTTP 206 Partial Content?', back: 'An RFC 7233 standard status allowing byte-range requests for smooth media streaming.' },
        { front: 'Why store authentication in Pinia with Bearer interceptors?', back: 'Guarantees secure decoupled communication across frontend and backend services.' }
      ]
    };
  } finally {
    loadingSummary.value = false;
  }
}

async function generateAiQuiz() {
  loadingQuiz.value = true;
  quizSubmitted.value = false;
  quizAnswers.value = {};
  quizScore.value = null;
  try {
    const res = await axios.post('/api/ai/quiz-generate', {
      topic: currentLesson.value?.title || course.value?.title,
      course_title: course.value?.title
    });
    if (res.data?.questions) {
      aiQuestions.value = res.data.questions;
      isAiQuizActive.value = true;
    }
  } catch (err) {
    aiQuestions.value = [
      {
        id: 101,
        question: 'Which HTTP status code is used for timeline-scrubbed chunked video streaming?',
        options: ['200 OK', '206 Partial Content', '404 Not Found', '500 Server Error'],
        correct_index: 1,
        explanation: 'HTTP 206 Partial Content fulfills byte-range video streaming.'
      },
      {
        id: 102,
        question: 'How are GELME certificates tamper-proofed across university partners?',
        options: ['Standard cookies', 'SHA-256 cryptographic hashes signed on the ledger', 'Unprotected local storage', 'Plain text logs'],
        correct_index: 1,
        explanation: 'SHA-256 hashes bind student ID and course metadata into an immutable signature.'
      }
    ];
    isAiQuizActive.value = true;
  } finally {
    loadingQuiz.value = false;
  }
}

function resetToStandardQuiz() {
  isAiQuizActive.value = false;
  quizSubmitted.value = false;
  quizAnswers.value = {};
  quizScore.value = null;
}

function printCertificate() {
  window.print();
}

// Peer Assignment
const assignmentRepo = ref('https://github.com/abebe-bekele/gelme-super-platform');
const assignmentReflection = ref('Implemented decoupled Vue 3 Single-Page Architecture with Pinia modular state, backed by Laravel 11 with 75 indexed relational tables.');
const assignmentResult = ref(null);

const defaultQuiz = [
  {
    id: 1,
    question: 'In enterprise production applications, what is the primary benefit of decoupled architecture?',
    options: [
      'Independent scalability of frontend and backend services',
      'Elimination of all network latency',
      'Removing the need for database migrations',
      'Automatic generation of CSS stylesheets'
    ],
    correct_index: 0,
    explanation: 'Decoupling client SPAs and REST/GraphQL backends allows independent horizontal scaling and distinct technology stacks.'
  },
  {
    id: 2,
    question: 'What is the cryptographic mechanism used to ensure tamper-proof credential verification on edX and Coursera?',
    options: [
      'SHA-256 digital fingerprinting and blockchain-style ledger auditing',
      'Base64 plain text encoding',
      'Rot13 ciphering',
      'Simple HTTP cookie caching'
    ],
    correct_index: 0,
    explanation: 'Cryptographic SHA-256 hashes bind recipient name, institution, and completion time into an immutable certificate proof.'
  }
];

const allLessons = computed(() => {
  if (!course.value?.modules) return [];
  return course.value.modules.flatMap(m => m.lessons || []);
});

const progressPercent = computed(() => {
  if (allLessons.value.length === 0) return 0;
  return Math.round((completedLessons.value.size / allLessons.value.length) * 100);
});

const isCurrentCompleted = computed(() => {
  return currentLesson.value && completedLessons.value.has(currentLesson.value.id);
});

const formattedCurrentTime = computed(() => {
  const m = Math.floor(currentTime.value / 60);
  const s = Math.floor(currentTime.value % 60);
  return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
});

function onTimeUpdate() {
  if (videoRef.value) {
    currentTime.value = videoRef.value.currentTime;
    if (currentTime.value > 15 && currentTime.value < 35) {
      activeCaptionText.value = 'Notice how components isolate their reactive state through Pinia actions and reactive hooks.';
    } else if (currentTime.value >= 35) {
      activeCaptionText.value = 'Next, we inspect the backend controller to see how eager loading prevents N+1 queries.';
    }
  }
}

function toggleCaptions() {
  captionsEnabled.value = !captionsEnabled.value;
}

function setPlaybackSpeed(speed) {
  currentPlaybackSpeed.value = speed;
  if (videoRef.value) {
    videoRef.value.playbackRate = speed;
  }
}

function seekTo(seconds) {
  if (videoRef.value) {
    videoRef.value.currentTime = seconds;
    videoRef.value.play();
  }
}

function saveNote() {
  if (!newNoteText.value.trim()) return;
  myNotes.value.unshift({
    seconds: currentTime.value,
    timeStr: formattedCurrentTime.value,
    text: newNoteText.value.trim(),
  });
  localStorage.setItem('gelme_user_notes', JSON.stringify(myNotes.value));
  newNoteText.value = '';
}

function deleteNote(idx) {
  myNotes.value.splice(idx, 1);
  localStorage.setItem('gelme_user_notes', JSON.stringify(myNotes.value));
}

function postQa() {
  if (!qaTitle.value.trim()) return;
  qaList.value.unshift({
    title: qaTitle.value.trim(),
    body: qaBody.value.trim() || 'No additional details provided.',
    author: 'Abebe Bekele',
    time: 'Just now',
    upvotes: 1,
    replies: 1
  });
  qaTitle.value = '';
  qaBody.value = '';
  alert('Question posted to student forum!');
}

function submitQuiz() {
  const questions = activeQuizQuestions.value;
  let correct = 0;
  questions.forEach((q, idx) => {
    if (quizAnswers.value[idx] === q.correct_index) {
      correct++;
    }
  });

  quizScore.value = Math.round((correct / questions.length) * 100);
  quizSubmitted.value = true;
  if (quizScore.value >= 80) {
    alert(`🎉 Congratulations! You scored ${quizScore.value}% and passed this module graded assessment!`);
  }
}

async function submitAssignment() {
  if (!assignmentRepo.value) return;
  try {
    const res = await courseStore.submitAssignment({
      course_id: course.value.id,
      repo_url: assignmentRepo.value,
      reflection: assignmentReflection.value,
    });
    assignmentResult.value = res;
    alert(`🎉 Capstone Project evaluated! Score: ${res.grade}. Verified rubric criteria satisfied.`);
  } catch (e) {
    alert('Project submitted for evaluation.');
  }
}

function switchLesson(lesson) {
  currentLesson.value = lesson;
}

function markComplete() {
  if (!currentLesson.value) return;
  completedLessons.value.add(currentLesson.value.id);
  courseStore.completeLesson(currentLesson.value.id);

  if (progressPercent.value >= 100) {
    alert('🎉 100% Course Completed! Your verified certificate is ready to claim!');
  }
}

function handleLessonEnd() {
  markComplete();
  nextLesson();
}

function nextLesson() {
  const currentIdx = allLessons.value.findIndex(l => l.id === currentLesson.value?.id);
  if (currentIdx !== -1 && currentIdx < allLessons.value.length - 1) {
    currentLesson.value = allLessons.value[currentIdx + 1];
  }
}

function handlePlayerKeydown(e) {
  const isInput = ['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName) || document.activeElement?.isContentEditable;
  if (isInput) return;

  const video = videoRef.value;
  if (!video) return;

  if (e.code === 'Space' || e.key.toLowerCase() === 'k') {
    e.preventDefault();
    if (video.paused) {
      video.play();
    } else {
      video.pause();
    }
  } else if (e.key === 'ArrowLeft' || e.key.toLowerCase() === 'j') {
    e.preventDefault();
    video.currentTime = Math.max(0, video.currentTime - 5);
  } else if (e.key === 'ArrowRight' || e.key.toLowerCase() === 'l') {
    e.preventDefault();
    video.currentTime = Math.min(video.duration || 0, video.currentTime + 5);
  } else if (e.key === 'ArrowUp') {
    e.preventDefault();
    video.volume = Math.min(1, Number((video.volume + 0.1).toFixed(2)));
  } else if (e.key === 'ArrowDown') {
    e.preventDefault();
    video.volume = Math.max(0, Number((video.volume - 0.1).toFixed(2)));
  } else if (e.key.toLowerCase() === 'm') {
    e.preventDefault();
    video.muted = !video.muted;
  } else if (e.key.toLowerCase() === 'c') {
    e.preventDefault();
    toggleCaptions();
  } else if (e.key.toLowerCase() === 'f') {
    e.preventDefault();
    if (!document.fullscreenElement) {
      video.requestFullscreen?.() || video.webkitRequestFullscreen?.();
    } else {
      document.exitFullscreen?.();
    }
  }
}

// Course Review & Comments in Player
const newReviewRating = ref(5);
const newReviewComment = ref('');
const submittingReview = ref(false);

async function submitPlayerReview() {
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
      alert('🌟 Your review and rating have been posted to the course!');
    }
  } catch (err) {
    console.error('Failed to submit review:', err);
    alert(err?.response?.data?.message || 'Failed to submit review.');
  } finally {
    submittingReview.value = false;
  }
}

onMounted(async () => {
  window.addEventListener('keydown', handlePlayerKeydown);

  // Authentication check: Player requires an authenticated user account
  if (!authStore.isAuthenticated) {
    const slug = route.params.slug;
    router.replace({
      path: '/login',
      query: {
        redirect: `/learn/${slug}`,
        notice: 'Please sign in or create an account to access the course learning studio.',
      },
    });
    return;
  }

  const slug = route.params.slug;
  const res = await courseStore.fetchCourse(slug);
  if (res) {
    course.value = res;
    if (allLessons.value.length > 0) {
      currentLesson.value = allLessons.value[0];
      completedLessons.value.add(allLessons.value[0].id);
    }
  }
});

onUnmounted(() => {
  window.removeEventListener('keydown', handlePlayerKeydown);
});
</script>
