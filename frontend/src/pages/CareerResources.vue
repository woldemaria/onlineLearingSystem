<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 pb-20">
    <!-- Hero Banner -->
    <section class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-indigo-950">
      <div class="max-w-6xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-semibold mb-4 border border-indigo-500/30">
          <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
          Coursera & GELME Career Resource Center
        </div>
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight max-w-3xl leading-tight mb-4">
          Accelerate Your Career with Industry Guides & Certifications
        </h1>
        <p class="text-slate-300 text-base sm:text-lg max-w-2xl leading-relaxed mb-8">
          Explore comprehensive guides to IT, cybersecurity, and project management certifications, take our personalized Career Aptitude Test, or share your learning journey.
        </p>

        <!-- Quick Jump Filter Tabs -->
        <div class="flex flex-wrap gap-2">
          <button
            @click="activeSection = 'aptitude'"
            :class="activeSection === 'aptitude' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-800'"
            class="px-4 py-2 text-xs font-bold rounded-lg transition-all border border-slate-700/60 flex items-center gap-2"
          >
            <span>🎯</span> Career Aptitude Test
          </button>
          <button
            @click="activeSection = 'certifications'"
            :class="activeSection === 'certifications' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-800'"
            class="px-4 py-2 text-xs font-bold rounded-lg transition-all border border-slate-700/60 flex items-center gap-2"
          >
            <span>📜</span> Certification Requirements
          </button>
          <button
            @click="activeSection = 'high-income'"
            :class="activeSection === 'high-income' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-800'"
            class="px-4 py-2 text-xs font-bold rounded-lg transition-all border border-slate-700/60 flex items-center gap-2"
          >
            <span>🚀</span> High-Income Skills & AI
          </button>
          <button
            @click="activeSection = 'stories'"
            :class="activeSection === 'stories' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-800'"
            class="px-4 py-2 text-xs font-bold rounded-lg transition-all border border-slate-700/60 flex items-center gap-2"
          >
            <span>💬</span> Share Your Story
          </button>
        </div>
      </div>
    </section>

    <!-- Main Content Area -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

      <!-- SECTION 1: CAREER APTITUDE TEST -->
      <section v-if="activeSection === 'aptitude' || activeSection === 'all'" class="mb-14">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-4">
            <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">🎯</span>
            <div>
              <h2 class="text-2xl font-bold text-slate-900">Interactive Career Aptitude Test</h2>
              <p class="text-xs text-slate-500">Answer 5 quick questions to match your working style with top-earning professional certificates.</p>
            </div>
          </div>

          <div v-if="!aptitudeCompleted" class="mt-6">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-2">
              <span>Question {{ currentQuestionIndex + 1 }} of {{ aptitudeQuestions.length }}</span>
              <span>{{ Math.round(((currentQuestionIndex + 1) / aptitudeQuestions.length) * 100) }}% complete</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 mb-6 overflow-hidden">
              <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" :style="{ width: ((currentQuestionIndex + 1) / aptitudeQuestions.length) * 100 + '%' }"></div>
            </div>

            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 mb-6">
              <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">{{ currentQuestion.question }}</h3>
              <div class="space-y-3">
                <button
                  v-for="(option, idx) in currentQuestion.options"
                  :key="idx"
                  @click="selectOption(option.domain)"
                  class="w-full text-left p-3.5 rounded-lg border text-sm font-medium transition-all flex items-start gap-3 hover:border-indigo-600 hover:bg-indigo-50/50 bg-white border-slate-200 shadow-sm"
                >
                  <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">
                    {{ String.fromCharCode(65 + idx) }}
                  </span>
                  <span>{{ option.label }}</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Aptitude Result Card -->
          <div v-else class="mt-6 p-6 sm:p-8 bg-gradient-to-br from-indigo-50 via-white to-purple-50 rounded-2xl border-2 border-indigo-200 shadow-sm text-center">
            <div class="inline-block p-4 bg-indigo-600 text-white rounded-2xl shadow-lg mb-4 text-3xl">
              🏆
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-2">
              Your Recommended Pathway: {{ aptitudeRecommendation.title }}
            </h3>
            <p class="text-sm text-slate-600 max-w-xl mx-auto mb-6">
              {{ aptitudeRecommendation.description }}
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-xl mx-auto mb-6 text-left">
              <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Avg US Salary</span>
                <span class="text-lg font-black text-emerald-600">{{ aptitudeRecommendation.salary }}</span>
              </div>
              <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Completion Time</span>
                <span class="text-lg font-black text-slate-800">{{ aptitudeRecommendation.time }}</span>
              </div>
              <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Open Roles</span>
                <span class="text-lg font-black text-indigo-600">{{ aptitudeRecommendation.roles }}</span>
              </div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3">
              <router-link
                :to="'/courses/' + aptitudeRecommendation.courseSlug"
                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md transition-all inline-flex items-center gap-2"
              >
                <span>Enroll in {{ aptitudeRecommendation.certificate }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
              </router-link>
              <button
                @click="resetAptitudeTest"
                class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm rounded-xl transition-all"
              >
                Retake Test
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- SECTION 2: CERTIFICATION REQUIREMENTS GUIDES -->
      <section v-if="activeSection === 'certifications' || activeSection === 'all'" class="mb-14">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h2 class="text-2xl font-black text-slate-900">Certification Requirements Guides</h2>
            <p class="text-xs text-slate-500">Official prerequisites, exam breakdowns, and preparation roadmaps.</p>
          </div>
          <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-200">
            Updated for 2026
          </span>
        </div>

        <div class="space-y-4">
          <!-- Guide 1: CAPM Certification Requirements -->
          <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
            <button
              @click="toggleAccordion('capm')"
              class="w-full flex items-center justify-between p-5 text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors"
            >
              <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 font-black text-xs flex items-center justify-center">CAPM</span>
                <div>
                  <h3 class="text-base font-bold text-slate-900">CAPM Certification Requirements (PMI)</h3>
                  <p class="text-xs text-slate-500 font-normal">Certified Associate in Project Management — Entry to mid-level project lead credential</p>
                </div>
              </div>
              <svg :class="accordions.capm ? 'rotate-180' : ''" class="w-5 h-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div v-show="accordions.capm" class="p-5 border-t border-slate-100 bg-slate-50 text-sm text-slate-700 space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                  <h4 class="font-bold text-slate-900 text-xs uppercase mb-2">1. Educational Prerequisite</h4>
                  <p class="text-xs text-slate-600">Secondary diploma (high school diploma, associate's degree or the global equivalent).</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                  <h4 class="font-bold text-slate-900 text-xs uppercase mb-2">2. Professional Education</h4>
                  <p class="text-xs text-slate-600">23 contact hours of formal project management education (Satisfied 100% by Google Project Management Certificate).</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                  <h4 class="font-bold text-slate-900 text-xs uppercase mb-2">3. Exam Structure</h4>
                  <p class="text-xs text-slate-600">150 multiple-choice questions in 3 hours. Covers Predictive Plan-Based, Agile Frameworks, Business Analysis.</p>
                </div>
              </div>
              <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200 text-xs flex items-center justify-between">
                <span>Direct Alignment: Google Project Management Certificate waives the 23 contact hours requirement!</span>
                <router-link to="/courses/google-project-management-certificate" class="font-bold text-indigo-600 hover:text-indigo-800 underline">Start Google PM</router-link>
              </div>
            </div>
          </div>

          <!-- Guide 2: CompTIA A+ Certification Requirements -->
          <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
            <button
              @click="toggleAccordion('comptiaA')"
              class="w-full flex items-center justify-between p-5 text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors"
            >
              <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 font-black text-xs flex items-center justify-center">A+</span>
                <div>
                  <h3 class="text-base font-bold text-slate-900">CompTIA A+ Certification Requirements</h3>
                  <p class="text-xs text-slate-500 font-normal">Core 1 (220-1101) & Core 2 (220-1102) — Industry standard for computer hardware and IT helpdesk</p>
                </div>
              </div>
              <svg :class="accordions.comptiaA ? 'rotate-180' : ''" class="w-5 h-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div v-show="accordions.comptiaA" class="p-5 border-t border-slate-100 bg-slate-50 text-sm text-slate-700 space-y-4">
              <p class="text-xs text-slate-600 leading-relaxed">
                CompTIA A+ certifies foundational IT support abilities including mobile devices, networking technology, hardware, virtualization, cloud computing, operating systems, security, and operational procedures.
              </p>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                  <h4 class="font-bold text-slate-900 text-xs uppercase mb-2">Exam 220-1101 (Core 1)</h4>
                  <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                    <li>Mobile Devices (15%)</li>
                    <li>Networking & Virtualization (20%)</li>
                    <li>Hardware & Peripheral Configurations (25%)</li>
                    <li>Cloud Computing and Virtualization (11%)</li>
                    <li>Hardware and Network Troubleshooting (29%)</li>
                  </ul>
                </div>
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                  <h4 class="font-bold text-slate-900 text-xs uppercase mb-2">Exam 220-1102 (Core 2)</h4>
                  <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                    <li>Operating Systems Windows, macOS, Linux (31%)</li>
                    <li>Security protocols & malware mitigation (25%)</li>
                    <li>Software Troubleshooting (22%)</li>
                    <li>Operational Procedures & Ticketing (22%)</li>
                  </ul>
                </div>
              </div>
              <div class="bg-amber-50 p-4 rounded-lg border border-amber-200 text-xs flex items-center justify-between">
                <span>Recommended Prep: Google IT Support Professional Certificate prepares you directly for CompTIA A+ with dual credentialing.</span>
                <router-link to="/courses/google-it-support-certificate" class="font-bold text-amber-800 hover:text-amber-900 underline">View IT Support Prep</router-link>
              </div>
            </div>
          </div>

          <!-- Guide 3: CompTIA Security+ Certification Requirements -->
          <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
            <button
              @click="toggleAccordion('comptiaSec')"
              class="w-full flex items-center justify-between p-5 text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors"
            >
              <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 font-black text-xs flex items-center justify-center">SEC+</span>
                <div>
                  <h3 class="text-base font-bold text-slate-900">CompTIA Security+ Certification Requirements (SY0-701)</h3>
                  <p class="text-xs text-slate-500 font-normal">Global benchmark for core cybersecurity technical skills, threat hunting, and DoD 8570 compliance</p>
                </div>
              </div>
              <svg :class="accordions.comptiaSec ? 'rotate-180' : ''" class="w-5 h-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div v-show="accordions.comptiaSec" class="p-5 border-t border-slate-100 bg-slate-50 text-sm text-slate-700 space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                  <h4 class="font-bold text-slate-900 text-xs uppercase mb-2">Passing Score</h4>
                  <p class="text-xs text-slate-600">750 (on a scale of 100-900). 90 maximum questions in 90 minutes with Performance-Based Questions (PBQs).</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                  <h4 class="font-bold text-slate-900 text-xs uppercase mb-2">Prerequisites</h4>
                  <p class="text-xs text-slate-600">No formal requirements; CompTIA recommends Network+ and 2 years hands-on security experience.</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                  <h4 class="font-bold text-slate-900 text-xs uppercase mb-2">Exam Domains</h4>
                  <p class="text-xs text-slate-600">General Security Concepts (12%), Threat Vectors (22%), Security Architecture (18%), Security Operations (28%), Program Management (20%).</p>
                </div>
              </div>
              <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-200 text-xs flex items-center justify-between">
                <span>Google Partnership: Complete Google Cybersecurity Certificate to receive a 30% discount on the CompTIA Security+ exam voucher.</span>
                <router-link to="/courses/google-cybersecurity-certificate" class="font-bold text-emerald-800 hover:text-emerald-900 underline">Start Cyber Prep</router-link>
              </div>
            </div>
          </div>

          <!-- Guide 4: PMP Certification Requirements -->
          <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
            <button
              @click="toggleAccordion('pmp')"
              class="w-full flex items-center justify-between p-5 text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors"
            >
              <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 font-black text-xs flex items-center justify-center">PMP</span>
                <div>
                  <h3 class="text-base font-bold text-slate-900">PMP Certification Requirements (PMI)</h3>
                  <p class="text-xs text-slate-500 font-normal">Project Management Professional — The gold standard for senior project managers worldwide</p>
                </div>
              </div>
              <svg :class="accordions.pmp ? 'rotate-180' : ''" class="w-5 h-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div v-show="accordions.pmp" class="p-5 border-t border-slate-100 bg-slate-50 text-sm text-slate-700 space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                  <h4 class="font-bold text-slate-900 text-xs uppercase mb-2">Category 1: Four-Year Degree Holders</h4>
                  <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                    <li>36 months (3 years) of non-overlapping project leadership</li>
                    <li>4,500 hours directing project tasks</li>
                    <li>35 contact hours / PDUs of formal PM training</li>
                  </ul>
                </div>
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                  <h4 class="font-bold text-slate-900 text-xs uppercase mb-2">Category 2: Secondary Degree Holders</h4>
                  <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                    <li>60 months (5 years) of non-overlapping project leadership</li>
                    <li>7,500 hours directing project tasks</li>
                    <li>35 contact hours / PDUs of formal PM training</li>
                  </ul>
                </div>
              </div>
              <p class="text-xs text-slate-600">
                The 180-question exam covers three domains: <strong>People (42%)</strong>, <strong>Process (50%)</strong>, and <strong>Business Environment (8%)</strong> across predictive, agile, and hybrid approaches.
              </p>
            </div>
          </div>

          <!-- Guide 5: Essential IT Certifications Matrix -->
          <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
            <button
              @click="toggleAccordion('essentialIT')"
              class="w-full flex items-center justify-between p-5 text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors"
            >
              <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 font-black text-xs flex items-center justify-center">IT</span>
                <div>
                  <h3 class="text-base font-bold text-slate-900">Essential IT Certifications Roadmap</h3>
                  <p class="text-xs text-slate-500 font-normal">Rankings, tracks, and expected career progression from beginner to staff cloud architect</p>
                </div>
              </div>
              <svg :class="accordions.essentialIT ? 'rotate-180' : ''" class="w-5 h-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div v-show="accordions.essentialIT" class="p-5 border-t border-slate-100 bg-slate-50 text-sm text-slate-700">
              <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                  <thead class="bg-slate-200 text-slate-700 uppercase font-bold">
                    <tr>
                      <th class="p-2.5">Level</th>
                      <th class="p-2.5">Top Certifications</th>
                      <th class="p-2.5">Target Job Roles</th>
                      <th class="p-2.5">Avg Compensation</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-200 bg-white">
                    <tr>
                      <td class="p-2.5 font-bold text-indigo-700">Entry Level</td>
                      <td class="p-2.5">Google IT Support, CompTIA A+, Cisco CCST</td>
                      <td class="p-2.5">Help Desk Specialist, IT Technician</td>
                      <td class="p-2.5 font-semibold text-emerald-600">$55,000 - $68,000</td>
                    </tr>
                    <tr>
                      <td class="p-2.5 font-bold text-indigo-700">Mid-Tier</td>
                      <td class="p-2.5">CompTIA Network+, CompTIA Security+, AWS Cloud Practitioner</td>
                      <td class="p-2.5">Network Admin, Cloud Support Associate</td>
                      <td class="p-2.5 font-semibold text-emerald-600">$78,000 - $95,000</td>
                    </tr>
                    <tr>
                      <td class="p-2.5 font-bold text-indigo-700">Advanced</td>
                      <td class="p-2.5">AWS Certified Solutions Architect, Google Cloud Engineer, CISA</td>
                      <td class="p-2.5">DevOps Engineer, Systems Architect</td>
                      <td class="p-2.5 font-semibold text-emerald-600">$120,000 - $160,000</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Guide 6: Popular Cybersecurity Certifications -->
          <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
            <button
              @click="toggleAccordion('cyberCerts')"
              class="w-full flex items-center justify-between p-5 text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors"
            >
              <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 font-black text-xs flex items-center justify-center">🛡️</span>
                <div>
                  <h3 class="text-base font-bold text-slate-900">Popular Cybersecurity Certifications</h3>
                  <p class="text-xs text-slate-500 font-normal">Comparing Security+, CEH, CISSP, and Google Cybersecurity credentials</p>
                </div>
              </div>
              <svg :class="accordions.cyberCerts ? 'rotate-180' : ''" class="w-5 h-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div v-show="accordions.cyberCerts" class="p-5 border-t border-slate-100 bg-slate-50 text-sm text-slate-700 space-y-3">
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                <div class="bg-white p-3 rounded-lg border border-slate-200">
                  <span class="font-bold text-indigo-600 block mb-1">Google Cybersecurity</span>
                  <span class="text-slate-500 block mb-2">Zero prior experience needed</span>
                  <span class="font-semibold text-slate-800">SOC Analyst Tier 1</span>
                </div>
                <div class="bg-white p-3 rounded-lg border border-slate-200">
                  <span class="font-bold text-emerald-600 block mb-1">CompTIA Security+</span>
                  <span class="text-slate-500 block mb-2">DoD 8570 approved</span>
                  <span class="font-semibold text-slate-800">Security Analyst</span>
                </div>
                <div class="bg-white p-3 rounded-lg border border-slate-200">
                  <span class="font-bold text-amber-600 block mb-1">CEH (Certified Ethical Hacker)</span>
                  <span class="text-slate-500 block mb-2">Offensive security & pen-testing</span>
                  <span class="font-semibold text-slate-800">Penetration Tester</span>
                </div>
                <div class="bg-white p-3 rounded-lg border border-slate-200">
                  <span class="font-bold text-purple-600 block mb-1">CISSP ((ISC)²)</span>
                  <span class="text-slate-500 block mb-2">5+ years security experience</span>
                  <span class="font-semibold text-slate-800">CISO / Security Director</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- SECTION 3: HIGH INCOME SKILLS & HOW TO LEARN AI -->
      <section v-if="activeSection === 'high-income' || activeSection === 'all'" class="mb-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- High Income Skills -->
          <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 mb-4">
              <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">💰</span>
              <div>
                <h3 class="text-xl font-bold text-slate-900">High-Income Skills to Learn in 2026</h3>
                <p class="text-xs text-slate-500">Skills commanding $100K+ remote and on-site contracts</p>
              </div>
            </div>
            <div class="space-y-3 mt-6">
              <div v-for="(skill, idx) in highIncomeSkills" :key="idx" class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
                <div>
                  <h4 class="text-sm font-bold text-slate-900">{{ skill.name }}</h4>
                  <p class="text-xs text-slate-500">{{ skill.application }}</p>
                </div>
                <div class="text-right">
                  <span class="text-xs font-black text-emerald-600 block">{{ skill.salaryRange }}</span>
                  <router-link :to="'/courses?search=' + skill.name" class="text-[11px] font-bold text-indigo-600 hover:underline">View Courses</router-link>
                </div>
              </div>
            </div>
          </div>

          <!-- How to Learn Artificial Intelligence -->
          <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 mb-4">
              <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">🧠</span>
              <div>
                <h3 class="text-xl font-bold text-slate-900">How to Learn Artificial Intelligence</h3>
                <p class="text-xs text-slate-500">A structured 4-step path from zero code to training neural networks</p>
              </div>
            </div>
            <div class="space-y-4 mt-6">
              <div class="flex gap-4 items-start">
                <span class="w-7 h-7 rounded-full bg-purple-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                <div>
                  <h4 class="text-sm font-bold text-slate-900">AI Literacy & Prompt Engineering</h4>
                  <p class="text-xs text-slate-600">Start with <em>AI For Everyone</em> and <em>Prompt Engineering for ChatGPT</em> to master generative tools and LLM foundations without coding.</p>
                </div>
              </div>
              <div class="flex gap-4 items-start">
                <span class="w-7 h-7 rounded-full bg-purple-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                <div>
                  <h4 class="text-sm font-bold text-slate-900">Python & Mathematical Foundations</h4>
                  <p class="text-xs text-slate-600">Learn Python syntax, NumPy, Pandas, linear algebra, and calculus through <em>Python for Everybody</em> and statistical modeling.</p>
                </div>
              </div>
              <div class="flex gap-4 items-start">
                <span class="w-7 h-7 rounded-full bg-purple-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                <div>
                  <h4 class="text-sm font-bold text-slate-900">Machine Learning Specialization</h4>
                  <p class="text-xs text-slate-600">Study supervised learning, logistic regression, decision trees, and recommender systems with Andrew Ng.</p>
                </div>
              </div>
              <div class="flex gap-4 items-start">
                <span class="w-7 h-7 rounded-full bg-purple-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                <div>
                  <h4 class="text-sm font-bold text-slate-900">Deep Learning & Transformer Models</h4>
                  <p class="text-xs text-slate-600">Build CNNs, RNNs, transformers, and multimodal AI pipelines with PyTorch and TensorFlow in the <em>Deep Learning Specialization</em>.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- SECTION 4: SHARE YOUR LEARNING STORY -->
      <section v-if="activeSection === 'stories' || activeSection === 'all'" class="mb-14">
        <div class="bg-gradient-to-br from-indigo-900 to-slate-950 text-white rounded-3xl p-6 sm:p-10 shadow-xl">
          <div class="max-w-3xl mb-8">
            <h2 class="text-2xl sm:text-3xl font-black mb-2">Share Your Coursera & GELME Learning Story</h2>
            <p class="text-slate-300 text-sm">
              Did a professional certificate or live language immersion change your career or land you a promotion? Inspire millions of aspiring learners worldwide.
            </p>
          </div>

          <form @submit.prevent="submitStory" class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 mb-8 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-200 mb-1">Your Name</label>
                <input
                  v-model="newStory.name"
                  required
                  type="text"
                  placeholder="e.g. Sarah Jenkins"
                  class="w-full px-3.5 py-2.5 rounded-lg bg-slate-900/60 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-400"
                />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-200 mb-1">Your Current / New Role</label>
                <input
                  v-model="newStory.role"
                  required
                  type="text"
                  placeholder="e.g. Junior Cybersecurity Analyst at Deloitte"
                  class="w-full px-3.5 py-2.5 rounded-lg bg-slate-900/60 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-400"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-200 mb-1">Course or Certificate Completed</label>
                <input
                  v-model="newStory.course"
                  required
                  type="text"
                  placeholder="e.g. Google Cybersecurity Certificate"
                  class="w-full px-3.5 py-2.5 rounded-lg bg-slate-900/60 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-400"
                />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-200 mb-1">Outcome / Salary Increase (%)</label>
                <input
                  v-model="newStory.outcome"
                  type="text"
                  placeholder="e.g. +45% salary jump & remote role"
                  class="w-full px-3.5 py-2.5 rounded-lg bg-slate-900/60 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-400"
                />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-200 mb-1">Your Learning Story & Advice</label>
              <textarea
                v-model="newStory.message"
                required
                rows="3"
                placeholder="Describe how the coursework and hands-on projects helped you ace your interviews..."
                class="w-full px-3.5 py-2.5 rounded-lg bg-slate-900/60 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-400"
              ></textarea>
            </div>

            <div class="flex items-center justify-between pt-2">
              <span v-if="storySubmitted" class="text-xs font-bold text-emerald-400 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                Thank you! Your story has been posted to the community wall.
              </span>
              <span v-else></span>
              <button
                type="submit"
                class="px-5 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded-lg transition-all shadow-md"
              >
                Submit Story
              </button>
            </div>
          </form>

          <!-- Testimonial Wall -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div
              v-for="(story, idx) in stories"
              :key="idx"
              class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10"
            >
              <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-amber-400 to-rose-500 flex items-center justify-center font-bold text-white text-xs">
                  {{ story.name.split(' ').map(n => n[0]).join('') }}
                </div>
                <div>
                  <h4 class="text-xs font-bold text-white">{{ story.name }}</h4>
                  <p class="text-[10px] text-slate-300">{{ story.role }}</p>
                </div>
              </div>
              <p class="text-xs text-slate-200 italic mb-3">"{{ story.message }}"</p>
              <div class="flex items-center justify-between text-[10px] text-slate-400 border-t border-white/10 pt-2">
                <span>{{ story.course }}</span>
                <span class="text-emerald-400 font-bold">{{ story.outcome }}</span>
              </div>
            </div>
          </div>
        </div>
      </section>

    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const activeSection = ref('all');

// Aptitude test questions
const currentQuestionIndex = ref(0);
const aptitudeScores = ref({
  data: 0,
  cyber: 0,
  ai: 0,
  pm: 0,
  ux: 0,
  bookkeeping: 0,
});
const aptitudeCompleted = ref(false);

const aptitudeQuestions = [
  {
    question: "When presented with a complex problem, what is your natural first step?",
    options: [
      { label: "Analyze charts, uncover patterns, and verify raw numbers", domain: "data" },
      { label: "Search for security vulnerabilities, test permissions, and protect systems", domain: "cyber" },
      { label: "Experiment with cutting-edge prompts, automated scripts, and neural models", domain: "ai" },
      { label: "Break work into milestones, organize teams, and set delivery deadlines", domain: "pm" },
    ]
  },
  {
    question: "Which type of day-to-day task sounds most rewarding to you?",
    options: [
      { label: "Designing clean spreadsheets, balancing ledgers, and tracking cashflow", domain: "bookkeeping" },
      { label: "Sketching user journeys, wireframing intuitive apps, and user empathy interviews", domain: "ux" },
      { label: "Writing SQL queries and building visual Tableau dashboards for stakeholders", domain: "data" },
      { label: "Investigating packet traces, configuring firewalls, and incident response", domain: "cyber" },
    ]
  },
  {
    question: "What environment do you thrive in best?",
    options: [
      { label: "High-paced cross-functional team coordination and agile sprint ceremonies", domain: "pm" },
      { label: "Deep focus on code, algorithmic intelligence, and LLM orchestration", domain: "ai" },
      { label: "High-stakes defense, monitoring cyber telemetry, and zero-day threat analysis", domain: "cyber" },
      { label: "Structured financial audit trails and GAAP bookkeeping accuracy", domain: "bookkeeping" },
    ]
  },
  {
    question: "Which of the following software tools would you most love to master?",
    options: [
      { label: "Figma, Adobe XD, and interactive prototype testing", domain: "ux" },
      { label: "Python, TensorFlow, LangChain, and Google Gemini API", domain: "ai" },
      { label: "Wireshark, Splunk, Linux Bash, and SIEM security telemetry", domain: "cyber" },
      { label: "Tableau, SQL Workbench, and Microsoft Excel Pivot Tables", domain: "data" },
    ]
  },
  {
    question: "What is your primary career transformation goal for this year?",
    options: [
      { label: "Transitioning into tech with a high-paying entry-level remote job under 6 months", domain: "data" },
      { label: "Guarding enterprise infrastructure against international cyber threats", domain: "cyber" },
      { label: "Leading digital product teams and managing multimillion-dollar budgets", domain: "pm" },
      { label: "Pioneering generative AI workflows to automate business operations", domain: "ai" },
    ]
  }
];

const currentQuestion = computed(() => aptitudeQuestions[currentQuestionIndex.value]);

function selectOption(domain) {
  if (aptitudeScores.value[domain] !== undefined) {
    aptitudeScores.value[domain] += 1;
  }
  if (currentQuestionIndex.value < aptitudeQuestions.length - 1) {
    currentQuestionIndex.value++;
  } else {
    aptitudeCompleted.value = true;
  }
}

function resetAptitudeTest() {
  currentQuestionIndex.value = 0;
  aptitudeScores.value = { data: 0, cyber: 0, ai: 0, pm: 0, ux: 0, bookkeeping: 0 };
  aptitudeCompleted.value = false;
}

const aptitudeRecommendation = computed(() => {
  const scores = aptitudeScores.value;
  let highest = 'data';
  let max = -1;
  for (const [k, v] of Object.entries(scores)) {
    if (v > max) {
      max = v;
      highest = k;
    }
  }

  const recommendations = {
    data: {
      title: "Data Analytics & Business Intelligence",
      certificate: "Google Data Analytics Certificate",
      courseSlug: "google-data-analytics-certificate",
      salary: "$92,000 / yr",
      time: "3-6 Months",
      roles: "480,000+ globally",
      description: "You have a natural analytical mindset. Learn SQL, Tableau, R programming, and spreadsheet modeling to help organizations make data-informed strategic decisions."
    },
    cyber: {
      title: "Cybersecurity Analyst",
      certificate: "Google Cybersecurity Certificate",
      courseSlug: "google-cybersecurity-certificate",
      salary: "$103,000 / yr",
      time: "4-6 Months",
      roles: "750,000+ globally",
      description: "You excel at risk analysis, security architecture, and system defense. Learn Linux, Python, SIEM detection, and prepare for the CompTIA Security+."
    },
    ai: {
      title: "Artificial Intelligence Specialist",
      certificate: "Google AI Certificate",
      courseSlug: "google-ai-certificate",
      salary: "$128,000 / yr",
      time: "2-4 Months",
      roles: "350,000+ globally",
      description: "You are future-focused and excited about generative tech. Master prompt engineering, machine learning workflows, and responsible AI implementation."
    },
    pm: {
      title: "Project Management Lead",
      certificate: "Google Project Management Certificate",
      courseSlug: "google-project-management-certificate",
      salary: "$98,000 / yr",
      time: "3-6 Months",
      roles: "1.2 Million globally",
      description: "You thrive when empowering people, managing budgets, and steering Agile sprints. Complete 100+ hours and qualify for PMI CAPM exam waiver."
    },
    ux: {
      title: "UX/UI Product Designer",
      certificate: "Google UX Design Certificate",
      courseSlug: "google-ux-design-certificate",
      salary: "$95,000 / yr",
      time: "3-6 Months",
      roles: "290,000+ globally",
      description: "You have strong empathy and a visual flair. Build a professional 3-project design portfolio in Figma and Adobe XD from scratch."
    },
    bookkeeping: {
      title: "Bookkeeper & Financial Specialist",
      certificate: "Intuit Academy Bookkeeping Certificate",
      courseSlug: "intuit-academy-bookkeeping-certificate",
      salary: "$58,000 / yr",
      time: "2-4 Months",
      roles: "220,000+ globally",
      description: "You have an eye for precision, double-entry bookkeeping, and accounting regulations. Prepares you for Intuit QuickBooks certified roles."
    }
  };

  return recommendations[highest] || recommendations.data;
});

// Accordions state
const accordions = ref({
  capm: true,
  comptiaA: false,
  comptiaSec: false,
  pmp: false,
  essentialIT: false,
  cyberCerts: false
});

function toggleAccordion(key) {
  accordions.value[key] = !accordions.value[key];
}

// High income skills
const highIncomeSkills = [
  { name: "Artificial Intelligence (AI) & Prompt Engineering", application: "LLM fine-tuning, RAG pipelines, enterprise agents", salaryRange: "$130k - $190k" },
  { name: "Cybersecurity & Cloud Threat Detection", application: "Zero trust architecture, SIEM triage, penetration testing", salaryRange: "$115k - $175k" },
  { name: "Data Analytics & SQL Architecture", application: "BigQuery, Snowflake, dimensional modeling, BI metrics", salaryRange: "$95k - $145k" },
  { name: "Python Automation & API Engineering", application: "Microservices, ETL pipelines, automated web workflows", salaryRange: "$105k - $160k" },
  { name: "Agile Project & Product Management", application: "Scrum, Jira workflows, cross-functional delivery leadership", salaryRange: "$98k - $155k" },
];

// Story submission
const newStory = ref({
  name: '',
  role: '',
  course: '',
  outcome: '',
  message: ''
});
const storySubmitted = ref(false);

const stories = ref([
  {
    name: "Elena Rostova",
    role: "SOC Analyst at CyberVault",
    course: "Google Cybersecurity Certificate",
    outcome: "+65% Salary Jump",
    message: "The hands-on Linux and Python labs gave me the exact skills tested during my interview. I landed the role 3 weeks after earning my certificate!"
  },
  {
    name: "Marcus Thorne",
    role: "Senior Project Manager",
    course: "Google PM + PMP Prep",
    outcome: "Promoted to Team Lead",
    message: "The 23 contact hours requirement was completely waived thanks to Coursera & GELME. Passed my PMI exam on the first attempt."
  },
  {
    name: "Kenji Takahashi",
    role: "AI Workflow Specialist",
    course: "Deep Learning Specialization",
    outcome: "Remote Global Contract",
    message: "Andrew Ng's clear explanations coupled with the 25-minute live speaking practice in Online Global Classroom gave me total confidence."
  }
]);

function submitStory() {
  stories.value.unshift({
    name: newStory.value.name,
    role: newStory.value.role,
    course: newStory.value.course,
    outcome: newStory.value.outcome || "Career Transformation",
    message: newStory.value.message
  });
  storySubmitted.value = true;
  newStory.value = { name: '', role: '', course: '', outcome: '', message: '' };
  setTimeout(() => { storySubmitted.value = false; }, 6000);
}
</script>
