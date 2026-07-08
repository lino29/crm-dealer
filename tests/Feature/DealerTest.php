<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Dealer;

class DealerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::create(['role_name' => 'admin']);
        $this->adminUser = User::factory()->create(['role_id' => $role->role_id]);
    }

    public function test_login_and_role_access(): void
    {
        // Assert unauthenticated users cannot access admin routes
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));

        // Assert authenticated admin can access admin dashboard
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_tambah_dealer(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.dealers.store'), [
            'dealer_name' => 'Test Dealer',
            'dealer_code' => 'TD01',
            'address' => 'Test Address',
            'phone' => '08123456789',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.dealers.index'));
        $this->assertDatabaseHas('dealers', [
            'dealer_code' => 'TD01'
        ]);
    }
}
