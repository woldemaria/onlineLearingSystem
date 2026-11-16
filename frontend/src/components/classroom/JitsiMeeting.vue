<template>
  <div class="relative w-full h-full min-h-[500px] bg-slate-950 rounded-2xl overflow-hidden flex flex-col shadow-2xl border border-slate-800">
    <!-- Room Control Bar -->
    <div class="bg-slate-900 border-b border-slate-800 px-4 py-3 flex items-center justify-between text-white text-xs z-10">
      <div class="flex items-center gap-2">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        <span class="font-bold text-sm tracking-tight text-white">{{ title }}</span>
        <span class="bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded font-mono font-medium">LIVE 25-MIN</span>
      </div>
      <div class="flex items-center gap-3">
        <span class="text-slate-400">Host: <strong class="text-slate-200">{{ hostName }}</strong></span>
        <button @click="toggleFullscreen" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 rounded text-slate-300 transition-colors">
          Fullscreen
        </button>
      </div>
    </div>

    <!-- Video Stage / Embedded Jitsi Meet -->
    <div class="flex-1 w-full relative bg-slate-950 flex items-center justify-center">
      <iframe
        :src="embedUrl"
        class="w-full h-full border-0 absolute inset-0"
        allow="camera; microphone; fullscreen; display-capture; autoplay"
      ></iframe>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  roomName: { type: String, default: 'gelme-global-classroom' },
  title: { type: String, default: 'Online Global Classroom' },
  hostName: { type: String, default: 'Native Specialist' },
  userName: { type: String, default: 'Student' }
});

const embedUrl = computed(() => {
  return `https://meet.jit.si/${encodeURIComponent(props.roomName)}#userInfo.displayName="${encodeURIComponent(props.userName)}"&config.prejoinPageEnabled=false`;
});

function toggleFullscreen() {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().catch(() => {});
  } else {
    document.exitFullscreen().catch(() => {});
  }
}
</script>
