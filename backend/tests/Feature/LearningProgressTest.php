<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Course;
use App\Models\Lesson;

class LearningProgressTest extends TestCase
{
    protected function getAuthToken(): string
    {
        $loginRes = $this->postJson('/api/auth/login', [
            'email' => 'student@gelme.global',
            'password' => 'password123',
        ]);
        return $loginRes->json('token');
    }

    public function test_student_can_enroll_and_view_my_learning(): void
    {
        $token = $this->getAuthToken();
        $course = Course::first();

        $enrollRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/courses/{$course->id}/enroll");

        $enrollRes->assertStatus(200)
            ->assertJsonStructure(['message', 'enrollment']);

        $myLearningRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/my-learning');

        $myLearningRes->assertStatus(200)
            ->assertJsonStructure(['enrollments']);
    }

    public function test_student_can_complete_lesson_and_track_progress(): void
    {
        $token = $this->getAuthToken();
        $lesson = Lesson::first();

        $res = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/lessons/{$lesson->id}/complete");

        $res->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'progress_percent',
                'completed_lessons',
            ]);
    }

    public function test_student_can_apply_for_financial_aid(): void
    {
        $token = $this->getAuthToken();
        $course = Course::first();

        $res = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/financial-aid', [
                'course_id' => $course->id,
                'education_level' => 'Bachelor of Science',
                'annual_income' => 12000,
                'statement' => 'I am dedicating 20 hours a week to master full-stack and generative AI systems.',
            ]);

        $res->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_student_can_submit_capstone_assignment(): void
    {
        $token = $this->getAuthToken();
        $course = Course::first();

        $res = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/assignments/submit', [
                'course_id' => $course->id,
                'repo_url' => 'https://github.com/gelme/production-capstone',
                'reflection' => 'Successfully decoupled Vue 3 Pinia state with Laravel 11 Eloquent models.',
            ]);

        $res->assertStatus(200)
            ->assertJson([
                'success' => true,
                'passed' => true,
            ]);
    }

    public function test_unauthenticated_request_to_my_learning_returns_401(): void
    {
        $res = $this->getJson('/api/my-learning');
        $res->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated. Valid Bearer token required.',
            ]);
    }
}
