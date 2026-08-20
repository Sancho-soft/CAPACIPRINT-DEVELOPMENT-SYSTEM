<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed demo accounts for testing.
     */
    public function run(): void
    {
        // 1. Customer
        User::updateOrCreate(
            ['email' => 'customer@capaciprint.com'],
            [
                'name'     => 'Demo Customer',
                'password' => Hash::make('password'),
                'role'     => 'customer',
                'phone'    => '+63 912 345 6789',
                'address'  => '123 Main Street, Quezon City',
            ]
        );

        // 2. Sales / Customer Service Staff
        User::updateOrCreate(
            ['email' => 'staff@capaciprint.com'],
            [
                'name'     => 'Maria Santos (Sales)',
                'password' => Hash::make('password'),
                'role'     => 'staff',
                'phone'    => '+63 918 234 5678',
                'address'  => 'Quezon City Main Office',
            ]
        );

        // 3. Branch Manager / Production Supervisor
        User::updateOrCreate(
            ['email' => 'manager@capaciprint.com'],
            [
                'name'     => 'John Supervisor (Manager)',
                'password' => Hash::make('password'),
                'role'     => 'manager',
                'phone'    => '+63 919 345 6789',
                'address'  => 'Main Printing Hub QC',
            ]
        );

        // 4. Production Staff
        User::updateOrCreate(
            ['email' => 'production@capaciprint.com'],
            [
                'name'     => 'Pedro Operator (Production)',
                'password' => Hash::make('password'),
                'role'     => 'production',
                'phone'    => '+63 920 456 7890',
                'address'  => 'Main Hub Production Floor',
            ]
        );

        // 5. Inventory Staff
        User::updateOrCreate(
            ['email' => 'inventory@capaciprint.com'],
            [
                'name'     => 'Elena Stock (Inventory)',
                'password' => Hash::make('password'),
                'role'     => 'inventory',
                'phone'    => '+63 921 567 8901',
                'address'  => 'Central Warehouse, Manila',
            ]
        );

        // 6. Owner / Management
        User::updateOrCreate(
            ['email' => 'management@capaciprint.com'],
            [
                'name'     => 'Director Morningstar (Executive)',
                'password' => Hash::make('password'),
                'role'     => 'management',
                'phone'    => '+63 922 678 9012',
                'address'  => 'Executive Headquarters',
            ]
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@capaciprint.com'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );
    }
}
