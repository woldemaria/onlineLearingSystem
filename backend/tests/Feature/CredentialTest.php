<?php

namespace Tests\Feature;

use Tests\TestCase;

class CredentialTest extends TestCase
{
    public function test_can_verify_credential_by_code(): void
    {
        $response = $this->getJson('/api/certificates/GELME-CERT-2026-X892K');

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
            ])
            ->assertJsonStructure([
                'certificate' => [
                    'verification_code',
                    'title',
                    'recipient_name',
                    'issuer_name',
                    'grade',
                    'blockchain_hash',
                ]
            ]);
    }

    public function test_invalid_credential_returns_404(): void
    {
        $response = $this->getJson('/api/certificates/NON_EXISTENT_CODE_9999');

        $response->assertStatus(404)
            ->assertJson([
                'valid' => false,
            ]);
    }
}
