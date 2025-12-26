<?php

namespace Tests\Feature\Government;

use App\Models\User;
use App\Models\Certificate;
use App\Models\CertificateRevocationRequest;
use App\Models\DecisionLedgerEvent;
use App\Models\GovEntity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoManRuleTest extends TestCase
{
    use RefreshDatabase;

    protected $entity;
    protected $officer;
    protected $supervisor;
    protected $certificate;

    protected function setUp(): void
    {
        parent::setUp();

        // Create government entity
        $this->entity = GovEntity::factory()->create([
            'name_en' => 'Test Authority',
            'entity_type' => 'authority',
            'status' => 'active',
        ]);

        // Create authority officer
        $this->officer = User::factory()->create([
            'account_type' => 'gov_authority_officer',
            'is_government_verified' => true,
        ]);

        // Create authority supervisor
        $this->supervisor = User::factory()->create([
            'account_type' => 'gov_authority_supervisor',
            'is_government_verified' => true,
        ]);

        // Create test certificate
        $this->certificate = Certificate::factory()->create([
            'status' => 'valid',
            'legal_status' => 'valid',
        ]);
    }

    /** @test */
    public function officer_can_request_revocation()
    {
        $this->actingAs($this->officer);

        $response = $this->post(route('authority.certificate.request-revoke', $this->certificate->id), [
            'reason' => 'Fraudulent document',
            'legal_reference' => 'Article 123',
            'jurisdiction_country' => 'US',
            'jurisdiction_purpose' => 'court',
            'legal_basis_code' => 'FRAUD',
        ]);

        $response->assertStatus(302); // Redirect on success

        $this->assertDatabaseHas('certificate_revocation_requests', [
            'certificate_id' => $this->certificate->id,
            'requested_by' => $this->officer->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function officer_cannot_approve_own_request()
    {
        // Officer creates request
        $request = CertificateRevocationRequest::create([
            'certificate_id' => $this->certificate->id,
            'requested_by' => $this->officer->id,
            'reason' => 'Fraudulent document',
            'legal_reference' => 'Article 123',
            'jurisdiction_country' => 'US',
            'jurisdiction_purpose' => 'court',
            'legal_basis_code' => 'FRAUD',
            'authority_entity_id' => $this->entity->id,
            'status' => 'pending',
        ]);

        // Officer tries to approve their own request
        $canApprove = $request->canBeApprovedBy($this->officer);

        $this->assertFalse($canApprove, 'Officer should NOT be able to approve own request');
    }

    /** @test */
    public function supervisor_can_approve_request_from_different_user()
    {
        // Officer creates request
        $request = CertificateRevocationRequest::create([
            'certificate_id' => $this->certificate->id,
            'requested_by' => $this->officer->id,
            'reason' => 'Fraudulent document',
            'legal_reference' => 'Article 123',
            'jurisdiction_country' => 'US',
            'jurisdiction_purpose' => 'court',
            'legal_basis_code' => 'FRAUD',
            'authority_entity_id' => $this->entity->id,
            'status' => 'pending',
        ]);

        // Supervisor approves
        $canApprove = $request->canBeApprovedBy($this->supervisor);

        $this->assertTrue($canApprove, 'Supervisor should be able to approve request from officer');
    }

    /** @test */
    public function approved_request_creates_revocation_and_ledger_events()
    {
        // Create pending request
        $request = CertificateRevocationRequest::create([
            'certificate_id' => $this->certificate->id,
            'requested_by' => $this->officer->id,
            'reason' => 'Fraudulent document',
            'legal_reference' => 'Article 123',
            'jurisdiction_country' => 'US',
            'jurisdiction_purpose' => 'court',
            'legal_basis_code' => 'FRAUD',
            'authority_entity_id' => $this->entity->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->supervisor);

        $response = $this->post(route('authority.revocation-request.approve', $request->id));

        $response->assertStatus(302);

        // Check certificate is revoked
        $this->assertDatabaseHas('certificates', [
            'id' => $this->certificate->id,
            'legal_status' => 'revoked',
        ]);

        // Check revocation record created
        $this->assertDatabaseHas('certificate_revocations', [
            'certificate_id' => $this->certificate->id,
            'reason' => 'Fraudulent document',
        ]);

        // Check ledger has two events: request + approval
        $ledgerEvents = DecisionLedgerEvent::where('certificate_id', $this->certificate->id)
            ->whereIn('event_type', ['revocation_requested', 'revocation_approved'])
            ->count();

        $this->assertEquals(2, $ledgerEvents, 'Should have 2 ledger events: request and approval');
    }

    /** @test */
    public function revocation_receipt_pdf_is_generated()
    {
        $request = CertificateRevocationRequest::create([
            'certificate_id' => $this->certificate->id,
            'requested_by' => $this->officer->id,
            'reason' => 'Fraudulent document',
            'legal_reference' => 'Article 123',
            'jurisdiction_country' => 'US',
            'jurisdiction_purpose' => 'court',
            'legal_basis_code' => 'FRAUD',
            'authority_entity_id' => $this->entity->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->supervisor);

        $this->post(route('authority.revocation-request.approve', $request->id));

        // Check receipt_path is set
        $this->assertDatabaseMissing('certificate_revocations', [
            'certificate_id' => $this->certificate->id,
            'receipt_path' => null,
        ]);
    }
}
