<?php

namespace Database\Seeders;

use App\Models\AppUser;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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

        $roles = [
            'FIELD_WORKER',
            'HSE_OFFICER',
            'HSE_MANAGER',
            'EXECUTIVE',
            'CONTRACTOR',
            'TENANT_ADMIN',
            'AUDITOR_EXTERNAL',
            'Assessor',
            'SAFETY_OFFICER',
            'Manager',
            'SITE_MANAGER',
        ];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web', 'tenant_id' => $tenant->id]);

            $email = strtolower($roleName) . '@demo.com';
            $user = AppUser::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Demo ' . ucwords(str_replace('_', ' ', strtolower($roleName))),
                    'password' => Hash::make('password123'),
                    'tenant_id' => $tenant->id
                ]
            );

            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }
        }

        // Original Admin
        $roleAdmin = Role::firstOrCreate(['name' => 'HSE_MANAGER', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
        $userAdmin = AppUser::firstOrCreate(
            ['email' => 'admin@aegis.system'],
            [
                'name' => 'Ricky Darmawan',
                'password' => Hash::make('password123'),
                'tenant_id' => $tenant->id
            ]
        );
        if (!$userAdmin->hasRole($roleAdmin)) {
            $userAdmin->assignRole($roleAdmin);
        }
    }
}
