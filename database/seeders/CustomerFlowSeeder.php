<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use App\Models\Machine;
use App\Models\PrintRequest;
use App\Models\Quotation;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductionJob;
use App\Models\ClaimReference;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CustomerFlowSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');
        $branchA = Branch::firstOrCreate(['name' => 'Morning Star Printing Press'], [
            'location' => 'Branch A', 'status' => 'active', 'max_daily_jobs' => 85
        ]);
        $branchB = Branch::firstOrCreate(['name' => 'Morning Star Printing Network'], [
            'location' => 'Branch B', 'status' => 'active', 'max_daily_jobs' => 60
        ]);
        $branchC = Branch::firstOrCreate(['name' => 'Green Heart Printing Hub'], [
            'location' => 'Branch C', 'status' => 'active', 'max_daily_jobs' => 50
        ]);

        $prodUser = User::where('role', 'production')->first();
        $machine1 = Machine::where('branch_id', $branchA->id)->first();

        // ═══════════════════════════════════════════════════════════════
        // CUSTOMER 1: Maria Santos (Corporate Marketing)
        // Stage: STAGE 1 - NEW INTAKE (submitted)
        // Tests: Staff -> Customer Requests -> Verify Specs
        // ═══════════════════════════════════════════════════════════════
        $c1 = User::updateOrCreate(
            ['email' => 'maria.santos@gmail.com'],
            [
                'name'        => 'Maria Santos',
                'password'    => $defaultPassword,
                'role'        => 'customer',
                'phone'       => '+63 917 123 4501',
                'address'     => 'Apex Creative Marketing, Suite 501, Ayala Ave, Makati City',
                'is_archived' => false,
            ]
        );

        PrintRequest::updateOrCreate(
            ['user_id' => $c1->id, 'service' => 'Flyers & Brochures'],
            [
                'quantity'                => 1000,
                'size'                    => 'A4 Tri-fold',
                'material'                => 'Glossy Paper 150gsm',
                'finishing'               => 'Tri-fold & Gloss Lamination',
                'deadline'                => Carbon::now()->addDays(4),
                'preferred_branch'        => $branchA->name,
                'collection_mode'         => 'pickup',
                'additional_instructions' => 'Please ensure fold lines are sharp and colors match CMYK sample.',
                'status'                  => 'submitted',
            ]
        );

        // ═══════════════════════════════════════════════════════════════
        // CUSTOMER 2: Carlo Mendez (Event & Wedding Organizer)
        // Stage: STAGE 2 - PENDING QUOTATION
        // Tests: Customer -> My Quotations -> Confirm & Pay
        // ═══════════════════════════════════════════════════════════════
        $c2 = User::updateOrCreate(
            ['email' => 'carlo.mendez@gmail.com'],
            [
                'name'        => 'Carlo Mendez',
                'password'    => $defaultPassword,
                'role'        => 'customer',
                'phone'       => '+63 918 234 5602',
                'address'     => 'Eleganza Event Stylings, Emerald Ave, Ortigas, Pasig City',
                'is_archived' => false,
            ]
        );

        $pr2 = PrintRequest::updateOrCreate(
            ['user_id' => $c2->id, 'service' => 'Calling Cards'],
            [
                'quantity'                => 250,
                'size'                    => '5x7 inches',
                'material'                => 'Matte Cardstock 220gsm',
                'finishing'               => 'Gold Foil Stamping',
                'deadline'                => Carbon::now()->addDays(5),
                'preferred_branch'        => $branchA->name,
                'collection_mode'         => 'pickup',
                'additional_instructions' => 'Special wedding invitation cards. Foil stamping on bride & groom names.',
                'status'                  => 'quotation',
            ]
        );

        Quotation::updateOrCreate(
            ['quotation_number' => 'QT-2026-301'],
            [
                'print_request_id' => $pr2->id,
                'user_id'          => $c2->id,
                'base_cost'        => 3200.00,
                'material_cost'    => 1100.00,
                'finishing_cost'   => 550.00,
                'total_price'      => 4850.00,
                'valid_until'      => Carbon::now()->addDays(7),
                'status'           => 'pending',
                'notes'            => 'Includes premium cardstock and custom hot foil plate stamping setup.',
            ]
        );

        // ═══════════════════════════════════════════════════════════════
        // CUSTOMER 3: Beatrice Aquino (University Student Council)
        // Stage: STAGE 3 - SHOP-FLOOR PRODUCTION (in_production)
        // Tests: Manager -> Production Planning & Operator Queue
        // ═══════════════════════════════════════════════════════════════
        $c3 = User::updateOrCreate(
            ['email' => 'beatrice.aquino@gmail.com'],
            [
                'name'        => 'Beatrice Aquino',
                'password'    => $defaultPassword,
                'role'        => 'customer',
                'phone'       => '+63 919 345 6703',
                'address'     => 'University Student Council, University Belt, Manila',
                'is_archived' => false,
            ]
        );

        $pr3 = PrintRequest::updateOrCreate(
            ['user_id' => $c3->id, 'service' => 'Stickers & Labels'],
            [
                'quantity'         => 500,
                'size'             => '2.5x2.5 in Die-cut',
                'material'         => 'Gloss Vinyl Waterproof Sticker Roll',
                'finishing'        => 'Kiss Cut & Waterproof Coating',
                'deadline'         => Carbon::now()->addDays(3),
                'preferred_branch' => $branchA->name,
                'collection_mode'  => 'pickup',
                'status'           => 'production',
            ]
        );

        $qt3 = Quotation::updateOrCreate(
            ['quotation_number' => 'QT-2026-302'],
            [
                'print_request_id' => $pr3->id,
                'user_id'          => $c3->id,
                'base_cost'        => 1600.00,
                'material_cost'    => 500.00,
                'finishing_cost'   => 250.00,
                'total_price'      => 2350.00,
                'valid_until'      => Carbon::now()->addDays(14),
                'status'           => 'confirmed',
            ]
        );

        $ord3 = Order::updateOrCreate(
            ['order_number' => 'ORD-2026-201'],
            [
                'user_id'              => $c3->id,
                'print_request_id'     => $pr3->id,
                'quotation_id'         => $qt3->id,
                'assigned_branch'      => $branchA->name,
                'estimated_completion' => Carbon::now()->addDays(2),
                'status'               => 'production',
                'payment_status'       => 'confirmed',
            ]
        );

        Payment::updateOrCreate(
            ['order_id' => $ord3->id],
            [
                'user_id'           => $c3->id,
                'payment_method'    => 'GCash',
                'amount'            => 2350.00,
                'payment_reference' => 'PAY-GCASH-98214',
                'status'            => 'confirmed',
                'paid_at'           => Carbon::now()->subDay(),
            ]
        );

        ProductionJob::updateOrCreate(
            ['job_number' => 'JOB-2026-001'],
            [
                'order_id'        => $ord3->id,
                'branch_id'       => $branchA->id,
                'machine_id'      => $machine1->id ?? 1,
                'assigned_to'     => $prodUser->id ?? null,
                'status'          => 'in_production',
                'priority'        => 'normal',
                'estimated_hours' => 3,
                'started_at'      => Carbon::now()->subHours(2),
            ]
        );

        // ═══════════════════════════════════════════════════════════════
        // CUSTOMER 4: Dave Villanueva (Cafe & Restaurant Owner)
        // Stage: STAGE 4 - READY FOR PICKUP (QR Code Active)
        // Tests: Customer -> QR/Claiming & Staff -> Claim Scanner
        // ═══════════════════════════════════════════════════════════════
        $c4 = User::updateOrCreate(
            ['email' => 'dave.villanueva@gmail.com'],
            [
                'name'        => 'Dave Villanueva',
                'password'    => $defaultPassword,
                'role'        => 'customer',
                'phone'       => '+63 920 456 7804',
                'address'     => 'Ka-Kape Artisan Cafe, Tomas Morato, Quezon City',
                'is_archived' => false,
            ]
        );

        $pr4 = PrintRequest::updateOrCreate(
            ['user_id' => $c4->id, 'service' => 'Tarpaulin Banner'],
            [
                'quantity'         => 4,
                'size'             => '4x8 ft',
                'material'         => 'Flex Tarpaulin Banner Media 13oz',
                'finishing'        => 'Eyelets & Reinforced Hemming',
                'deadline'         => Carbon::now()->addDay(),
                'preferred_branch' => $branchA->name,
                'collection_mode'  => 'pickup',
                'status'           => 'ready_for_pickup',
            ]
        );

        $qt4 = Quotation::updateOrCreate(
            ['quotation_number' => 'QT-2026-303'],
            [
                'print_request_id' => $pr4->id,
                'user_id'          => $c4->id,
                'base_cost'        => 2000.00,
                'material_cost'    => 850.00,
                'finishing_cost'   => 300.00,
                'total_price'      => 3150.00,
                'valid_until'      => Carbon::now()->addDays(10),
                'status'           => 'confirmed',
            ]
        );

        $ord4 = Order::updateOrCreate(
            ['order_number' => 'ORD-2026-202'],
            [
                'user_id'              => $c4->id,
                'print_request_id'     => $pr4->id,
                'quotation_id'         => $qt4->id,
                'assigned_branch'      => $branchA->name,
                'estimated_completion' => Carbon::now(),
                'status'               => 'ready_for_pickup',
                'payment_status'       => 'confirmed',
            ]
        );

        Payment::updateOrCreate(
            ['order_id' => $ord4->id],
            [
                'user_id'           => $c4->id,
                'payment_method'    => 'Bank Transfer',
                'amount'            => 3150.00,
                'payment_reference' => 'PAY-BDO-55192',
                'status'            => 'confirmed',
                'paid_at'           => Carbon::now()->subDays(2),
            ]
        );

        ProductionJob::updateOrCreate(
            ['job_number' => 'JOB-2026-002'],
            [
                'order_id'        => $ord4->id,
                'branch_id'       => $branchA->id,
                'machine_id'      => $machine1->id ?? 1,
                'assigned_to'     => $prodUser->id ?? null,
                'status'          => 'completed',
                'priority'        => 'normal',
                'estimated_hours' => 2,
                'started_at'      => Carbon::now()->subHours(6),
                'completed_at'    => Carbon::now()->subHours(2),
            ]
        );

        ClaimReference::updateOrCreate(
            ['order_id' => $ord4->id],
            [
                'user_id'         => $c4->id,
                'claim_code'      => 'CLM-8824-DAVE',
                'pickup_branch'   => $branchA->name,
                'completion_date' => Carbon::now(),
                'is_claimed'      => false,
            ]
        );

        // ═══════════════════════════════════════════════════════════════
        // CUSTOMER 5: Sarah Geronimo (Retail Brand Manager)
        // Stage: STAGE 5 - CLAIMED HISTORY (completed)
        // Tests: Customer Order History & CRM Analytics
        // ═══════════════════════════════════════════════════════════════
        $c5 = User::updateOrCreate(
            ['email' => 'sarah.geronimo@gmail.com'],
            [
                'name'        => 'Sarah Geronimo',
                'password'    => $defaultPassword,
                'role'        => 'customer',
                'phone'       => '+63 921 567 8905',
                'address'     => 'Bloom Fashion & Retail, BGC High Street, Taguig City',
                'is_archived' => false,
            ]
        );

        $pr5 = PrintRequest::updateOrCreate(
            ['user_id' => $c5->id, 'service' => 'Document Printing'],
            [
                'quantity'         => 150,
                'size'             => 'A4 Booklet',
                'material'         => 'Glossy Paper 150gsm',
                'finishing'        => 'Saddle Stitch Binding',
                'deadline'         => Carbon::now()->subDays(2),
                'preferred_branch' => $branchB->name,
                'collection_mode'  => 'pickup',
                'status'           => 'completed',
            ]
        );

        $qt5 = Quotation::updateOrCreate(
            ['quotation_number' => 'QT-2026-304'],
            [
                'print_request_id' => $pr5->id,
                'user_id'          => $c5->id,
                'base_cost'        => 2800.00,
                'material_cost'    => 900.00,
                'finishing_cost'   => 500.00,
                'total_price'      => 4200.00,
                'valid_until'      => Carbon::now()->addDays(5),
                'status'           => 'confirmed',
            ]
        );

        $ord5 = Order::updateOrCreate(
            ['order_number' => 'ORD-2026-203'],
            [
                'user_id'              => $c5->id,
                'print_request_id'     => $pr5->id,
                'quotation_id'         => $qt5->id,
                'assigned_branch'      => $branchB->name,
                'estimated_completion' => Carbon::now()->subDays(2),
                'status'               => 'claimed',
                'payment_status'       => 'confirmed',
            ]
        );

        Payment::updateOrCreate(
            ['order_id' => $ord5->id],
            [
                'user_id'           => $c5->id,
                'payment_method'    => 'Bank Transfer',
                'amount'            => 4200.00,
                'payment_reference' => 'PAY-CC-77189',
                'status'            => 'confirmed',
                'paid_at'           => Carbon::now()->subDays(3),
            ]
        );

        ClaimReference::updateOrCreate(
            ['order_id' => $ord5->id],
            [
                'user_id'         => $c5->id,
                'claim_code'      => 'CLM-9102-SARA',
                'pickup_branch'   => $branchB->name,
                'completion_date' => Carbon::now()->subDays(2),
                'is_claimed'      => true,
                'claimed_at'      => Carbon::now()->subDay(),
            ]
        );

        // ═══════════════════════════════════════════════════════════════
        // CUSTOMER 6: Engr. Ramon Bautista (Construction & Architecture)
        // Stage: STAGE 6 - PAYMENT VERIFICATION PENDING
        // Tests: Staff -> Payment Verification Portal
        // ═══════════════════════════════════════════════════════════════
        $c6 = User::updateOrCreate(
            ['email' => 'ramon.bautista@gmail.com'],
            [
                'name'        => 'Engr. Ramon Bautista',
                'password'    => $defaultPassword,
                'role'        => 'customer',
                'phone'       => '+63 922 678 9006',
                'address'     => 'Bautista Builders & Engineering, Roxas Blvd, Pasay City',
                'is_archived' => false,
            ]
        );

        $pr6 = PrintRequest::updateOrCreate(
            ['user_id' => $c6->id, 'service' => 'Document Printing'],
            [
                'quantity'         => 50,
                'size'             => 'A1 Large Format',
                'material'         => 'Standard Material',
                'finishing'        => 'Blueprint Roll Binding',
                'deadline'         => Carbon::now()->addDays(6),
                'preferred_branch' => $branchC->name,
                'collection_mode'  => 'pickup',
                'status'           => 'payment',
            ]
        );

        $qt6 = Quotation::updateOrCreate(
            ['quotation_number' => 'QT-2026-305'],
            [
                'print_request_id' => $pr6->id,
                'user_id'          => $c6->id,
                'base_cost'        => 2000.00,
                'material_cost'    => 500.00,
                'finishing_cost'   => 300.00,
                'total_price'      => 2800.00,
                'valid_until'      => Carbon::now()->addDays(12),
                'status'           => 'confirmed',
            ]
        );

        $ord6 = Order::updateOrCreate(
            ['order_number' => 'ORD-2026-204'],
            [
                'user_id'              => $c6->id,
                'print_request_id'     => $pr6->id,
                'quotation_id'         => $qt6->id,
                'assigned_branch'      => $branchC->name,
                'estimated_completion' => Carbon::now()->addDays(5),
                'status'               => 'payment',
                'payment_status'       => 'submitted',
            ]
        );

        Payment::updateOrCreate(
            ['order_id' => $ord6->id],
            [
                'user_id'           => $c6->id,
                'payment_method'    => 'Bank Transfer',
                'amount'            => 2800.00,
                'payment_reference' => 'PAY-BPI-44219',
                'status'            => 'submitted',
                'paid_at'           => Carbon::now()->subHours(4),
                'notes'             => 'Transferred via BPI Mobile Banking. Awaiting staff receipt verification.',
            ]
        );
    }
}