<template>
  <div class="relative min-h-screen w-full flex flex-col justify-between overflow-x-hidden bg-slate-950 text-white selection:bg-indigo-500 selection:text-white">
    <!-- Full-Page WebGL2 Topography Interactive Background (Same as Login and Register) -->
    <div class="fixed inset-0 w-screen h-screen overflow-hidden z-0">
      <Topography
        color="#4f7cff"
        :lineCount="22"
        :lineWidth="1.2"
        :elevation="1"
        :foreground="0.2"
        :tilt="32"
        :speed="0.25"
        direction="forward"
        :glow="0.6"
        :depthOfField="0.4"
        :fog="0.35"
        hoverEffect="lift"
        clickEffect="pulse"
        :majorLines="true"
        :scale="1"
        :detail="0.35"
        :morph="0.2"
        :brightness="1"
        markers="none"
        :glitch="0"
        :opacity="1"
        :mouseInteraction="true"
        :mouseRadius="1"
        :mouseStrength="0.6"
        :trail="0.3"
        :parallax="0.5"
        :intro="true"
        :lightMode="false"
        class="w-full h-full"
      />
    </div>

    <!-- Radial Vignette Overlay for Crisp Contrast (Click-through to allow canvas interaction) -->
    <div class="fixed inset-0 pointer-events-none bg-radial-gradient from-transparent via-slate-950/40 to-slate-950/80 z-[1]"></div>

    <!-- Main Content Container Above Canvas -->
    <div class="relative z-10 flex-1">
      <!-- Header Banner -->
      <section class="py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800/80 bg-slate-950/60 backdrop-blur-md text-center">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/15 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-4">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
          <span>24/7 Global Learner Support</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight mb-3 drop-shadow-md">
          Get in Touch with GELME Consortium
        </h1>
        <p class="text-sm text-slate-300 max-w-xl mx-auto drop-shadow-sm">
          Have questions about university degree enrollment, instructor creator tools, or 25-minute live immersion slots? Our team is available 24/7.
        </p>
      </section>

      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Form Card -->
        <div class="p-8 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800 shadow-2xl">
          <h2 class="text-xl font-black text-white mb-2">Send a Direct Message</h2>
          <p class="text-xs text-slate-400 mb-6">Our academic council and technical team reply within 2 hours.</p>

          <!-- Success Alert -->
          <div v-if="submitted" class="p-4 bg-emerald-950/70 border border-emerald-700 text-emerald-300 text-xs rounded-2xl mb-6 flex items-center gap-2 shadow-md">
            <span>✓</span>
            <span>Thank you! Your message has been received. Ticket #GELME-{{ ticketId }} has been opened.</span>
          </div>

          <form @submit.prevent="handleSubmit" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Your Full Name</label>
              <input
                v-model="name"
                type="text"
                required
                placeholder="e.g. Dr. Jane Smith"
                class="w-full p-3 bg-slate-950/70 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Email Address</label>
              <input
                v-model="email"
                type="email"
                required
                placeholder="name@university.edu"
                class="w-full p-3 bg-slate-950/70 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Inquiry Type</label>
              <select
                v-model="inquiryType"
                class="w-full p-3 bg-slate-950/70 border border-slate-700/80 rounded-xl text-white focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors"
              >
                <option value="student_support">Student & Learning Support</option>
                <option value="instructor_onboarding">Instructor & Creator Studio Onboarding</option>
                <option value="institution_partnership">University & Institutional Enterprise Licensing</option>
                <option value="certificate_verification">Cryptographic Certificate Verification Inquiry</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Message</label>
              <textarea
                v-model="message"
                rows="4"
                required
                placeholder="Describe your request or question in detail..."
                class="w-full p-3 bg-slate-950/70 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors"
              ></textarea>
            </div>

            <button
              type="submit"
              class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]"
            >
              <span>Send Message →</span>
            </button>
          </form>
        </div>

        <!-- Contact Info & Global Hubs -->
        <div class="space-y-6">
          <div class="p-6 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800 shadow-xl hover:border-slate-700 transition-all">
            <div class="w-10 h-10 rounded-2xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center text-xl mb-3 shadow-md">
              📍
            </div>
            <h3 class="text-base font-bold text-white mb-1">Consortium Headquarters</h3>
            <p class="text-xs text-slate-300 leading-relaxed">
              GELME Education Tower, Bole Sub-City, Addis Ababa, Ethiopia & Stanford Research Park, Palo Alto, CA 94304, USA.
            </p>
          </div>

          <div class="p-6 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800 shadow-xl hover:border-slate-700 transition-all">
            <div class="w-10 h-10 rounded-2xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center text-xl mb-3 shadow-md">
              ✉️
            </div>
            <h3 class="text-base font-bold text-white mb-1">Direct Communications</h3>
            <p class="text-xs text-slate-300 leading-relaxed">
              <strong class="text-white">Admissions & Student Care:</strong> support@gelme.global<br />
              <strong class="text-white">Institutional Partnerships:</strong> enterprise@gelme.global<br />
              <strong class="text-white">Instructor Guild:</strong> faculty@gelme.global
            </p>
          </div>

          <div class="p-6 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800 shadow-xl hover:border-slate-700 transition-all">
            <div class="w-10 h-10 rounded-2xl bg-purple-600/20 text-purple-400 flex items-center justify-center text-xl mb-3 shadow-md">
              ⚡
            </div>
            <h3 class="text-base font-bold text-white mb-1">Live AI Study Companion</h3>
            <p class="text-xs text-slate-300 leading-relaxed">
              Need immediate tutoring, homework debugging, or CEFR language placement help? Open the 24/7 AI Tutor widget in the lower right corner of any page.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Topography from '@/components/common/Topography';

const name = ref('');
const email = ref('');
const inquiryType = ref('student_support');
const message = ref('');
const submitted = ref(false);
const ticketId = ref(Math.floor(Math.random() * 90000 + 10000));

function handleSubmit() {
  submitted.value = true;
  ticketId.value = Math.floor(Math.random() * 90000 + 10000);
  name.value = '';
  email.value = '';
  message.value = '';
}
</script>
