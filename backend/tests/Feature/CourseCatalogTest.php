<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserSession;

class CourseCatalogTest extends TestCase
{
    public function test_can_list_courses_with_filtering_and_search(): void
    {
        $response = $this->getJson('/api/courses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'slug', 'category', 'price', 'thumbnail_url']
                ],
                'total',
            ]);

        $searchRes = $this->getJson('/api/courses?search=Bootcamp');
        $searchRes->assertStatus(200);
    }

    public function test_can_get_single_course_details(): void
    {
        $response = $this->getJson('/api/courses/complete-full-stack-web-development-bootcamp');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'course' => [
                    'id', 'title', 'slug', 'price', 'instructor', 'modules', 'objectives'
                ]
            ]);
    }

    public function test_can_list_categories_and_institutions(): void
    {
        $catRes = $this->getJson('/api/categories');
        $catRes->assertStatus(200)->assertJsonStructure(['categories']);

        $instRes = $this->getJson('/api/institutions');
        $instRes->assertStatus(200)->assertJsonStructure(['institutions']);
    }

    public function test_authenticated_user_can_add_review(): void
    {
        $loginRes = $this->postJson('/api/auth/login', [
            'email' => 'student@gelme.global',
            'password' => 'password123',
        ]);
        $token = $loginRes->json('token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/courses/complete-full-stack-web-development-bootcamp/reviews', [
                'rating' => 5,
                'comment' => 'Outstanding course! The capstone projects and WebRTC live immersion are game changers.',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'review' => ['id', 'author', 'rating', 'comment']
            ]);
    }
}
