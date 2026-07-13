<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $adminRole = \App\Models\Role::firstOrCreate(
            ['role_name' => 'admin'],
            ['description' => 'Administrator']
        );
        $leaderRole = \App\Models\Role::firstOrCreate(
            ['role_name' => 'leader'],
            ['description' => 'Leader']
        );

        $supportRole = \App\Models\Role::firstOrCreate(
            ['role_name' => 'admin_support'],
            ['description' => 'Admin Support Pembuatan Member']
        );
        $stnkRole = \App\Models\Role::firstOrCreate(
            ['role_name' => 'admin_stnk'],
            ['description' => 'Admin STNK Pengecekan & Penyerahan']
        );

        \App\Models\User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin User',
                'email' => 'admin@trijaya.com',
                'role_id' => $adminRole->role_id,
                'password' => bcrypt('password'),
            ]
        );

        \App\Models\User::firstOrCreate(
            ['username' => 'leader'],
            [
                'name' => 'Leader User',
                'email' => 'leader@trijaya.com',
                'role_id' => $leaderRole->role_id,
                'password' => bcrypt('password'),
            ]
        );

        \App\Models\User::firstOrCreate(
            ['username' => 'support'],
            [
                'name' => 'Support User',
                'email' => 'support@trijaya.com',
                'role_id' => $supportRole->role_id,
                'password' => bcrypt('password'),
            ]
        );

        \App\Models\User::firstOrCreate(
            ['username' => 'stnk'],
            [
                'name' => 'STNK User',
                'email' => 'stnk@trijaya.com',
                'role_id' => $stnkRole->role_id,
                'password' => bcrypt('password'),
            ]
        );
    }
}
