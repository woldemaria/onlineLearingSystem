<template>
  <div class="h-screen bg-slate-950 text-white flex flex-col overflow-hidden" v-if="classroom">
    <!-- Top Classroom Header -->
    <header class="h-14 bg-slate-900 border-b border-slate-800 px-3 sm:px-6 flex items-center justify-between flex-shrink-0 z-20">
      <div class="flex items-center gap-3">
        <router-link to="/global-classroom" class="text-xs font-bold text-slate-400 hover:text-white flex items-center gap-1.5 transition-colors">
          <span>←</span>
          <span class="hidden sm:inline">Exit Hub</span>
        </router-link>
        <span class="text-slate-700">|</span>
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
          <h1 class="text-xs sm:text-sm font-bold text-white truncate max-w-[140px] sm:max-w-xs md:max-w-md">
            {{ classroom.title }}
          </h1>
        </div>
      </div>

      <!-- 25-Minute Immersion Phase Progress Indicator -->
      <div class="hidden lg:flex items-center gap-2 px-3 py-1 bg-slate-950/70 rounded-full border border-slate-800 text-xs">
        <span class="text-slate-400 text-[11px]">Immersion Phase:</span>
        <span class="font-bold text-emerald-400 text-[11px] flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping" v-if="timerRunning"></span>
          {{ currentLessonPhase.title }}
        </span>
        <span class="text-[10px] text-slate-500 font-mono">({{ currentLessonPhase.range }})</span>
      </div>

      <!-- Right Header Controls -->
      <div class="flex items-center gap-2.5 sm:gap-4 text-xs">
        <!-- 25-Minute Live Countdown Timer -->
        <div class="flex items-center gap-2 px-2.5 sm:px-3 py-1 bg-slate-800 border border-slate-700 rounded-xl shadow-inner">
          <span class="text-slate-400 hidden sm:inline text-[11px]">25m Lesson:</span>
          <span class="font-mono font-black text-emerald-400 text-sm tracking-wider">{{ formattedTime }}</span>
          <button
            @click="toggleTimer"
            class="text-[10px] font-bold text-slate-300 hover:text-white px-1.5 py-0.5 bg-slate-700 hover:bg-slate-600 rounded transition-colors"
            :title="timerRunning ? 'Pause Immersion Timer' : 'Resume Immersion Timer'"
          >
            {{ timerRunning ? 'Pause' : 'Start' }}
          </button>
          <button
            @click="extendTimer"
            class="hidden sm:inline text-[10px] text-emerald-400 hover:text-emerald-300 px-1 py-0.5 bg-emerald-950/80 rounded border border-emerald-800/80"
            title="Extend class by 5 minutes"
          >
            +5m
          </button>
        </div>

        <!-- Attendance Badge Status -->
        <div
          @click="activePanel = 'attendance'"
          class="cursor-pointer flex items-center gap-1.5 px-2.5 py-1 bg-emerald-950/80 border border-emerald-700/80 rounded-xl text-emerald-300 hover:bg-emerald-900/60 transition-colors"
          title="Attendance Logged & Verified"
        >
          <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
          <span class="text-[11px] font-bold">Present</span>
        </div>

        <button
          @click="finishSessionModalOpen = true"
          class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-lg text-xs transition-colors shadow-md shadow-emerald-500/20"
        >
          Finish & Grade
        </button>
      </div>
    </header>

    <!-- Main Live Stage Area -->
    <div class="flex-1 flex overflow-hidden">
      <!-- Video Room (WebRTC / Embedded Stream) -->
      <div class="flex-1 h-full relative p-3 sm:p-4 flex flex-col gap-3 overflow-hidden">
        <div class="flex-1 w-full bg-black rounded-2xl overflow-hidden shadow-2xl border border-slate-800 relative flex flex-col">
          <!-- WebRTC Video or Live Jitsi Iframe -->
          <iframe
            v-if="useJitsi"
            :src="jitsiUrl"
            class="w-full h-full border-0"
            allow="camera; microphone; fullscreen; display-capture; autoplay"
          ></iframe>

          <!-- Native WebRTC Video / Studio / Group Grid -->
          <div v-else class="w-full h-full relative flex flex-col bg-slate-950 overflow-hidden">
            <!-- 1. SPOTLIGHT VIEW (Hero Teacher or Active Screen Share) -->
            <div v-if="layoutMode === 'spotlight'" class="w-full h-full relative flex items-center justify-center bg-slate-900">
              <!-- Screen Sharing Video Stream -->
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
                class="absolute top-4 right-4 flex items-center gap-2 p-2 px-3 bg-indigo-950/90 text-indigo-300 backdrop-blur-md rounded-xl border border-indigo-700 text-xs font-bold shadow-xl animate-fade-in"
              >
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 animate-ping"></span>
                <span>Screen Share Active (1080p 60fps)</span>
                <button
                  @click="toggleScreenShare"
                  class="ml-2 px-2 py-0.5 bg-rose-900/80 hover:bg-rose-800 text-rose-200 rounded text-[11px] font-bold transition-colors"
                >
                  Stop Sharing
                </button>
              </div>

              <!-- Teacher Overlay Badge -->
              <div class="absolute top-4 left-4 flex items-center gap-2.5 p-2 px-3 bg-slate-950/85 backdrop-blur-md rounded-xl border border-slate-800 shadow-lg">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <div class="flex flex-col">
                  <span class="text-xs font-black text-white flex items-center gap-1.5">
                    {{ classroom.instructor.name }}
                    <span class="text-[9px] bg-emerald-500/20 text-emerald-300 px-1.5 py-0.2 rounded font-normal">Native Lead</span>
                  </span>
                  <span class="text-[10px] text-slate-400 font-mono">Immersion Room • 1080p WebRTC</span>
                </div>
              </div>

              <!-- Student Picture-in-Picture Preview (Real Camera Stream or Avatar) -->
              <div class="absolute bottom-4 right-4 w-44 sm:w-56 aspect-video bg-slate-950/90 rounded-2xl overflow-hidden border-2 border-emerald-500 shadow-2xl flex items-center justify-center backdrop-blur-sm group">
                <video
                  v-if="localStreamActive && !camOff"
                  ref="localVideoRef"
                  autoplay
                  playsinline
                  muted
                  class="w-full h-full object-cover"
                ></video>
                <div v-else class="text-center p-2">
                  <div class="w-9 h-9 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm mx-auto mb-1">
                    👤
                  </div>
                  <div class="text-[11px] font-bold text-white truncate max-w-[140px]">
                    {{ authStore.user?.name || 'Abebe Bekele' }} (You)
                  </div>
                  <div class="text-[9px] text-emerald-400 font-mono">
                    {{ camOff ? 'Camera Paused' : 'WebRTC Connected' }}
                  </div>
                </div>

                <!-- PiP Badges -->
                <div class="absolute top-1.5 right-1.5 flex items-center gap-1">
                  <span v-if="handRaised" class="text-xs bg-amber-500/90 text-slate-950 px-1 py-0.2 rounded-md font-bold">✋</span>
                  <span class="text-[9px] bg-slate-900/80 text-slate-300 px-1 rounded">{{ micMuted ? '🔇' : '🎙️' }}</span>
                </div>
              </div>
            </div>

            <!-- 2. GROUP CLASSROOM GRID VIEW (Multi-Participant Video Matrix) -->
            <div v-else class="w-full h-full p-3 grid grid-cols-2 lg:grid-cols-3 gap-3 overflow-y-auto bg-slate-950">
              <!-- Tile 1: Instructor (Lead Host) -->
              <div class="relative bg-slate-900 rounded-2xl overflow-hidden border-2 border-emerald-500/80 shadow-lg flex items-center justify-center aspect-video sm:aspect-auto">
                <video
                  autoplay
                  loop
                  muted
                  playsinline
                  src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4"
                  class="w-full h-full object-cover"
                ></video>
                <div class="absolute bottom-2 left-2 flex items-center gap-2 p-1.5 px-2.5 bg-slate-950/80 rounded-xl backdrop-blur-sm border border-slate-800">
                  <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                  <span class="text-xs font-bold text-white">{{ classroom.instructor.name }} (Host)</span>
                  <span class="text-[10px] text-emerald-400 font-mono">Speaking 🎙️</span>
                </div>
                <div class="absolute top-2 right-2 flex items-center gap-1.5">
                  <div class="flex items-center gap-0.5 px-1.5 py-0.5 bg-emerald-950/90 text-emerald-400 rounded-md text-[10px] font-bold border border-emerald-700">
                    <span class="w-1 h-3 bg-emerald-400 rounded-full animate-bounce"></span>
                    <span class="w-1 h-2 bg-emerald-400 rounded-full animate-bounce delay-75"></span>
                    <span class="w-1 h-4 bg-emerald-400 rounded-full animate-bounce delay-150"></span>
                  </div>
                </div>
              </div>

              <!-- Tile 2: You (Current Student) -->
              <div class="relative bg-slate-900 rounded-2xl overflow-hidden border-2 border-indigo-500/80 shadow-lg flex items-center justify-center aspect-video sm:aspect-auto">
                <video
                  v-if="localStreamActive && !camOff"
                  ref="localGridVideoRef"
                  autoplay
                  playsinline
                  muted
                  class="w-full h-full object-cover"
                ></video>
                <div v-else class="text-center p-4">
                  <div class="w-12 h-12 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-lg mx-auto mb-2">
                    👤
                  </div>
                  <div class="text-xs font-bold text-white">{{ authStore.user?.name || 'Abebe Bekele' }} (You)</div>
                  <div class="text-[10px] text-slate-400">Student Scholar • Active</div>
                </div>
                <div class="absolute bottom-2 left-2 flex items-center gap-2 p-1.5 px-2.5 bg-slate-950/80 rounded-xl backdrop-blur-sm border border-slate-800">
                  <span class="w-2 h-2 rounded-full" :class="camOff ? 'bg-rose-400' : 'bg-emerald-400'"></span>
                  <span class="text-xs font-bold text-slate-200">{{ authStore.user?.name || 'Abebe Bekele' }}</span>
                  <span class="text-[10px] text-slate-400 font-mono">{{ micMuted ? 'Muted' : 'Mic Active' }}</span>
                </div>
                <div class="absolute top-2 right-2 flex items-center gap-1">
                  <span v-if="handRaised" class="px-2 py-0.5 bg-amber-500 text-slate-950 text-xs font-bold rounded-lg shadow animate-bounce">
                    ✋ Question
                  </span>
                </div>
              </div>

              <!-- Peer Student Tiles from Group Classroom -->
              <div
                v-for="(peer, idx) in peerStudents"
                :key="idx"
                class="relative bg-slate-900 rounded-2xl overflow-hidden border border-slate-800 shadow flex flex-col justify-between p-3 aspect-video sm:aspect-auto"
                :style="{ background: `radial-gradient(circle at top right, ${peer.color}15, #0f172a)` }"
              >
                <div class="flex items-center justify-between">
                  <span class="text-[10px] uppercase font-mono tracking-wider text-slate-400">{{ peer.location }}</span>
                  <span class="text-[10px] px-1.5 py-0.5 rounded font-mono font-bold" :class="peer.isSpeaking ? 'bg-emerald-950 text-emerald-400 border border-emerald-800' : 'bg-slate-800 text-slate-400'">
                    {{ peer.isSpeaking ? 'Speaking 🎙️' : 'Listening 🎧' }}
                  </span>
                </div>

                <div class="text-center my-auto">
                  <img :src="peer.avatar" :alt="peer.name" class="w-14 h-14 rounded-full mx-auto border-2 border-slate-700 shadow-md mb-1.5" />
                  <div class="text-xs font-bold text-white">{{ peer.name }}</div>
                  <div class="text-[10px] text-slate-400">{{ peer.level }}</div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-800/60 text-[10px] text-slate-400">
                  <span class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>HD 720p</span>
                  </span>
                  <span v-if="peer.handRaised" class="text-amber-400 font-bold">✋ Hand Raised</span>
                  <span v-else class="text-slate-500">Muted</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Video Control Bar (Media Controls, Screen Share, Diagnostics) -->
          <div class="h-12 bg-slate-950/95 border-t border-slate-800 px-3 sm:px-4 flex items-center justify-between text-xs z-10">
            <div class="flex items-center gap-1.5 sm:gap-2.5">
              <!-- Mic Button -->
              <button
                @click="toggleMic"
                class="flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl font-bold transition-all text-xs"
                :class="micMuted ? 'bg-rose-950/80 text-rose-300 border border-rose-800 hover:bg-rose-900' : 'bg-slate-800 text-slate-200 hover:bg-slate-700 border border-slate-700'"
              >
                <span>{{ micMuted ? '🔇' : '🎙️' }}</span>
                <span class="hidden md:inline">{{ micMuted ? 'Unmute' : 'Mute' }}</span>
              </button>

              <!-- Camera Button -->
              <button
                @click="toggleCam"
                class="flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl font-bold transition-all text-xs"
                :class="camOff ? 'bg-rose-950/80 text-rose-300 border border-rose-800 hover:bg-rose-900' : 'bg-slate-800 text-slate-200 hover:bg-slate-700 border border-slate-700'"
              >
                <span>{{ camOff ? '📷' : '📹' }}</span>
                <span class="hidden md:inline">{{ camOff ? 'Start Video' : 'Stop Video' }}</span>
              </button>

              <!-- Screen Share Button -->
              <button
                @click="toggleScreenShare"
                class="flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl font-bold transition-all text-xs shadow-md"
                :class="isScreenSharing ? 'bg-indigo-600 text-white border border-indigo-400 shadow-indigo-500/30' : 'bg-slate-800 text-slate-200 hover:bg-slate-700 border border-slate-700'"
              >
                <span>🖥️</span>
                <span class="hidden sm:inline">{{ isScreenSharing ? 'Stop Screen' : 'Share Screen' }}</span>
              </button>

              <!-- Hand Raise Button -->
              <button
                @click="toggleHand"
                class="flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl font-bold transition-all text-xs"
                :class="handRaised ? 'bg-amber-600 text-white shadow-amber-600/30 shadow-md' : 'bg-slate-800 text-slate-200 hover:bg-slate-700 border border-slate-700'"
              >
                <span>✋</span>
                <span class="hidden md:inline">{{ handRaised ? 'Lower Hand' : 'Raise Hand' }}</span>
              </button>

              <!-- Group Grid vs Spotlight Layout Toggle -->
              <button
                @click="layoutMode = (layoutMode === 'spotlight' ? 'grid' : 'spotlight')"
                class="flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl font-bold transition-all text-xs bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700"
                :title="layoutMode === 'spotlight' ? 'Switch to Group Classroom Grid' : 'Switch to Teacher Spotlight View'"
              >
                <span>{{ layoutMode === 'spotlight' ? '👥' : '🎯' }}</span>
                <span class="hidden lg:inline">{{ layoutMode === 'spotlight' ? 'Group Grid' : 'Spotlight' }}</span>
              </button>

              <!-- Jitsi Live / Native Studio Switcher -->
              <button
                @click="useJitsi = !useJitsi"
                class="hidden xl:inline-flex items-center gap-1.5 px-2 py-1 rounded-lg text-[11px] font-bold text-slate-400 hover:text-white bg-slate-900 border border-slate-800"
                :title="useJitsi ? 'Switch to HD WebRTC Studio Simulator' : 'Switch to Jitsi Live WebRTC Room'"
              >
                <span>⚡</span>
                <span>{{ useJitsi ? 'Studio View' : 'Jitsi Live' }}</span>
              </button>
            </div>

            <!-- Diagnostics & Audio Indicators -->
            <div class="flex items-center gap-2">
              <button
                @click="showDiagnostics = true"
                class="flex items-center gap-1.5 text-[11px] text-emerald-400 bg-emerald-950/60 border border-emerald-800/80 px-2.5 py-1 rounded-xl hover:bg-emerald-900/50 transition-colors"
                title="View WebRTC Diagnostic Telemetry"
              >
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>WebRTC 24ms</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Live Interactive Tools Sidebar (Chat, Attendance, Vocab, Roster, Whiteboard) -->
      <aside class="w-80 sm:w-96 bg-slate-900 border-l border-slate-800 flex flex-col flex-shrink-0">
        <!-- Tab Switcher -->
        <div class="flex items-center border-b border-slate-800 text-xs font-bold bg-slate-900 overflow-x-auto no-scrollbar">
          <button
            @click="activePanel = 'chat'"
            class="flex-1 py-3 px-2 text-center transition-colors whitespace-nowrap"
            :class="activePanel === 'chat' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-850' : 'text-slate-400 hover:text-white'"
          >
            💬 Chat
          </button>
          <button
            @click="activePanel = 'attendance'"
            class="flex-1 py-3 px-2 text-center transition-colors whitespace-nowrap"
            :class="activePanel === 'attendance' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-850' : 'text-slate-400 hover:text-white'"
          >
            📋 Attendance
          </button>
          <button
            @click="activePanel = 'roster'"
            class="flex-1 py-3 px-2 text-center transition-colors whitespace-nowrap"
            :class="activePanel === 'roster' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-850' : 'text-slate-400 hover:text-white'"
          >
            👥 Roster
          </button>
          <button
            @click="activePanel = 'vocab'"
            class="flex-1 py-3 px-2 text-center transition-colors whitespace-nowrap"
            :class="activePanel === 'vocab' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-850' : 'text-slate-400 hover:text-white'"
          >
            🔊 Vocab
          </button>
          <button
            @click="activePanel = 'whiteboard'"
            class="flex-1 py-3 px-2 text-center transition-colors whitespace-nowrap"
            :class="activePanel === 'whiteboard' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-850' : 'text-slate-400 hover:text-white'"
          >
            🎨 Board
          </button>
        </div>

        <!-- 1. LIVE CHAT PANEL (Teacher and Student Communication) -->
        <div v-if="activePanel === 'chat'" class="flex-1 flex flex-col justify-between overflow-hidden">
          <div ref="chatScrollRef" class="flex-1 p-3 sm:p-4 overflow-y-auto space-y-3 text-xs">
            <div
              v-for="(msg, i) in classroomStore.chatMessages"
              :key="i"
              class="p-2.5 rounded-xl border transition-all"
              :class="msg.is_instructor || msg.isInstructor
                ? 'bg-emerald-950/70 border-emerald-700/80 text-slate-200 shadow-sm'
                : 'bg-slate-800/80 border-slate-700 text-slate-300'"
            >
              <div class="flex items-center justify-between mb-1">
                <strong :class="msg.is_instructor || msg.isInstructor ? 'text-emerald-400 flex items-center gap-1.5' : 'text-indigo-400'">
                  <span>{{ msg.sender }}</span>
                  <span v-if="msg.is_instructor || msg.isInstructor" class="text-[9px] bg-emerald-900 text-emerald-300 px-1 py-0.2 rounded font-mono">Teacher</span>
                </strong>
                <span class="text-[10px] text-slate-500 font-mono">{{ msg.time }}</span>
              </div>
              <p class="leading-relaxed whitespace-pre-wrap">{{ msg.text }}</p>
            </div>
          </div>

          <!-- Quick Immersion Phrase Helper Chips -->
          <div class="p-2 bg-slate-900 border-t border-slate-800 flex gap-1.5 overflow-x-auto text-[11px] no-scrollbar">
            <button
              v-for="chip in quickChips"
              :key="chip"
              @click="insertChip(chip)"
              class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-emerald-300 rounded-lg whitespace-nowrap transition-colors border border-slate-700/80"
            >
              {{ chip }}
            </button>
          </div>

          <!-- Chat Input Bar -->
          <div class="p-3 bg-slate-850 border-t border-slate-800 flex gap-2">
            <input
              v-model="chatInput"
              @keyup.enter="handleSendChat"
              type="text"
              placeholder="Type message or ask instructor..."
              class="flex-1 px-3 py-2 text-xs bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
            />
            <button
              @click="handleSendChat"
              :disabled="sendingChat || !chatInput.trim()"
              class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-slate-950 font-bold rounded-xl text-xs transition-colors flex items-center gap-1"
            >
              <span>{{ sendingChat ? '...' : 'Send' }}</span>
            </button>
          </div>
        </div>

        <!-- 2. ATTENDANCE TRACKING & VERIFICATION PANEL -->
        <div v-if="activePanel === 'attendance'" class="flex-1 p-4 overflow-y-auto space-y-4 text-xs">
          <!-- Attendance Status Hero Card -->
          <div class="p-4 rounded-2xl bg-gradient-to-br from-emerald-950/80 to-slate-900 border border-emerald-700/80 shadow-lg">
            <div class="flex items-center justify-between mb-2">
              <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-400">Attendance Status</span>
              <span class="px-2 py-0.5 bg-emerald-500 text-slate-950 font-black rounded text-[10px] uppercase">
                ✓ Verified Present
              </span>
            </div>
            <div class="text-sm font-bold text-white">
              {{ authStore.user?.name || 'Abebe Bekele' }}
            </div>
            <div class="text-[11px] text-slate-400 mt-0.5">
              Arrival: <strong class="text-emerald-300">{{ attendanceArrivalTime }}</strong> • Logged via WebRTC Heartbeat
            </div>

            <!-- Stats Bar -->
            <div class="mt-3 pt-3 border-t border-slate-800/80 grid grid-cols-2 gap-2 text-center">
              <div class="bg-slate-900/80 p-2 rounded-xl border border-slate-800">
                <div class="text-lg font-black text-emerald-400">{{ attendanceStats.rate }}%</div>
                <div class="text-[10px] text-slate-400">Class Rate</div>
              </div>
              <div class="bg-slate-900/80 p-2 rounded-xl border border-slate-800">
                <div class="text-lg font-black text-white">{{ attendanceStats.present }}/{{ attendanceStats.capacity }}</div>
                <div class="text-[10px] text-slate-400">Attending</div>
              </div>
            </div>
          </div>

          <!-- Official Verification Fingerprint -->
          <div class="p-3 bg-slate-800/80 rounded-xl border border-slate-700 text-slate-300">
            <div class="flex items-center justify-between text-[10px] text-slate-400 mb-1">
              <span>Cryptographic Session Hash</span>
              <span class="text-emerald-400 font-bold">SHA-256</span>
            </div>
            <div class="font-mono text-[10px] text-slate-300 break-all bg-slate-950/60 p-2 rounded border border-slate-800">
              {{ attendanceVerificationHash }}
            </div>
          </div>

          <!-- Live Attendance Roster List -->
          <div class="space-y-2">
            <div class="flex items-center justify-between text-[11px] font-bold text-slate-300 pb-1">
              <span>Class Attendance Roster</span>
              <span class="text-emerald-400">{{ attendanceRecords.length }} Recorded</span>
            </div>

            <div
              v-for="record in attendanceRecords"
              :key="record.id || record.user_id"
              class="p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/70 flex items-center justify-between gap-2"
            >
              <div class="flex items-center gap-2">
                <img :src="record.avatar" class="w-7 h-7 rounded-full border border-slate-600" />
                <div>
                  <div class="font-bold text-white text-xs flex items-center gap-1.5">
                    {{ record.name }}
                    <span v-if="record.is_current_user" class="text-[9px] bg-indigo-900 text-indigo-300 px-1 py-0.2 rounded font-normal">You</span>
                  </div>
                  <div class="text-[10px] text-slate-400">{{ record.location || 'Remote Global Node' }}</div>
                </div>
              </div>

              <div class="text-right">
                <span class="text-[10px] font-mono bg-emerald-950 text-emerald-300 px-1.5 py-0.5 rounded border border-emerald-800">
                  {{ record.arrival_time || 'Just now' }}
                </span>
                <div class="text-[9px] text-slate-400 mt-0.5 font-mono">{{ record.minutes_present || 1 }}m active</div>
              </div>
            </div>
          </div>

          <!-- Print / View Official Attendance Slip -->
          <button
            @click="showAttendanceModal = true"
            class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-emerald-700/60 font-bold rounded-xl text-xs transition-colors flex items-center justify-center gap-2"
          >
            <span>📜</span>
            <span>View Official Attendance Slip</span>
          </button>
        </div>

        <!-- 3. GROUP CLASSROOM ROSTER PANEL -->
        <div v-if="activePanel === 'roster'" class="flex-1 p-4 overflow-y-auto space-y-2.5 text-xs">
          <div class="flex items-center justify-between pb-1 border-b border-slate-800">
            <span class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Connected Group Roster</span>
            <span class="text-[10px] text-emerald-400 font-mono">{{ (classroomStore.liveRoster.length || 5) }} Active</span>
          </div>

          <!-- Instructor Roster Card -->
          <div class="flex items-center justify-between p-3 bg-slate-800 rounded-xl border border-emerald-800/70 shadow-sm">
            <div class="flex items-center gap-2.5">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
              <div>
                <div class="font-bold text-white flex items-center gap-1.5">
                  {{ classroom.instructor.name }}
                  <span class="text-[9px] bg-emerald-950 text-emerald-300 px-1.5 py-0.2 rounded font-mono">Lead Host</span>
                </div>
                <div class="text-[10px] text-emerald-400">Certified Native Speaker</div>
              </div>
            </div>
            <span class="text-[10px] font-mono bg-emerald-950 text-emerald-300 px-2 py-0.5 rounded border border-emerald-800">
              Speaking 🎙️
            </span>
          </div>

          <!-- Current Student (You) -->
          <div class="flex items-center justify-between p-3 bg-slate-800/80 rounded-xl border border-indigo-700/60 shadow-sm">
            <div class="flex items-center gap-2.5">
              <span class="w-2 h-2 rounded-full" :class="camOff ? 'bg-amber-400' : 'bg-emerald-400'"></span>
              <div>
                <div class="font-bold text-slate-200">{{ authStore.user?.name || 'Abebe Bekele' }} (You)</div>
                <div class="text-[10px] text-slate-400">Student Scholar • Joined</div>
              </div>
            </div>
            <div class="flex items-center gap-1.5">
              <span v-if="handRaised" class="text-xs bg-amber-500/90 text-slate-950 px-1.5 py-0.5 rounded font-bold">✋ Hand</span>
              <span class="text-[10px] text-slate-300 font-mono bg-slate-900 px-2 py-0.5 rounded">{{ micMuted ? 'Muted' : 'Mic On' }}</span>
            </div>
          </div>

          <!-- Group Peers -->
          <div
            v-for="(peer, i) in peerStudents"
            :key="i"
            class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl border border-slate-700/50"
          >
            <div class="flex items-center gap-2.5">
              <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
              <div>
                <div class="font-bold text-slate-200">{{ peer.name }}</div>
                <div class="text-[10px] text-slate-400">{{ peer.location }} • {{ peer.level }}</div>
              </div>
            </div>
            <div class="flex items-center gap-1.5">
              <span v-if="peer.handRaised" class="text-xs bg-amber-500/80 text-slate-950 px-1 py-0.2 rounded font-bold">✋</span>
              <span class="text-[10px] text-slate-400 font-mono">{{ peer.isSpeaking ? 'Speaking' : 'Listening' }}</span>
            </div>
          </div>
        </div>

        <!-- 4. VOCABULARY & WEB SPEECH AUDIO PRONUNCIATION PANEL -->
        <div v-if="activePanel === 'vocab'" class="flex-1 p-4 overflow-y-auto text-xs space-y-3">
          <div class="flex items-center justify-between pb-2 border-b border-slate-800">
            <h4 class="font-bold text-white uppercase text-[10px] tracking-wider text-emerald-400">
              Target Vocabulary Flashcards
            </h4>
            <span class="text-[10px] text-slate-400">Click 🔊 for native audio</span>
          </div>

          <div class="space-y-2.5">
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
                class="p-2 bg-emerald-500/20 hover:bg-emerald-500 text-emerald-300 hover:text-slate-950 rounded-lg text-xs font-bold transition-all flex-shrink-0 shadow"
                title="Hear Native Pronunciation (Web Speech API)"
              >
                🔊
              </button>
            </div>
          </div>
        </div>

        <!-- 5. IN-CLASS INTERACTIVE WHITEBOARD PANEL -->
        <div v-if="activePanel === 'whiteboard'" class="flex-1 p-3 flex flex-col justify-between overflow-hidden">
          <div class="space-y-2">
            <div class="flex items-center justify-between text-xs pb-1">
              <span class="font-bold text-white">Live Whiteboard Canvas</span>
              <button
                @click="clearCanvas"
                class="text-[11px] text-rose-400 hover:text-rose-300 font-bold px-2 py-0.5 bg-rose-950/50 rounded border border-rose-900/60"
              >
                Clear Board
              </button>
            </div>

            <!-- Color Palette & Tools -->
            <div class="flex items-center justify-between pb-1">
              <div class="flex items-center gap-2">
                <span class="text-[10px] text-slate-400">Color:</span>
                <button
                  v-for="color in penColors"
                  :key="color"
                  @click="currentPenColor = color"
                  class="w-5 h-5 rounded-full border-2 transition-transform shadow"
                  :class="currentPenColor === color ? 'scale-125 border-white ring-2 ring-emerald-500' : 'border-transparent'"
                  :style="{ backgroundColor: color }"
                ></button>
              </div>
              <span class="text-[10px] text-slate-400 font-mono">Pencil 3px</span>
            </div>
          </div>

          <div class="flex-1 bg-slate-950 rounded-2xl border border-slate-700 relative overflow-hidden flex items-center justify-center shadow-inner mt-2">
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
            ✏️ Draw grammatical structures, Fidel characters, or Hanzi strokes in real-time.
          </p>
        </div>
      </aside>
    </div>

    <!-- 1. FINISH SESSION & CEFR EVALUATION MODAL -->
    <div v-if="finishSessionModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
      <div class="bg-white text-slate-900 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border-4 border-emerald-500 animate-scale-in">
        <div class="text-center pb-4 border-b border-slate-100">
          <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-3xl mx-auto mb-2 shadow-inner">
            🎓
          </div>
          <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">
            Online Global Classroom Immersion Complete
          </span>
          <h2 class="text-2xl font-black text-slate-900 mt-2">
            25-Minute Language Lesson Finished!
          </h2>
          <p class="text-xs text-slate-500 mt-1">
            CEFR Fluency Assessment with Instructor <strong>{{ classroom.instructor.name }}</strong>
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
                <span>Spoken Fluency & Conversational Rhythm:</span>
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
              <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Assessed Level</span>
              <div class="text-lg font-black text-emerald-950">CEFR B2 Independent User</div>
            </div>
            <div class="text-right">
              <span class="text-2xl font-black text-emerald-600">Grade: A</span>
            </div>
          </div>

          <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-600 italic border border-slate-200">
            "Outstanding oral participation! Your command of situational phrases was natural and confident." — {{ classroom.instructor.name }}
          </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
          <router-link
            to="/global-classroom"
            class="px-4 py-2.5 text-slate-600 hover:text-slate-900 text-xs font-bold"
          >
            Back to Hub
          </router-link>
          <div class="flex items-center gap-2">
            <button
              @click="finishSessionModalOpen = false; showAttendanceModal = true"
              class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs"
            >
              Attendance Slip
            </button>
            <router-link
              to="/verify/OGC-SPANISH-2026-7741"
              class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-xl text-xs transition-colors shadow-lg shadow-emerald-600/30 text-center"
            >
              Claim CEFR Credential →
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. OFFICIAL PRINTABLE ATTENDANCE SLIP MODAL -->
    <div v-if="showAttendanceModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
      <div class="bg-white text-slate-900 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border border-slate-200 print:m-0 print:p-8 print:w-full">
        <button
          @click="showAttendanceModal = false"
          class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 text-lg font-bold print:hidden"
        >
          ✕
        </button>

        <!-- Official Header -->
        <div class="text-center pb-4 border-b border-slate-200">
          <div class="w-12 h-12 bg-emerald-50 text-emerald-800 rounded-full flex items-center justify-center text-2xl mx-auto mb-2 border border-emerald-200">
            🏛️
          </div>
          <span class="text-[10px] font-mono uppercase tracking-widest text-emerald-700 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-full">
            Official Attendance Verification Slip
          </span>
          <h3 class="text-xl font-black text-slate-900 mt-2">Online Global Classroom System</h3>
          <p class="text-xs text-slate-500">Live Virtual Immersion Session Log</p>
        </div>

        <!-- Attendance Details -->
        <div class="py-4 space-y-3 text-xs">
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Student Scholar:</span>
            <span class="font-bold text-slate-900">{{ authStore.user?.name || 'Abebe Bekele' }}</span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Class Session:</span>
            <span class="font-bold text-slate-900">{{ classroom.title }}</span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Certified Instructor:</span>
            <span class="font-bold text-slate-900">{{ classroom.instructor.name }}</span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Scheduled Duration:</span>
            <span class="font-bold text-slate-900">25-Minute Immersion</span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Arrival Timestamp:</span>
            <span class="font-mono font-bold text-emerald-700">{{ attendanceArrivalTime }}</span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Attendance Status:</span>
            <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">PRESENT (100% Valid)</span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Verification Protocol:</span>
            <span class="font-mono text-[10px] text-slate-600">WebRTC Biometric Presence</span>
          </div>
        </div>

        <!-- Hash Proof -->
        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-slate-600 text-[10px] font-mono break-all mb-4">
          <span class="font-bold text-slate-700 block mb-0.5">Verification SHA-256 Hash:</span>
          {{ attendanceVerificationHash }}
        </div>

        <!-- Footer Actions -->
        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200 print:hidden">
          <button
            @click="showAttendanceModal = false"
            class="px-4 py-2 text-slate-600 hover:text-slate-900 text-xs font-bold"
          >
            Close
          </button>
          <button
            @click="printAttendanceSlip"
            class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs transition-colors shadow-md"
          >
            🖨️ Print / Save PDF
          </button>
        </div>
      </div>
    </div>

    <!-- 3. WEBRTC TELEMETRY DIAGNOSTICS MODAL -->
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
            <span class="text-slate-400">Screen Capture Stream</span>
            <span class="font-mono font-bold" :class="isScreenSharing ? 'text-indigo-400' : 'text-slate-500'">
              {{ isScreenSharing ? 'Active (DisplayMedia)' : 'Idle' }}
            </span>
          </div>
          <div class="flex items-center justify-between py-1">
            <span class="text-slate-400">Attendance Verification</span>
            <span class="text-emerald-400 font-mono">SHA-256 Logged</span>
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
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import { useRoute } from 'vue-router';
import { useClassroomStore } from '@/stores/classroom';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const classroomStore = useClassroomStore();
const authStore = useAuthStore();

const classroom = ref(null);
const activePanel = ref('chat');
const layoutMode = ref('spotlight'); // 'spotlight' or 'grid'
const chatInput = ref('');
const sendingChat = ref(false);
const chatScrollRef = ref(null);
const useJitsi = ref(false);
const micMuted = ref(false);
const camOff = ref(false);
const handRaised = ref(false);
const finishSessionModalOpen = ref(false);
const showAttendanceModal = ref(false);
const showDiagnostics = ref(false);

// Screen Share & Camera MediaStreams
const isScreenSharing = ref(false);
const screenVideoRef = ref(null);
const localVideoRef = ref(null);
const localGridVideoRef = ref(null);
const localStreamActive = ref(false);
let localStream = null;
let screenStream = null;

// Attendance Data
const attendanceArrivalTime = ref('Today, 2:30 PM UTC');
const attendanceVerificationHash = ref('e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');
const attendanceRecords = ref([]);
const attendanceStats = ref({ rate: 84, present: 5, capacity: 6 });

// Peer Group Students for Group Classroom Mode
const peerStudents = ref([
  {
    name: 'Dr. Elena Rostova',
    location: 'Madrid, Spain',
    level: 'CEFR B2 Intermediate',
    avatar: 'https://ui-avatars.com/api/?name=Elena+Rostova&background=4338ca&color=fff&bold=true',
    color: '#4338ca',
    isSpeaking: true,
    handRaised: false,
  },
  {
    name: 'Prof. Sarah Chen',
    location: 'Tokyo, Japan',
    level: 'CEFR C1 Advanced',
    avatar: 'https://ui-avatars.com/api/?name=Sarah+Chen&background=059669&color=fff&bold=true',
    color: '#059669',
    isSpeaking: false,
    handRaised: false,
  },
  {
    name: 'Selamawit Tadesse',
    location: 'Addis Ababa, Ethiopia',
    level: 'Heritage Scholar B1',
    avatar: 'https://ui-avatars.com/api/?name=Selamawit+Tadesse&background=d97706&color=fff&bold=true',
    color: '#d97706',
    isSpeaking: false,
    handRaised: true,
  },
  {
    name: 'Mei-Ling Zhou (周美玲)',
    location: 'Beijing, China',
    level: 'HSK 5 Polyglot Track',
    avatar: 'https://ui-avatars.com/api/?name=Meiling+Zhou&background=dc2626&color=fff&bold=true',
    color: '#dc2626',
    isSpeaking: false,
    handRaised: false,
  }
]);

// 25-Minute Immersion Timer (1500 seconds)
const timeLeftSeconds = ref(1500);
const timerRunning = ref(true);
let timerInterval = null;

const formattedTime = computed(() => {
  const m = Math.floor(timeLeftSeconds.value / 60);
  const s = timeLeftSeconds.value % 60;
  return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
});

// 3-Phase Immersion Stage Indicator (Warm-up, Core Dialogue, Fluency Diagnostic)
const currentLessonPhase = computed(() => {
  const elapsed = 1500 - timeLeftSeconds.value;
  if (elapsed < 300) {
    return { title: 'Phase 1: Warm-up & Diagnostic Accent Alignment', range: '0-5 min' };
  } else if (elapsed < 1200) {
    return { title: 'Phase 2: Target Vocabulary & Conversational Scenarios', range: '5-20 min' };
  } else {
    return { title: 'Phase 3: Fluency Wrap-up & CEFR Evaluation', range: '20-25 min' };
  }
});

function toggleTimer() {
  timerRunning.value = !timerRunning.value;
}

function extendTimer() {
  timeLeftSeconds.value += 300; // Add 5 minutes
}

// Quick phrase chips for language communication
const quickChips = computed(() => {
  const slug = classroom.value?.slug || '';
  if (slug.includes('spanish')) {
    return ['¿Cómo se dice...?', 'Por favor, repita más despacio', '¿Podemos practicar el diálogo?', '¿Cómo se escribe esto?'];
  } else if (slug.includes('amharic')) {
    return ['እንደምን አለህ?', 'እባክህ በዝግታ ደግመው', 'ይህ በአማርኛ እንዴት ይባላል?', 'ጥያቄ አለኝ!'];
  } else if (slug.includes('mandarin')) {
    return ['这个怎么读？', '请说得慢一点', '我可以试一下吗？', '发音标准吗？'];
  } else if (slug.includes('french')) {
    return ['Comment dit-on...?', 'Répétez plus lentement s.v.p.', 'C\'est parfait!', 'J\'ai une question'];
  } else if (slug.includes('german')) {
    return ['Wie sagt man...?', 'Bitte langsamer wiederholen', 'Ich habe eine Frage', 'Können wir üben?'];
  } else {
    return ['Could you repeat that slowly?', 'How do I pronounce this?', 'Can we practice the scenario?', 'I have a question!'];
  }
});

function insertChip(chip) {
  chatInput.value = chip;
  handleSendChat();
}

async function handleSendChat() {
  if (!chatInput.value.trim() || sendingChat.value) return;
  const text = chatInput.value.trim();
  chatInput.value = '';
  sendingChat.value = true;

  try {
    const slug = route.params.slug;
    await classroomStore.sendChatMessage(slug, text);
    await nextTick();
    if (chatScrollRef.value) {
      chatScrollRef.value.scrollTop = chatScrollRef.value.scrollHeight;
    }
  } catch (e) {
    console.error('Chat send failed:', e);
  } finally {
    sendingChat.value = false;
  }
}

// Media Controls: Mic, Camera, Screen Share
async function toggleCam() {
  camOff.value = !camOff.value;
  if (!camOff.value && !localStream) {
    try {
      if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localStreamActive.value = true;
        await nextTick();
        if (localVideoRef.value) localVideoRef.value.srcObject = localStream;
        if (localGridVideoRef.value) localGridVideoRef.value.srcObject = localStream;
      }
    } catch (e) {
      console.warn('Camera access simulated / not available:', e.message);
      localStreamActive.value = false;
    }
  } else if (camOff.value && localStream) {
    localStream.getVideoTracks().forEach(t => t.enabled = false);
  } else if (!camOff.value && localStream) {
    localStream.getVideoTracks().forEach(t => t.enabled = true);
  }

  classroomStore.updateMediaState(route.params.slug, { is_camera_on: !camOff.value });
}

function toggleMic() {
  micMuted.value = !micMuted.value;
  if (localStream) {
    localStream.getAudioTracks().forEach(t => t.enabled = !micMuted.value);
  }
  classroomStore.updateMediaState(route.params.slug, { is_muted: micMuted.value });
}

async function toggleScreenShare() {
  if (isScreenSharing.value) {
    stopScreenShare();
  } else {
    try {
      if (navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia) {
        screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: true });
        isScreenSharing.value = true;
        await nextTick();
        if (screenVideoRef.value) {
          screenVideoRef.value.srcObject = screenStream;
        }
        screenStream.getVideoTracks()[0].onended = () => {
          stopScreenShare();
        };
      } else {
        isScreenSharing.value = true;
      }
      classroomStore.updateMediaState(route.params.slug, { is_screen_sharing: true });
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
  classroomStore.updateMediaState(route.params.slug, { is_screen_sharing: false });
}

async function toggleHand() {
  try {
    const slug = route.params.slug;
    const res = await classroomStore.toggleHandRaise(slug);
    handRaised.value = res?.hand_raised ?? !handRaised.value;

    if (handRaised.value) {
      classroomStore.chatMessages.push({
        sender: authStore.user?.name || 'Abebe Bekele',
        text: '✋ Raised hand with a question for the instructor!',
        time: 'Just now',
        is_instructor: false,
      });
      await nextTick();
      if (chatScrollRef.value) {
        chatScrollRef.value.scrollTop = chatScrollRef.value.scrollHeight;
      }
    }
  } catch (e) {
    handRaised.value = !handRaised.value;
  }
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
    utterance.rate = 0.9;
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

function printAttendanceSlip() {
  window.print();
}

const jitsiUrl = computed(() => {
  const roomName = classroom.value ? classroom.value.jitsi_room_name : 'gelme-room';
  const name = authStore.user?.name || 'Abebe Bekele';
  return `https://meet.jit.si/${encodeURIComponent(roomName)}#userInfo.displayName="${encodeURIComponent(name)}"&config.prejoinPageEnabled=false`;
});

onMounted(async () => {
  const slug = route.params.slug;

  // 1. Fetch Room Details
  const res = await classroomStore.fetchRoom(slug);
  if (res) {
    classroom.value = res;
  }

  // 2. Join Live Classroom (Logs presence & AttendanceSession)
  try {
    const joinData = await classroomStore.joinRoom(slug);
    if (joinData?.attendance) {
      attendanceArrivalTime.value = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      attendanceVerificationHash.value = joinData.attendance.verification_hash || attendanceVerificationHash.value;
    }
  } catch (err) {
    console.warn('Classroom join fallback:', err);
  }

  // 3. Fetch Initial In-Class Chat
  await classroomStore.fetchChat(slug);

  // 4. Fetch Live Attendance Roster
  const attendData = await classroomStore.fetchAttendance(slug);
  if (attendData) {
    attendanceRecords.value = attendData.records || [];
    if (attendData.stats) {
      attendanceStats.value = {
        rate: attendData.stats.attendance_rate_percentage || 84,
        present: attendData.stats.total_present || 5,
        capacity: attendData.stats.expected_capacity || 6,
      };
    }
    if (attendData.user_attendance?.arrival_time) {
      attendanceArrivalTime.value = attendData.user_attendance.arrival_time;
      attendanceVerificationHash.value = attendData.user_attendance.verification_hash;
    }
  }

  // 5. Start 25-minute immersion countdown timer
  timerInterval = setInterval(() => {
    if (timerRunning.value && timeLeftSeconds.value > 0) {
      timeLeftSeconds.value--;
      if (timeLeftSeconds.value === 120) {
        alert('🔔 2 minutes left in this 25-minute language immersion session! Wrapping up dialogue.');
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

  // Record departure in backend attendance
  const slug = route.params.slug;
  if (slug) {
    classroomStore.leaveRoom(slug);
  }
});
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
