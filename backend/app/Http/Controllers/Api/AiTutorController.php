<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiSession;
use App\Models\AiMessage;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\UserSession;

class AiTutorController extends Controller
{
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'session_id' => 'nullable|integer',
            'course_slug' => 'nullable|string',
            'course_id' => 'nullable|integer',
            'lesson_title' => 'nullable|string',
            'lesson_id' => 'nullable|integer',
            'context' => 'nullable|string',
            'code_snippet' => 'nullable|string',
        ]);

        $query = trim($validated['message']);
        $contextType = $validated['context'] ?? 'general';
        $courseSlug = $validated['course_slug'] ?? null;
        $courseId = $validated['course_id'] ?? null;
        $lessonTitle = $validated['lesson_title'] ?? null;
        $codeSnippet = $validated['code_snippet'] ?? null;

        // Resolve authenticated user from Bearer token if present
        $user = $request->user();
        if (!$user && $token = $request->bearerToken()) {
            $session = UserSession::with('user')->where('token', $token)->where('expires_at', '>', now())->first();
            $user = $session?->user;
        }
        $userId = $user?->id ?? 4; // Default to student

        // Resolve active course context if provided
        $course = null;
        if ($courseSlug) {
            $course = Course::with(['category', 'institution', 'modules.lessons'])->where('slug', $courseSlug)->first();
        } elseif ($courseId) {
            $course = Course::with(['category', 'institution', 'modules.lessons'])->find($courseId);
        }

        // Retrieve or initiate AI Session
        $sessionId = $validated['session_id'] ?? null;
        $aiSession = null;
        if ($sessionId) {
            $aiSession = AiSession::where('id', $sessionId)->where('user_id', $userId)->first();
        }

        if (!$aiSession) {
            $aiSession = AiSession::create([
                'user_id' => $userId,
                'course_id' => $course?->id,
                'type' => 'tutor',
                'context_type' => $contextType,
                'context_id' => $course?->slug ?? 'general_learning',
                'model' => 'gemini-3.8-flash',
                'provider' => 'Google DeepMind',
                'status' => 'active',
                'metadata' => [
                    'course_title' => $course?->title ?? null,
                    'lesson_title' => $lessonTitle,
                    'user_name' => $user?->name ?? 'Student Learner',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        // Record User Message
        AiMessage::create([
            'ai_session_id' => $aiSession->id,
            'role' => 'user',
            'content' => $query,
            'tokens_used' => max(1, (int) (strlen($query) / 4)),
            'model' => 'gemini-3.8-flash',
            'provider' => 'Google DeepMind',
        ]);

        // Generate Context-Aware AI Tutoring Response
        $result = $this->generateContextualReply($query, $course, $lessonTitle, $contextType, $codeSnippet, $user);

        // Record AI Assistant Message
        AiMessage::create([
            'ai_session_id' => $aiSession->id,
            'role' => 'assistant',
            'content' => $result['reply'],
            'sources' => $result['sources'] ?? null,
            'tokens_used' => max(10, (int) (strlen($result['reply']) / 4)),
            'latency_ms' => 140,
            'model' => 'gemini-3.8-flash',
            'provider' => 'Google DeepMind',
        ]);

        // Update token metrics on session
        $aiSession->increment('total_tokens', (int) ((strlen($query) + strlen($result['reply'])) / 4));

        return response()->json([
            'success' => true,
            'session_id' => $aiSession->id,
            'reply' => $result['reply'],
            'context_badge' => $result['context_badge'],
            'suggested_actions' => $result['suggested_actions'],
            'sources' => $result['sources'],
            'created_at' => now()->toIso8601String(),
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();
        if (!$user && $token = $request->bearerToken()) {
            $session = UserSession::with('user')->where('token', $token)->where('expires_at', '>', now())->first();
            $user = $session?->user;
        }
        $userId = $user?->id ?? 4;

        $sessionId = $request->input('session_id');
        $courseSlug = $request->input('course_slug');

        $query = AiSession::with('messages')
            ->where('user_id', $userId)
            ->where('status', 'active');

        if ($sessionId) {
            $query->where('id', $sessionId);
        } elseif ($courseSlug) {
            $query->where('context_id', $courseSlug);
        }

        $session = $query->orderBy('updated_at', 'desc')->first();

        if (!$session) {
            return response()->json([
                'session_id' => null,
                'messages' => [],
            ]);
        }

        return response()->json([
            'session_id' => $session->id,
            'context_id' => $session->context_id,
            'messages' => $session->messages->map(fn($m) => [
                'sender' => $m->role === 'assistant' ? 'ai' : 'user',
                'text' => $m->content,
                'time' => $m->created_at->format('h:i A'),
            ]),
        ]);
    }

    public function clearSession(Request $request)
    {
        $sessionId = $request->input('session_id');
        if ($sessionId) {
            AiSession::where('id', $sessionId)->update(['status' => 'archived']);
        }

        return response()->json([
            'success' => true,
            'message' => 'AI study session cleared.',
        ]);
    }

    public function generateQuiz(Request $request)
    {
        $topic = $request->input('topic', 'Software Architecture');
        $courseTitle = $request->input('course_title', 'Masterclass');

        $tLower = strtolower($topic . ' ' . $courseTitle);

        if (str_contains($tLower, 'spanish') || str_contains($tLower, 'español')) {
            $questions = [
                [
                    'id' => 1,
                    'question' => "In Spanish, which verb is used to describe permanent characteristics, origin, and professions?",
                    'options' => ["Estar", "Ser", "Haber", "Tener"],
                    'correct_index' => 1,
                    'explanation' => "'Ser' is used for permanent attributes, identities, and origins (e.g. 'Soy ingeniero'), whereas 'Estar' is used for temporary states, emotions, and locations."
                ],
                [
                    'id' => 2,
                    'question' => "How do you politely ask 'Could you repeat that more slowly, please?' in a conversational immersion session?",
                    'options' => [
                        "¿Dónde está la biblioteca?",
                        "¿Podría repetir más despacio, por favor?",
                        "¿Cuánto cuesta el billete de tren?",
                        "Mucho gusto en conocerte."
                    ],
                    'correct_index' => 1,
                    'explanation' => "'¿Podría repetir más despacio, por favor?' is the standard polite request during CEFR oral practice."
                ],
                [
                    'id' => 3,
                    'question' => "What is the key grammatical rule when using the indirect object pronoun with 'gustar'?",
                    'options' => [
                        "The verb conjugates to match the person who likes the item",
                        "The verb conjugates to match the item or activity being liked, preceded by 'me/te/le/nos/les'",
                        "It must always end in '-ando'",
                        "It requires the subjunctive mood in all tenses"
                    ],
                    'correct_index' => 1,
                    'explanation' => "With 'gustar', the subject is actually the thing being liked (e.g. 'Me gustan los cursos'), so the verb conjugates to match the noun."
                ]
            ];
        } elseif (str_contains($tLower, 'amharic') || str_contains($tLower, 'አማርኛ')) {
            $questions = [
                [
                    'id' => 1,
                    'question' => "In Amharic, what is the polite formal greeting used when addressing an elder or respected colleague?",
                    'options' => ["ሰላም (Selam)", "እንደምን አደሩ / እንዴት ነዎት (Endetenewot)", "ቻው (Chao)", "እሺ (Ishi)"],
                    'correct_index' => 1,
                    'explanation' => "'እንዴት ነዎት' (Endetenewot) is the respectful formal address for elders and formal situations in Ethiopian culture."
                ],
                [
                    'id' => 2,
                    'question' => "Which Ge'ez alphabet vowel order represents the fourth form (ካዕብ)?",
                    'options' => ["ግዕዝ (ä)", "ካዕብ (u)", "ሣልስ (i)", "ራብዕ (a)"],
                    'correct_index' => 3,
                    'explanation' => "The 7 Ge'ez vowel orders are: 1st ግዕዝ (ä), 2nd ካዕብ (u), 3rd ሣልስ (i), 4th ራብዕ (a), 5th ኃምስ (e), 6th ሳድስ (ɨ), and 7th ሳብዕ (o)."
                ],
                [
                    'id' => 3,
                    'question' => "How do you express sincere gratitude in Amharic?",
                    'options' => ["አመሰግናለሁ (Ameseginalehu)", "ይቅርታ (Yiqirta)", "እባክህ (Ibakih)", "በጣም ጥሩ (Betam tiru)"],
                    'correct_index' => 0,
                    'explanation' => "'አመሰግናለሁ' (Ameseginalehu) means 'Thank you / I express gratitude'."
                ]
            ];
        } elseif (str_contains($tLower, 'ai') || str_contains($tLower, 'deep learning') || str_contains($tLower, 'transformer')) {
            $questions = [
                [
                    'id' => 1,
                    'question' => "What is the primary role of the Query, Key, and Value projections in the Scaled Dot-Product Attention mechanism?",
                    'options' => [
                        "To convert images to compressed audio waveforms",
                        "To compute dynamic attention affinity scores by taking the dot product of Query and Key, scaling by sqrt(d_k), and weighting the Values",
                        "To perform gradient clipping on backpropagation",
                        "To hash weights into immutable ledger strings"
                    ],
                    'correct_index' => 1,
                    'explanation' => "Attention(Q, K, V) = softmax(Q * K^T / sqrt(d_k)) * V, computing soft associations between all tokens in a sequence."
                ],
                [
                    'id' => 2,
                    'question' => "Why is positional encoding required in Transformer architectures?",
                    'options' => [
                        "Because self-attention operations are inherently permutation-invariant and do not model sequence order without explicit position vectors",
                        "To prevent model weights from exceeding 32-bit floats",
                        "To reduce the token context window size",
                        "To encrypt model outputs during inference"
                    ],
                    'correct_index' => 0,
                    'explanation' => "Since self-attention processes tokens in parallel without recurrence or convolutions, positional encodings provide the necessary sequence order signal."
                ],
                [
                    'id' => 3,
                    'question' => "In Retrieval-Augmented Generation (RAG), what is the key function of a dense vector embedding database?",
                    'options' => [
                        "Storing raw relational user passwords",
                        "Performing cosine similarity search to retrieve semantically relevant context chunks for LLM generation",
                        "Compiling frontend Vue templates into WebGL shaders",
                        "Serving video files via HTTP 206 Partial Content"
                    ],
                    'correct_index' => 1,
                    'explanation' => "Dense vector embeddings represent text semantics in high-dimensional space, allowing instant retrieval of the most relevant source passages to ground LLM prompts."
                ]
            ];
        } else {
            $questions = [
                [
                    'id' => 1,
                    'question' => "When optimizing state management in high-throughput applications, what is the primary benefit of fine-grained reactive subscriptions?",
                    'options' => [
                        "It minimizes unnecessary DOM re-renders by updating only the components that read mutated values.",
                        "It stores state directly in browser cookies for faster synchronization.",
                        "It converts client-side state into SQL queries on every user interaction.",
                        "It eliminates the need for HTTP authentication headers."
                    ],
                    'correct_index' => 0,
                    'explanation' => "Fine-grained reactivity (like Vue 3's proxy-based reactivity) tracks dependency getters and setters at the property level, ensuring that only components actually consuming the changed state are triggered to re-render."
                ],
                [
                    'id' => 2,
                    'question' => "In RESTful API design, which HTTP status code and header combination should be used to support chunked media streaming with timeline scrubbing (RFC 7233)?",
                    'options' => [
                        "200 OK with 'Cache-Control: no-cache'",
                        "206 Partial Content with 'Content-Range' and 'Accept-Ranges: bytes'",
                        "304 Not Modified with 'ETag'",
                        "416 Range Not Satisfiable without body"
                    ],
                    'correct_index' => 1,
                    'explanation' => "RFC 7233 defines HTTP 206 Partial Content with 'Content-Range: bytes START-END/TOTAL' so browsers can seek forward and backward through large video and audio files without buffering the whole file."
                ],
                [
                    'id' => 3,
                    'question' => "What cryptographic mechanism ensures that verifiable certificates cannot be forged or tampered with after issuance?",
                    'options' => [
                        "Base64 encoding of the student name",
                        "A digital signature combining issuer private key and SHA-256 hash registered on a verifiable ledger",
                        "Storing certificates exclusively as uncompressed JPEG files",
                        "Sending the certificate via standard unencrypted SMS"
                    ],
                    'correct_index' => 1,
                    'explanation' => "Verifiable credentials (following W3C and edX/Coursera specifications) use asymmetric cryptography where the issuer signs a SHA-256 digest of the credential payload, allowing instant public verification against the issuer's public key."
                ]
            ];
        }

        return response()->json([
            'success' => true,
            'topic' => $topic,
            'course' => $courseTitle,
            'generated_at' => now()->toIso8601String(),
            'questions' => $questions
        ]);
    }

    public function summarize(Request $request)
    {
        $lessonTitle = $request->input('lesson_title', 'Core Lecture Architecture');
        $courseTitle = $request->input('course_title', 'Masterclass');

        $tLower = strtolower($lessonTitle . ' ' . $courseTitle);

        if (str_contains($tLower, 'spanish')) {
            $summary = [
                'title' => "AI Smart Summary: {$lessonTitle}",
                'overview' => "Conversational Spanish immersion focusing on high-frequency vocabulary, travel dialogue, and natural flow with native speakers.",
                'key_takeaways' => [
                    "Core Distinction: 'Ser' for identity and origin vs. 'Estar' for temporary conditions and physical location.",
                    "Direct & Indirect Object Pronouns: Place pronouns before conjugated verbs or attach to the end of infinitives.",
                    "Fluency Strategy: Focus on communicative competence and pacing rather than pausing for perfect grammar."
                ],
                'flashcards' => [
                    ['front' => "¿Cómo se dice 'Where is the nearest train station'?", 'back' => "¿Dónde está la estación de tren más cercana?"],
                    ['front' => "When do you use 'por' vs. 'para'?", 'back' => "'Por' denotes cause, duration, or exchange; 'Para' denotes destination, recipient, or purpose."]
                ],
                'action_item' => "Practice the pronunciation drill in your next 25-minute 1-on-1 session with Carlos Méndez."
            ];
        } elseif (str_contains($tLower, 'amharic') || str_contains($tLower, 'አማርኛ')) {
            $summary = [
                'title' => "AI Smart Summary: {$lessonTitle}",
                'overview' => "Amharic & Ethiopian Heritage immersion covering foundational Fidel phonetic syllables, cultural etiquette, and greetings.",
                'key_takeaways' => [
                    "Pronoun Gender Sensitivity: Distinguish between masculine address (አለህ - aleh) and feminine address (አለሽ - alesh).",
                    "Cultural Respect: 'እንዴት ነዎት' (Endetenewot) shows reverence when greeting elders and instructors.",
                    "Syllabic Structure: The Ge'ez Fidel script consists of 33 core consonant bases, each with 7 distinct vowel orders."
                ],
                'flashcards' => [
                    ['front' => "How do you respond to 'እንደምን አለህ?' (How are you?)", 'back' => "'እግዚአብሔር ይመስገን፣ ደህና ነኝ' (Praise God, I am well)."],
                    ['front' => "What is the polite phrase for 'Please' in Amharic?", 'back' => "'እባክህ' (Ibakih - to male) / 'እባክሽ' (Ibakish - to female)."]
                ],
                'action_item' => "Review the Fidel vowel chart and join Selamawit Tadesse in the Live Classroom Hub."
            ];
        } else {
            $summary = [
                'title' => "AI Smart Summary: {$lessonTitle}",
                'overview' => "This session focuses on industry-grade patterns, decoupling application layers, and ensuring zero-downtime scalability.",
                'key_takeaways' => [
                    "Layered Architecture: Separation between controllers, service layers, and reactive state stores prevents tight coupling.",
                    "Performance First: Utilizing partial-content chunked streaming (HTTP 206) reduces video startup latency by up to 70%.",
                    "Cryptographic Integrity: All completion milestones are recorded with tamper-evident hashes for automated credentials.",
                ],
                'flashcards' => [
                    ['front' => "What is Partial Content (HTTP 206)?", 'back' => "An HTTP status code indicating the server is fulfilling a partial GET request for byte ranges (RFC 7233)."],
                    ['front' => "Why use Pinia stores over raw global objects?", 'back' => "Pinia provides type safety, devtools time-travel debugging, and SSR hydration resilience without global window pollution."]
                ],
                'action_item' => "Complete the interactive assessment in the next tab to cement your understanding before advancing."
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    private function generateContextualReply(string $query, ?Course $course, ?string $lessonTitle, string $contextType, ?string $codeSnippet, $user): array
    {
        $q = strtolower($query);
        $courseTitle = $course?->title ?? '';
        $cLower = strtolower($courseTitle);

        $badge = $course ? "🎯 Context: {$course->title}" : "🧠 Context: GELME Super Companion";
        if ($lessonTitle) {
            $badge .= " • {$lessonTitle}";
        }

        $sources = [];
        if ($course) {
            $sources[] = "Course Syllabus: {$course->title}";
            if ($lessonTitle) $sources[] = "Lecture Topic: {$lessonTitle}";
        }

        // 1. Language Learning Contexts (Spanish, Amharic, Mandarin, Japanese, German, French)
        if (str_contains($cLower, 'spanish') || str_contains($q, 'spanish') || str_contains($q, 'español')) {
            $reply = "### ¡Hola! Conversational Spanish Study Companion 🇪🇸

" . ($course ? "Currently reviewing **{$course->title}**" . ($lessonTitle ? " ({$lessonTitle})" : "") . ":\n\n" : "") . "
**Contextual Explanation:**
In conversational immersion, natural pacing and common collocations are key.

**Interactive Drill & Vocabulary:**
- **Greeting:** *¡Buenos días! ¿Cómo estás hoy?* (Good morning! How are you today?)
- **Asking for clarification:** *¿Podrías repetir eso un poco más despacio, por favor?*
- **Connecting ideas:** *Por lo tanto* (Therefore) / *Sin embargo* (However) / *En cuanto a...* (Regarding...)

**Grammar Insight (Ser vs. Estar):**
- *Soy de Colombia* (Origin → **Ser**)
- *Estoy estudiando en el campus* (Current state/location → **Estar**)

Would you like to roleplay a dialogue or practice ordering at a restaurant?";

            return [
                'reply' => $reply,
                'context_badge' => $badge,
                'sources' => array_merge($sources, ['CEFR B2 Immersion Guide', 'Online Global Classroom Curriculum']),
                'suggested_actions' => [
                    'Practice a dialogue about travel',
                    'Explain difference between por and para',
                    'Quiz me on Spanish irregular verbs',
                    'Book 25-min session with Carlos Méndez'
                ]
            ];
        }

        if (str_contains($cLower, 'amharic') || str_contains($q, 'amharic') || str_contains($q, 'አማርኛ') || str_contains($q, 'geez')) {
            $reply = "### እንደምን አለህ! Amharic & Ethiopian Heritage Mentor 🇪🇹

" . ($course ? "Aligned with **{$course->title}**" . ($lessonTitle ? " ({$lessonTitle})" : "") . ":\n\n" : "") . "
**Contextual Phrases & Etiquette:**
1. **ሰላም (Selam)** — Hello / Peace
2. **እንደምን አለህ? (Endemen aleh?)** — How are you? (to a male)
3. **እንደምን አለሽ? (Endemen alesh?)** — How are you? (to a female)
4. **እግዚአብሔር ይመስገን (Egziabher yimesgen)** — Praise God / I am well
5. **አመሰግናለሁ (Ameseginalehu)** — Thank you very much!
6. **መልካም ቀን (Melkam qen)** — Have a wonderful day

**Grammar & Script Tip:**
Amharic is written in the ancient **Ge'ez Fidel (ፊደል)** abugida script. Each consonant character combines with one of seven vowels to form an acoustic syllable.

Would you like to practice conversational greetings or explore the 7 vowel orders of the Ge'ez script?";

            return [
                'reply' => $reply,
                'context_badge' => $badge,
                'sources' => array_merge($sources, ['Ge\'ez Phonetic Syllabary', 'Ethiopian Heritage Language Series']),
                'suggested_actions' => [
                    'Teach me polite greetings in Amharic',
                    'Explain the 7 Ge\'ez vowel orders',
                    'Practice numbers 1 to 10 in Amharic',
                    'Book 25-min immersion with Selamawit'
                ]
            ];
        }

        // 2. Code / Technical / Full-Stack Context
        if (str_contains($q, 'vue') || str_contains($q, 'pinia') || str_contains($q, 'reactive') || str_contains($q, 'frontend') || str_contains($cLower, 'full-stack')) {
            $reply = "### Vue 3 Composition API & State Architecture ⚡

" . ($course ? "In **{$course->title}**" . ($lessonTitle ? " ({$lessonTitle})" : "") . ":\n\n" : "") . "
In production single-page applications, Vue 3's Composition API coupled with Pinia provides decoupled, testable reactivity.

```vue
<script setup>
import { ref, computed } from 'vue';
import { useCourseStore } from '@/stores/courses';

const courseStore = useCourseStore();
const activeLectureId = ref(102);

// Computed property tracks dependencies automatically
const isCompleted = computed(() => {
  return courseStore.myEnrollments.some(e => e.progress_percent >= 100);
});
</script>
```

**Architectural Best Practices:**
1. **Single Source of Truth:** Keep shared state inside Pinia stores, using component `ref()` solely for local UI concerns (e.g. modals, active tabs).
2. **Fine-Grained Reactivity:** Vue 3's Proxy-based system ensures that only the exact DOM nodes reading mutated values trigger layout passes.
3. **Clean Teardown:** Always clear global event listeners in `onUnmounted()` to prevent memory leaks in Single-Page Applications.";

            return [
                'reply' => $reply,
                'context_badge' => $badge,
                'sources' => array_merge($sources, ['Vue 3 Composition Guide', 'Pinia Architecture Patterns']),
                'suggested_actions' => [
                    'Show Pinia store setup with localStorage',
                    'Explain how to prevent memory leaks in Vue',
                    'Quiz me on Vue 3 reactivity',
                    'How does RFC 7233 partial video streaming work?'
                ]
            ];
        }

        if (str_contains($q, 'laravel') || str_contains($q, 'api') || str_contains($q, 'rest') || str_contains($q, 'sql') || str_contains($q, 'backend') || str_contains($q, 'token') || str_contains($q, 'auth')) {
            $reply = "### Laravel 11 Decoupled REST API Architecture 🛡️

" . ($course ? "Relevant to **{$course->title}**:\n\n" : "") . "
In decoupled enterprise LMS architectures, the Laravel API acts as a stateless, high-throughput microservice.

**1. Authentication with Bearer Tokens:**
Every request from the Single-Page Application passes the session token in the HTTP Authorization header:
```http
Authorization: Bearer gelme_sec_99a8b7...
```

**2. Optimizing Eloquent Queries (Prevent N+1):**
```php
// Eager-load relations across modules and lessons
$course = Course::with(['institution', 'modules.lessons', 'category'])
    ->where('slug', \$slug)
    ->firstOrFail();
```

**3. Partial Content Video Scrubbing (RFC 7233):**
The media stream endpoint inspects `Range: bytes=START-END` and streams partial chunks with HTTP 206, enabling instant video seek times without high memory overhead.";

            return [
                'reply' => $reply,
                'context_badge' => $badge,
                'sources' => array_merge($sources, ['Laravel 11 Architecture', 'RFC 7233 Specification']),
                'suggested_actions' => [
                    'Show middleware authentication example',
                    'How does chunked video streaming work in PHP?',
                    'Explain database indexing for fast curriculum lookups',
                    'Generate AI practice quiz'
                ]
            ];
        }

        if (str_contains($q, 'ai') || str_contains($q, 'transformer') || str_contains($q, 'llm') || str_contains($q, 'deep learning') || str_contains($cLower, 'ai') || str_contains($cLower, 'machine learning')) {
            $reply = "### Deep Learning & Generative AI Systems 🧠

" . ($course ? "Studying **{$course->title}**" . ($lessonTitle ? " ({$lessonTitle})" : "") . ":\n\n" : "") . "
Modern Generative AI is built upon the **Transformer architecture** (Vaswani et al.), which replaces sequential recurrence with multi-head self-attention.

**Key Mechanics of Self-Attention:**
$$\\text{Attention}(Q, K, V) = \\text{softmax}\\left(\\frac{QK^T}{\\sqrt{d_k}}\\right)V$$

- **Query (Q):** What the current token is seeking.
- **Key (K):** What other tokens offer as addressable keys.
- **Value (V):** The actual semantic content retrieved and combined.

**Practical Tip:** When building RAG (Retrieval-Augmented Generation) pipelines, chunk documents into 250–500 token windows with 10% overlap to preserve semantic context across chunk boundaries.";

            return [
                'reply' => $reply,
                'context_badge' => $badge,
                'sources' => array_merge($sources, ['Stanford CS224N', 'Attention Is All You Need (Vaswani et al.)']),
                'suggested_actions' => [
                    'Explain Scaled Dot-Product Attention in simple terms',
                    'What is temperature and top-p in LLM generation?',
                    'How does RAG semantic search work?',
                    'Quiz me on Transformers'
                ]
            ];
        }

        if (str_contains($q, 'certificate') || str_contains($q, 'verify') || str_contains($q, 'hash') || str_contains($q, 'credential')) {
            $reply = "### Cryptographic Credential Verification 🏆

GELME certificates mirror the rigorous verification standards of **edX, Coursera, and MITx**:
1. **Immutable SHA-256 Hash:** Every certificate carries an irreversible digital digest binding student identity, course curriculum, and conferring date.
2. **Zero-Trust Public Ledger:** Anyone or employer can verify authenticity at `/verify/{code}` without requiring an account.
3. **CEU Accredited:** Credits and capstone grades are cryptographically recorded on the GELME academic consortium ledger.";

            return [
                'reply' => $reply,
                'context_badge' => $badge,
                'sources' => array_merge($sources, ['GELME Credential Protocol', 'W3C Verifiable Credentials Standard']),
                'suggested_actions' => [
                    'Show sample certificate code',
                    'How does SHA-256 hashing protect against forgery?',
                    'View my verified certificates on Dashboard'
                ]
            ];
        }

        // Generic / Conversational fallback
        $reply = "### Hello " . ($user ? $user->name : "Scholar") . "! I am your GELME AI Study Companion ✨

" . ($course ? "I see you're working on **{$course->title}**" . ($lessonTitle ? " ({$lessonTitle})" : "") . ". " : "") . "
I can assist you 24/7 with:
- **Code Walkthroughs & Debugging:** Explaining Vue 3 reactivity, Laravel REST APIs, and Docker configurations.
- **Polyglot Language Coaching (14+ Languages):** Real-time conversational immersion in Spanish, Amharic, Mandarin, Japanese, and English.
- **Coursera & edX Assessment Prep:** Socratic question breakdowns, key concept flashcards, and practice quizzes.
- **Verifiable Credentials:** Verifying SHA-256 blockchain-style credentials.

What topic or question would you like to explore right now?";

        return [
            'reply' => $reply,
            'context_badge' => $badge,
            'sources' => $sources,
            'suggested_actions' => [
                'Summarize the current lesson',
                'Quiz me on key concepts',
                'Explain code architecture',
                'Practice conversational language immersion'
            ]
        ];
    }
}
