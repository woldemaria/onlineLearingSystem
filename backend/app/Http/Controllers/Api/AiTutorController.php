<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiTutorController extends Controller
{
    public function ask(Request $request)
    {
        $query = $request->input('message', '');
        $context = $request->input('context', 'general');

        $q = strtolower($query);

        if (str_contains($q, 'vue') || str_contains($q, 'react') || str_contains($q, 'frontend') || str_contains($q, 'pinia')) {
            $reply = "### Vue 3 Composition API & Reactive State

In modern Vue 3, `<script setup>` provides the most concise, performant DX. Reactive values use `ref()` for primitives and `reactive()` for objects.

```vue
<script setup>
import { ref, computed } from 'vue';
import { useCourseStore } from '@/stores/courses';

const currentLesson = ref(1);
const progress = ref(85);
const isComplete = computed(() => progress.value >= 100);
</script>
```

**Key Tip:** In Pinia stores, keep actions asynchronous with `try/catch` blocks and expose only what the view requires.";

        } elseif (str_contains($q, 'laravel') || str_contains($q, 'backend') || str_contains($q, 'sql') || str_contains($q, 'api')) {
            $reply = "### Laravel 11 Scalable REST API Architecture

Laravel 11 streamlines bootstrapping in `bootstrap/app.php` and eliminates boilerplate kernel files. For enterprise performance across large schemas (like our 75 relational tables):

1. **Eager Loading:** Always prevent N+1 queries with `with()`:
```php
$courses = Course::with(['category', 'institution', 'modules.lessons'])->get();
```
2. **Resource Transformations:** Use dedicated API resources or custom map projections.
3. **Foreign Key Indexing:** Ensure composite indexes on `['course_id', 'order']` for instantaneous curriculum traversal.";

        } elseif (str_contains($q, 'spanish') || str_contains($q, 'español')) {
            $reply = "¡Hola! En **Online Global Classroom**, practicamos inmersión conversacional de 25 minutos con hablantes nativos.

**Useful Conversational Phrases:**
- *¿Podría explicarme esto con un ejemplo practical?* (Could you explain this with a practical example?)
- *¿Cuál es la diferencia entre 'por' y 'para'?*
- *Estoy emocionado por participar en la sesión de hoy.* (I'm excited to join today's session.)

Would you like to book a 25-minute live immersion with Carlos Méndez right now?";

        } elseif (str_contains($q, 'amharic') || str_contains($q, 'አማርኛ') || str_contains($q, 'geez')) {
            $reply = "እንደምን አለህ! Welcome to the **Online Global Classroom Amharic & Ge'ez Cultural Immersion**.

**Essential Everyday Amharic Phrases:**
1. **ሰላም (Selam)** — Hello / Peace
2. **እንደምን አለህ? (Endemen aleh?)** — How are you? (to a male) / **እንደምን አለሽ? (Endemen alesh?)** (to a female)
3. **እግዚአብሔር ይመስገን (Egziabher yimesgen)** — Praise God / I am well
4. **አመሰግናለሁ (Ameseginalehu)** — Thank you!
5. **መልካም ቀን (Melkam qen)** — Have a wonderful day

You can book a 25-minute native immersion class with Selamawit Tadesse in the Live Classroom Hub!";

        } elseif (str_contains($q, 'mandarin') || str_contains($q, 'chinese') || str_contains($q, '中文')) {
            $reply = "你好! Welcome to **Online Global Classroom Mandarin Chinese Immersion (普通话)** with Mei-Ling Zhou.

**Daily Survival & Business Greetings:**
- **你好 (Nǐ hǎo)** — Hello
- **很高兴认识你 (Hěn gāoxìng rènshí nǐ)** — Nice to meet you
- **请问，这个怎么读？ (Qǐngwèn, zhège zěnme dú?)** — Excuse me, how do you pronounce this?
- **合作愉快 (Hézuò yúkuài)** — A pleasure doing business with you";

        } elseif (str_contains($q, 'japanese') || str_contains($q, '日本語')) {
            $reply = "こんにちは! Welcome to **Online Global Classroom Japanese Immersion (日本語)** with Kenji Sato.

**Key Everyday Expressions:**
- **はじめまして、よろしくお願いします (Hajimemashite, yoroshiku onegaishimasu)** — Nice to meet you, please treat me well.
- **もう一度お願いします (Mō ichido onegaishimasu)** — Could you repeat that once more, please?
- **なるほど、わかりました (Naruhodo, wakarimashita)** — I see, I understand now!";

        } elseif (str_contains($q, 'certificate') || str_contains($q, 'verify') || str_contains($q, 'edx') || str_contains($q, 'coursera')) {
            $reply = "GELME verified credentials mirror the cryptographic rigor of **edX and Coursera**:
- Every credential is generated upon 100% course completion or passing a proctored capstone.
- Embedded with an immutable **SHA-256 tamper-proof hash** registered on the GELME ledger.
- Anyone or prospective employer can verify instantly at `/verify/{code}` without requiring an account. Test with: `GELME-CERT-2026-X892K` or `STANFORD-AI-2026-9901`!";

        } elseif (str_contains($q, 'quiz') || str_contains($q, 'test')) {
            $reply = "Here is a quick practice quiz question for you:

**Question:** Which design pattern is most effective for handling global authentication state across decoupled Single-Page Applications?
A) Storing tokens in global window variables
B) Modular Pinia/Vuex store backed by reactive localStorage with HTTP Bearer interceptors
C) Reloading the entire DOM on every route change
D) Query string URL parameter propagation

*(Hint: Think about state reactivity, security, and persistence across page reloads!)*";

        } else {
            $reply = "Hello! I am your **GELME AI Study Companion & Polyglot Tutor**.

I can assist you 24/7 with:
1. **Full-Stack & Cloud Architecture**: Explaining Vue 3 reactivity, Laravel REST APIs, Docker, and PostgreSQL.
2. **Native Language Immersion (14+ Languages)**: Conversational drills in Spanish, Amharic, Mandarin, Japanese, German, and English.
3. **Exam & Quiz Preparation**: Coursera-style graded practice questions with instant rationales.
4. **Verifiable Credentials**: Cryptographic blockchain SHA-256 certificate validation.

What would you like to explore or practice right now?";
        }

        return response()->json([
            'reply' => $reply,
            'suggested_actions' => [
                'Give me a code example',
                'Quiz me on this module',
                'Practice Spanish phrases',
                'Practice Amharic greetings',
                'How does certificate verification work?'
            ]
        ]);
    }

    public function generateQuiz(Request $request)
    {
        $topic = $request->input('topic', 'General Web & Software Engineering');
        $courseTitle = $request->input('course_title', 'Software Architecture');

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

        $summary = [
            'title' => "AI Smart Summary: {$lessonTitle}",
            'overview' => "This session focuses on industry-grade patterns, decoupling application layers, and ensuring zero-downtime scalability.",
            'key_takeaways' => [
                "Layered Architecture: Separation between controllers, service layers, and reactive state stores prevents tight coupling.",
                "Performance First: Utilizing partial-content chunked streaming (HTTP 206) reduces video startup latency by up to 70%.",
                "Cryptographic Integrity: All completion milestones are recorded with tamper-evident hashes for automated credentials.",
            ],
            'flashcards' => [
                [
                    'front' => "What is Partial Content (HTTP 206)?",
                    'back' => "An HTTP status code indicating the server is fulfilling a partial GET request for byte ranges (RFC 7233)."
                ],
                [
                    'front' => "Why use Pinia stores over raw global objects?",
                    'back' => "Pinia provides type safety, devtools time-travel debugging, and SSR hydration resilience without global window pollution."
                ]
            ],
            'action_item' => "Complete the interactive assessment in the next tab to cement your understanding before advancing."
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
}
