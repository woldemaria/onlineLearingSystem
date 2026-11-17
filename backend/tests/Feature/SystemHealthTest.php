<?php

namespace Tests\Feature;

use Tests\TestCase;

class SystemHealthTest extends TestCase
{
    public function test_health_check_returns_healthy_status(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'healthy',
                'service' => 'GELME Super Platform API',
                'database' => 'connected',
            ]);
    }
}
