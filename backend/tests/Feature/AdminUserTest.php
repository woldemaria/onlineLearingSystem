<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AdminUserTest extends TestCase
{
    protected function getAdminToken(): string
    {
        $loginRes = $this->postJson('/api/auth/login', [
            'email' => 'admin@gelme.global',
            'password' => 'password123',
        ]);
        return $loginRes->json('token');
    }

    public function test_admin_can_access_stats_and_users(): void
    {
        $token = $this->getAdminToken();

        $statsRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/admin/stats');

        $statsRes->assertStatus(200)
            ->assertJsonStructure([
                'total_users',
                'total_students',
                'total_instructors',
                'active_users',
            ]);

        $usersRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/admin/users');

        $usersRes->assertStatus(200)
            ->assertJsonStructure(['users']);
    }

    public function test_admin_can_update_user_role_and_status(): void
    {
        $token = $this->getAdminToken();
        $targetUser = User::where('email', 'not like', '%admin%')->first();

        // Update role
        $roleRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson("/api/admin/users/{$targetUser->id}/role", [
                'role' => 'instructor',
            ]);

        $roleRes->assertStatus(200)
            ->assertJson(['success' => true]);

        // Toggle status
        $statusRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson("/api/admin/users/{$targetUser->id}/status", [
                'is_active' => true,
            ]);

        $statusRes->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_admin_can_export_users_csv_without_errors(): void
    {
        $token = $this->getAdminToken();

        $exportRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->get('/api/admin/users/export?role=student');

        $exportRes->assertStatus(200);
        $this->assertStringContainsString('text/csv', $exportRes->headers->get('Content-Type'));
    }
}
