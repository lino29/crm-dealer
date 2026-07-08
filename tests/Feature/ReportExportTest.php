<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::create(['role_name' => 'leader']);
        $this->adminUser = User::factory()->create(['role_id' => $role->role_id]);
    }

    public function test_export_reports(): void
    {
        // Testing that all export routes return 200 (download headers)
        $routes = [
            'customers',
            'vehicles',
            'service-histories',
            'service-schedules',
            'whatsapp-notifications',
            'scan-logs'
        ];

        foreach ($routes as $type) {
            $response = $this->actingAs($this->adminUser)->get(route('leader.reports.export.excel', ['type' => $type]));
            $response->assertStatus(200);

            $response = $this->actingAs($this->adminUser)->get(route('leader.reports.export.pdf', ['type' => $type]));
            $response->assertStatus(200);
        }
    }
}
