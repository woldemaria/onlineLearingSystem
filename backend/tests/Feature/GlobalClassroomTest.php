<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\VirtualClassroom;

class GlobalClassroomTest extends TestCase
{
    protected function getAuthToken(): string
    {
        $loginRes = $this->postJson('/api/auth/login', [
            'email' => 'student@gelme.global',
            'password' => 'password123',
        ]);
        return $loginRes->json('token');
    }

    public function test_can_list_live_global_classrooms(): void
    {
        $response = $this->getJson('/api/classrooms');

        $response->assertStatus(200)
            ->assertJsonStructure(['classrooms']);
    }

    public function test_can_join_and_leave_classroom(): void
    {
        $token = $this->getAuthToken();
        $room = VirtualClassroom::first();

        $joinRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/classrooms/{$room->slug}/join");

        $joinRes->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'session_id',
                'roster',
                'attendance',
            ]);

        $attendRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/classrooms/{$room->slug}/attendance");

        $attendRes->assertStatus(200)
            ->assertJsonStructure(['session', 'records']);

        $leaveRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/classrooms/{$room->slug}/leave");

        $leaveRes->assertStatus(200)
            ->assertJsonStructure(['message', 'minutes_present']);
    }

    public function test_can_chat_and_toggle_media(): void
    {
        $token = $this->getAuthToken();
        $room = VirtualClassroom::first();

        $chatRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/classrooms/{$room->slug}/chat", [
                'text' => 'Hola profesor, ¿podemos practicar conversación cotidiana?',
            ]);

        $chatRes->assertStatus(200)
            ->assertJsonStructure(['message']);

        // Test fetching chat messages
        $getChatRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/classrooms/{$room->slug}/chat");
        $getChatRes->assertStatus(200)
            ->assertJsonStructure(['messages']);

        // Test sending reaction
        $reactRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/classrooms/{$room->slug}/reactions", [
                'emoji' => '🔥',
            ]);
        $reactRes->assertStatus(200)
            ->assertJsonStructure(['message', 'reaction']);

        // Test fetching reactions
        $getReactRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/classrooms/{$room->slug}/reactions");
        $getReactRes->assertStatus(200)
            ->assertJsonStructure(['reactions']);

        $handRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/classrooms/{$room->slug}/hand-raise");

        $handRes->assertStatus(200)
            ->assertJsonStructure(['hand_raised']);
    }

    public function test_can_book_immersion_and_take_placement_test(): void
    {
        $room = VirtualClassroom::first();

        $bookRes = $this->postJson('/api/classrooms/book', [
            'classroom_id' => $room->id,
            'preferred_time' => 'Today at 03:00 PM UTC',
            'format' => '25-Min Native Immersion',
        ]);

        $bookRes->assertStatus(200)
            ->assertJsonStructure(['message', 'join_url']);

        $testRes = $this->postJson('/api/placement-test', [
            'answers' => ['Puedo comunicarme con fluidez', 'yes', 'yes'],
            'language' => 'Spanish',
        ]);

        $testRes->assertStatus(200)
            ->assertJsonStructure(['cefr_level', 'recommended_teacher']);
    }
}
