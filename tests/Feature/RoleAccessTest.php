<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verify that super_admin role and demo user do not exist in the database after seeding.
     */
    public function test_super_admin_role_does_not_exist_in_database(): void
    {
        $this->seed(\Database\Seeders\UserSeeder::class);
        $this->assertEquals(0, User::where('role', 'super_admin')->count());
        $this->assertEquals(0, User::where('email', 'superadmin@capaciprint.com')->count());
    }

    /**
     * Verify that CustomerMiddleware restricts access to customer role only.
     */
    public function test_customer_routes_redirect_non_customers_to_their_dashboards(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin']);
        }

        $response = $this->actingAs($admin)->get('/customer/dashboard');
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Verify that customer users can access customer dashboard.
     */
    public function test_customer_can_access_customer_dashboard(): void
    {
        $customer = User::where('role', 'customer')->first();
        if (!$customer) {
            $customer = User::factory()->create(['role' => 'customer']);
        }

        $response = $this->actingAs($customer)->get('/customer/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Verify that customer users cannot access admin dashboard.
     */
    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::where('role', 'customer')->first();
        if (!$customer) {
            $customer = User::factory()->create(['role' => 'customer']);
        }

        $response = $this->actingAs($customer)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Verify that admin can access admin dashboard.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin']);
        }

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }
}
