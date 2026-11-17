<?php

namespace Tests\Feature;

use Tests\TestCase;

class NotificationTest extends TestCase
{
    protected function getAuthToken(): string
    {
        $loginRes = $this->postJson('/api/auth/login', [
            'email' => 'student@gelme.global',
            'password' => 'password123',
        ]);
        return $loginRes->json('token');
    }

    public function test_can_list_and_manage_notifications(): void
    {
        $token = $this->getAuthToken();

        $listRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/notifications');

        $listRes->assertStatus(200)
            ->assertJsonStructure(['unread_count', 'data']);

        $notifications = $listRes->json('data');
        if (!empty($notifications)) {
            $firstId = $notifications[0]['id'];

            $readRes = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->patchJson("/api/notifications/{$firstId}/read");

            $readRes->assertStatus(200)
                ->assertJsonStructure(['success', 'unread_count']);
        }

        $allReadRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/notifications/mark-all-read');

        $allReadRes->assertStatus(200)
            ->assertJson(['success' => true, 'unread_count' => 0]);
    }
}
