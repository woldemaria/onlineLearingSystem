<?php

namespace Tests\Feature;

use Tests\TestCase;

class AiTutorTest extends TestCase
{
    public function test_ai_tutor_answers_query(): void
    {
        $response = $this->postJson('/api/ai/tutor', [
            'message' => 'Explain Vue 3 reactivity and Pinia stores.',
            'context' => 'general',
            'course_slug' => 'complete-full-stack-web-development-bootcamp',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'session_id',
                'reply',
                'suggested_actions',
            ]);
    }

    public function test_ai_tutor_generates_quiz_and_summary(): void
    {
        $quizRes = $this->postJson('/api/ai/quiz-generate', [
            'topic' => 'Full-Stack Architecture',
            'course_title' => 'Complete Full-Stack Bootcamp',
        ]);

        $quizRes->assertStatus(200)
            ->assertJsonStructure(['success', 'questions']);

        $sumRes = $this->postJson('/api/ai/summarize', [
            'lesson_title' => 'WebRTC and Jitsi Meeting Integration',
            'course_title' => 'Online Global Classroom',
        ]);

        $sumRes->assertStatus(200)
            ->assertJsonStructure(['success', 'data' => ['overview', 'key_takeaways']]);
    }
}
