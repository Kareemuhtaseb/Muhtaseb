<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@muhtaseb.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'permissions' => ['*'],
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Manager user
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@muhtaseb.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'permissions' => ['properties.view', 'properties.create', 'properties.edit', 'units.view', 'units.create', 'units.edit', 'tenants.view', 'tenants.create', 'tenants.edit', 'income.view', 'income.create', 'income.edit', 'expenses.view', 'expenses.create', 'expenses.edit'],
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Viewer user
        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@muhtaseb.com',
            'password' => Hash::make('password'),
            'role' => 'viewer',
            'permissions' => ['properties.view', 'units.view', 'tenants.view', 'income.view', 'expenses.view', 'reports.view'],
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
