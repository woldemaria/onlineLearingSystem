<?php

namespace Tests\Feature;

use Tests\TestCase;

class TestimonialsTest extends TestCase
{
    protected function getAuthToken(): string
    {
        $loginRes = $this->postJson('/api/auth/login', [
            'email' => 'student@gelme.global',
            'password' => 'password123',
        ]);
        return $loginRes->json('token');
    }

    public function test_can_fetch_testimonials_and_metrics(): void
    {
        $response = $this->getJson('/api/testimonials');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'testimonials',
                'metrics' => [
                    'average_rating',
                    'total_reviews',
                    'career_benefit_percent',
                    'countries_count',
                    'satisfaction_rate',
                    'live_users'
                ]
            ]);

        $this->assertNotEmpty($response->json('testimonials'));
    }

    public function test_can_fetch_live_users_count(): void
    {
        $response = $this->getJson('/api/live-users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'count',
                'formatted',
                'active_classrooms',
                'active_countries'
            ]);

        $this->assertGreaterThan(1000, $response->json('count'));
    }

    public function test_unauthenticated_user_cannot_submit_testimonial(): void
    {
        $payload = [
            'name' => 'Anonymous',
            'content' => 'Trying to submit without being authenticated.',
            'rating' => 5
        ];

        $response = $this->postJson('/api/testimonials', $payload);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_submit_testimonial(): void
    {
        $token = $this->getAuthToken();
        $payload = [
            'name' => 'Kassahun Belay',
            'role' => 'Data Science Engineer',
            'company' => 'Addis Tech Hub',
            'category' => 'AI & Deep Learning',
            'course_title' => 'AI For Everyone & Deep Learning Specialization',
            'rating' => 5,
            'highlight' => 'The capstone project and AI tutor helped me land a job in 2 months!',
            'content' => 'GELME provides the most structured and comprehensive curriculum for distributed AI systems. The live classroom immersion combined with verifiable certificates was invaluable.',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/testimonials', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'message',
                'testimonial' => [
                    'id',
                    'name',
                    'role',
                    'rating',
                    'highlight',
                    'content',
                ]
            ]);
    }

    public function test_can_vote_helpful_on_testimonial(): void
    {
        $response = $this->postJson('/api/testimonials/1/helpful');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }
}
