<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Dealer;
use App\Models\Customer;
use App\Models\MemberCard;
use Illuminate\Support\Str;

class ScanQRTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $memberCard;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::create(['role_name' => 'admin']);
        $this->adminUser = User::factory()->create(['role_id' => $role->role_id]);
        
        $dealer = Dealer::create([
            'dealer_code' => 'S12',
            'dealer_name' => 'Trijaya Motor',
            'address' => 'Addr',
            'phone' => '123'
        ]);

        $customer = Customer::create([
            'dealer_id' => $dealer->dealer_id,
            'customer_name' => 'Scan Test User',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'phone' => '08123456789',
            'address' => 'Test',
            'usk_month' => '05',
            'status' => 'active',
            'created_by' => $this->adminUser->user_id,
        ]);

        $this->memberCard = MemberCard::create([
            'customer_id' => $customer->customer_id,
            'dealer_id' => $dealer->dealer_id,
            'member_code' => 'M-S12-USK-123',
            'member_code_base' => 'M-S12-USK-123',
            'duplicate_sequence' => 0,
            'qr_token' => (string) Str::uuid(),
            'qr_payload' => '',
            'issued_date' => now()->toDateString(),
            'print_count' => 0,
            'status' => 'active'
        ]);
    }

    public function test_scan_token_valid(): void
    {
        $response = $this->actingAs($this->adminUser)->postJson(route('admin.scan.validate'), [
            'qr_token' => $this->memberCard->qr_token
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        // Assert ScanLog created
        $this->assertDatabaseHas('scan_logs', [
            'qr_token_scanned' => $this->memberCard->qr_token,
            'status' => 'success'
        ]);
    }

    public function test_scan_token_invalid(): void
    {
        $response = $this->actingAs($this->adminUser)->postJson(route('admin.scan.validate'), [
            'qr_token' => 'invalid-token-123'
        ]);

        $response->assertStatus(404);
        $response->assertJson(['success' => false]);
        
        // Assert ScanLog created with invalid status
        $this->assertDatabaseHas('scan_logs', [
            'qr_token_scanned' => 'invalid-token-123',
            'status' => 'invalid'
        ]);
    }
}
