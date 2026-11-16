<template>
  <div class="min-h-screen bg-slate-950 text-white selection:bg-indigo-500 selection:text-white">
    <!-- Header -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 bg-gradient-to-b from-slate-900 to-slate-950 text-center">
      <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/15 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-4">
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        <span>24/7 Global Learner Support</span>
      </div>
      <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight mb-3">
        Get in Touch with GELME Consortium
      </h1>
      <p class="text-sm text-slate-400 max-w-xl mx-auto">
        Have questions about university degree enrollment, instructor creator tools, or 25-minute live immersion slots? Our team is available 24/7.
      </p>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 lg:grid-cols-2 gap-12">
      <!-- Contact Form -->
      <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl">
        <h2 class="text-xl font-black text-white mb-2">Send a Direct Message</h2>
        <p class="text-xs text-slate-400 mb-6">Our academic council and technical team reply within 2 hours.</p>

        <!-- Success Alert -->
        <div v-if="submitted" class="p-4 bg-emerald-950/60 border border-emerald-700 text-emerald-300 text-xs rounded-2xl mb-6 flex items-center gap-2">
          <span>✓</span>
          <span>Thank you! Your message has been received. Ticket #GELME-{{ Math.floor(Math.random() * 90000 + 10000) }} has been opened.</span>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4 text-xs">
          <div>
            <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Your Full Name</label>
            <input
              v-model="name"
              type="text"
              required
              placeholder="e.g. Dr. Jane Smith"
              class="w-full p-3 bg-slate-950/70 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400"
            />
          </div>

          <div>
            <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Email Address</label>
            <input
              v-model="email"
              type="email"
              required
              placeholder="name@university.edu"
              class="w-full p-3 bg-slate-950/70 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400"
            />
          </div>

          <div>
            <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1">Inquiry Type</label>
            <select
              v-model="inquiryType"
              class="w-full p-3 bg-slate-950/70 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-indigo-400"
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
              class="w-full p-3 bg-slate-950/70 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-400"
            ></textarea>
          </div>

          <button
            type="submit"
            class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2"
          >
            <span>Send Message →</span>
          </button>
        </form>
      </div>

      <!-- Contact Info & Global Hubs -->
      <div class="space-y-6">
        <div class="p-6 rounded-3xl bg-slate-900/60 border border-slate-800">
          <div class="w-10 h-10 rounded-2xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center text-xl mb-3">
            📍
          </div>
          <h3 class="text-base font-bold text-white mb-1">Consortium Headquarters</h3>
          <p class="text-xs text-slate-400 leading-relaxed">
            GELME Education Tower, Bole Sub-City, Addis Ababa, Ethiopia & Stanford Research Park, Palo Alto, CA 94304, USA.
          </p>
        </div>

        <div class="p-6 rounded-3xl bg-slate-900/60 border border-slate-800">
          <div class="w-10 h-10 rounded-2xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center text-xl mb-3">
            ✉️
          </div>
          <h3 class="text-base font-bold text-white mb-1">Direct Communications</h3>
          <p class="text-xs text-slate-400 leading-relaxed">
            <strong>Admissions & Student Care:</strong> support@gelme.global<br />
            <strong>Institutional Partnerships:</strong> enterprise@gelme.global<br />
            <strong>Instructor Guild:</strong> faculty@gelme.global
          </p>
        </div>

        <div class="p-6 rounded-3xl bg-slate-900/60 border border-slate-800">
          <div class="w-10 h-10 rounded-2xl bg-purple-600/20 text-purple-400 flex items-center justify-center text-xl mb-3">
            ⚡
          </div>
          <h3 class="text-base font-bold text-white mb-1">Live AI Study Companion</h3>
          <p class="text-xs text-slate-400 leading-relaxed">
            Need immediate tutoring, homework debugging, or CEFR language placement help? Open the 24/7 AI Tutor widget in the lower right corner of any page.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const name = ref('');
const email = ref('');
const inquiryType = ref('student_support');
const message = ref('');
const submitted = ref(false);

function handleSubmit() {
  submitted.value = true;
  name.value = '';
  email.value = '';
  message.value = '';
}
</script>
