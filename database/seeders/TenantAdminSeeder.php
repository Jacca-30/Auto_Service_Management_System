<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantAdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // Pirmasis autoservisas
            $tenant1 = Tenant::updateOrCreate(
                ['slug' => 'autoservisas-vilnius'],
                [
                    'name' => 'Autoservisas Vilnius',
                    'email' => 'vilnius@example.com',
                    'phone' => '+37060000001',
                    'address' => 'Vilnius',
                ]
            );

            $admin1 = User::firstOrNew([
                'email' => 'admin1@example.com',
            ]);

            $admin1->name = 'Vilniaus administratorius';
            $admin1->tenant_id = $tenant1->id;
            $admin1->role = 'admin';
            $admin1->password = 'VilniusAdmin#2026';
            $admin1->save();

            // Antrasis autoservisas
            $tenant2 = Tenant::updateOrCreate(
                ['slug' => 'autoservisas-kaunas'],
                [
                    'name' => 'Autoservisas Kaunas',
                    'email' => 'kaunas@example.com',
                    'phone' => '+37060000002',
                    'address' => 'Kaunas',
                ]
            );

            $admin2 = User::firstOrNew([
                'email' => 'admin2@example.com',
            ]);

            $admin2->name = 'Kauno administratorius';
            $admin2->tenant_id = $tenant2->id;
            $admin2->role = 'admin';
            $admin2->password = 'KaunasAdmin#2026';
            $admin2->save();
        });
    }
}
