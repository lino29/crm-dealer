<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Dealer;
use App\Models\Customer;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $dealer;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::create(['role_name' => 'admin']);
        $this->adminUser = User::factory()->create(['role_id' => $role->role_id]);
        $this->dealer = Dealer::create([
            'dealer_code' => 'TD01',
            'dealer_name' => 'Test Dealer',
            'address' => 'Addr',
            'phone' => '123'
        ]);
    }

    public function test_tambah_customer(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.customers.store'), [
            'dealer_id' => $this->dealer->dealer_id,
            'customer_name' => 'John Doe',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'phone' => '08123456789',
            'address' => 'Test Address',
            'usk_month' => '05',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('customers', [
            'customer_name' => 'John Doe',
            'phone' => '08123456789'
        ]);
    }

    public function test_search_customer_lintas_relasi(): void
    {
        $customer = Customer::create([
            'dealer_id' => $this->dealer->dealer_id,
            'customer_name' => 'Budi Santoso',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'phone' => '089988776655',
            'address' => 'Jalan A',
            'usk_month' => '05',
            'status' => 'active',
            'created_by' => $this->adminUser->user_id,
        ]);

        // Create vehicle for customer
        $customer->vehicles()->create([
            'police_number' => 'B 1234 XYZ',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => 2020,
            'color' => 'Black',
            'created_by' => $this->adminUser->user_id,
        ]);

        // Search by name
        $response = $this->actingAs($this->adminUser)->get(route('admin.customers.index', ['search' => 'Budi']));
        $response->assertSee('Budi Santoso');

        // Search by police number
        $response = $this->actingAs($this->adminUser)->get(route('admin.customers.index', ['search' => '1234']));
        $response->assertSee('Budi Santoso');
    }
}
