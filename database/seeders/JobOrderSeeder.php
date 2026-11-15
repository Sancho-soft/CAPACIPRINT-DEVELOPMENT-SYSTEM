<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\Machine;
use App\Models\PrintRequest;
use App\Models\Quotation;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductionJob;
use App\Models\ClaimReference;
use App\Models\DesignProof;
use App\Models\CapacityEvaluation;
use App\Models\BranchRecommendation;

class JobOrderSeeder extends Seeder
{
    /**
     * Seeds rich, realistic November 2026 data for pagination across all tables:
     * - Aldren Reyes (customer portal): 16 print requests (3 pages), 12 quotes, 9 orders (2 pages), 9 payments (2 pages)
     * - 10 Primary Customers: 20 requests, 12 orders, 12 payments
     * - Assorted payment methods: Cash (OTC), GCash (E-Wallet), Bank Transfer (Direct Wire)
     * - Centered around simulated current date: November 15, 2026.
     */
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────────
        // 1. CLEANUP ALL TRANSACTIONAL RECORDS (SAFE RESET)
        // ─────────────────────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ClaimReference::truncate();
        ProductionJob::truncate();
        Payment::truncate();
        Order::truncate();
        Quotation::truncate();
        DesignProof::truncate();
        CapacityEvaluation::truncate();
        BranchRecommendation::truncate();
        PrintRequest::truncate();

        // Remove obsolete dummy customer records from previous test seeds
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

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ─────────────────────────────────────────────────────────────
        // 2. RESOLVE SYSTEM OPERATORS & BRANCHES
        // ─────────────────────────────────────────────────────────────
        $branchA = Branch::where('name', 'like', '%Morning Star Printing Press%')
            ->orWhere('location', 'Branch A')
            ->first() ?? Branch::first();

        $branchB = Branch::where('name', 'like', '%Morning Star Printing Network%')
            ->orWhere('location', 'Branch B')
            ->first() ?? $branchA;

        $branchC = Branch::where('name', 'like', '%Green Heart Printing Hub%')
            ->orWhere('location', 'Branch C')
            ->first() ?? $branchA;

        $prodOperator = User::where('role', 'production')->first() ?? User::where('email', 'production@capaciprint.com')->first();
        if (!$prodOperator) {
            $prodOperator = User::create([
                'name'     => 'Kim Lupogan',
                'email'    => 'production@capaciprint.com',
                'password' => Hash::make('password'),
                'role'     => 'production',
                'phone'    => '+63 920 456 7890',
                'address'  => 'Main Hub Production Floor, Davao City',
            ]);
        }

        $designerUser = User::where('role', 'designer')->first() ?? User::where('email', 'designer@capaciprint.com')->first();
        $officerUser  = User::where('role', 'officer')->first() ?? User::where('email', 'officer@capaciprint.com')->first();

        // System Machines
        $mSpeedmaster = Machine::where('branch_id', $branchA->id)->where('type', 'Offset Press')->first()
            ?? Machine::where('branch_id', $branchA->id)->first();
        $mIndigo = Machine::where('branch_id', $branchA->id)->where('type', 'Digital Press')->first()
            ?? Machine::where('branch_id', $branchA->id)->first();
        $mRoland = Machine::where('branch_id', $branchB->id)->where('type', 'Large Format')->first()
            ?? Machine::where('branch_id', $branchB->id)->first();
        $mSureColor = Machine::where('branch_id', $branchB->id)->where('type', 'Signage & Vinyl')->first()
            ?? Machine::where('branch_id', $branchB->id)->first();
        $mCanon = Machine::where('branch_id', $branchC->id)->first() ?? Machine::first();

        // ─────────────────────────────────────────────────────────────
        // 3. SEED THE 10 CUSTOMERS
        // ─────────────────────────────────────────────────────────────
        $password = Hash::make('password123');

        $customers = [
            'c1'  => User::updateOrCreate(['email' => 'jhonrex.partoza@gmail.com'], [
                'name' => 'Jhon Rex Partoza', 'password' => $password, 'role' => 'customer',
                'phone' => '+63 917 101 2231', 'address' => 'Matina Crossing, Davao City, 8000',
            ]),
            'c2'  => User::updateOrCreate(['email' => 'ryan.luza@gmail.com'], [
                'name' => 'Ryan Luza', 'password' => $password, 'role' => 'customer',
                'phone' => '+63 917 202 3342', 'address' => 'Bajada, Davao City, 8000',
            ]),
            'c3'  => User::updateOrCreate(['email' => 'mark.asucan@gmail.com'], [
                'name' => 'Mark Asucan', 'password' => $password, 'role' => 'customer',
                'phone' => '+63 917 303 4453', 'address' => 'Toril District, Davao City, 8000',
            ]),
            'c4'  => User::updateOrCreate(['email' => 'grace.cabilles@gmail.com'], [
                'name' => 'Grace Cabilles', 'password' => $password, 'role' => 'customer',
                'phone' => '+63 917 404 5564', 'address' => 'Ecoland Phase 2, Davao City, 8000',
            ]),
            'c5'  => User::updateOrCreate(['email' => 'ryza.cagabhion@gmail.com'], [
                'name' => 'Ryza Cagabhion', 'password' => $password, 'role' => 'customer',
                'phone' => '+63 917 505 6675', 'address' => 'Buhangin Road, Davao City, 8000',
            ]),
            'c6'  => User::updateOrCreate(['email' => 'joanna.plewa@gmail.com'], [
                'name' => 'Joanna Plewa', 'password' => $password, 'role' => 'customer',
                'phone' => '+63 917 606 7786', 'address' => 'Lanang Commercial Hub, Davao City, 8000',
            ]),
            'c7'  => User::updateOrCreate(['email' => 'joyceanne.muring@gmail.com'], [
                'name' => 'Joyce Ann Muring', 'password' => $password, 'role' => 'customer',
                'phone' => '+63 917 707 8897', 'address' => 'Magsaysay Ave, Davao City, 8000',
            ]),
            'c8'  => User::updateOrCreate(['email' => 'dianne.badlon@gmail.com'], [
                'name' => 'Dianne Badlon', 'password' => $password, 'role' => 'customer',
                'phone' => '+63 917 808 9908', 'address' => 'Obrero District, Davao City, 8000',
            ]),
            'c9'  => User::updateOrCreate(['email' => 'prencess.baldeconza@gmail.com'], [
                'name' => 'Prencess Baldeconza', 'password' => $password, 'role' => 'customer',
                'phone' => '+63 917 909 1129', 'address' => 'Maa, Davao City, 8000',
            ]),
            'c10' => User::updateOrCreate(['email' => 'marykate.silat@gmail.com'], [
                'name' => 'Mary Kate Silat', 'password' => $password, 'role' => 'customer',
                'phone' => '+63 917 010 3340', 'address' => 'Calinan District, Davao City, 8000',
            ]),
        ];

        // ─────────────────────────────────────────────────────────────
        // 4. SEED ALDREN REYES (16 REQUESTS FOR BEAUTIFUL 3-PAGE PAGINATION)
        // ─────────────────────────────────────────────────────────────
        $aldren = User::where('email', 'customer@capaciprint.com')->first();
        if ($aldren) {
            $aldrenDatasets = [
                // 1. Submitted - Today Nov 15
                [
                    'service' => 'Tarpaulin Printing', 'qty' => 1, 'size' => '2 × 3 FT', 'mat' => '10oz Standard Outdoor Tarpaulin',
                    'fin' => 'Folded Edges & 4 Corner Eyelets', 'desc' => 'Storefront Promotional Banner for Weekend Sale',
                    'status' => 'submitted', 'branch' => $branchA, 'has_q' => false, 'has_ord' => false,
                    'created_at' => Carbon::create(2026, 11, 15, 10, 30), 'deadline' => Carbon::create(2026, 11, 18),
                ],
                // 2. Submitted - Today Nov 15
                [
                    'service' => 'Indoor Sticker Printing', 'qty' => 500, 'size' => '2 × 2 IN (Circle)', 'mat' => 'Glossy Sticker Paper',
                    'fin' => 'Gloss UV Varnish & Kiss-Cut Sheet', 'desc' => 'Packaging Jar Labels for Honey Bee Organics',
                    'status' => 'submitted', 'branch' => $branchA, 'has_q' => false, 'has_ord' => false,
                    'created_at' => Carbon::create(2026, 11, 15, 9, 15), 'deadline' => Carbon::create(2026, 11, 19),
                ],
                // 3. Quotation Pending - Nov 14
                [
                    'service' => 'Flyers & Brochures', 'qty' => 250, 'size' => 'A4 (210 × 297 mm)', 'mat' => 'Glossy Paper 150gsm (A4/A3)',
                    'fin' => 'Double-Sided Full Color & Tri-Fold', 'desc' => 'Tri-Fold Product Showcase Catalogs',
                    'status' => 'quotation', 'branch' => $branchA, 'has_q' => true, 'q_status' => 'pending', 'price' => 1250.00,
                    'has_ord' => false, 'created_at' => Carbon::create(2026, 11, 14, 14, 0), 'deadline' => Carbon::create(2026, 11, 20),
                ],
                // 4. Quotation Pending - Nov 14
                [
                    'service' => 'Document Printing', 'qty' => 30, 'size' => 'A4', 'mat' => '80gsm High-White Bond',
                    'fin' => 'Wire-O Binding with Matte Frost Cover', 'desc' => 'Annual Corporate Performance Summary Packets',
                    'status' => 'quotation', 'branch' => $branchC, 'has_q' => true, 'q_status' => 'pending', 'price' => 1800.00,
                    'has_ord' => false, 'created_at' => Carbon::create(2026, 11, 14, 11, 30), 'deadline' => Carbon::create(2026, 11, 21),
                ],
                // 5. Quotation Pending - Nov 13
                [
                    'service' => 'Calling Cards', 'qty' => 200, 'size' => 'Standard 3.5 × 2.0 IN', 'mat' => '300gsm Heavy Cardstock',
                    'fin' => 'Velvet Soft-Touch Lamination', 'desc' => 'Executive Calling Cards with QR Code on Back',
                    'status' => 'quotation', 'branch' => $branchA, 'has_q' => true, 'q_status' => 'pending', 'price' => 900.00,
                    'has_ord' => false, 'created_at' => Carbon::create(2026, 11, 13, 16, 20), 'deadline' => Carbon::create(2026, 11, 19),
                ],
                // 6. In Production (GCash) - Nov 13
                [
                    'service' => 'Outdoor Sticker Printing', 'qty' => 100, 'size' => '3 × 4 IN', 'mat' => 'Weatherproof Vinyl Sticker',
                    'fin' => 'UV Matte Coat & Precision Contour Cut', 'desc' => 'Heavy Equipment Waterproof Fleet Decals',
                    'status' => 'production', 'branch' => $branchB, 'machine' => $mRoland, 'has_q' => true, 'q_status' => 'confirmed',
                    'price' => 1100.00, 'has_ord' => true, 'ord_num' => 'ORD-2026-1001', 'job_num' => 'JOB-2026-1001',
                    'job_status' => 'in_production', 'priority' => 'standard', 'hours' => 3, 'started' => Carbon::now()->subHours(2),
                    'pay_method' => 'GCash', 'pay_ref' => 'Ref No. 917123456789012',
                    'created_at' => Carbon::create(2026, 11, 13, 10, 0), 'deadline' => Carbon::create(2026, 11, 17),
                ],
                // 7. In Production (Cash) - Nov 12
                [
                    'service' => 'Tarpaulin Printing', 'qty' => 1, 'size' => '3 × 4 FT (12.00 sq ft)', 'mat' => '13oz Heavy Duty Glossy Tarpaulin',
                    'fin' => 'Hemmed Edges & 4 Corner Eyelets', 'desc' => 'Store Grand Opening Welcome Banner',
                    'status' => 'production', 'branch' => $branchA, 'machine' => $mRoland, 'has_q' => true, 'q_status' => 'confirmed',
                    'price' => 650.00, 'has_ord' => true, 'ord_num' => 'ORD-2026-1002', 'job_num' => 'JOB-2026-1002',
                    'job_status' => 'in_production', 'priority' => 'standard', 'hours' => 2, 'started' => Carbon::now()->subHours(3),
                    'pay_method' => 'Cash', 'pay_ref' => null,
                    'created_at' => Carbon::create(2026, 11, 12, 13, 45), 'deadline' => Carbon::create(2026, 11, 16),
                ],
                // 8. Quality Checking (Bank Transfer) - Nov 12
                [
                    'service' => 'Flyers & Brochures', 'qty' => 1000, 'size' => 'A5 (148 × 210 mm)', 'mat' => 'Glossy Paper 150gsm (A4/A3)',
                    'fin' => 'Full Color Both Sides & Bundle Banding', 'desc' => 'Restaurant Takeout & Delivery Promo Menus',
                    'status' => 'production', 'branch' => $branchA, 'machine' => $mSpeedmaster, 'has_q' => true, 'q_status' => 'confirmed',
                    'price' => 3200.00, 'has_ord' => true, 'ord_num' => 'ORD-2026-1003', 'job_num' => 'JOB-2026-1003',
                    'job_status' => 'quality_checking', 'priority' => 'standard', 'hours' => 4, 'started' => Carbon::now()->subHours(5),
                    'pay_method' => 'Bank Transfer', 'pay_ref' => 'PAY-2026-100392',
                    'created_at' => Carbon::create(2026, 11, 12, 9, 10), 'deadline' => Carbon::create(2026, 11, 16),
                ],
                // 9. Preparing / Scheduled (Cash) - Nov 11
                [
                    'service' => 'Calling Cards', 'qty' => 100, 'size' => 'Standard 3.5 × 2.0 IN', 'mat' => '350gsm Heavy Board Cardstock',
                    'fin' => 'Matte Soft-Touch & Rounded Corners', 'desc' => 'Staff ID Cards with NFC Tag Area',
                    'status' => 'production', 'branch' => $branchA, 'machine' => $mIndigo, 'has_q' => true, 'q_status' => 'confirmed',
                    'price' => 500.00, 'has_ord' => true, 'ord_num' => 'ORD-2026-1004', 'job_num' => 'JOB-2026-1004',
                    'job_status' => 'preparing', 'priority' => 'standard', 'hours' => 2, 'started' => null,
                    'pay_method' => 'Cash', 'pay_ref' => null,
                    'created_at' => Carbon::create(2026, 11, 11, 15, 30), 'deadline' => Carbon::create(2026, 11, 17),
                ],
                // 10. Preparing (Bank Transfer) - Nov 11
                [
                    'service' => 'Document Printing', 'qty' => 20, 'size' => 'A1 (594 × 841 mm)', 'mat' => '80gsm High-White Technical Bond',
                    'fin' => 'Engineering Trim & Corner Clamp Fold', 'desc' => 'Commercial Building Floor Plans & Schematics',
                    'status' => 'production', 'branch' => $branchC, 'machine' => $mCanon, 'has_q' => true, 'q_status' => 'confirmed',
                    'price' => 1950.00, 'has_ord' => true, 'ord_num' => 'ORD-2026-1005', 'job_num' => 'JOB-2026-1005',
                    'job_status' => 'preparing', 'priority' => 'urgent', 'hours' => 3, 'started' => null,
                    'pay_method' => 'Bank Transfer', 'pay_ref' => 'PAY-2026-100511',
                    'created_at' => Carbon::create(2026, 11, 11, 11, 0), 'deadline' => Carbon::create(2026, 11, 18),
                ],
                // 11. Ready for Pickup (GCash) - Nov 10
                [
                    'service' => 'Outdoor Sticker Printing', 'qty' => 350, 'size' => '2 × 3 IN (Clear)', 'mat' => 'Weatherproof Transparent Vinyl',
                    'fin' => 'UV Gloss Resistant Coating & Sheet Cut', 'desc' => 'Waterproof Beverage Bottle Decals',
                    'status' => 'ready_for_pickup', 'branch' => $branchB, 'machine' => $mSureColor, 'has_q' => true, 'q_status' => 'confirmed',
                    'price' => 1450.00, 'has_ord' => true, 'ord_num' => 'ORD-2026-1006', 'job_num' => 'JOB-2026-1006',
                    'job_status' => 'completed', 'priority' => 'standard', 'hours' => 3, 'started' => Carbon::now()->subHours(10),
                    'completed_at' => Carbon::now()->subHours(2), 'pay_method' => 'GCash', 'pay_ref' => 'Ref No. 917889900112233',
                    'claim_code' => 'CLM-2026-ALDREN-01', 'is_claimed' => false,
                    'created_at' => Carbon::create(2026, 11, 10, 14, 15), 'deadline' => Carbon::create(2026, 11, 15),
                ],
                // 12. Ready for Pickup (Bank Transfer) - Nov 09
                [
                    'service' => 'Tarpaulin Printing', 'qty' => 2, 'size' => '2.75 × 6.5 FT (Roll-Up)', 'mat' => 'Synthetic Non-Curl Matte Film',
                    'fin' => 'Mounted on Aluminum Base Standee', 'desc' => 'Corporate Event Hallway Directional Standees',
                    'status' => 'ready_for_pickup', 'branch' => $branchA, 'machine' => $mRoland, 'has_q' => true, 'q_status' => 'confirmed',
                    'price' => 3600.00, 'has_ord' => true, 'ord_num' => 'ORD-2026-1007', 'job_num' => 'JOB-2026-1007',
                    'job_status' => 'completed', 'priority' => 'standard', 'hours' => 4, 'started' => Carbon::create(2026, 11, 9, 11, 0),
                    'completed_at' => Carbon::create(2026, 11, 9, 16, 0), 'pay_method' => 'Bank Transfer', 'pay_ref' => 'PAY-2026-100744',
                    'claim_code' => 'CLM-2026-ALDREN-02', 'is_claimed' => false,
                    'created_at' => Carbon::create(2026, 11, 9, 10, 20), 'deadline' => Carbon::create(2026, 11, 14),
                ],
                // 13. Completed & Claimed (Cash) - Nov 08
                [
                    'service' => 'Calling Cards', 'qty' => 300, 'size' => 'Standard 3.5 × 2.0 IN', 'mat' => 'Linen Textured Cardstock 280gsm',
                    'fin' => 'Corner Die-Cut & Metallic Silver Edge', 'desc' => 'Founder & Managing Director Business Cards',
                    'status' => 'completed', 'branch' => $branchA, 'machine' => $mIndigo, 'has_q' => true, 'q_status' => 'confirmed',
                    'price' => 1200.00, 'has_ord' => true, 'ord_num' => 'ORD-2026-1008', 'job_num' => 'JOB-2026-1008',
                    'job_status' => 'completed', 'priority' => 'standard', 'hours' => 2, 'started' => Carbon::create(2026, 11, 8, 9, 0),
                    'completed_at' => Carbon::create(2026, 11, 8, 12, 0), 'pay_method' => 'Cash', 'pay_ref' => null,
                    'claim_code' => 'CLM-2026-ALDREN-03', 'is_claimed' => true,
                    'created_at' => Carbon::create(2026, 11, 8, 9, 0), 'deadline' => Carbon::create(2026, 11, 12),
                ],
                // 14. Completed & Claimed (GCash) - Nov 06
                [
                    'service' => 'Flyers & Brochures', 'qty' => 500, 'size' => 'A4 (210 × 297 mm)', 'mat' => 'Glossy Paper 150gsm (A4/A3)',
                    'fin' => 'Double-Sided Full Color Lamination', 'desc' => 'Trade Expo Company Profile Handouts',
                    'status' => 'completed', 'branch' => $branchA, 'machine' => $mSpeedmaster, 'has_q' => true, 'q_status' => 'confirmed',
                    'price' => 2100.00, 'has_ord' => true, 'ord_num' => 'ORD-2026-1009', 'job_num' => 'JOB-2026-1009',
                    'job_status' => 'completed', 'priority' => 'standard', 'hours' => 3, 'started' => Carbon::create(2026, 11, 6, 12, 0),
                    'completed_at' => Carbon::create(2026, 11, 6, 15, 0), 'pay_method' => 'GCash', 'pay_ref' => 'Ref No. 917445566778899',
                    'claim_code' => 'CLM-2026-ALDREN-04', 'is_claimed' => true,
                    'created_at' => Carbon::create(2026, 11, 6, 11, 30), 'deadline' => Carbon::create(2026, 11, 10),
                ],
                // 15. Completed Quote Only - Nov 04
                [
                    'service' => 'Document Printing', 'qty' => 50, 'size' => 'A4', 'mat' => 'Premium Bond Paper 80gsm',
                    'fin' => 'Thermal Perfect Binding with Color Cover', 'desc' => 'Staff Training & Safety Operational Manuals',
                    'status' => 'quotation', 'branch' => $branchC, 'has_q' => true, 'q_status' => 'confirmed', 'price' => 2750.00,
                    'has_ord' => false, 'created_at' => Carbon::create(2026, 11, 4, 14, 0), 'deadline' => Carbon::create(2026, 11, 9),
                ],
                // 16. Completed Quote Only - Nov 02
                [
                    'service' => 'Tarpaulin Printing', 'qty' => 1, 'size' => '4 × 6 FT', 'mat' => '13oz Heavy Duty Glossy Tarpaulin',
                    'fin' => 'Eyelets on 4 Sides & Reinforced Rope', 'desc' => 'Campus Sports Fest Welcome Tarpaulin',
                    'status' => 'quotation', 'branch' => $branchB, 'has_q' => true, 'q_status' => 'confirmed', 'price' => 880.00,
                    'has_ord' => false, 'created_at' => Carbon::create(2026, 11, 2, 8, 30), 'deadline' => Carbon::create(2026, 11, 7),
                ],
            ];

            foreach ($aldrenDatasets as $d) {
                $pr = PrintRequest::create([
                    'user_id'                 => $aldren->id,
                    'service'                 => $d['service'],
                    'quantity'                => $d['qty'],
                    'size'                    => $d['size'],
                    'material'                => $d['mat'],
                    'finishing'               => $d['fin'],
                    'deadline'                => $d['deadline'],
                    'preferred_branch'        => $d['branch']->name,
                    'collection_mode'         => 'pickup',
                    'status'                  => $d['status'],
                    'additional_instructions' => $d['desc'],
                    'created_at'              => $d['created_at'],
                    'updated_at'              => $d['created_at'],
                ]);

                if (!empty($d['has_q'])) {
                    $qNum = str_pad($pr->id, 5, '0', STR_PAD_LEFT);
                    $quote = Quotation::create([
                        'quotation_number' => $qNum,
                        'print_request_id' => $pr->id,
                        'user_id'          => $aldren->id,
                        'base_cost'        => round($d['price'] * 0.60, 2),
                        'material_cost'    => round($d['price'] * 0.25, 2),
                        'finishing_cost'   => round($d['price'] * 0.15, 2),
                        'total_price'      => $d['price'],
                        'valid_until'      => (clone $d['created_at'])->addDays(14),
                        'status'           => $d['q_status'] ?? 'pending',
                        'created_at'       => (clone $d['created_at'])->addHours(1),
                        'updated_at'       => (clone $d['created_at'])->addHours(1),
                    ]);

                    if (!empty($d['has_ord'])) {
                        $order = Order::create([
                            'order_number'         => $d['ord_num'],
                            'print_request_id'     => $pr->id,
                            'quotation_id'         => $quote->id,
                            'user_id'              => $aldren->id,
                            'payment_status'       => 'confirmed',
                            'status'               => $d['status'],
                            'assigned_branch'      => $d['branch']->name,
                            'estimated_completion' => $d['deadline'],
                            'created_at'           => (clone $d['created_at'])->addHours(2),
                            'updated_at'           => (clone $d['created_at'])->addHours(2),
                        ]);

                        Payment::create([
                            'order_id'          => $order->id,
                            'user_id'           => $aldren->id,
                            'amount'            => $d['price'],
                            'payment_method'    => $d['pay_method'],
                            'payment_reference' => $d['pay_ref'],
                            'status'            => 'confirmed',
                            'paid_at'           => (clone $d['created_at'])->addHours(3),
                            'created_at'        => (clone $d['created_at'])->addHours(2),
                            'updated_at'        => (clone $d['created_at'])->addHours(2),
                        ]);

                        ProductionJob::create([
                            'job_number'      => $d['job_num'],
                            'order_id'        => $order->id,
                            'branch_id'       => $d['branch']->id,
                            'machine_id'      => $d['machine']->id ?? 1,
                            'assigned_to'     => $prodOperator->id,
                            'status'          => $d['job_status'],
                            'priority'        => $d['priority'] ?? 'standard',
                            'estimated_hours' => $d['hours'] ?? 3,
                            'delay_reason'    => null,
                            'started_at'      => $d['started'] ?? null,
                            'completed_at'    => $d['completed_at'] ?? null,
                            'created_at'      => (clone $d['created_at'])->addHours(3),
                            'updated_at'      => Carbon::now(),
                        ]);

                        if (!empty($d['claim_code'])) {
                            ClaimReference::create([
                                'order_id'        => $order->id,
                                'user_id'         => $aldren->id,
                                'claim_code'      => $d['claim_code'],
                                'pickup_branch'   => $d['branch']->name,
                                'completion_date' => $d['completed_at'] ?? Carbon::now(),
                                'is_claimed'      => $d['is_claimed'] ?? false,
                                'claimed_at'      => !empty($d['is_claimed']) ? (clone $d['created_at'])->addDays(2) : null,
                            ]);
                        }
                    }
                }
            }

            // Seed Pre-Press Design Proof & Capacity Evaluation on Aldren's Order #6
            $samplePr = PrintRequest::where('user_id', $aldren->id)->where('service', 'Outdoor Sticker Printing')->first();
            if ($samplePr && $designerUser) {
                DesignProof::create([
                    'print_request_id'     => $samplePr->id,
                    'designer_id'          => $designerUser->id,
                    'version'              => 1,
                    'proof_file_name'      => 'fleet_stickers_proof_v1.pdf',
                    'proof_file_path'      => 'proofs/fleet_stickers_proof_v1.pdf',
                    'production_file_name' => 'fleet_stickers_cmyk_press.pdf',
                    'designer_notes'       => 'Contour kiss-cut vector line verified with 2mm bleed allowance.',
                    'customer_feedback'    => 'Approved by customer for vinyl roll press run.',
                    'status'               => 'approved',
                    'approved_at'          => Carbon::now()->subDays(2),
                ]);
            }
            if ($samplePr && $officerUser) {
                CapacityEvaluation::create([
                    'print_request_id'     => $samplePr->id,
                    'branch_id'            => $branchB->id,
                    'evaluated_by'         => $officerUser->id,
                    'machine_score'        => 95,
                    'material_score'       => 94,
                    'employee_score'       => 90,
                    'workload_score'       => 85,
                    'deadline_score'       => 95,
                    'total_score'          => 92,
                    'capacity_status'      => 'qualified',
                    'available_machines'   => 2,
                    'current_workload_pct' => 52.00,
                    'estimated_completion' => Carbon::create(2026, 11, 17),
                    'deadline_feasible'    => true,
                    'evaluation_notes'     => 'Roland TrueVIS large-format eco-solvent plotter has optimal media loading.',
                ]);
                BranchRecommendation::create([
                    'print_request_id'      => $samplePr->id,
                    'recommended_branch_id' => $branchB->id,
                    'created_by'            => $officerUser->id,
                    'recommendation_score'  => 92,
                    'reason'                => 'Branch B specialized in large-format vinyl printing with outdoor UV laminate.',
                    'status'                => 'confirmed',
                ]);
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 5. SCENARIOS FOR THE 10 CUSTOMERS (20 RICH REQUESTS ACROSS WORKFLOW)
        // ─────────────────────────────────────────────────────────────
        $customerScenarios = [
            // ── Customer 1: Jhon Rex Partoza (2 requests) ──
            [
                'customer' => $customers['c1'], 'branch' => $branchA, 'service' => 'Tarpaulin Printing',
                'details' => '3x4 FT Outdoor Fiesta Stage Banner with corner eyelets', 'qty' => 1, 'size' => '3 × 4 FT',
                'mat' => '13oz Heavy Duty Glossy Tarpaulin', 'fin' => 'Folded Edges & 4 Corner Eyelets',
                'price' => 450.00, 'status' => 'submitted', 'has_q' => false, 'has_ord' => false,
                'created_at' => Carbon::create(2026, 11, 15, 8, 30), 'deadline' => Carbon::create(2026, 11, 18),
            ],
            [
                'customer' => $customers['c1'], 'branch' => $branchA, 'service' => 'Calling Cards',
                'details' => 'Personal calling cards with minimalist black foil text', 'qty' => 100, 'size' => 'Standard 3.5 × 2.0 IN',
                'mat' => '300gsm Heavy Cardstock', 'fin' => 'Matte Soft-Touch',
                'price' => 480.00, 'status' => 'quotation', 'has_q' => true, 'q_status' => 'pending', 'has_ord' => false,
                'created_at' => Carbon::create(2026, 11, 14, 15, 0), 'deadline' => Carbon::create(2026, 11, 21),
            ],

            // ── Customer 2: Ryan Luza (2 requests) ──
            [
                'customer' => $customers['c2'], 'branch' => $branchA, 'service' => 'Flyers & Brochures',
                'details' => 'Tri-fold Product Showcase Catalogs for retail store launch', 'qty' => 500, 'size' => 'A4 (210 × 297 mm)',
                'mat' => 'Glossy Paper 150gsm (A4/A3)', 'fin' => 'Double-Sided Full Color & Tri-Fold Creasing',
                'price' => 2450.00, 'status' => 'quotation', 'has_q' => true, 'q_status' => 'pending', 'has_ord' => false,
                'created_at' => Carbon::create(2026, 11, 14, 11, 15), 'deadline' => Carbon::create(2026, 11, 20),
            ],
            [
                'customer' => $customers['c2'], 'branch' => $branchA, 'machine' => $mSpeedmaster, 'service' => 'Document Printing',
                'details' => 'Product spec sheets and user manual handouts', 'qty' => 200, 'size' => 'A4',
                'mat' => '80gsm High-White Bond', 'fin' => 'Corner Stapled & Folded',
                'price' => 1150.00, 'status' => 'production', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1108', 'job_num' => 'JOB-2026-1108',
                'job_status' => 'in_production', 'priority' => 'standard', 'hours' => 3, 'started' => Carbon::now()->subHours(2),
                'pay_method' => 'GCash', 'pay_ref' => 'Ref No. 917202334211009',
                'created_at' => Carbon::create(2026, 11, 11, 13, 0), 'deadline' => Carbon::create(2026, 11, 16),
            ],

            // ── Customer 3: Mark Asucan (2 requests) ──
            [
                'customer' => $customers['c3'], 'branch' => $branchB, 'machine' => $mRoland, 'service' => 'Outdoor Sticker Printing',
                'details' => 'Die-cut waterproof food truck emblem vinyl decals', 'qty' => 300, 'size' => '2.5 × 2.5 IN (Die-Cut)',
                'mat' => 'Weatherproof Vinyl Sticker with UV Coat', 'fin' => 'Precision Contour Die-Cut',
                'price' => 1350.00, 'status' => 'production', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1101', 'job_num' => 'JOB-2026-1101',
                'job_status' => 'in_production', 'priority' => 'rush', 'hours' => 3, 'started' => Carbon::now()->subHours(2),
                'pay_method' => 'GCash', 'pay_ref' => 'Ref No. 917303445312345',
                'created_at' => Carbon::create(2026, 11, 12, 9, 0), 'deadline' => Carbon::create(2026, 11, 16),
            ],
            [
                'customer' => $customers['c3'], 'branch' => $branchB, 'machine' => $mRoland, 'service' => 'Tarpaulin Printing',
                'details' => 'Food truck weekend price board banner', 'qty' => 1, 'size' => '2 × 3 FT',
                'mat' => '10oz Standard Outdoor Tarpaulin', 'fin' => 'Folded Edges & 4 Corner Eyelets',
                'price' => 450.00, 'status' => 'completed', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1109', 'job_num' => 'JOB-2026-1109',
                'job_status' => 'completed', 'priority' => 'standard', 'hours' => 2, 'started' => Carbon::create(2026, 11, 6, 9, 0),
                'completed_at' => Carbon::create(2026, 11, 6, 12, 0), 'pay_method' => 'Cash', 'pay_ref' => null,
                'created_at' => Carbon::create(2026, 11, 6, 8, 30), 'deadline' => Carbon::create(2026, 11, 9),
            ],

            // ── Customer 4: Grace Cabilles (2 requests) ──
            [
                'customer' => $customers['c4'], 'branch' => $branchA, 'machine' => $mIndigo, 'service' => 'Calling Cards',
                'details' => 'Corporate executive calling cards with velvet matte lamination', 'qty' => 200, 'size' => 'Standard 3.5 × 2.0 IN',
                'mat' => '350gsm Heavy Board Cardstock', 'fin' => 'Matte Soft-Touch & Spot Gloss Foil',
                'price' => 850.00, 'status' => 'ready_for_pickup', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1102', 'job_num' => 'JOB-2026-1102',
                'job_status' => 'completed', 'priority' => 'standard', 'hours' => 2, 'started' => Carbon::now()->subHours(8),
                'completed_at' => Carbon::now()->subHours(2), 'pay_method' => 'Cash', 'pay_ref' => null,
                'claim_code' => 'CLM-2026-GRACE', 'is_claimed' => false,
                'created_at' => Carbon::create(2026, 11, 10, 13, 20), 'deadline' => Carbon::create(2026, 11, 15),
            ],
            [
                'customer' => $customers['c4'], 'branch' => $branchA, 'machine' => $mIndigo, 'service' => 'Indoor Sticker Printing',
                'details' => 'Gold foil cosmetic skincare jar product packaging labels', 'qty' => 400, 'size' => '2 × 3 IN (Oval)',
                'mat' => 'Glossy Self-Adhesive Sticker Paper', 'fin' => 'Gloss UV Varnish & Roll Slitting',
                'price' => 1650.00, 'status' => 'production', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1110', 'job_num' => 'JOB-2026-1110',
                'job_status' => 'quality_checking', 'priority' => 'standard', 'hours' => 3, 'started' => Carbon::now()->subHours(4),
                'pay_method' => 'GCash', 'pay_ref' => 'Ref No. 917404556422334',
                'created_at' => Carbon::create(2026, 11, 13, 9, 30), 'deadline' => Carbon::create(2026, 11, 17),
            ],

            // ── Customer 5: Ryza Cagabhion (2 requests) ──
            [
                'customer' => $customers['c5'], 'branch' => $branchA, 'machine' => $mRoland, 'service' => 'Tarpaulin Printing',
                'details' => 'Retractable pull-up aluminum standee banner for medical convention', 'qty' => 2, 'size' => '2.75 × 6.5 FT (Roll-Up)',
                'mat' => 'Synthetic Non-Curl Matte Film 220gsm', 'fin' => 'Mounted on Aluminum Base with Padded Carry Bag',
                'price' => 3600.00, 'status' => 'completed', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1103', 'job_num' => 'JOB-2026-1103',
                'job_status' => 'completed', 'priority' => 'standard', 'hours' => 4, 'started' => Carbon::create(2026, 11, 5, 8, 0),
                'completed_at' => Carbon::create(2026, 11, 5, 14, 0), 'pay_method' => 'Bank Transfer', 'pay_ref' => 'PAY-2026-847291',
                'claim_code' => 'CLM-2026-RYZA', 'is_claimed' => true,
                'created_at' => Carbon::create(2026, 11, 5, 10, 0), 'deadline' => Carbon::create(2026, 11, 8),
            ],
            [
                'customer' => $customers['c5'], 'branch' => $branchA, 'machine' => $mSpeedmaster, 'service' => 'Flyers & Brochures',
                'details' => 'Medical convention abstract program booklets', 'qty' => 600, 'size' => 'A5 (148 × 210 mm)',
                'mat' => 'Glossy Paper 150gsm (A4/A3)', 'fin' => 'Full Color Saddle Stitch Booklet',
                'price' => 2550.00, 'status' => 'ready_for_pickup', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1111', 'job_num' => 'JOB-2026-1111',
                'job_status' => 'completed', 'priority' => 'standard', 'hours' => 4, 'started' => Carbon::create(2026, 11, 9, 8, 0),
                'completed_at' => Carbon::create(2026, 11, 9, 15, 0), 'pay_method' => 'Bank Transfer', 'pay_ref' => 'PAY-2026-RYZA-HANDOUT',
                'claim_code' => 'CLM-2026-RYZA-2', 'is_claimed' => false,
                'created_at' => Carbon::create(2026, 11, 9, 8, 0), 'deadline' => Carbon::create(2026, 11, 14),
            ],

            // ── Customer 6: Joanna Plewa (2 requests) ──
            [
                'customer' => $customers['c6'], 'branch' => $branchA, 'machine' => $mSpeedmaster, 'service' => 'Indoor Sticker Printing',
                'details' => 'High-gloss cosmetic skincare jar product packaging labels', 'qty' => 1000, 'size' => '2 × 3 IN (Oval)',
                'mat' => 'Glossy Self-Adhesive Sticker Paper', 'fin' => 'Gloss UV Varnish & Roll Slitting',
                'price' => 2800.00, 'status' => 'production', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1104', 'job_num' => 'JOB-2026-1104',
                'job_status' => 'quality_checking', 'priority' => 'standard', 'hours' => 5, 'started' => Carbon::now()->subHours(4),
                'pay_method' => 'GCash', 'pay_ref' => 'Ref No. 917606778643210',
                'created_at' => Carbon::create(2026, 11, 13, 11, 40), 'deadline' => Carbon::create(2026, 11, 17),
            ],
            [
                'customer' => $customers['c6'], 'branch' => $branchA, 'service' => 'Calling Cards',
                'details' => 'Personal aesthetic appointment booking cards with embossed logo', 'qty' => 150, 'size' => 'Standard 3.5 × 2.0 IN',
                'mat' => 'Metallic Linen Cardstock 300gsm', 'fin' => 'Matte Soft-Touch & Spot Emboss',
                'price' => 720.00, 'status' => 'submitted', 'has_q' => false, 'has_ord' => false,
                'created_at' => Carbon::create(2026, 11, 15, 11, 0), 'deadline' => Carbon::create(2026, 11, 20),
            ],

            // ── Customer 7: Joyce Ann Muring (2 requests) ──
            [
                'customer' => $customers['c7'], 'branch' => $branchC, 'machine' => $mCanon, 'service' => 'Document Printing',
                'details' => 'Construction structural blueprints for subdivision development', 'qty' => 50, 'size' => 'A1 (594 × 841 mm)',
                'mat' => '80gsm High-White Technical Bond', 'fin' => 'Trimmed, Corner Stapled & Heavy Stock Cover',
                'price' => 3250.00, 'status' => 'production', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1105', 'job_num' => 'JOB-2026-1105',
                'job_status' => 'preparing', 'priority' => 'urgent', 'hours' => 4, 'started' => null,
                'pay_method' => 'Bank Transfer', 'pay_ref' => 'PAY-2026-551920',
                'created_at' => Carbon::create(2026, 11, 11, 14, 0), 'deadline' => Carbon::create(2026, 11, 18),
            ],
            [
                'customer' => $customers['c7'], 'branch' => $branchB, 'machine' => $mSureColor, 'service' => 'Outdoor Sticker Printing',
                'details' => 'Subdivision construction site warning & safety helmet stickers', 'qty' => 200, 'size' => '3 × 3 IN (Circle)',
                'mat' => 'Weatherproof Vinyl Sticker', 'fin' => 'UV Matte Coat & Precision Cut',
                'price' => 1100.00, 'status' => 'production', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1112', 'job_num' => 'JOB-2026-1112',
                'job_status' => 'in_production', 'priority' => 'standard', 'hours' => 3, 'started' => Carbon::now()->subHours(2),
                'pay_method' => 'Cash', 'pay_ref' => null,
                'created_at' => Carbon::create(2026, 11, 12, 10, 0), 'deadline' => Carbon::create(2026, 11, 17),
            ],

            // ── Customer 8: Dianne Badlon (2 requests) ──
            [
                'customer' => $customers['c8'], 'branch' => $branchA, 'machine' => $mIndigo, 'service' => 'Calling Cards',
                'details' => 'Wedding formal invitation cards with gold hot foil embossing', 'qty' => 150, 'size' => '5 × 7 IN',
                'mat' => 'Metallic Linen Cardstock 300gsm', 'fin' => 'Gold Foil Stamping on Bride & Groom Names',
                'price' => 1950.00, 'status' => 'submitted', 'has_q' => false, 'has_ord' => false,
                'created_at' => Carbon::create(2026, 11, 15, 12, 10), 'deadline' => Carbon::create(2026, 11, 22),
            ],
            [
                'customer' => $customers['c8'], 'branch' => $branchA, 'service' => 'Flyers & Brochures',
                'details' => 'Wedding reception program & table menu inserts', 'qty' => 150, 'size' => 'A5 (148 × 210 mm)',
                'mat' => 'Glossy Paper 150gsm (A4/A3)', 'fin' => 'Double-Sided Color Printing & Scored Fold',
                'price' => 950.00, 'status' => 'quotation', 'has_q' => true, 'q_status' => 'pending', 'has_ord' => false,
                'created_at' => Carbon::create(2026, 11, 14, 16, 0), 'deadline' => Carbon::create(2026, 11, 21),
            ],

            // ── Customer 9: Prencess Baldeconza (2 requests) ──
            [
                'customer' => $customers['c9'], 'branch' => $branchC, 'machine' => $mCanon, 'service' => 'Flyers & Brochures',
                'details' => 'Department store promotional catalogs and price flyers', 'qty' => 750, 'size' => 'A4 (210 × 297 mm)',
                'mat' => 'Glossy Paper 150gsm (A4/A3)', 'fin' => 'Full Color Folding & Bundle Shrinkwrap',
                'price' => 3800.00, 'status' => 'production', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1106', 'job_num' => 'JOB-2026-1106',
                'job_status' => 'delayed', 'delay_reason' => 'Substrate feeder roller calibration & media realignment',
                'priority' => 'rush', 'hours' => 5, 'started' => Carbon::now()->subHours(5),
                'pay_method' => 'Cash', 'pay_ref' => null,
                'created_at' => Carbon::create(2026, 11, 12, 8, 45), 'deadline' => Carbon::create(2026, 11, 15), // Due today!
            ],
            [
                'customer' => $customers['c9'], 'branch' => $branchB, 'machine' => $mRoland, 'service' => 'Tarpaulin Printing',
                'details' => 'Store Grand Opening Outdoor Roadside Banner', 'qty' => 1, 'size' => '4 × 6 FT',
                'mat' => '13oz Heavy Duty Glossy Tarpaulin', 'fin' => 'Eyelets on all 4 corners and center folds',
                'price' => 1350.00, 'status' => 'ready_for_pickup', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1113', 'job_num' => 'JOB-2026-1113',
                'job_status' => 'completed', 'priority' => 'standard', 'hours' => 3, 'started' => Carbon::create(2026, 11, 9, 9, 0),
                'completed_at' => Carbon::create(2026, 11, 9, 14, 0), 'pay_method' => 'Bank Transfer', 'pay_ref' => 'PAY-2026-BALDE-441',
                'claim_code' => 'CLM-2026-PRENCESS', 'is_claimed' => false,
                'created_at' => Carbon::create(2026, 11, 9, 8, 30), 'deadline' => Carbon::create(2026, 11, 13),
            ],

            // ── Customer 10: Mary Kate Silat (2 requests) ──
            [
                'customer' => $customers['c10'], 'branch' => $branchB, 'machine' => $mSureColor, 'service' => 'Outdoor Sticker Printing',
                'details' => 'Transparent waterproof glass bottle labels with white ink underbase', 'qty' => 250, 'size' => '2 × 3 IN (Clear)',
                'mat' => 'Weatherproof Transparent Vinyl Film', 'fin' => 'UV Gloss Resistant Coating & Sheet Cut',
                'price' => 1650.00, 'status' => 'ready_for_pickup', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1107', 'job_num' => 'JOB-2026-1107',
                'job_status' => 'completed', 'priority' => 'standard', 'hours' => 3, 'started' => Carbon::now()->subHours(6),
                'completed_at' => Carbon::now()->subHours(1), 'pay_method' => 'GCash', 'pay_ref' => 'Ref No. 917010334055443',
                'claim_code' => 'CLM-2026-KATE', 'is_claimed' => false,
                'created_at' => Carbon::create(2026, 11, 9, 16, 0), 'deadline' => Carbon::create(2026, 11, 14),
            ],
            [
                'customer' => $customers['c10'], 'branch' => $branchA, 'machine' => $mIndigo, 'service' => 'Calling Cards',
                'details' => 'Studio boutique clothing hangtags and care instruction cards', 'qty' => 200, 'size' => 'Standard 3.5 × 2.0 IN',
                'mat' => '300gsm Heavy Cardstock', 'fin' => 'Hole Punched with Matte Finish',
                'price' => 750.00, 'status' => 'completed', 'has_q' => true, 'q_status' => 'confirmed',
                'has_ord' => true, 'ord_num' => 'ORD-2026-1114', 'job_num' => 'JOB-2026-1114',
                'job_status' => 'completed', 'priority' => 'standard', 'hours' => 2, 'started' => Carbon::create(2026, 11, 4, 10, 0),
                'completed_at' => Carbon::create(2026, 11, 4, 13, 0), 'pay_method' => 'Cash', 'pay_ref' => null,
                'created_at' => Carbon::create(2026, 11, 4, 9, 30), 'deadline' => Carbon::create(2026, 11, 8),
            ],
        ];

        // ─────────────────────────────────────────────────────────────
        // 6. EXECUTE SEEDING OF THE 10 CUSTOMERS
        // ─────────────────────────────────────────────────────────────
        foreach ($customerScenarios as $item) {
            $customer = $item['customer'];
            $branch   = $item['branch'];
            $machine  = $item['machine'] ?? $mSpeedmaster;

            // A. Print Request
            $pr = PrintRequest::create([
                'user_id'                 => $customer->id,
                'service'                 => $item['service'],
                'quantity'                => $item['qty'],
                'size'                    => $item['size'],
                'material'                => $item['mat'],
                'finishing'               => $item['fin'],
                'deadline'                => $item['deadline'],
                'preferred_branch'        => $branch->name,
                'collection_mode'         => 'pickup',
                'status'                  => $item['status'],
                'additional_instructions' => $item['details'],
                'created_at'              => $item['created_at'],
                'updated_at'              => $item['created_at'],
            ]);

            // B. Quotation
            if ($item['has_q']) {
                $quoteNum = str_pad($pr->id, 5, '0', STR_PAD_LEFT);
                $quotation = Quotation::create([
                    'quotation_number' => $quoteNum,
                    'print_request_id' => $pr->id,
                    'user_id'          => $customer->id,
                    'base_cost'        => round($item['price'] * 0.60, 2),
                    'material_cost'    => round($item['price'] * 0.25, 2),
                    'finishing_cost'   => round($item['price'] * 0.15, 2),
                    'total_price'      => $item['price'],
                    'valid_until'      => (clone $item['created_at'])->addDays(14),
                    'status'           => $item['q_status'] ?? 'pending',
                    'created_at'       => (clone $item['created_at'])->addHours(1),
                    'updated_at'       => (clone $item['created_at'])->addHours(1),
                ]);
            }

            // C. Order & Payment
            if (!empty($item['has_ord']) && isset($quotation)) {
                $order = Order::create([
                    'order_number'         => $item['ord_num'],
                    'print_request_id'     => $pr->id,
                    'quotation_id'         => $quotation->id,
                    'user_id'              => $customer->id,
                    'payment_status'       => 'confirmed',
                    'status'               => $item['status'],
                    'assigned_branch'      => $branch->name,
                    'estimated_completion' => $item['deadline'],
                    'created_at'           => (clone $item['created_at'])->addHours(2),
                    'updated_at'           => (clone $item['created_at'])->addHours(2),
                ]);

                Payment::create([
                    'order_id'          => $order->id,
                    'user_id'           => $customer->id,
                    'amount'            => $item['price'],
                    'payment_method'    => $item['pay_method'],
                    'payment_reference' => $item['pay_ref'],
                    'status'            => 'confirmed',
                    'paid_at'           => (clone $item['created_at'])->addHours(3),
                    'created_at'        => (clone $item['created_at'])->addHours(2),
                    'updated_at'        => (clone $item['created_at'])->addHours(2),
                ]);

                // D. Production Job
                ProductionJob::create([
                    'job_number'      => $item['job_num'],
                    'order_id'        => $order->id,
                    'branch_id'       => $branch->id,
                    'machine_id'      => $machine->id ?? 1,
                    'assigned_to'     => $prodOperator->id,
                    'status'          => $item['job_status'],
                    'priority'        => $item['priority'],
                    'estimated_hours' => $item['hours'],
                    'delay_reason'    => $item['delay_reason'] ?? null,
                    'started_at'      => $item['started'] ?? null,
                    'completed_at'    => $item['completed_at'] ?? null,
                    'created_at'      => (clone $item['created_at'])->addHours(3),
                    'updated_at'      => Carbon::now(),
                ]);

                // E. Claim Reference
                if (!empty($item['claim_code'])) {
                    ClaimReference::create([
                        'order_id'        => $order->id,
                        'user_id'         => $customer->id,
                        'claim_code'      => $item['claim_code'],
                        'pickup_branch'   => $branch->name,
                        'completion_date' => $item['completed_at'] ?? Carbon::now(),
                        'is_claimed'      => $item['is_claimed'] ?? false,
                        'claimed_at'      => !empty($item['is_claimed']) ? (clone $item['created_at'])->addDays(3) : null,
                    ]);
                }
            }
        }

        $this->command->info("Successfully seeded rich November 2026 data with clean pagination across all tables!");
    }
}
