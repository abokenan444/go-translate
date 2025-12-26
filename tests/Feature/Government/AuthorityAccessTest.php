<?php

namespace Tests\Feature\Government;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorityAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authority_middleware_blocks_non_authority_users()
    {
        $user = User::factory()->create([
            'account_type' => 'individual',
            'is_government_verified' => false,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('authority.dashboard'));

        $response->assertStatus(403);
    }

    /** @test */
    public function authority_middleware_blocks_unverified_government_users()
    {
        $user = User::factory()->create([
            'account_type' => 'gov_authority_officer',
            'is_government_verified' => false, // Not verified
        ]);

        $this->actingAs($user);

        $response = $this->get(route('authority.dashboard'));

        $response->assertStatus(403);
    }

    /** @test */
    public function authority_middleware_allows_verified_authority_officer()
    {
        $user = User::factory()->create([
            'account_type' => 'gov_authority_officer',
            'is_government_verified' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('authority.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function authority_middleware_allows_verified_authority_supervisor()
    {
        $user = User::factory()->create([
            'account_type' => 'gov_authority_supervisor',
            'is_government_verified' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('authority.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function government_client_cannot_access_authority_console()
    {
        $user = User::factory()->create([
            'account_type' => 'gov_client_operator',
            'is_government_verified' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('authority.dashboard'));

        $response->assertStatus(403);
    }
}
