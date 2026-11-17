<template>
  <div class="min-h-screen bg-slate-950 text-white selection:bg-indigo-500 selection:text-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <!-- Breadcrumbs & Badge -->
      <div class="mb-6 flex items-center justify-between">
        <router-link to="/" class="text-xs font-bold text-slate-400 hover:text-indigo-400 flex items-center gap-1.5 transition-colors">
          <span>←</span>
          <span>Back to Home</span>
        </router-link>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-950/80 text-emerald-400 border border-emerald-800">
          <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
          <span>GDPR, FERPA & ISO 27001 Compliant</span>
        </div>
      </div>

      <!-- Hero Header -->
      <div class="border-b border-slate-800 pb-10 mb-10">
        <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">
          Global Privacy Policy
        </h1>
        <p class="text-slate-400 text-sm sm:text-base mt-3 leading-relaxed">
          How GELME (Global Education & Learning Management Ecosystem) protects your personal identity, academic records, live video classrooms, and verifiable credentials.
        </p>
        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-400 mt-4">
          <span>Effective Date: <strong>November 17, 2026</strong></span>
          <span>•</span>
          <span>Version 3.2</span>
          <span>•</span>
          <span class="text-indigo-400">DPO Contact: privacy@gelme.global</span>
        </div>
      </div>

      <!-- Core Privacy Pillars (Quick Cards) -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12">
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800">
          <span class="text-2xl mb-2 block">🔒</span>
          <h3 class="text-sm font-bold text-white mb-1">Encrypted Video Classrooms</h3>
          <p class="text-xs text-slate-400 leading-relaxed">
            Live 25-minute WebRTC video streams are encrypted end-to-end. We never record audio or video without explicit mutual consent.
          </p>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800">
          <span class="text-2xl mb-2 block">🎓</span>
          <h3 class="text-sm font-bold text-white mb-1">FERPA Academic Privacy</h3>
          <p class="text-xs text-slate-400 leading-relaxed">
            Your course grades, quiz progress, and financial aid applications are strictly private and never sold or shared with third parties.
          </p>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800">
          <span class="text-2xl mb-2 block">📜</span>
          <h3 class="text-sm font-bold text-white mb-1">Zero Tracker Cookies</h3>
          <p class="text-xs text-slate-400 leading-relaxed">
            We store only essential authentication session tokens and cart items in localStorage. No advertising trackers or data brokers.
          </p>
        </div>
      </div>

      <!-- Detailed Sections -->
      <div class="space-y-10 text-xs sm:text-sm text-slate-300 leading-relaxed">
        <!-- Section 1 -->
        <section class="space-y-3">
          <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
            <span class="text-indigo-400">1.</span>
            <span>Information We Collect</span>
          </h2>
          <p>
            When you register, enroll in courses, or join live classroom immersion on GELME, we collect:
          </p>
          <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-400">
            <li><strong>Account Identity:</strong> Your full name, email address, password hash (bcrypt), and account role (Student, Instructor, Scholar).</li>
            <li><strong>Academic History:</strong> Course enrollments, lesson completion status, quiz attempts, submitted assignments, and CEFR oral language proficiency results.</li>
            <li><strong>Financial Aid Applications:</strong> Education level, annual household income tier, and personal statements submitted under our Coursera-standard scholarship program.</li>
            <li><strong>Credentials:</strong> Cryptographic verification IDs and SHA-256 certificate hashes published to the audit verification ledger.</li>
          </ul>
        </section>

        <!-- Section 2 -->
        <section class="space-y-3">
          <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
            <span class="text-indigo-400">2.</span>
            <span>Live Virtual Classroom & Video Meeting Privacy</span>
          </h2>
          <p>
            Our high-intensity 25-minute live native immersion sessions and group classrooms run over WebRTC peer-to-peer and secure media bridges:
          </p>
          <div class="p-4 rounded-2xl bg-indigo-950/40 border border-indigo-700/50 text-indigo-200">
            <strong>Zero Automatic Meeting Recording:</strong> We do NOT record, transcribe, or store audio, webcam video, or screen-shares from live rooms unless an instructor and all enrolled participants explicitly activate opt-in recording for educational review.
          </div>
          <p>
            Live chat messages sent during an active classroom session are ephemeral and automatically cleared upon session conclusion.
          </p>
        </section>

        <!-- Section 3 -->
        <section class="space-y-3">
          <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
            <span class="text-indigo-400">3.</span>
            <span>Public Ledger & Verifiable Credentials</span>
          </h2>
          <p>
            When you complete an accredited bootcamp or Stanford Online specialization, GELME issues a verifiable credential with a public QR code and cryptographic verification link (e.g. <code class="text-indigo-300 bg-slate-900 px-1 py-0.5 rounded">/verify/:code</code>).
          </p>
          <p>
            The public verification page displays solely: recipient name, credential title, issuing university consortium, completion date, and verified grade. Sensitive financial details or contact info are never publicly accessible.
          </p>
        </section>

        <!-- Section 4 -->
        <section class="space-y-3">
          <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
            <span class="text-indigo-400">4.</span>
            <span>Your Rights Under GDPR & International Privacy Laws</span>
          </h2>
          <p>
            You hold complete sovereign ownership of your educational and personal data:
          </p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
            <div class="p-3 bg-slate-900 rounded-xl border border-slate-800">
              <strong class="text-white block mb-0.5">Right of Access</strong>
              <span>You may export a full transcript of your learning history, enrollments, and invoices at any time.</span>
            </div>
            <div class="p-3 bg-slate-900 rounded-xl border border-slate-800">
              <strong class="text-white block mb-0.5">Right to Erasure ("Be Forgotten")</strong>
              <span>Request permanent deletion of your account and personal identifiers by contacting our DPO.</span>
            </div>
            <div class="p-3 bg-slate-900 rounded-xl border border-slate-800">
              <strong class="text-white block mb-0.5">Right to Rectification</strong>
              <span>Update your name, legal billing info, or email address directly in your student profile.</span>
            </div>
            <div class="p-3 bg-slate-900 rounded-xl border border-slate-800">
              <strong class="text-white block mb-0.5">Opt-Out of Communications</strong>
              <span>Manage your email preferences and platform alerts with one click.</span>
            </div>
          </div>
        </section>

        <!-- Section 5 -->
        <section class="space-y-3 border-t border-slate-800 pt-8">
          <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
            <span class="text-indigo-400">5.</span>
            <span>Contact Our Data Protection Officer (DPO)</span>
          </h2>
          <p>
            For privacy inquiries, FERPA compliance requests, or data deletion tickets, reach out to our legal and data protection council:
          </p>
          <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
              <div class="font-bold text-white">GELME Consortium Data Protection Office</div>
              <div class="text-slate-400 text-xs">Addis Ababa & Palo Alto • privacy@gelme.global</div>
            </div>
            <router-link
              to="/contact"
              class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-colors shadow-md"
            >
              Submit Privacy Inquiry →
            </router-link>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup>
// Privacy Policy Page
</script>
