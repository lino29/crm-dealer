<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Role::firstOrCreate(
            ['role_name' => 'admin'],
            ['description' => 'Admin Customer Service']
        );

        \App\Models\Role::firstOrCreate(
            ['role_name' => 'leader'],
            ['description' => 'Pimpinan']
        );

        \App\Models\Role::firstOrCreate(
            ['role_name' => 'admin_support'],
            ['description' => 'Admin Support Pembuatan Member']
        );

        \App\Models\Role::firstOrCreate(
            ['role_name' => 'admin_stnk'],
            ['description' => 'Admin STNK Pengecekan & Penyerahan']
        );
    }
}
