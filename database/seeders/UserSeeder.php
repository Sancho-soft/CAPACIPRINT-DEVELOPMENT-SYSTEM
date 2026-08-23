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
        // 1. Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@capaciprint.com'],
            [
                'name'     => 'Alex Mercer (Super Admin)',
                'password' => Hash::make('password'),
                'role'     => 'superadmin',
                'phone'    => '+63 900 000 0001',
                'address'  => 'CapaciPrint Global Operations HQ',
            ]
        );

        // 2. Owner
        User::updateOrCreate(
            ['email' => 'management@capaciprint.com'],
            [
                'name'     => 'Director Morningstar (Owner)',
                'password' => Hash::make('password'),
                'role'     => 'management',
                'phone'    => '+63 922 678 9012',
                'address'  => 'Executive Headquarters',
            ]
        );

        // 3. System Admin
        User::updateOrCreate(
            ['email' => 'admin@capaciprint.com'],
            [
                'name'     => 'System Admin (IT)',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '+63 911 111 2222',
                'address'  => 'IT Operations Hub',
            ]
        );

        // 4. Branch Manager
        User::updateOrCreate(
            ['email' => 'manager@capaciprint.com'],
            [
                'name'     => 'John Manager (Branch Manager)',
                'password' => Hash::make('password'),
                'role'     => 'manager',
                'phone'    => '+63 919 345 6789',
                'address'  => 'Main Printing Hub QC',
            ]
        );

        // 5. Production Officer
        User::updateOrCreate(
            ['email' => 'officer@capaciprint.com'],
            [
                'name'     => 'Robert Planner (Production Officer)',
                'password' => Hash::make('password'),
                'role'     => 'production_officer',
                'phone'    => '+63 919 999 8888',
                'address'  => 'QC Operations Planning Center',
            ]
        );

        // 6. Customer Service (CS)
        User::updateOrCreate(
            ['email' => 'staff@capaciprint.com'],
            [
                'name'     => 'Maria Santos (Customer Service)',
                'password' => Hash::make('password'),
                'role'     => 'staff',
                'phone'    => '+63 918 234 5678',
                'address'  => 'Quezon City Main Office',
            ]
        );

        // 7. Layout Designer
        User::updateOrCreate(
            ['email' => 'designer@capaciprint.com'],
            [
                'name'     => 'Carlos Creative (Layout Designer)',
                'password' => Hash::make('password'),
                'role'     => 'designer',
                'phone'    => '+63 917 777 6666',
                'address'  => 'Pre-Press & Creative Studio',
            ]
        );

        // 8. Production Operator
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

        // 9. Inventory Staff
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

        // 10. Customer
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
    }
}
