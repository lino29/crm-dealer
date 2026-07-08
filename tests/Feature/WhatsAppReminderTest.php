<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Dealer;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\ServiceSchedule;
use App\Models\WhatsAppNotification;
use App\Jobs\SendWhatsAppReminderJob;

class WhatsAppReminderTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $schedule;

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
            'customer_name' => 'WA Test User',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'phone' => '08123456789',
            'address' => 'Test',
            'usk_month' => '05',
            'status' => 'active',
            'created_by' => $this->adminUser->user_id,
        ]);

        $vehicle = Vehicle::create([
            'customer_id' => $customer->customer_id,
            'police_number' => 'B 1234 ABC',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => 2022,
            'color' => 'Black',
            'created_by' => $this->adminUser->user_id,
        ]);

        $this->schedule = ServiceSchedule::create([
            'vehicle_id' => $vehicle->vehicle_id,
            'scheduled_date' => now()->addDays(2),
            'status' => 'pending',
            'notification_status' => 'pending',
            'created_by' => $this->adminUser->user_id,
        ]);
        
        \App\Models\Setting::create(['key' => 'wa_template', 'value' => 'Hello {customer_name}', 'setting_group' => 'whatsapp']);
    }

    public function test_whatsapp_reminder_creates_log_and_dispatches_job(): void
    {
        Queue::fake();

        $response = $this->actingAs($this->adminUser)->post(route('admin.whatsapp.send'), [
            'schedule_id' => $this->schedule->schedule_id
        ]);

        $response->assertRedirect();
        
        // Assert log is created
        $this->assertDatabaseHas('whatsapp_notifications', [
            'schedule_id' => $this->schedule->schedule_id,
            'send_status' => 'pending'
        ]);

        // Assert job is dispatched
        Queue::assertPushed(SendWhatsAppReminderJob::class);
    }
}
