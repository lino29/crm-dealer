<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Dealer;
use App\Models\Customer;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::create(['role_name' => 'admin']);
        $this->adminUser = User::factory()->create(['role_id' => $role->role_id]);
        
        $dealer = Dealer::create([
            'dealer_code' => 'TD01',
            'dealer_name' => 'Test Dealer',
            'address' => 'Addr',
            'phone' => '123'
        ]);

        $this->customer = Customer::create([
            'dealer_id' => $dealer->dealer_id,
            'customer_name' => 'John Doe',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'phone' => '08123456789',
            'address' => 'Test Address',
            'usk_month' => '05',
            'status' => 'active',
            'created_by' => $this->adminUser->user_id,
        ]);
    }

    public function test_tambah_vehicle(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.vehicles.store'), [
            'customer_id' => $this->customer->customer_id,
            'police_number' => 'B 1234 ABC',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => 2022,
            'color' => 'Black',
            'engine_number' => 'ENG123',
            'chassis_number' => 'CHA123',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.customers.show', $this->customer->customer_id));
        $this->assertDatabaseHas('vehicles', [
            'police_number' => 'B 1234 ABC',
            'brand' => 'Yamaha'
        ]);
    }
}
