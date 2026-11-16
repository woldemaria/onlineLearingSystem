<template>
  <div class="min-h-screen bg-slate-900 text-slate-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Search Bar -->
      <div class="bg-slate-800 border border-slate-700 rounded-3xl p-6 sm:p-8 shadow-xl mb-10 text-center print:hidden">
        <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 bg-indigo-950 px-3 py-1 rounded-full border border-indigo-800">
          edX & Coursera Cryptographic Registry
        </span>
        <h1 class="text-2xl sm:text-3xl font-black text-white mt-3 mb-2">
          Public Credential Verification Portal
        </h1>
        <p class="text-xs text-slate-400 max-w-lg mx-auto mb-6">
          Verify authentic certificates, degrees, and language proficiency micro-credentials issued by GELME, Stanford Online, and Online Global Classroom.
        </p>

        <!-- Search Bar with Quick Sample Buttons -->
        <div class="max-w-lg mx-auto space-y-3">
          <div class="flex gap-2">
            <input
              v-model="verificationCode"
              @keyup.enter="searchCertificate"
              type="text"
              placeholder="Enter Certificate Code (e.g. GELME-CERT-2026-X892K)"
              class="flex-1 px-4 py-3 text-xs bg-slate-900 border border-slate-700 rounded-xl text-white font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 uppercase font-bold"
            />
            <button
              @click="searchCertificate"
              class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-xl text-xs transition-colors"
            >
              Verify
            </button>
          </div>

          <!-- Sample Codes -->
          <div class="flex flex-wrap items-center justify-center gap-1.5 text-[11px] text-slate-400">
            <span>Try sample codes:</span>
            <button
              v-for="code in sampleCodes"
              :key="code"
              @click="setAndSearch(code)"
              class="px-2 py-0.5 bg-slate-700/60 hover:bg-slate-700 text-indigo-300 font-mono rounded text-[10px]"
            >
              {{ code }}
            </button>
          </div>
        </div>
      </div>

      <!-- Verified Certificate Presentation Card -->
      <div
        v-if="cert"
        class="bg-white text-slate-900 rounded-3xl p-8 sm:p-14 shadow-2xl border-8 border-amber-400/90 relative overflow-hidden print:border-4 print:p-8 print:shadow-none"
      >
        <!-- Background Security Guilloche Texture / Watermark -->
        <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:16px_16px] opacity-40 pointer-events-none"></div>

        <!-- Certificate Header -->
        <div class="flex items-center justify-between border-b-2 border-slate-100 pb-8 mb-8 relative z-10">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-slate-900 to-indigo-950 text-white flex items-center justify-center font-black text-2xl shadow-xl">
              G
            </div>
            <div>
              <div class="text-xl font-black tracking-tight text-slate-900">GELME GLOBAL CONSORTIUM</div>
              <div class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                {{ cert.issuer_name || 'Stanford Online & Accredited University Partners' }}
              </div>
            </div>
          </div>

          <div class="text-right">
            <span class="px-3.5 py-1.5 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-full text-xs font-black flex items-center gap-1.5 shadow-sm">
              <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
              OFFICIALLY VERIFIED
            </span>
            <div class="font-mono text-xs text-slate-400 mt-1 font-bold">{{ cert.verification_code }}</div>
          </div>
        </div>

        <!-- Certificate Body -->
        <div class="text-center py-6 space-y-4 relative z-10">
          <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">This is to certify that</p>
          <h2 class="text-3xl sm:text-5xl font-serif font-black text-slate-900 italic tracking-wide">
            {{ cert.recipient_name }}
          </h2>
          <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto leading-relaxed">
            has successfully satisfied all rigorous curriculum specifications, module examinations, and enterprise architecture capstone requirements for the professional program:
          </p>
          <h3 class="text-xl sm:text-2xl font-black text-indigo-900 tracking-tight">
            {{ cert.title }}
          </h3>
          <div class="text-xs text-slate-500 font-medium">
            Conferred on <strong>{{ cert.issued_at }}</strong> with a cumulative grade of <strong class="text-emerald-700">{{ cert.grade }}</strong> ({{ cert.credits }})
          </div>

          <!-- Skills Badges -->
          <div class="flex flex-wrap justify-center gap-1.5 pt-2">
            <span
              v-for="skill in cert.skills"
              :key="skill"
              class="px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-700 text-[11px] font-semibold rounded-lg"
            >
              {{ skill }}
            </span>
          </div>
        </div>

        <!-- Cryptographic Ledger & Signatures -->
        <div class="pt-8 mt-8 border-t-2 border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-6 items-end text-xs relative z-10">
          <!-- Signatures -->
          <div class="text-center sm:text-left space-y-1">
            <div class="font-serif italic text-lg text-slate-800 font-bold border-b border-slate-300 pb-1">
              Dr. Elena Rostova
            </div>
            <div class="text-[10px] uppercase tracking-wider text-slate-500 font-bold">
              Chief Academic Officer & Consortium Chair
            </div>
          </div>

          <!-- Official Gold Seal -->
          <div class="flex justify-center">
            <div class="w-24 h-24 rounded-full border-4 border-amber-400 bg-amber-50 flex items-center justify-center text-center p-3 shadow-inner">
              <span class="text-[9px] font-black uppercase text-amber-900 tracking-tighter leading-tight">
                OFFICIAL SEAL OF ACCREDITATION & VERIFICATION
              </span>
            </div>
          </div>

          <!-- QR Proof & SHA-256 Ledger Hash -->
          <div class="text-center sm:text-right font-mono text-[10px] text-slate-500 space-y-1">
            <div class="text-slate-800 font-bold">SHA-256 Tamper-Proof Hash:</div>
            <div class="truncate max-w-[220px] ml-auto text-slate-500 font-bold bg-slate-100 p-1 rounded">
              {{ cert.blockchain_hash }}
            </div>
            <div class="text-emerald-700 font-bold">Immutable Ledger Audit ID: #{{ cert.id }}</div>
          </div>
        </div>

        <!-- Print & Share Actions -->
        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between print:hidden">
          <span class="text-xs text-slate-500">
            Public verification link: <a :href="cert.verification_url" class="text-indigo-600 font-mono font-bold hover:underline">{{ cert.verification_code }}</a>
          </span>

          <div class="flex items-center gap-2">
            <button
              @click="copyShareLink"
              class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl transition-colors"
            >
              📋 Copy Link
            </button>
            <button
              @click="printCertificate"
              class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-colors shadow-md"
            >
              🖨️ Print / Download PDF
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const verificationCode = ref(route.params.code || 'GELME-CERT-2026-X892K');
const cert = ref(null);

const sampleCodes = [
  'GELME-CERT-2026-X892K',
  'STANFORD-AI-2026-9901',
  'OGC-SPANISH-2026-7741'
];

function setAndSearch(code) {
  verificationCode.value = code;
  searchCertificate();
}

async function searchCertificate() {
  if (!verificationCode.value) return;
  try {
    const res = await axios.get(`/api/certificates/${verificationCode.value.trim()}`);
    cert.value = res.data.certificate;
  } catch (e) {
    alert(`Certificate code "${verificationCode.value}" was not found on the registry.`);
  }
}

function printCertificate() {
  window.print();
}

async function copyShareLink() {
  const url = window.location.origin + '/verify/' + verificationCode.value.trim();
  if (navigator.share && navigator.canShare && navigator.canShare({ url })) {
    try {
      await navigator.share({
        title: cert.value?.name || 'Verified Credential - GELME Global Consortium',
        text: `Verify official credential for ${cert.value?.recipient_name || 'Recipient'}: ${cert.value?.name}`,
        url,
      });
      return;
    } catch (e) {
      if (e.name === 'AbortError') return;
    }
  }

  if (navigator.clipboard?.writeText) {
    try {
      await navigator.clipboard.writeText(url);
      alert('Verification URL copied to clipboard: ' + url);
      return;
    } catch (e) {
      console.warn('Clipboard write failed', e);
    }
  }
  prompt('Copy this verification URL:', url);
}

onMounted(() => {
  searchCertificate();
});
</script>
