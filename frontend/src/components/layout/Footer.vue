<template>
  <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
        <div class="col-span-2">
          <div class="flex items-center gap-2.5 mb-4">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-500 via-purple-500 to-emerald-400 flex items-center justify-center text-white font-black text-lg">
              G
            </div>
            <span class="text-xl font-extrabold text-white tracking-tight">GELME</span>
          </div>
          <p class="text-sm text-slate-400 max-w-sm mb-6 leading-relaxed">
            Global Education & Learning Management Ecosystem — Synthesizing the on-demand scale of Udemy, university excellence of Coursera, open micro-credentials of edX, and 25-minute live native immersion of Online Global Classroom.
          </p>
          <div class="flex items-center gap-4 text-xs text-slate-400">
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> 99.98% Platform Uptime</span>
            <span>•</span>
            <span>ISO/IEC/IEEE 29148 Compliant</span>
          </div>
        </div>

        <div>
          <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Learning Models</h4>
          <ul class="space-y-2.5 text-sm">
            <li><router-link to="/courses" class="hover:text-white transition-colors">On-Demand Bootcamps</router-link></li>
            <li><router-link to="/courses?type=cohort_based" class="hover:text-white transition-colors">University Specializations</router-link></li>
            <li><router-link to="/global-classroom" class="hover:text-emerald-400 font-medium transition-colors">Online Global Classroom (Live)</router-link></li>
            <li><router-link to="/verify" class="hover:text-white transition-colors">Verifiable Credentials</router-link></li>
          </ul>
        </div>

        <div>
          <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Global Network</h4>
          <ul class="space-y-2.5 text-sm">
            <li><a href="#" class="hover:text-white transition-colors">Stanford Online Lab</a></li>
            <li><a href="#" class="hover:text-white transition-colors">MITx MicroMasters</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Online Global Classroom HQ</a></li>
            <li><a href="#" class="hover:text-white transition-colors">14+ Languages Immersion</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Enterprise & Legal</h4>
          <ul class="space-y-2.5 text-sm">
            <li><router-link to="/instructor" class="hover:text-white transition-colors">Teach on GELME</router-link></li>
            <li><a href="#" class="hover:text-white transition-colors">GDPR & Data Privacy</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
            <li><router-link to="/verify" class="hover:text-white transition-colors">Credential Audit Ledger</router-link></li>
          </ul>
        </div>
      </div>

      <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
        <p>© 2026 GELME (Global Education & Learning Management Ecosystem). All rights reserved.</p>
        <div class="flex items-center gap-6">
          <span>English (US)</span>
          <span>USD ($)</span>
          <span class="text-emerald-400 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            SSL Verified
          </span>
        </div>
      </div>
    </div>
  </footer>
</template>
