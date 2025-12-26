<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GovernmentApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function government_can_verify_document_with_valid_credentials()
    {
        $document = Document::factory()->create([
            'reference_number' => 'DOC-2024-001',
            'status' => 'completed',
        ]);

        $response = $this->postJson('/api/government/verify-document', [
            'document_id' => 'DOC-2024-001',
            'government_id' => 'GOV-UAE-001',
            'api_key' => 'test_api_key',
            'verification_data' => [
                'verified' => true,
                'officer_id' => '12345',
                'department' => 'Ministry of Foreign Affairs',
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 'verified',
            ]);

        $this->assertDatabaseHas('government_verifications', [
            'document_id' => $document->id,
            'government_id' => 'GOV-UAE-001',
            'verification_status' => 'verified',
        ]);
    }

    /** @test */
    public function government_cannot_verify_document_without_api_key()
    {
        $response = $this->postJson('/api/government/verify-document', [
            'document_id' => 'DOC-2024-001',
            'government_id' => 'GOV-UAE-001',
            'verification_data' => [],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['api_key']);
    }

    /** @test */
    public function government_can_get_document_status()
    {
        $document = Document::factory()->create([
            'reference_number' => 'DOC-2024-002',
            'status' => 'in_progress',
        ]);

        $response = $this->getJson('/api/government/document/DOC-2024-002/status', [
            'X-API-Key' => 'test_api_key',
            'X-Government-ID' => 'GOV-UAE-001',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'document_id' => 'DOC-2024-002',
                'status' => 'in_progress',
            ]);
    }

    /** @test */
    public function government_can_get_verification_stats()
    {
        $response = $this->getJson('/api/government/stats', [
            'X-API-Key' => 'test_api_key',
            'X-Government-ID' => 'GOV-UAE-001',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'stats' => [
                    'total_verifications',
                    'verified_today',
                    'verified_this_month',
                    'pending_verifications',
                ],
            ]);
    }

    /** @test */
    public function government_api_rate_limit_is_enforced()
    {
        // This test would require mocking the rate limiter
        $this->markTestSkipped('Rate limiting test requires additional setup');
    }
}
