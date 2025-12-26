<?php

namespace Tests\Feature\Api;

use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerRegistryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_public_partner_registry()
    {
        Partner::factory()->count(5)->create([
            'status' => 'active',
            'is_public' => true,
        ]);

        $response = $this->getJson('/api/partners/registry');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'certification_level',
                        'overall_rating',
                    ],
                ],
                'pagination',
            ]);
    }

    /** @test */
    public function can_filter_partners_by_certification()
    {
        Partner::factory()->create([
            'status' => 'active',
            'is_public' => true,
            'certification_level' => 'gold',
        ]);

        Partner::factory()->create([
            'status' => 'active',
            'is_public' => true,
            'certification_level' => 'silver',
        ]);

        $response = $this->getJson('/api/partners/registry?certification=gold');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('gold', $data[0]['certification_level']);
    }

    /** @test */
    public function can_get_specific_partner_details()
    {
        $partner = Partner::factory()->create([
            'status' => 'active',
            'is_public' => true,
        ]);

        $response = $this->getJson("/api/partners/registry/{$partner->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $partner->id,
                    'name' => $partner->name,
                ],
            ]);
    }

    /** @test */
    public function cannot_get_private_partner_details()
    {
        $partner = Partner::factory()->create([
            'status' => 'active',
            'is_public' => false,
        ]);

        $response = $this->getJson("/api/partners/registry/{$partner->id}");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Partner not found',
            ]);
    }

    /** @test */
    public function can_get_certified_partners_only()
    {
        Partner::factory()->count(3)->create([
            'status' => 'active',
            'is_public' => true,
            'certification_level' => 'gold',
        ]);

        Partner::factory()->count(2)->create([
            'status' => 'active',
            'is_public' => true,
            'certification_level' => null,
        ]);

        $response = $this->getJson('/api/partners/certified');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertCount(3, $data);

        foreach ($data as $partner) {
            $this->assertNotNull($partner['certification_level']);
        }
    }
}
