<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Dealer;
use App\Models\Customer;

class MemberCardTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $customer;
    protected $dealer;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::create(['role_name' => 'admin']);
        $this->adminUser = User::factory()->create(['role_id' => $role->role_id]);
        
        $this->dealer = Dealer::create([
            'dealer_code' => 'S12', // Trijaya Motor (Simulasi S12)
            'dealer_name' => 'Trijaya Motor',
            'address' => 'Addr',
            'phone' => '123'
        ]);

        $this->customer = Customer::create([
            'dealer_id' => $this->dealer->dealer_id,
            'customer_name' => 'Usk User',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'phone' => '08123456789',
            'address' => 'USK',
            'usk_month' => '05',
            'status' => 'active',
            'created_by' => $this->adminUser->user_id,
        ]);
    }

    public function test_generate_member_card(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.member_cards.generate', $this->customer));

        $response->assertRedirect(route('admin.member_cards.preview', $this->customer));
        
        // Assert member card exists in database
        $this->assertDatabaseHas('member_cards', [
            'customer_id' => $this->customer->customer_id,
            'dealer_id' => $this->dealer->dealer_id,
        ]);

        // Get the generated card to check format
        $card = \App\Models\MemberCard::where('customer_id', $this->customer->customer_id)->first();
        
        // Assert UUID is generated
        $this->assertNotEmpty($card->qr_token);
        // Assert Code base matches
        $this->assertStringContainsString('S12-USK-', $card->member_code);
    }

    public function test_validasi_duplikasi_kode_member(): void
    {
        // First card
        $this->actingAs($this->adminUser)->post(route('admin.member_cards.generate', $this->customer));
        $card1 = \App\Models\MemberCard::where('customer_id', $this->customer->customer_id)->first();
        
        // Setup second customer to force duplicate sequence
        $customer2 = Customer::create([
            'dealer_id' => $this->dealer->dealer_id,
            'customer_name' => 'Usk User 2',
            'gender' => 'female',
            'birth_date' => '1990-01-01',
            'phone' => '0899',
            'address' => 'USK',
            'usk_month' => '05',
            'status' => 'active',
            'created_by' => $this->adminUser->user_id,
        ]);

        // Manipulate created_at to be exactly the same day so code_base matches
        $customer2->created_at = $this->customer->created_at;
        $customer2->save();

        $this->actingAs($this->adminUser)->post(route('admin.member_cards.generate', $customer2));
        $card2 = \App\Models\MemberCard::where('customer_id', $customer2->customer_id)->first();

        // They must share the same base code but different sequence
        $this->assertEquals($card1->member_code_base, $card2->member_code_base);
        $this->assertEquals(0, $card1->duplicate_sequence);
        $this->assertEquals(1, $card2->duplicate_sequence);
        $this->assertStringEndsWith('-1', $card2->member_code);
    }
}
