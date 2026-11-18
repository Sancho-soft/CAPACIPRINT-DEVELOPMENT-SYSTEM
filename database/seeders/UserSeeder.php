<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed demo accounts for system roles.
     */
    public function run(): void
    {
        // 0. Remove obsolete dummy customer accounts from previous test seeds
        User::whereIn('email', [
            'juan.delacruz@gmail.com',
            'maria.santos@gmail.com',
            'carlo.mendez@gmail.com',
            'beatrice.aquino@gmail.com',
            'dave.villanueva@gmail.com',
            'sarah.geronimo@gmail.com',
            'ramon.bautista@gmail.com',
            'robert.tan@buildcraft.ph',
            'archive.data@capaciprint.com',
        ])->delete();

        // 1. Owner
        User::updateOrCreate(
            ['email' => 'owner@capaciprint.com'],
            [
                'name'     => 'Hearty Macpao',
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
                'name'     => 'Justine Bieber',
                'password' => Hash::make('password'),
                'role'     => 'owner',
                'phone'    => '+63 922 678 9012',
                'address'  => 'Executive Headquarters',
            ]
        );

        // 2. System Admin
        User::updateOrCreate(
            ['email' => 'admin@capaciprint.com'],
            [
                'name'     => 'Jessie Revalde',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '+63 900 000 0002',
                'address'  => 'System Operations HQ',
            ]
        );

        // 3. Branch Manager
        User::updateOrCreate(
            ['email' => 'manager@capaciprint.com'],
            [
                'name'     => 'Niere Lupogan',
                'password' => Hash::make('password'),
                'role'     => 'manager',
                'phone'    => '+63 919 345 6789',
                'address'  => 'Morning Star Printing Press',
            ]
        );

        // 4. Production Officer
        User::updateOrCreate(
            ['email' => 'officer@capaciprint.com'],
            [
                'name'     => 'Isagani Canal',
                'password' => Hash::make('password'),
                'role'     => 'production_officer',
                'phone'    => '+63 919 444 5555',
                'address'  => 'Operations Planning Hub',
            ]
        );
        User::updateOrCreate(
            ['email' => 'production_officer@capaciprint.com'],
            [
                'name'     => 'Isagani Canal',
                'password' => Hash::make('password'),
                'role'     => 'production_officer',
                'phone'    => '+63 919 444 5555',
                'address'  => 'Operations Planning Hub',
            ]
        );

        // 5. Customer Service (CS)
        User::updateOrCreate(
            ['email' => 'staff@capaciprint.com'],
            [
                'name'     => 'Alex Beniga',
                'password' => Hash::make('password'),
                'role'     => 'staff',
                'phone'    => '+63 918 234 5678',
                'address'  => 'Main Customer Desk',
            ]
        );

        // 6. Pre-Press & Customer Service Staff
        User::updateOrCreate(
            ['email' => 'designer@capaciprint.com'],
            [
                'name'     => 'Clarence Gotas',
                'password' => Hash::make('password'),
                'role'     => 'staff',
                'phone'    => '+63 917 888 9999',
                'address'  => 'Pre-Press & CS Studio',
            ]
        );

        // 7. Production Operator
        User::updateOrCreate(
            ['email' => 'production@capaciprint.com'],
            [
                'name'     => 'Kim Lupogan',
                'password' => Hash::make('password'),
                'role'     => 'production',
                'phone'    => '+63 920 456 7890',
                'address'  => 'Main Hub Production Floor',
            ]
        );

        // 8. Inventory Staff
        User::updateOrCreate(
            ['email' => 'inventory@capaciprint.com'],
            [
                'name'     => 'Mike Kosa',
                'password' => Hash::make('password'),
                'role'     => 'inventory',
                'phone'    => '+63 921 777 8888',
                'address'  => 'Central Materials Storage',
            ]
        );

        // 9. Default Demo Customer
        User::updateOrCreate(
            ['email' => 'customer@capaciprint.com'],
            [
                'name'     => 'Aldren Reyes',
                'password' => Hash::make('password'),
                'role'     => 'customer',
                'phone'    => '+63 912 345 6789',
                'address'  => 'Matina Aplaya, Davao City, 8000',
            ]
        );

        // 10. The 10 Primary System Customers (November 2026)
        $customerPassword = Hash::make('password123');
        $primaryCustomers = [
            ['name' => 'Jhon Rex Partoza',   'email' => 'jhonrex.partoza@gmail.com',   'phone' => '+63 917 101 2231', 'address' => 'Matina Crossing, Davao City, 8000'],
            ['name' => 'Ryan Luza',          'email' => 'ryan.luza@gmail.com',          'phone' => '+63 917 202 3342', 'address' => 'Bajada, Davao City, 8000'],
            ['name' => 'Mark Asucan',        'email' => 'mark.asucan@gmail.com',        'phone' => '+63 917 303 4453', 'address' => 'Toril District, Davao City, 8000'],
            ['name' => 'Grace Cabilles',     'email' => 'grace.cabilles@gmail.com',     'phone' => '+63 917 404 5564', 'address' => 'Ecoland Phase 2, Davao City, 8000'],
            ['name' => 'Ryza Cagabhion',     'email' => 'ryza.cagabhion@gmail.com',     'phone' => '+63 917 505 6675', 'address' => 'Buhangin Road, Davao City, 8000'],
            ['name' => 'Joanna Plewa',       'email' => 'joanna.plewa@gmail.com',       'phone' => '+63 917 606 7786', 'address' => 'Lanang Commercial Hub, Davao City, 8000'],
            ['name' => 'Joyce Ann Muring',   'email' => 'joyceanne.muring@gmail.com',   'phone' => '+63 917 707 8897', 'address' => 'Magsaysay Ave, Davao City, 8000'],
            ['name' => 'Dianne Badlon',      'email' => 'dianne.badlon@gmail.com',      'phone' => '+63 917 808 9908', 'address' => 'Obrero District, Davao City, 8000'],
            ['name' => 'Prencess Baldeconza','email' => 'prencess.baldeconza@gmail.com','phone' => '+63 917 909 1129', 'address' => 'Maa, Davao City, 8000'],
            ['name' => 'Mary Kate Silat',    'email' => 'marykate.silat@gmail.com',    'phone' => '+63 917 010 3340', 'address' => 'Calinan District, Davao City, 8000'],
        ];

        foreach ($primaryCustomers as $cust) {
            User::updateOrCreate(
                ['email' => $cust['email']],
                [
                    'name'     => $cust['name'],
                    'password' => $customerPassword,
                    'role'     => 'customer',
                    'phone'    => $cust['phone'],
                    'address'  => $cust['address'],
                ]
            );
        }
    }
}
