<template>
  <div class="fixed bottom-6 right-6 z-40">
    <!-- Collapsed Floating Button -->
    <button
      v-if="!isOpen"
      @click="isOpen = true"
      class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-600 text-white rounded-full shadow-2xl shadow-indigo-500/40 hover:scale-105 active:scale-95 transition-all group"
    >
      <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm">
        ✨
      </div>
      <div class="text-left pr-1">
        <div class="text-xs font-semibold uppercase tracking-wider text-indigo-200">AI Study Buddy</div>
        <div class="text-sm font-bold">Ask anything...</div>
      </div>
    </button>

    <!-- Expanded Chat Window -->
    <div
      v-else
      class="w-96 sm:w-[440px] h-[550px] bg-white rounded-3xl shadow-2xl border border-slate-200 flex flex-col overflow-hidden animate-in fade-in slide-in-from-bottom-6 duration-200"
    >
      <!-- Header -->
      <div class="p-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-900 text-white flex items-center justify-between">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-base">
            🧠
          </div>
          <div>
            <h3 class="font-bold text-sm leading-tight">GELME AI Study Companion</h3>
            <p class="text-[11px] text-indigo-200">Polyglot Language Tutor & Full-Stack Mentor</p>
          </div>
        </div>
        <button
          @click="isOpen = false"
          class="p-1.5 hover:bg-white/10 rounded-lg transition-colors text-white/80 hover:text-white"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <!-- Messages Area -->
      <div class="flex-1 p-4 overflow-y-auto space-y-3 bg-slate-50 text-xs sm:text-sm">
        <div
          v-for="(msg, i) in messages"
          :key="i"
          class="flex flex-col"
          :class="msg.sender === 'user' ? 'items-end' : 'items-start'"
        >
          <div
            class="max-w-[85%] rounded-2xl p-3.5 shadow-sm leading-relaxed"
            :class="msg.sender === 'user' ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white border border-slate-200 text-slate-800 rounded-bl-none'"
          >
            <div class="whitespace-pre-wrap">{{ msg.text }}</div>
          </div>
          <span class="text-[10px] text-slate-400 mt-1 px-1">{{ msg.time }}</span>
        </div>

        <div v-if="isTyping" class="flex items-center gap-1.5 p-3 bg-white border border-slate-200 rounded-2xl w-fit">
          <div class="w-2 h-2 rounded-full bg-indigo-600 animate-bounce"></div>
          <div class="w-2 h-2 rounded-full bg-indigo-600 animate-bounce [animation-delay:0.2s]"></div>
          <div class="w-2 h-2 rounded-full bg-indigo-600 animate-bounce [animation-delay:0.4s]"></div>
        </div>
      </div>

      <!-- Quick Prompt Chips -->
      <div class="px-3 py-2 bg-white border-t border-slate-100 flex items-center gap-1.5 overflow-x-auto no-scrollbar">
        <button
          v-for="prompt in quickPrompts"
          :key="prompt"
          @click="sendMessage(prompt)"
          class="text-[11px] whitespace-nowrap bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 px-2.5 py-1 rounded-full text-slate-600 transition-colors font-medium"
        >
          {{ prompt }}
        </button>
      </div>

      <!-- Input Bar -->
      <div class="p-3 bg-white border-t border-slate-200 flex items-center gap-2">
        <input
          v-model="inputQuery"
          @keyup.enter="sendMessage(inputQuery)"
          type="text"
          placeholder="Ask a question about code, quizzes, or languages..."
          class="flex-1 px-3.5 py-2.5 text-xs bg-slate-100 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:bg-white"
        />
        <button
          @click="sendMessage(inputQuery)"
          :disabled="!inputQuery.trim() || isTyping"
          class="p-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-xl transition-colors"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const isOpen = ref(false);
const inputQuery = ref('');
const isTyping = ref(false);

const quickPrompts = [
  'How does Vue 3 Composition API work?',
  'Practice Spanish conversation',
  'Practice Amharic greetings',
  'Quiz me on this module',
  'How do edX certificates verify?'
];

const messages = ref([
  {
    sender: 'ai',
    text: "Hello! I am your GELME AI Study Companion & Polyglot Mentor. Whether you want to debug code, practice speaking 14+ world languages for Online Global Classroom, or prepare for Coursera-style graded assessments, I'm here 24/7!",
    time: 'Just now'
  }
]);

async function sendMessage(text) {
  if (!text || !text.trim()) return;

  const userText = text.trim();
  messages.value.push({
    sender: 'user',
    text: userText,
    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
  });

  inputQuery.value = '';
  isTyping.value = true;

  try {
    const res = await axios.post('/api/ai/tutor', {
      message: userText,
      context: 'study_session'
    });

    messages.value.push({
      sender: 'ai',
      text: res.data.reply,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    });
  } catch (e) {
    messages.value.push({
      sender: 'ai',
      text: 'I can help you review full-stack code or practice 25-minute conversational immersion. What language or technical concept shall we review?',
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    });
  } finally {
    isTyping.value = false;
  }
}
</script>
