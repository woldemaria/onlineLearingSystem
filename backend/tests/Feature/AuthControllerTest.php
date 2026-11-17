<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Support\Str;

class AuthControllerTest extends TestCase
{
    public function test_user_can_register_as_student(): void
    {
        $email = 'test_student_' . Str::random(6) . '@gelme.global';
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test Student',
            'email' => $email,
            'password' => 'password123',
            'role' => 'student',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'role'],
                'token',
            ]);

        $this->assertEquals('student', $response->json('user.role'));
    }

    public function test_user_can_register_as_instructor(): void
    {
        $email = 'test_inst_' . Str::random(6) . '@gelme.global';
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test Instructor',
            'email' => $email,
            'password' => 'password123',
            'role' => 'instructor',
        ]);

        $response->assertStatus(201);
        $this->assertEquals('instructor', $response->json('user.role'));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'student@gelme.global',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'role'],
                'token',
            ]);
    }

    public function test_authenticated_user_can_fetch_profile_and_logout(): void
    {
        $loginRes = $this->postJson('/api/auth/login', [
            'email' => 'student@gelme.global',
            'password' => 'password123',
        ]);

        $token = $loginRes->json('token');

        $meRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/me');

        $meRes->assertStatus(200)
            ->assertJsonPath('user.email', 'student@gelme.global');

        $logoutRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout');

        $logoutRes->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out.']);
    }
}
