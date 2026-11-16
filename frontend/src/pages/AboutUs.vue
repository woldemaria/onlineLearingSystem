<template>
  <div class="min-h-screen bg-slate-950 text-white selection:bg-indigo-500 selection:text-white">
    <!-- Hero Banner -->
    <section class="relative py-20 px-4 sm:px-6 lg:px-8 border-b border-slate-800 bg-gradient-to-b from-slate-900 to-slate-950 overflow-hidden">
      <div class="max-w-5xl mx-auto text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/15 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-4">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
          <span>About GELME Consortium</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight mb-4">
          Democratizing Higher Education, Micro-Credentials & Native Immersion
        </h1>
        <p class="text-sm sm:text-base text-slate-400 max-w-3xl mx-auto leading-relaxed">
          GELME (Global Education & Learning Management Ecosystem) bridges the gap between self-paced marketplace agility and university-accredited excellence, synthesizing the best of <strong>Udemy</strong>, <strong>Coursera</strong>, <strong>edX</strong>, and <strong>Online Global Classroom</strong> into a single super-platform.
        </p>
      </div>
    </section>

    <!-- 4 Super-Platform Pillars Grid -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <span class="text-xs font-bold uppercase tracking-wider text-indigo-400">Architectural Framework</span>
        <h2 class="text-2xl sm:text-3xl font-black text-white mt-1">The Four Pillars of GELME</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Udemy Pillar -->
        <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 hover:border-indigo-500/50 transition-all flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-400 border border-rose-500/20 flex items-center justify-center text-2xl mb-4 font-black">
              U
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Udemy Marketplace Scale</h3>
            <p class="text-xs text-slate-400 leading-relaxed">
              Thousands of on-demand practical skill courses, Docker & Kubernetes bootcamps, and developer labs taught by industry practitioners.
            </p>
          </div>
          <div class="mt-4 pt-4 border-t border-slate-800 text-[11px] font-bold text-rose-400">
            On-Demand Lifetime Access →
          </div>
        </div>

        <!-- Coursera Pillar -->
        <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 hover:border-indigo-500/50 transition-all flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center text-2xl mb-4 font-black">
              C
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Coursera Academic Rigor</h3>
            <p class="text-xs text-slate-400 leading-relaxed">
              Cohort-based university degree specializations, peer-reviewed capstone projects, and graded assignments with financial aid accessibility.
            </p>
          </div>
          <div class="mt-4 pt-4 border-t border-slate-800 text-[11px] font-bold text-indigo-400">
            Accredited University Tracks →
          </div>
        </div>

        <!-- edX Pillar -->
        <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 hover:border-indigo-500/50 transition-all flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-400 border border-purple-500/20 flex items-center justify-center text-2xl mb-4 font-black">
              E
            </div>
            <h3 class="text-lg font-bold text-white mb-2">edX Verifiable Credentials</h3>
            <p class="text-xs text-slate-400 leading-relaxed">
              Tamper-evident cryptographic micro-credentials verified by SHA-256 signatures, institutional ledgers, and employer audit portals.
            </p>
          </div>
          <div class="mt-4 pt-4 border-t border-slate-800 text-[11px] font-bold text-purple-400">
            Cryptographic Audit Ready →
          </div>
        </div>

        <!-- Online Global Classroom Pillar -->
        <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 hover:border-emerald-500/50 transition-all flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-2xl mb-4 font-black">
              O
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Online Global Classroom</h3>
            <p class="text-xs text-slate-400 leading-relaxed">
              25-minute live spoken immersion with native teachers across 14+ languages (Amharic, Spanish, Mandarin, English, French, and more).
            </p>
          </div>
          <div class="mt-4 pt-4 border-t border-slate-800 text-[11px] font-bold text-emerald-400">
            Real-Time WebRTC Immersion →
          </div>
        </div>
      </div>
    </section>

    <!-- Student Testimonials Section ("testmonia") -->
    <section class="py-16 bg-slate-900/50 border-t border-b border-slate-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Global Testimonials</span>
          <h2 class="text-2xl sm:text-3xl font-black text-white mt-1">What Our Learners Achieve</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800">
            <div class="flex items-center gap-1 text-amber-400 text-sm mb-3">★★★★★</div>
            <p class="text-xs text-slate-300 leading-relaxed mb-4">
              "The ability to watch full-stack distributed system bootcamps and immediately book a 25-minute live speaking practice with native instructors helped me secure a remote DevOps role in Berlin."
            </p>
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                AK
              </div>
              <div>
                <div class="text-xs font-bold text-white">Alemayehu Kassa</div>
                <div class="text-[10px] text-slate-500">Cloud Architect • Addis Ababa</div>
              </div>
            </div>
          </div>

          <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800">
            <div class="flex items-center gap-1 text-amber-400 text-sm mb-3">★★★★★</div>
            <p class="text-xs text-slate-300 leading-relaxed mb-4">
              "Stanford Online's AI specialization on GELME, combined with verifiable cryptographic certificates on edX, was verified by my employer instantly. Game-changing quality."
            </p>
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-purple-600 text-white font-bold flex items-center justify-center text-xs">
                SR
              </div>
              <div>
                <div class="text-xs font-bold text-white">Sofia Ramirez</div>
                <div class="text-[10px] text-slate-500">AI Engineer • Madrid</div>
              </div>
            </div>
          </div>

          <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800">
            <div class="flex items-center gap-1 text-amber-400 text-sm mb-3">★★★★★</div>
            <p class="text-xs text-slate-300 leading-relaxed mb-4">
              "Online Global Classroom's high-intensity 25-minute oral sessions gave me confidence in Mandarin Chinese that traditional textbooks couldn't deliver in years."
            </p>
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-xs">
                MC
              </div>
              <div>
                <div class="text-xs font-bold text-white">Marcus Chen</div>
                <div class="text-[10px] text-slate-500">Product Manager • Singapore</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
