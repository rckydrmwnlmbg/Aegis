<?php

namespace Database\Seeders;

use App\Models\AppUser;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(['tenant_code' => 'AEGIS'], [
            'name' => 'Aegis System',
            'status' => 'active',
            'deployment_mode' => 'saas',
        ]);

        $role = Role::firstOrCreate(['name' => 'HSE_MANAGER', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);

        $user = AppUser::firstOrCreate(
            ['email' => 'admin@aegis.system'],
            [
                'name' => 'Ricky Darmawan',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'tenant_id' => $tenant->id
            ]
        );

        $user->assignRole($role);
    }
}
