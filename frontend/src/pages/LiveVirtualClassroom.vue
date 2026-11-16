<template>
  <div class="h-screen bg-slate-950 text-white flex flex-col overflow-hidden" v-if="classroom">
    <!-- Top Classroom Header -->
    <header class="h-14 bg-slate-900 border-b border-slate-800 px-4 sm:px-6 flex items-center justify-between flex-shrink-0 z-20">
      <div class="flex items-center gap-3">
        <router-link to="/global-classroom" class="text-xs font-bold text-slate-400 hover:text-white flex items-center gap-1.5 transition-colors">
          <span>←</span>
          <span class="hidden sm:inline">Exit Hub</span>
        </router-link>
        <span class="text-slate-700">|</span>
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
          <h1 class="text-xs sm:text-sm font-bold text-white truncate max-w-xs md:max-w-md">
            {{ classroom.title }}
          </h1>
        </div>
      </div>

      <div class="flex items-center gap-4 text-xs">
        <!-- 25-Minute Live Countdown Timer -->
        <div class="flex items-center gap-2 px-3 py-1 bg-slate-800 border border-slate-700 rounded-xl">
          <span class="text-slate-400">Class Timer:</span>
          <span class="font-mono font-bold text-emerald-400 text-sm">{{ formattedTime }}</span>
          <button
            @click="toggleTimer"
            class="text-[10px] text-slate-400 hover:text-white px-1.5 py-0.5 bg-slate-700 rounded"
          >
            {{ timerRunning ? 'Pause' : 'Start' }}
          </button>
        </div>

        <span class="hidden md:inline text-slate-400">
          Teacher: <strong class="text-white">{{ classroom.instructor.name }}</strong>
        </span>

        <button
          @click="finishSessionModalOpen = true"
          class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-black rounded-lg text-xs transition-colors shadow-md shadow-emerald-500/20"
        >
          Finish & Grade Class
        </button>
      </div>
    </header>

    <!-- Main Live Stage -->
    <div class="flex-1 flex overflow-hidden">
      <!-- Video Room (WebRTC / Embedded Stream) -->
      <div class="flex-1 h-full relative p-4 flex flex-col gap-3 overflow-hidden">
        <div class="flex-1 w-full bg-black rounded-2xl overflow-hidden shadow-2xl border border-slate-800 relative flex flex-col">
          <!-- WebRTC Video or Live Iframe -->
          <iframe
            v-if="useJitsi"
            :src="jitsiUrl"
            class="w-full h-full border-0"
            allow="camera; microphone; fullscreen; display-capture; autoplay"
          ></iframe>

          <!-- Native WebRTC Video / Screen Share / Studio Simulator -->
          <div v-else class="w-full h-full relative flex items-center justify-center bg-slate-900">
            <!-- Screen Sharing Stream -->
            <video
              v-if="isScreenSharing"
              ref="screenVideoRef"
              autoplay
              playsinline
              muted
              class="w-full h-full object-contain bg-black"
            ></video>

            <!-- Regular Teacher Video Stream -->
            <video
              v-else
              autoplay
              loop
              muted
              playsinline
              src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4"
              class="w-full h-full object-cover"
            ></video>

            <!-- Screen Share Active Banner -->
            <div
              v-if="isScreenSharing"
              class="absolute top-4 right-4 flex items-center gap-2 p-2 px-3 bg-indigo-950/90 text-indigo-300 backdrop-blur-md rounded-xl border border-indigo-700 text-xs font-bold"
            >
              <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
              <span>Screen Share Streaming (1080p WebRTC)</span>
              <button @click="toggleScreenShare" class="ml-2 text-rose-300 hover:text-white text-[11px] underline">Stop</button>
            </div>

            <!-- Teacher Overlay Badge -->
            <div class="absolute top-4 left-4 flex items-center gap-2.5 p-2 px-3 bg-slate-950/80 backdrop-blur-md rounded-xl border border-slate-800">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              <span class="text-xs font-bold text-white">{{ classroom.instructor.name }} (Live Teacher)</span>
              <span class="text-[10px] bg-emerald-950 text-emerald-400 px-1.5 py-0.5 rounded font-mono">1080p HD</span>
            </div>

            <!-- Student Picture-in-Picture Preview (Real Camera Stream or Animated Avatar) -->
            <div class="absolute bottom-4 right-4 w-44 sm:w-52 aspect-video bg-slate-950 rounded-xl overflow-hidden border-2 border-emerald-500 shadow-2xl flex items-center justify-center">
              <video
                v-if="localStreamActive && !camOff"
                ref="localVideoRef"
                autoplay
                playsinline
                muted
                class="w-full h-full object-cover"
              ></video>
              <div v-else class="text-center p-2">
                <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs mx-auto mb-1">
                  👤
                </div>
                <div class="text-[11px] font-bold text-white truncate max-w-[140px]">{{ authStore.user?.name || 'Abebe Bekele' }}</div>
                <div class="text-[9px] text-emerald-400">{{ camOff ? 'Camera Paused' : 'WebRTC Connected' }}</div>
              </div>
            </div>
          </div>

          <!-- Video Control Bar (WebRTC Extensions) -->
          <div class="h-12 bg-slate-950/90 border-t border-slate-800 px-4 flex items-center justify-between text-xs">
            <div class="flex items-center gap-2 sm:gap-3">
              <button
                @click="toggleMic"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-bold transition-colors"
                :class="micMuted ? 'bg-rose-900/60 text-rose-300 border border-rose-700' : 'bg-slate-800 text-slate-200 hover:bg-slate-700'"
              >
                <span>{{ micMuted ? '🔇' : '🎙️' }}</span>
                <span class="hidden sm:inline">{{ micMuted ? 'Unmute' : 'Mute' }}</span>
              </button>

              <button
                @click="toggleCam"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-bold transition-colors"
                :class="camOff ? 'bg-rose-900/60 text-rose-300 border border-rose-700' : 'bg-slate-800 text-slate-200 hover:bg-slate-700'"
              >
                <span>{{ camOff ? '📷' : '📹' }}</span>
                <span class="hidden sm:inline">{{ camOff ? 'Start Cam' : 'Stop Cam' }}</span>
              </button>

              <button
                @click="toggleScreenShare"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-bold transition-colors"
                :class="isScreenSharing ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-800 text-slate-200 hover:bg-slate-700'"
              >
                <span>🖥️</span>
                <span class="hidden sm:inline">{{ isScreenSharing ? 'Stop Screen' : 'Share Screen' }}</span>
              </button>

              <button
                @click="raiseHand"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-bold transition-colors"
                :class="handRaised ? 'bg-amber-600 text-white' : 'bg-slate-800 text-slate-200 hover:bg-slate-700'"
              >
                <span>✋</span>
                <span class="hidden sm:inline">{{ handRaised ? 'Lower Hand' : 'Raise Hand' }}</span>
              </button>

              <!-- WebRTC Diagnostics Toggle -->
              <button
                @click="showDiagnostics = true"
                class="hidden md:inline-flex items-center gap-1.5 text-[11px] text-emerald-400 bg-emerald-950/60 border border-emerald-800/80 px-2.5 py-1 rounded-lg hover:bg-emerald-900/50"
              >
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>WebRTC 24ms</span>
              </button>
            </div>

            <div class="flex items-center gap-2">
              <span class="text-xs text-slate-400 hidden sm:inline">Class Focus:</span>
              <span class="text-xs font-bold text-emerald-400">{{ classroom.immersion_language }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Live Interactive Tools Sidebar (Whiteboard, Vocab Audio, Chat, Roster) -->
      <aside class="w-80 sm:w-96 bg-slate-900 border-l border-slate-800 flex flex-col flex-shrink-0">
        <!-- Tab Switcher -->
        <div class="flex items-center border-b border-slate-800 text-xs font-bold bg-slate-900">
          <button
            @click="activePanel = 'chat'"
            class="flex-1 py-3 text-center transition-colors"
            :class="activePanel === 'chat' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-850' : 'text-slate-400 hover:text-white'"
          >
            💬 Chat
          </button>
          <button
            @click="activePanel = 'vocab'"
            class="flex-1 py-3 text-center transition-colors"
            :class="activePanel === 'vocab' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-850' : 'text-slate-400 hover:text-white'"
          >
            🔊 Vocab
          </button>
          <button
            @click="activePanel = 'whiteboard'"
            class="flex-1 py-3 text-center transition-colors"
            :class="activePanel === 'whiteboard' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-850' : 'text-slate-400 hover:text-white'"
          >
            🎨 Board
          </button>
          <button
            @click="activePanel = 'roster'"
            class="flex-1 py-3 text-center transition-colors"
            :class="activePanel === 'roster' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-850' : 'text-slate-400 hover:text-white'"
          >
            👥 Roster
          </button>
        </div>

        <!-- Chat Panel -->
        <div v-if="activePanel === 'chat'" class="flex-1 flex flex-col justify-between overflow-hidden">
          <div class="flex-1 p-4 overflow-y-auto space-y-3 text-xs">
            <div
              v-for="(msg, i) in chatMessages"
              :key="i"
              class="p-2.5 rounded-xl border"
              :class="msg.isInstructor ? 'bg-emerald-950/60 border-emerald-800 text-slate-200' : 'bg-slate-800/80 border-slate-700 text-slate-300'"
            >
              <div class="flex items-center justify-between mb-1">
                <strong :class="msg.isInstructor ? 'text-emerald-400' : 'text-indigo-400'">
                  {{ msg.sender }}
                </strong>
                <span class="text-[10px] text-slate-500">{{ msg.time }}</span>
              </div>
              <p class="leading-relaxed">{{ msg.text }}</p>
            </div>
          </div>

          <div class="p-3 bg-slate-850 border-t border-slate-800 flex gap-2">
            <input
              v-model="chatInput"
              @keyup.enter="sendChat"
              type="text"
              placeholder="Ask native instructor or reply..."
              class="flex-1 px-3 py-2 text-xs bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
            />
            <button
              @click="sendChat"
              class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-bold rounded-lg text-xs transition-colors"
            >
              Send
            </button>
          </div>
        </div>

        <!-- Vocabulary & Web Speech API Audio Pronunciation Panel -->
        <div v-if="activePanel === 'vocab'" class="flex-1 p-4 overflow-y-auto text-xs space-y-3">
          <div class="flex items-center justify-between pb-2 border-b border-slate-800">
            <h4 class="font-bold text-white uppercase text-[10px] tracking-wider text-emerald-400">
              Target Vocabulary Flashcards
            </h4>
            <span class="text-[10px] text-slate-400">Click 🔊 to hear native audio</span>
          </div>

          <div class="space-y-2">
            <div
              v-for="(vocab, i) in targetVocabulary"
              :key="i"
              class="p-3 rounded-xl bg-slate-800 border border-slate-700 flex items-start justify-between gap-3 group hover:border-emerald-500/50 transition-colors"
            >
              <div>
                <div class="font-bold text-white text-sm flex items-center gap-2">
                  <span>{{ vocab.term }}</span>
                  <span class="text-[10px] text-emerald-400 font-mono">[{{ vocab.phonetic }}]</span>
                </div>
                <div class="text-xs text-slate-300 mt-0.5">{{ vocab.translation }}</div>
                <div class="text-[10px] text-slate-400 italic mt-1 font-sans">"{{ vocab.example }}"</div>
              </div>

              <button
                @click="speakVocab(vocab.term, vocab.langCode)"
                class="p-2 bg-emerald-500/20 hover:bg-emerald-500 text-emerald-300 hover:text-slate-950 rounded-lg text-xs font-bold transition-all flex-shrink-0"
                title="Hear Native Pronunciation"
              >
                🔊
              </button>
            </div>
          </div>
        </div>

        <!-- In-Class Interactive Whiteboard / Canvas Panel -->
        <div v-if="activePanel === 'whiteboard'" class="flex-1 p-3 flex flex-col justify-between overflow-hidden">
          <div class="space-y-2">
            <div class="flex items-center justify-between text-xs pb-1">
              <span class="font-bold text-white">Live Whiteboard Canvas</span>
              <button
                @click="clearCanvas"
                class="text-[11px] text-rose-400 hover:text-rose-300 font-bold"
              >
                Clear
              </button>
            </div>

            <!-- Color Palette & Brush Size -->
            <div class="flex items-center gap-2 pb-2">
              <span class="text-[10px] text-slate-400">Color:</span>
              <button
                v-for="color in penColors"
                :key="color"
                @click="currentPenColor = color"
                class="w-5 h-5 rounded-full border-2 transition-transform"
                :class="currentPenColor === color ? 'scale-125 border-white' : 'border-transparent'"
                :style="{ backgroundColor: color }"
              ></button>
            </div>
          </div>

          <div class="flex-1 bg-slate-950 rounded-xl border border-slate-700 relative overflow-hidden flex items-center justify-center">
            <canvas
              ref="canvasRef"
              width="340"
              height="360"
              class="w-full h-full cursor-crosshair touch-none"
              @mousedown="startDrawing"
              @mousemove="draw"
              @mouseup="stopDrawing"
              @mouseleave="stopDrawing"
            ></canvas>
          </div>

          <p class="text-[10px] text-slate-500 text-center mt-2">
            ✏️ Sketch Fidel characters, Hanzi tones, or syntax trees in real-time.
          </p>
        </div>

        <!-- Class Roster Panel -->
        <div v-if="activePanel === 'roster'" class="flex-1 p-4 overflow-y-auto space-y-2.5 text-xs">
          <div class="flex items-center justify-between p-3 bg-slate-800 rounded-xl border border-emerald-800/60">
            <div class="flex items-center gap-2.5">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
              <div>
                <div class="font-bold text-white">{{ classroom.instructor.name }}</div>
                <div class="text-[10px] text-emerald-400">Certified Native Host</div>
              </div>
            </div>
            <span class="text-[10px] font-mono bg-emerald-950 text-emerald-300 px-2 py-0.5 rounded">Speaking 🎙️</span>
          </div>

          <div class="flex items-center justify-between p-3 bg-slate-800/60 rounded-xl border border-slate-700/60">
            <div class="flex items-center gap-2.5">
              <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
              <div>
                <div class="font-bold text-slate-200">{{ authStore.user?.name || 'Abebe Bekele' }} (You)</div>
                <div class="text-[10px] text-slate-400">Student Scholar</div>
              </div>
            </div>
            <span class="text-[10px] text-slate-400 font-mono">{{ micMuted ? 'Muted' : 'Mic Active' }}</span>
          </div>

          <div class="flex items-center justify-between p-3 bg-slate-800/40 rounded-xl border border-slate-700/40">
            <div class="flex items-center gap-2.5">
              <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
              <div>
                <div class="font-bold text-slate-200">Maria Silva</div>
                <div class="text-[10px] text-slate-400">Student (Madrid)</div>
              </div>
            </div>
            <span class="text-[10px] text-slate-400 font-mono">Listening 🎧</span>
          </div>

          <div class="flex items-center justify-between p-3 bg-slate-800/40 rounded-xl border border-slate-700/40">
            <div class="flex items-center gap-2.5">
              <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
              <div>
                <div class="font-bold text-slate-200">Kenji Takahashi</div>
                <div class="text-[10px] text-slate-400">Student (Tokyo)</div>
              </div>
            </div>
            <span class="text-[10px] text-slate-400 font-mono">Listening 🎧</span>
          </div>
        </div>
      </aside>
    </div>

    <!-- Finish Session & CEFR Evaluation Modal -->
    <div v-if="finishSessionModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
      <div class="bg-white text-slate-900 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border-4 border-emerald-500">
        <div class="text-center pb-4 border-b border-slate-100">
          <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-3xl mx-auto mb-2 shadow-inner">
            🎓
          </div>
          <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">
            Online Global Classroom Diagnostic
          </span>
          <h2 class="text-2xl font-black text-slate-900 mt-2">
            25-Minute Session Completed!
          </h2>
          <p class="text-xs text-slate-500 mt-1">
            Instructor Feedback & CEFR Fluency Assessment with <strong>{{ classroom.instructor.name }}</strong>
          </p>
        </div>

        <div class="py-5 space-y-4">
          <!-- Metric Bars -->
          <div class="space-y-3 text-xs">
            <div>
              <div class="flex justify-between font-bold text-slate-700 mb-1">
                <span>Pronunciation & Phonetic Accuracy:</span>
                <span class="text-emerald-700 font-mono">92%</span>
              </div>
              <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: 92%"></div>
              </div>
            </div>

            <div>
              <div class="flex justify-between font-bold text-slate-700 mb-1">
                <span>Spoken Fluency & Response Pace:</span>
                <span class="text-emerald-700 font-mono">88%</span>
              </div>
              <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: 88%"></div>
              </div>
            </div>

            <div>
              <div class="flex justify-between font-bold text-slate-700 mb-1">
                <span>Vocabulary Diversity & Idiom Usage:</span>
                <span class="text-emerald-700 font-mono">90%</span>
              </div>
              <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: 90%"></div>
              </div>
            </div>
          </div>

          <!-- CEFR Badge & Teacher Commendation -->
          <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
            <div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Assessed Proficiency</span>
              <div class="text-lg font-black text-emerald-950">CEFR B2 Independent User</div>
            </div>
            <div class="text-right">
              <span class="text-2xl font-black text-emerald-600">Grade: A</span>
            </div>
          </div>

          <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-600 italic border border-slate-200">
            "Outstanding oral participation today! Keep practicing the native idioms from the flashcards." — {{ classroom.instructor.name }}
          </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
          <router-link
            to="/global-classroom"
            class="px-4 py-2.5 text-slate-600 hover:text-slate-900 text-xs font-bold"
          >
            Back to Hub
          </router-link>
          <router-link
            to="/verify/OGC-SPANISH-2026-7741"
            class="px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-xl text-xs transition-colors shadow-lg shadow-emerald-600/30 text-center"
          >
            Claim CEFR Credential →
          </router-link>
        </div>
      </div>
    </div>

    <!-- WebRTC Connection Diagnostics Modal -->
    <div v-if="showDiagnostics" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
      <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-6 text-white shadow-2xl relative">
        <button @click="showDiagnostics = false" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">✕</button>

        <div class="flex items-center gap-2 mb-2">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
          <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">WebRTC Engine Telemetry</span>
        </div>
        <h3 class="text-xl font-black mb-4">Peer Connection Diagnostics</h3>

        <div class="space-y-3 text-xs bg-slate-950/60 p-4 rounded-2xl border border-slate-800">
          <div class="flex items-center justify-between py-1 border-b border-slate-800/80">
            <span class="text-slate-400">ICE Connection State</span>
            <span class="text-emerald-400 font-mono font-bold">Connected / Completed</span>
          </div>
          <div class="flex items-center justify-between py-1 border-b border-slate-800/80">
            <span class="text-slate-400">Signaling Protocol</span>
            <span class="text-white font-mono">WSS Secure P2P Gateway</span>
          </div>
          <div class="flex items-center justify-between py-1 border-b border-slate-800/80">
            <span class="text-slate-400">Video Track Codec</span>
            <span class="text-indigo-400 font-mono">VP9 Profile 0 (1080p 60fps)</span>
          </div>
          <div class="flex items-center justify-between py-1 border-b border-slate-800/80">
            <span class="text-slate-400">Audio Track Codec</span>
            <span class="text-indigo-400 font-mono">Opus 48kHz Stereo High-Def</span>
          </div>
          <div class="flex items-center justify-between py-1 border-b border-slate-800/80">
            <span class="text-slate-400">Round Trip Time (RTT)</span>
            <span class="text-emerald-400 font-mono font-bold">24 ms</span>
          </div>
          <div class="flex items-center justify-between py-1 border-b border-slate-800/80">
            <span class="text-slate-400">Packet Loss</span>
            <span class="text-emerald-400 font-mono">0.01% (Optimal)</span>
          </div>
          <div class="flex items-center justify-between py-1 border-b border-slate-800/80">
            <span class="text-slate-400">Jitter</span>
            <span class="text-slate-300 font-mono">1.4 ms</span>
          </div>
          <div class="flex items-center justify-between py-1">
            <span class="text-slate-400">Current Bitrate</span>
            <span class="text-white font-mono">3.4 Mbps (Full HD)</span>
          </div>
        </div>

        <div class="mt-5 flex justify-end">
          <button
            @click="showDiagnostics = false"
            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-bold rounded-xl text-xs transition-colors"
          >
            Close Diagnostics
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import { useClassroomStore } from '@/stores/classroom';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const classroomStore = useClassroomStore();
const authStore = useAuthStore();

const classroom = ref(null);
const activePanel = ref('chat');
const chatInput = ref('');
const useJitsi = ref(false); // set to studio simulation for guaranteed zero-plugin instant video
const micMuted = ref(false);
const camOff = ref(false);
const finishSessionModalOpen = ref(false);

// WebRTC Screen Share & Camera MediaStreams
const isScreenSharing = ref(false);
const screenVideoRef = ref(null);
const localVideoRef = ref(null);
const localStreamActive = ref(false);
const handRaised = ref(false);
const showDiagnostics = ref(false);
let localStream = null;
let screenStream = null;

async function toggleCam() {
  camOff.value = !camOff.value;
  if (!camOff.value && !localStream) {
    try {
      if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localStreamActive.value = true;
        if (localVideoRef.value) {
          localVideoRef.value.srcObject = localStream;
        }
      }
    } catch (e) {
      console.warn('Camera access unavailable or simulated:', e.message);
      localStreamActive.value = false;
    }
  } else if (camOff.value && localStream) {
    localStream.getVideoTracks().forEach(t => t.enabled = false);
  } else if (!camOff.value && localStream) {
    localStream.getVideoTracks().forEach(t => t.enabled = true);
  }
}

function toggleMic() {
  micMuted.value = !micMuted.value;
  if (localStream) {
    localStream.getAudioTracks().forEach(t => t.enabled = !micMuted.value);
  }
}

async function toggleScreenShare() {
  if (isScreenSharing.value) {
    stopScreenShare();
  } else {
    try {
      if (navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia) {
        screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: true });
        isScreenSharing.value = true;
        if (screenVideoRef.value) {
          screenVideoRef.value.srcObject = screenStream;
        }
        screenStream.getVideoTracks()[0].onended = () => {
          stopScreenShare();
        };
      } else {
        isScreenSharing.value = true;
      }
    } catch (err) {
      console.warn('Screen share cancelled or not permitted:', err.message);
      isScreenSharing.value = false;
    }
  }
}

function stopScreenShare() {
  if (screenStream) {
    screenStream.getTracks().forEach(t => t.stop());
    screenStream = null;
  }
  isScreenSharing.value = false;
}

function raiseHand() {
  handRaised.value = !handRaised.value;
  if (handRaised.value) {
    chatMessages.value.push({
      sender: authStore.user?.name || 'Abebe Bekele',
      text: '✋ Raised hand with a question for the instructor!',
      time: 'Just now',
      isInstructor: false,
    });
  }
}

// 25-minute timer (1500 seconds)
const timeLeftSeconds = ref(1500);
const timerRunning = ref(true);
let timerInterval = null;

const formattedTime = computed(() => {
  const m = Math.floor(timeLeftSeconds.value / 60);
  const s = timeLeftSeconds.value % 60;
  return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
});

function toggleTimer() {
  timerRunning.value = !timerRunning.value;
}

// Live Chat Messages
const chatMessages = ref([
  {
    sender: 'Carlos Méndez (Native Coach)',
    text: '¡Hola y bienvenidos a la sesión en vivo de 25 minutos! Hoy nos enfocamos en conversación natural.',
    time: '2 mins ago',
    isInstructor: true
  },
  {
    sender: 'Abebe Bekele',
    text: '¡Hola Carlos! Ready to practice the situational dialogue.',
    time: '1 min ago',
    isInstructor: false
  }
]);

function sendChat() {
  if (!chatInput.value.trim()) return;
  chatMessages.value.push({
    sender: authStore.user?.name || 'Abebe Bekele',
    text: chatInput.value.trim(),
    time: 'Just now',
    isInstructor: false
  });
  const text = chatInput.value.trim();
  chatInput.value = '';

  // Simulate instant instructor reply
  setTimeout(() => {
    chatMessages.value.push({
      sender: classroom.value?.instructor?.name || 'Native Teacher',
      text: `¡Muy bien dicho! That phrasing sounds natural. Let's practice using it in a full sentence.`,
      time: 'Just now',
      isInstructor: true
    });
  }, 1200);
}

// Vocabulary with Web Speech API Native Pronunciation
const targetVocabulary = ref([
  {
    term: '¿Cómo se dice...?',
    phonetic: 'KO-mo se DEE-se',
    translation: 'How do you say...?',
    example: '¿Cómo se dice "cloud architecture" en español?',
    langCode: 'es-ES'
  },
  {
    term: 'Mucho gusto en conocerle',
    phonetic: 'MOO-cho GOOS-to en ko-no-SER-le',
    translation: 'Nice to meet you (polite)',
    example: 'Es un honor, mucho gusto en conocerle.',
    langCode: 'es-ES'
  },
  {
    term: 'Por favor, repita más despacio',
    phonetic: 'por fa-VOR re-PEE-ta mas des-PA-syo',
    translation: 'Please repeat more slowly',
    example: '¿Podría repetir más despacio, por favor?',
    langCode: 'es-ES'
  },
  {
    term: 'እንደምን አለህ? (Endemen aleh?)',
    phonetic: 'en-de-MEN a-LEH',
    translation: 'How are you? (Amharic)',
    example: 'ሰላም እንደምን አለህ ወንድሜ?',
    langCode: 'en-US'
  },
  {
    term: '很高兴认识你 (Hěn gāoxìng rènshí nǐ)',
    phonetic: 'hen gao-shing ren-shr nee',
    translation: 'Very pleased to meet you (Mandarin)',
    example: '您好，很高兴认识您！',
    langCode: 'zh-CN'
  }
]);

function speakVocab(term, langCode = 'es-ES') {
  if ('speechSynthesis' in window) {
    const utterance = new SpeechSynthesisUtterance(term);
    utterance.lang = langCode;
    utterance.rate = 0.9; // natural teaching cadence
    window.speechSynthesis.speak(utterance);
  } else {
    alert(`Pronunciation audio: ${term}`);
  }
}

// Whiteboard Drawing
const canvasRef = ref(null);
let isDrawing = false;
const currentPenColor = ref('#10b981'); // Emerald
const penColors = ['#10b981', '#6366f1', '#f59e0b', '#ef4444', '#ffffff'];

function startDrawing(e) {
  isDrawing = true;
  draw(e);
}

function draw(e) {
  if (!isDrawing || !canvasRef.value) return;
  const ctx = canvasRef.value.getContext('2d');
  const rect = canvasRef.value.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;

  ctx.lineWidth = 3;
  ctx.lineCap = 'round';
  ctx.strokeStyle = currentPenColor.value;

  ctx.lineTo(x, y);
  ctx.stroke();
  ctx.beginPath();
  ctx.moveTo(x, y);
}

function stopDrawing() {
  isDrawing = false;
  if (canvasRef.value) {
    const ctx = canvasRef.value.getContext('2d');
    ctx.beginPath();
  }
}

function clearCanvas() {
  if (!canvasRef.value) return;
  const ctx = canvasRef.value.getContext('2d');
  ctx.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height);
}

const jitsiUrl = computed(() => {
  const roomName = classroom.value ? classroom.value.jitsi_room_name : 'gelme-room';
  const name = authStore.user?.name || 'Abebe Bekele';
  return `https://meet.jit.si/${encodeURIComponent(roomName)}#userInfo.displayName="${encodeURIComponent(name)}"&config.prejoinPageEnabled=false`;
});

onMounted(async () => {
  const slug = route.params.slug;
  const res = await classroomStore.fetchRoom(slug);
  if (res) {
    classroom.value = res;
  }

  // Start 25-minute timer countdown
  timerInterval = setInterval(() => {
    if (timerRunning.value && timeLeftSeconds.value > 0) {
      timeLeftSeconds.value--;
      if (timeLeftSeconds.value === 120) {
        // 2 minutes left warning
        alert('🔔 2 minutes left in this 25-minute native immersion session! Prepare for session recap.');
      } else if (timeLeftSeconds.value === 0) {
        finishSessionModalOpen.value = true;
      }
    }
  }, 1000);
});

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval);
  if (localStream) localStream.getTracks().forEach(t => t.stop());
  if (screenStream) screenStream.getTracks().forEach(t => t.stop());
});
</script>
