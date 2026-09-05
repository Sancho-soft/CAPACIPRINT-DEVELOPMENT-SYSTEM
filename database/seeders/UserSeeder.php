<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed demo accounts for all 9 system roles.
     */
    public function run(): void
    {
        // 1. Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@capaciprint.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
                'role'     => 'super_admin',
                'phone'    => '+63 969 195 2485',
                'address'  => 'CapaciPrint Technical Center',
            ]
        );

        // 2. Owner
        User::updateOrCreate(
            ['email' => 'owner@capaciprint.com'],
            [
                'name'     => 'Director Morningstar',
                'password' => Hash::make('password'),
                'role'     => 'owner',
                'phone'    => '+63 922 678 9012',
                'address'  => 'Executive Headquarters',
            ]
        );
        // Alias for management role
        User::updateOrCreate(
            ['email' => 'management@capaciprint.com'],
            [
                'name'     => 'Director Morningstar',
                'password' => Hash::make('password'),
                'role'     => 'owner',
                'phone'    => '+63 922 678 9012',
                'address'  => 'Executive Headquarters',
            ]
        );

        // 3. System Admin
        User::updateOrCreate(
            ['email' => 'admin@capaciprint.com'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '+63 900 000 0002',
                'address'  => 'System Operations HQ',
            ]
        );

        // 4. Branch Manager
        User::updateOrCreate(
            ['email' => 'manager@capaciprint.com'],
            [
                'name'     => 'Branch Manager',
                'password' => Hash::make('password'),
                'role'     => 'manager',
                'phone'    => '+63 919 345 6789',
                'address'  => 'Morning Star Printing Press',
            ]
        );

        // 5. Production Officer
        User::updateOrCreate(
            ['email' => 'officer@capaciprint.com'],
            [
                'name'     => 'Alex Planner',
                'password' => Hash::make('password'),
                'role'     => 'production_officer',
                'phone'    => '+63 919 444 5555',
                'address'  => 'Operations Planning Hub',
            ]
        );

        // 6. Customer Service (CS)
        User::updateOrCreate(
            ['email' => 'staff@capaciprint.com'],
            [
                'name'     => 'Maria Santos',
                'password' => Hash::make('password'),
                'role'     => 'staff',
                'phone'    => '+63 918 234 5678',
                'address'  => 'Main Customer Desk',
            ]
        );

        // 7. Layout Designer
        User::updateOrCreate(
            ['email' => 'designer@capaciprint.com'],
            [
                'name'     => 'Rafael Creative',
                'password' => Hash::make('password'),
                'role'     => 'designer',
                'phone'    => '+63 917 888 9999',
                'address'  => 'Pre-Press Studio',
            ]
        );

        // 8. Production Operator
        User::updateOrCreate(
            ['email' => 'production@capaciprint.com'],
            [
                'name'     => 'Pedro Operator',
                'password' => Hash::make('password'),
                'role'     => 'production',
                'phone'    => '+63 920 456 7890',
                'address'  => 'Main Hub Production Floor',
            ]
        );

        // 9. Customer
        User::updateOrCreate(
            ['email' => 'customer@capaciprint.com'],
            [
                'name'     => 'Demo Customer',
                'password' => Hash::make('password'),
                'role'     => 'customer',
                'phone'    => '+63 912 345 6789',
                'address'  => 'Client Address',
            ]
        );
    }
}
