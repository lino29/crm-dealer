<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Dealer;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\MemberCard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $supportUser;
    protected $stnkUser;
    protected $customer;
    protected $vehicle;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Roles
        $adminRole = Role::create(['role_name' => 'admin']);
        $supportRole = Role::create(['role_name' => 'admin_support']);
        $stnkRole = Role::create(['role_name' => 'admin_stnk']);

        // Create Users
        $this->adminUser = User::factory()->create(['role_id' => $adminRole->role_id]);
        $this->supportUser = User::factory()->create(['role_id' => $supportRole->role_id]);
        $this->stnkUser = User::factory()->create(['role_id' => $stnkRole->role_id]);

        // Setup Dealer
        $dealer = Dealer::create([
            'dealer_code' => 'D01',
            'dealer_name' => 'Test Dealer',
            'address' => 'Test Address',
            'phone' => '021123456',
        ]);

        // Setup Customer
        $this->customer = Customer::create([
            'dealer_id' => $dealer->dealer_id,
            'customer_name' => 'John Doe',
            'gender' => 'male',
            'birth_date' => '1995-05-15',
            'phone' => '08123456789',
            'address' => 'Customer Address',
            'usk_month' => '07',
            'status' => 'active',
            'created_by' => $this->adminUser->user_id,
        ]);

        // Setup Vehicle
        $this->vehicle = Vehicle::create([
            'customer_id' => $this->customer->customer_id,
            'police_number' => 'B 9999 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario',
            'color' => 'Red',
            'status' => 'active',
            'stnk_status' => 'proses',
        ]);
    }

    /**
     * Test admin support permissions.
     */
    public function test_admin_support_permissions(): void
    {
        // 1. Can access customer show page
        $response = $this->actingAs($this->supportUser)
            ->get(route('admin.customers.show', $this->customer));
        $response->assertOk();

        // 2. Can generate member card
        $response = $this->actingAs($this->supportUser)
            ->post(route('admin.member_cards.generate', $this->customer));
        $response->assertRedirect(route('admin.member_cards.preview', $this->customer));
        $this->assertDatabaseHas('member_cards', [
            'customer_id' => $this->customer->customer_id,
            'status' => 'active',
        ]);

        // 3. CANNOT update STNK status (should return 403 Forbidden)
        $response = $this->actingAs($this->supportUser)
            ->post(route('admin.vehicles.update_stnk', $this->vehicle), [
                'stnk_status' => 'ready',
            ]);
        $response->assertStatus(403);
    }

    /**
     * Test admin STNK permissions.
     */
    public function test_admin_stnk_permissions(): void
    {
        // 1. Can access vehicles list
        $response = $this->actingAs($this->stnkUser)
            ->get(route('admin.vehicles.index'));
        $response->assertOk();

        // 2. Can update STNK status
        $response = $this->actingAs($this->stnkUser)
            ->post(route('admin.vehicles.update_stnk', $this->vehicle), [
                'stnk_status' => 'ready',
            ]);
        $response->assertRedirect(route('admin.customers.show', $this->customer));
        $this->assertDatabaseHas('vehicles', [
            'vehicle_id' => $this->vehicle->vehicle_id,
            'stnk_status' => 'ready',
        ]);

        // 3. CANNOT generate member card (should return 403 Forbidden)
        $response = $this->actingAs($this->stnkUser)
            ->post(route('admin.member_cards.generate', $this->customer));
        $response->assertStatus(403);
    }

    /**
     * Test super admin permissions (can do both).
     */
    public function test_super_admin_can_do_both(): void
    {
        // 1. Can generate member card
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.member_cards.generate', $this->customer));
        $response->assertRedirect();

        // 2. Can update STNK status
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.vehicles.update_stnk', $this->vehicle), [
                'stnk_status' => 'ready',
            ]);
        $response->assertRedirect();
    }
}
