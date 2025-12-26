<?php

namespace Tests\Feature\Api;

use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerRegistryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_public_partner_registry()
    {
        Partner::factory()->count(5)->create([
            'status' => 'active',
            'is_public' => true,
        ]);

        $response = $this->getJson('/api/partners/registry');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'certification_level',
                        'overall_rating',
                    ]
                ],
                'pagination',
            ]);
    }

    public function test_can_filter_by_certification()
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

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertCount(1, $data);
        $this->assertEquals('gold', $data[0]['certification_level']);
    }

    public function test_can_get_certified_partners_only()
    {
        Partner::factory()->create([
            'status' => 'active',
            'is_public' => true,
            'certification_level' => 'gold',
        ]);

        Partner::factory()->create([
            'status' => 'active',
            'is_public' => true,
            'certification_level' => null,
        ]);

        $response = $this->getJson('/api/partners/certified');

        $response->assertStatus(200);
        $data = $response->json('data');
        
        foreach ($data as $partner) {
            $this->assertNotNull($partner['certification_level']);
        }
    }

    public function test_cannot_see_private_partners()
    {
        Partner::factory()->create([
            'status' => 'active',
            'is_public' => false,
            'name' => 'Private Partner',
        ]);

        $response = $this->getJson('/api/partners/registry');

        $response->assertStatus(200);
        $data = $response->json('data');
        
        foreach ($data as $partner) {
            $this->assertNotEquals('Private Partner', $partner['name']);
        }
    }
}
