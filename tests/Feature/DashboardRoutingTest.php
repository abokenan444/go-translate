<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class DashboardRoutingTest extends TestCase
{
    private function makeUser(string $accountType): User
    {
        $user = new User();
        $user->forceFill([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'account_type' => $accountType,
        ]);
        $user->exists = true;

        return $user;
    }

    /** @test */
    public function guest_is_redirected_to_login_when_visiting_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function dashboard_redirects_customer_to_customer_dashboard()
    {
        $user = $this->makeUser('customer');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/dashboard/customer');
    }

    /** @test */
    public function dashboard_redirects_government_to_government_dashboard()
    {
        $user = $this->makeUser('government');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/dashboard/government');
    }

    /** @test */
    public function dashboard_redirects_translator_to_translator_dashboard()
    {
        $user = $this->makeUser('translator');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/dashboard/translator');
    }

    /** @test */
    public function dashboard_redirects_affiliate_to_affiliate_dashboard()
    {
        $user = $this->makeUser('affiliate');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/dashboard/affiliate');
    }

    /** @test */
    public function dashboard_redirects_partner_to_partner_dashboard()
    {
        $user = $this->makeUser('partner');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/partner/dashboard');
    }

    /** @test */
    public function non_government_user_cannot_access_government_dashboard()
    {
        $user = $this->makeUser('customer');

        $response = $this->actingAs($user)->get('/dashboard/government');

        $response->assertStatus(403);
    }

    /** @test */
    public function non_partner_user_cannot_access_partner_dashboard()
    {
        $user = $this->makeUser('customer');

        $response = $this->actingAs($user)->get('/partner/dashboard');

        $response->assertStatus(403);
    }
}
