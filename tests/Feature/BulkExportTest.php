<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Dealer;
use App\Models\Customer;
use App\Models\MemberCard;

class BulkExportTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['role_name' => 'admin']);
        $this->adminUser = User::factory()->create(['role_id' => $role->role_id]);
    }

    private function createCustomers(int $count = 3): \Illuminate\Support\Collection
    {
        $dealer = Dealer::create([
            'dealer_name' => 'Test Dealer',
            'dealer_code' => 'TD01',
            'address'     => 'Jl. Test No. 1',
            'status'      => 'active',
        ]);

        $customers = collect();
        for ($i = 1; $i <= $count; $i++) {
            $customer = Customer::create([
                'customer_name' => "Customer $i",
                'phone'         => "0812345678$i",
                'gender'        => 'male',
                'birth_date'    => '1990-01-01',
                'address'       => "Jl. Pelanggan $i",
                'dealer_id'     => $dealer->dealer_id,
                'usk_month'     => 1,
                'status'        => 'active',
                'created_by'    => $this->adminUser->id,
            ]);
            $customers->push($customer);
        }
        return $customers;
    }

    public function test_bulk_export_excel_returns_download(): void
    {
        $customers = $this->createCustomers(3);
        $ids = $customers->pluck('customer_id')->toArray();

        $response = $this->actingAs($this->adminUser)->post(
            route('admin.customers.bulk_export_excel'),
            ['customer_ids' => $ids]
        );

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_bulk_export_pdf_returns_download(): void
    {
        $customers = $this->createCustomers(3);
        $ids = $customers->pluck('customer_id')->toArray();

        $response = $this->actingAs($this->adminUser)->post(
            route('admin.customers.bulk_export_pdf'),
            ['customer_ids' => $ids]
        );

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_bulk_export_redirects_when_no_ids_provided(): void
    {
        $response = $this->actingAs($this->adminUser)->post(
            route('admin.customers.bulk_export_excel'),
            []
        );

        $response->assertRedirect(route('admin.customers.index'));
        $response->assertSessionHas('error');
    }

    public function test_bulk_print_member_cards_returns_pdf(): void
    {
        $customers = $this->createCustomers(2);
        $ids = $customers->pluck('customer_id')->toArray();

        // Create member cards for each customer
        foreach ($customers as $customer) {
            MemberCard::create([
                'customer_id'      => $customer->customer_id,
                'dealer_id'        => $customer->dealer_id,
                'member_code'      => 'L-TEST-' . $customer->customer_id,
                'member_code_base' => 'L-TEST-' . $customer->customer_id,
                'duplicate_sequence' => 1,
                'qr_token'         => \Illuminate\Support\Str::uuid(),
                'qr_payload'       => json_encode(['type' => 'member_card']),
                'issued_date'      => now()->toDateString(),
                'print_count'      => 0,
                'status'           => 'active',
            ]);
        }

        $response = $this->actingAs($this->adminUser)->post(
            route('admin.member_cards.bulk_print'),
            ['customer_ids' => $ids]
        );

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_bulk_print_redirects_when_no_member_cards(): void
    {
        $customers = $this->createCustomers(2);
        $ids = $customers->pluck('customer_id')->toArray();
        // No member cards created — should redirect with error

        $response = $this->actingAs($this->adminUser)->post(
            route('admin.member_cards.bulk_print'),
            ['customer_ids' => $ids]
        );

        $response->assertRedirect(route('admin.customers.index'));
        $response->assertSessionHas('error');
    }
}
