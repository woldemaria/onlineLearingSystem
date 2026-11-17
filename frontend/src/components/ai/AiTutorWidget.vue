<template>
  <div class="fixed bottom-6 right-6 z-40">
    <!-- Collapsed Floating Button -->
    <button
      v-if="!isOpen"
      @click="openTutor"
      class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-600 text-white rounded-full shadow-2xl shadow-indigo-500/40 hover:scale-105 active:scale-95 transition-all group"
      title="Open AI Study Companion"
    >
      <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm">
        ✨
      </div>
      <div class="text-left pr-1">
        <div class="text-[10px] font-semibold uppercase tracking-wider text-indigo-200">AI Study Companion</div>
        <div class="text-xs font-bold truncate max-w-[150px]">{{ activeLearningContext.badgeTitle || 'Ask anything...' }}</div>
      </div>
    </button>

    <!-- Expanded Chat Window -->
    <div
      v-else
      class="w-96 sm:w-[460px] h-[580px] bg-white rounded-3xl shadow-2xl border border-slate-200 flex flex-col overflow-hidden animate-in fade-in slide-in-from-bottom-6 duration-200"
    >
      <!-- Header -->
      <div class="p-3.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-900 text-white flex items-center justify-between">
        <div class="flex items-center gap-2.5 min-w-0">
          <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-base flex-shrink-0">
            🧠
          </div>
          <div class="min-w-0">
            <div class="flex items-center gap-1.5">
              <h3 class="font-bold text-xs sm:text-sm leading-tight truncate">GELME AI Study Companion</h3>
              <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-emerald-500/30 text-emerald-300 font-mono font-bold">LIVE</span>
            </div>
            <p class="text-[10px] text-indigo-200 truncate mt-0.5">
              {{ activeLearningContext.badgeSubtitle || 'Context-Aware AI Tutor & Polyglot Mentor' }}
            </p>
          </div>
        </div>

        <div class="flex items-center gap-1 flex-shrink-0">
          <button
            @click="clearChat"
            class="p-1.5 hover:bg-white/10 rounded-lg transition-colors text-white/70 hover:text-white"
            title="Start New Discussion / Reset Context"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
          </button>
          <button
            @click="isOpen = false"
            class="p-1.5 hover:bg-white/10 rounded-lg transition-colors text-white/80 hover:text-white"
            title="Minimize"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>
      </div>

      <!-- Active Context Banner -->
      <div class="px-3.5 py-1.5 bg-indigo-50 border-b border-indigo-100 flex items-center justify-between text-[11px] text-indigo-900">
        <div class="flex items-center gap-1.5 truncate">
          <span class="w-2 h-2 rounded-full bg-indigo-600 animate-ping"></span>
          <span class="font-bold truncate">{{ activeLearningContext.bannerText }}</span>
        </div>
        <span class="text-[10px] text-indigo-600 font-mono flex-shrink-0 ml-2">Adaptive Model</span>
      </div>

      <!-- Messages Area -->
      <div ref="chatContainerRef" class="flex-1 p-4 overflow-y-auto space-y-3.5 bg-slate-50 text-xs sm:text-sm">
        <div
          v-for="(msg, i) in messages"
          :key="i"
          class="flex flex-col"
          :class="msg.sender === 'user' ? 'items-end' : 'items-start'"
        >
          <div
            class="max-w-[88%] rounded-2xl p-3.5 shadow-sm leading-relaxed"
            :class="msg.sender === 'user' ? 'bg-indigo-600 text-white rounded-br-none font-medium' : 'bg-white border border-slate-200 text-slate-800 rounded-bl-none'"
          >
            <!-- Badge if AI response has contextual tag -->
            <div v-if="msg.sender === 'ai' && msg.badge" class="mb-2 pb-1.5 border-b border-slate-100 flex items-center justify-between text-[10px] text-indigo-600 font-bold">
              <span>{{ msg.badge }}</span>
            </div>

            <!-- Formatted Content -->
            <div class="ai-formatted-content text-xs leading-relaxed space-y-2" v-html="renderMarkdown(msg.text)"></div>

            <!-- Sources / References -->
            <div v-if="msg.sources?.length" class="mt-2.5 pt-1.5 border-t border-slate-100 text-[10px] text-slate-400">
              <span class="font-bold text-slate-500">References: </span>
              <span>{{ msg.sources.join(' • ') }}</span>
            </div>

            <!-- Suggested Actions inside message -->
            <div v-if="msg.suggestedActions?.length" class="mt-3 pt-2 border-t border-slate-100 flex flex-wrap gap-1.5">
              <button
                v-for="(act, aIdx) in msg.suggestedActions"
                :key="aIdx"
                @click="sendMessage(act)"
                class="text-[10px] px-2 py-1 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-semibold rounded-lg transition-colors border border-slate-200/70"
              >
                ↳ {{ act }}
              </button>
            </div>
          </div>
          <span class="text-[9px] text-slate-400 mt-1 px-1 font-mono">{{ msg.time }}</span>
        </div>

        <div v-if="isTyping" class="flex items-center gap-2 p-3 bg-white border border-slate-200 rounded-2xl w-fit shadow-sm">
          <span class="text-[11px] text-slate-500 font-medium">Synthesizing tutor response...</span>
          <div class="flex items-center gap-1">
            <div class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce [animation-delay:0.2s]"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce [animation-delay:0.4s]"></div>
          </div>
        </div>
      </div>

      <!-- Quick Contextual Prompt Chips -->
      <div class="px-3 py-2 bg-white border-t border-slate-100 flex items-center gap-1.5 overflow-x-auto no-scrollbar">
        <button
          v-for="prompt in contextualQuickPrompts"
          :key="prompt"
          @click="sendMessage(prompt)"
          class="text-[11px] whitespace-nowrap bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 px-2.5 py-1 rounded-full text-slate-600 transition-colors font-medium border border-slate-200/60"
        >
          {{ prompt }}
        </button>
      </div>

      <!-- Input Bar -->
      <div class="p-3 bg-white border-t border-slate-200 flex items-center gap-2">
        <input
          ref="inputRef"
          v-model="inputQuery"
          @keyup.enter="sendMessage(inputQuery)"
          type="text"
          :placeholder="inputPlaceholder"
          class="flex-1 px-3.5 py-2.5 text-xs bg-slate-100 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
        />
        <button
          @click="sendMessage(inputQuery)"
          :disabled="!inputQuery.trim() || isTyping"
          class="p-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-xl transition-all shadow-md shadow-indigo-600/20"
          title="Send Question"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, watch } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import { useCourseStore } from '@/stores/courses';

const route = useRoute();
const authStore = useAuthStore();
const courseStore = useCourseStore();

const isOpen = ref(false);
const inputQuery = ref('');
const isTyping = ref(false);
const currentSessionId = ref(null);
const chatContainerRef = ref(null);
const inputRef = ref(null);

const activeLearningContext = computed(() => {
  const path = route.path;
  const slug = route.params.slug;

  if (path.startsWith('/learn/')) {
    const courseTitle = courseStore.currentCourse?.title || 'Interactive Course Player';
    return {
      type: 'course_player',
      courseSlug: slug,
      badgeTitle: 'Lecture Tutor',
      badgeSubtitle: 'GELME Interactive Course Player',
      bannerText: `Active Lecture: ${courseTitle}`,
    };
  } else if (path.startsWith('/classroom/')) {
    return {
      type: 'classroom',
      courseSlug: slug,
      badgeTitle: 'Immersion Coach',
      badgeSubtitle: 'Online Global Classroom Native Hub',
      bannerText: `Live Language Immersion: ${slug || '25-Min Session'}`,
    };
  } else if (path.startsWith('/courses/')) {
    const courseTitle = courseStore.currentCourse?.title || 'Course Overview';
    return {
      type: 'course_detail',
      courseSlug: slug,
      badgeTitle: 'Syllabus Advisor',
      badgeSubtitle: 'Course Syllabus & Prerequisites',
      bannerText: `Exploring: ${courseTitle}`,
    };
  } else if (path === '/dashboard') {
    return {
      type: 'dashboard',
      courseSlug: null,
      badgeTitle: 'Academic Advisor',
      badgeSubtitle: 'Degree & Career Milestones',
      bannerText: 'Personal Dashboard & Verified Credentials',
    };
  }

  return {
    type: 'general',
    courseSlug: null,
    badgeTitle: 'AI Study Companion',
    badgeSubtitle: 'Polyglot Language Tutor & Full-Stack Mentor',
    bannerText: 'Global Learning Ecosystem Assistant',
  };
});

const contextualQuickPrompts = computed(() => {
  const slug = (activeLearningContext.value.courseSlug || '').toLowerCase();
  const type = activeLearningContext.value.type;

  if (slug.includes('spanish') || slug.includes('immersion') || type === 'classroom') {
    return [
      '¿Cómo se dice "Where is the hotel"?',
      'Explain difference between ser and estar',
      'Practice conversational restaurant ordering',
      'Quiz me on Spanish irregular verbs',
    ];
  } else if (slug.includes('amharic')) {
    return [
      'Teach me polite greetings in Amharic',
      'Explain the 7 Ge\'ez vowel orders',
      'What does "እንደምን አለህ" mean?',
      'Practice numbers 1 to 10 in Amharic',
    ];
  } else if (slug.includes('ai') || slug.includes('deep-learning') || slug.includes('transformer')) {
    return [
      'Explain Scaled Dot-Product Attention',
      'How does RAG vector search work?',
      'What are Query, Key, and Value matrices?',
      'Quiz me on Transformer architecture',
    ];
  } else if (slug.includes('web') || slug.includes('full-stack') || slug.includes('devops')) {
    return [
      'How does Vue 3 Composition API work?',
      'Explain Pinia reactive state hydration',
      'How does RFC 7233 partial video streaming work?',
      'Quiz me on this module',
    ];
  }

  return [
    'How does certificate verification work?',
    'Quiz me on this topic',
    'Practice Spanish conversation',
    'Practice Amharic greetings',
    'Explain full-stack architecture',
  ];
});

const inputPlaceholder = computed(() => {
  if (activeLearningContext.value.type === 'classroom') {
    return 'Ask your native language coach or practice a phrase...';
  } else if (activeLearningContext.value.type === 'course_player') {
    return 'Ask about this lecture, debug code, or request a quiz...';
  }
  return 'Ask a question about code, quizzes, or languages...';
});

const messages = ref([
  {
    sender: 'ai',
    text: "Hello! I am your GELME AI Study Companion & Polyglot Mentor. I am context-aware across all courses, code labs, and 14+ native languages in the Online Global Classroom. How can I assist your study session today?",
    badge: '🧠 System Initialized',
    time: 'Just now',
    suggestedActions: [
      'Quiz me on this module',
      'Practice conversational immersion',
      'Explain course concepts',
    ]
  }
]);

function openTutor() {
  isOpen.value = true;
  nextTick(() => {
    inputRef.value?.focus();
    scrollToBottom();
  });
}

function clearChat() {
  messages.value = [
    {
      sender: 'ai',
      text: `Discussion refreshed for **${activeLearningContext.value.bannerText}**. What concept would you like to explore next?`,
      badge: '🔄 Context Refreshed',
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
      suggestedActions: contextualQuickPrompts.value.slice(0, 3),
    }
  ];
  if (currentSessionId.value) {
    axios.post('/api/ai/clear', { session_id: currentSessionId.value }).catch(() => {});
  }
  currentSessionId.value = null;
}

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
  scrollToBottom();

  try {
    const token = authStore.token || localStorage.getItem('gelme_auth_token');
    const headers = token ? { Authorization: `Bearer ${token}` } : {};

    const payload = {
      message: userText,
      session_id: currentSessionId.value,
      course_slug: activeLearningContext.value.courseSlug,
      context: activeLearningContext.value.type,
      lesson_title: courseStore.currentCourse?.modules?.[0]?.lessons?.[0]?.title || null,
    };

    const res = await axios.post('/api/ai/tutor', payload, { headers });

    if (res.data?.session_id) {
      currentSessionId.value = res.data.session_id;
    }

    messages.value.push({
      sender: 'ai',
      text: res.data.reply,
      badge: res.data.context_badge || null,
      sources: res.data.sources || null,
      suggestedActions: res.data.suggested_actions || null,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    });
  } catch (e) {
    messages.value.push({
      sender: 'ai',
      text: 'I am ready to assist you. Whether you need code architecture walkthroughs, Spanish / Amharic language drills, or verifiable credential explanations, ask me anything!',
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    });
  } finally {
    isTyping.value = false;
    scrollToBottom();
  }
}

function scrollToBottom() {
  nextTick(() => {
    if (chatContainerRef.value) {
      chatContainerRef.value.scrollTop = chatContainerRef.value.scrollHeight;
    }
  });
}

function renderMarkdown(text) {
  if (!text) return '';

  // Escape HTML first for XSS safety
  let safe = text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');

  // Code blocks: ```lang ... ```
  safe = safe.replace(/```([a-zA-Z0-9_\-]*)\n([\s\S]*?)```/g, function (match, lang, code) {
    return `<pre class="my-2 p-3 bg-slate-900 text-slate-100 rounded-xl font-mono text-[11px] overflow-x-auto border border-slate-800"><code>${code.trim()}</code></pre>`;
  });

  // Inline code: `code`
  safe = safe.replace(/`([^`]+)`/g, '<code class="px-1.5 py-0.5 bg-slate-100 text-indigo-700 rounded font-mono text-[11px] font-bold">$1</code>');

  // Headers: ### Header
  safe = safe.replace(/^### (.*$)/gim, '<h4 class="font-extrabold text-sm text-slate-900 mt-2 mb-1">$1</h4>');
  safe = safe.replace(/^## (.*$)/gim, '<h3 class="font-black text-sm text-slate-900 mt-2.5 mb-1 border-b pb-1">$1</h3>');

  // Bold: **text**
  safe = safe.replace(/\*\*([^*]+)\*\*/g, '<strong class="font-bold text-slate-900">$1</strong>');

  // Bullet items: - item
  safe = safe.replace(/^\s*-\s+(.*$)/gim, '<div class="flex items-start gap-1.5 my-0.5"><span class="text-indigo-500 font-bold">•</span><span>$1</span></div>');

  // Numbered list: 1. item
  safe = safe.replace(/^\s*(\d+)\.\s+(.*$)/gim, '<div class="flex items-start gap-1.5 my-0.5"><span class="text-indigo-600 font-bold">$1.</span><span>$2</span></div>');

  // Paragraph linebreaks
  safe = safe.replace(/\n\n/g, '<div class="h-2"></div>');

  return safe;
}

watch(
  () => route.path,
  () => {
    // If user navigates to a new course while tutor is open, update banner
    scrollToBottom();
  }
);
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
