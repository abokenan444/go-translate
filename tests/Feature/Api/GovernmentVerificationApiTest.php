<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GovernmentVerificationApiTest extends TestCase
{
    use RefreshDatabase;

    protected $governmentApiKey = 'test_gov_key_123';
    protected $governmentId = 'GOV_SA_001';

    public function test_can_verify_document_with_valid_credentials()
    {
        $document = Document::factory()->create([
            'reference_number' => 'DOC-2024-001',
            'status' => 'completed',
        ]);

        $response = $this->postJson('/api/government/verify-document', [
            'document_id' => 'DOC-2024-001',
            'government_id' => $this->governmentId,
            'api_key' => $this->governmentApiKey,
            'verification_data' => [
                'verified_by' => 'Ministry of Justice',
                'verification_method' => 'digital_signature',
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 'verified',
            ]);

        $this->assertDatabaseHas('government_verifications', [
            'document_id' => $document->id,
            'government_id' => $this->governmentId,
            'verification_status' => 'verified',
        ]);
    }

    public function test_cannot_verify_without_api_key()
    {
        $response = $this->postJson('/api/government/verify-document', [
            'document_id' => 'DOC-2024-001',
            'government_id' => $this->governmentId,
            'verification_data' => [],
        ]);

        $response->assertStatus(422);
    }

    public function test_can_get_document_status()
    {
        $document = Document::factory()->create([
            'reference_number' => 'DOC-2024-002',
            'status' => 'in_progress',
        ]);

        $response = $this->getJson('/api/government/document/DOC-2024-002/status', [
            'X-API-Key' => $this->governmentApiKey,
            'X-Government-ID' => $this->governmentId,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'document_id' => 'DOC-2024-002',
                'status' => 'in_progress',
            ]);
    }

    public function test_rate_limiting_works()
    {
        $document = Document::factory()->create([
            'reference_number' => 'DOC-2024-003',
        ]);

        // Make multiple requests to trigger rate limit
        for ($i = 0; $i < 1005; $i++) {
            $response = $this->getJson('/api/government/document/DOC-2024-003/status', [
                'X-API-Key' => $this->governmentApiKey,
                'X-Government-ID' => $this->governmentId,
            ]);

            if ($i >= 1000) {
                $response->assertStatus(429);
                break;
            }
        }
    }
}
