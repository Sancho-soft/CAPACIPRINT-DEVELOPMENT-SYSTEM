<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use App\Models\Machine;
use App\Models\Employee;
use App\Models\Material;
use App\Models\BranchInventory;
use App\Models\PricingRule;
use App\Models\PrintRequest;
use App\Models\Quotation;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductionJob;
use App\Models\CapacityEvaluation;
use App\Models\BranchRecommendation;
use App\Models\StockMovement;
use App\Models\PurchaseRequest;
use App\Models\DesignProof;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OperationalSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────────
        // 1. Core Users Lookup
        // ─────────────────────────────────────────────────────────────
        $customerUser = User::where('email', 'customer@capaciprint.com')->first();
        $staffUser    = User::where('email', 'staff@capaciprint.com')->first();
        $designerUser = User::where('email', 'designer@capaciprint.com')->first();
        $managerUser  = User::where('email', 'manager@capaciprint.com')->first();
        $officerUser  = User::where('email', 'officer@capaciprint.com')->first();
        $prodUser     = User::where('email', 'production@capaciprint.com')->first();
        $invUser      = User::where('email', 'inventory@capaciprint.com')->first();
        $adminUser    = User::where('email', 'admin@capaciprint.com')->first();
        $mgmtUser     = User::where('email', 'management@capaciprint.com')->first()
                     ?? User::where('email', 'owner@capaciprint.com')->first();

        // ─────────────────────────────────────────────────────────────
        // 2. Multi-Branch Facilities
        // ─────────────────────────────────────────────────────────────
        $branchA = Branch::updateOrCreate(
            ['name' => 'Morning Star Printing Press'],
            [
                'location'       => 'Branch A',
                'address'        => '132 Bolton Street, Poblacion District, Davao City, 8000',
                'phone'          => '+63 917 111 2233',
                'manager_name'   => $managerUser->name ?? 'Niere Lupogan',
                'status'         => 'active',
                'max_daily_jobs' => 85,
            ]
        );

        $branchB = Branch::updateOrCreate(
            ['name' => 'Morning Star Printing Network'],
            [
                'location'       => 'Branch B',
                'address'        => 'Ground Floor, The Paper Tree Building, Bolton Street, Barangay 35-D, Poblacion District, Davao City, 8000',
                'phone'          => '+63 917 222 3344',
                'manager_name'   => $managerUser->name ?? 'Niere Lupogan',
                'status'         => 'active',
                'max_daily_jobs' => 60,
            ]
        );

        $branchC = Branch::updateOrCreate(
            ['name' => 'Green Heart Printing Hub'],
            [
                'location'       => 'Branch C',
                'address'        => 'A. Pichon Street corner Magallanes Street, Poblacion District, Davao City, 8000',
                'phone'          => '+63 917 333 4455',
                'manager_name'   => $managerUser->name ?? 'Niere Lupogan',
                'status'         => 'active',
                'max_daily_jobs' => 50,
            ]
        );

        $branches = [$branchA, $branchB, $branchC];

        // ─────────────────────────────────────────────────────────────
        // 3. Machine Equipment Fleet
        // ─────────────────────────────────────────────────────────────
        $m1 = Machine::updateOrCreate(
            ['name' => 'Heidelberg Speedmaster XL 106', 'branch_id' => $branchA->id],
            ['type' => 'Offset Press', 'model' => 'XL 106-6+L', 'status' => 'available', 'jobs_per_day_capacity' => 18]
        );

        $m2 = Machine::updateOrCreate(
            ['name' => 'HP Indigo 12000 Digital Press', 'branch_id' => $branchA->id],
            ['type' => 'Digital Press', 'model' => 'Indigo 12000', 'status' => 'available', 'jobs_per_day_capacity' => 15]
        );

        $m3 = Machine::updateOrCreate(
            ['name' => 'Roland Soljet Pro 4 XR-640', 'branch_id' => $branchB->id],
            ['type' => 'Large Format', 'model' => 'XR-640 Eco-Solvent', 'status' => 'available', 'jobs_per_day_capacity' => 10]
        );

        $m4 = Machine::updateOrCreate(
            ['name' => 'Epson SureColor S80600', 'branch_id' => $branchB->id],
            ['type' => 'Signage & Vinyl', 'model' => 'SC-S80600', 'status' => 'available', 'jobs_per_day_capacity' => 12]
        );

        $m5 = Machine::updateOrCreate(
            ['name' => 'Canon ImagePRESS C10010VP', 'branch_id' => $branchC->id],
            ['type' => 'Digital Press', 'model' => 'C10010VP', 'status' => 'available', 'jobs_per_day_capacity' => 14]
        );

        $m6 = Machine::updateOrCreate(
            ['name' => 'Konica Minolta AccurioPress C4080', 'branch_id' => $branchC->id],
            ['type' => 'Color Digital', 'model' => 'C4080', 'status' => 'available', 'jobs_per_day_capacity' => 10]
        );

        // ─────────────────────────────────────────────────────────────
        // 4. Employee Roster
        // ─────────────────────────────────────────────────────────────
        if ($prodUser) {
            Employee::updateOrCreate(
                ['user_id' => $prodUser->id],
                ['branch_id' => $branchA->id, 'name' => $prodUser->name, 'position' => 'Senior Press Operator', 'availability_status' => 'available']
            );
        }
        if ($officerUser) {
            Employee::updateOrCreate(
                ['user_id' => $officerUser->id],
                ['branch_id' => $branchA->id, 'name' => $officerUser->name, 'position' => 'Lead Production Planner', 'availability_status' => 'available']
            );
        }
        if ($designerUser) {
            Employee::updateOrCreate(
                ['user_id' => $designerUser->id],
                ['branch_id' => $branchA->id, 'name' => $designerUser->name, 'position' => 'Pre-Press Graphic Specialist', 'availability_status' => 'available']
            );
        }
        if ($invUser) {
            Employee::updateOrCreate(
                ['user_id' => $invUser->id],
                ['branch_id' => $branchA->id, 'name' => $invUser->name, 'position' => 'Materials & Logistics Officer', 'availability_status' => 'available']
            );
        }
        if ($staffUser) {
            Employee::updateOrCreate(
                ['user_id' => $staffUser->id],
                ['branch_id' => $branchA->id, 'name' => $staffUser->name, 'position' => 'Customer Relations Specialist', 'availability_status' => 'available']
            );
        }

        // ─────────────────────────────────────────────────────────────
        // 5. Materials Catalog (8 Key Substrates & Consumables)
        // ─────────────────────────────────────────────────────────────
        $materials = [
            'matPaper' => Material::updateOrCreate(
                ['name' => 'Glossy Paper 150gsm (A4/A3)'],
                ['type' => 'paper', 'unit' => 'reams', 'description' => 'Premium high-gloss coated art paper for flyers', 'is_active' => true]
            ),
            'matCardstock' => Material::updateOrCreate(
                ['name' => 'Matte Cardstock 220gsm'],
                ['type' => 'paper', 'unit' => 'sheets', 'description' => 'Rigid matte substrate for calling cards and booklet covers', 'is_active' => true]
            ),
            'matBond' => Material::updateOrCreate(
                ['name' => 'Premium Bond Paper 80gsm'],
                ['type' => 'paper', 'unit' => 'reams', 'description' => 'High-opacity office documentation and legal print paper', 'is_active' => true]
            ),
            'matCyanToner' => Material::updateOrCreate(
                ['name' => 'CMYK Cyan Toner Cartridge'],
                ['type' => 'ink', 'unit' => 'cartridges', 'description' => 'High-capacity Cyan micro-toner for digital press fleet', 'is_active' => true]
            ),
            'matBlackToner' => Material::updateOrCreate(
                ['name' => 'CMYK Black Toner High Yield'],
                ['type' => 'ink', 'unit' => 'cartridges', 'description' => 'Ultra-dense black toner formulation for crisp text and imagery', 'is_active' => true]
            ),
            'matTarpaulin' => Material::updateOrCreate(
                ['name' => 'Flex Tarpaulin Banner Media 13oz'],
                ['type' => 'paper', 'unit' => 'rolls', 'description' => 'Weatherproof heavy outdoor tarpaulin fabric', 'is_active' => true]
            ),
            'matVinylSticker' => Material::updateOrCreate(
                ['name' => 'Gloss Vinyl Waterproof Sticker Roll'],
                ['type' => 'paper', 'unit' => 'rolls', 'description' => 'Permanent adhesive tear-proof vinyl substrate', 'is_active' => true]
            ),
            'matLamination' => Material::updateOrCreate(
                ['name' => 'Thermal Gloss Lamination Film 30mic'],
                ['type' => 'lamination', 'unit' => 'rolls', 'description' => 'Anti-scratch protective thermal sealing lamination film', 'is_active' => true]
            ),
        ];

        // ─────────────────────────────────────────────────────────────
        // 6. Branch Inventory Balances (Healthy, Low Stock & Out of Stock)
        // ─────────────────────────────────────────────────────────────
        foreach ($branches as $b) {
            foreach ($materials as $key => $mat) {
                $qty = rand(80, 240);
                $status = 'available';

                // Specific realistic inventory bottlenecks:
                if ($b->id === $branchB->id && $key === 'matCyanToner') {
                    $qty = 12; // Below 30 minimum
                    $status = 'low_stock';
                } elseif ($b->id === $branchA->id && $key === 'matLamination') {
                    $qty = 8; // Below 25 minimum
                    $status = 'low_stock';
                } elseif ($b->id === $branchC->id && $key === 'matBlackToner') {
                    $qty = 0; // Completely depleted
                    $status = 'out_of_stock';
                }

                BranchInventory::updateOrCreate(
                    ['branch_id' => $b->id, 'material_id' => $mat->id],
                    [
                        'quantity'      => $qty,
                        'minimum_stock' => in_array($mat->type, ['ink', 'lamination']) ? 25 : 50,
                        'status'        => $status,
                        'last_updated'  => now(),
                    ]
                );
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 7. Inventory Stock Movements Ledger
        // ─────────────────────────────────────────────────────────────
        StockMovement::firstOrCreate(
            ['reference' => 'SM-RESTOCK-001'],
            [
                'branch_id'     => $branchA->id,
                'material_id'   => $materials['matPaper']->id,
                'user_id'       => $invUser->id ?? null,
                'quantity'      => 50.00,
                'movement_type' => 'stock_in',
                'movement_date' => now()->subDays(2),
                'reason'        => 'Supplier Bulk Delivery PO-8821',
                'remarks'       => 'Passed humidity inspection and stored in Zone 1.',
            ]
        );

        StockMovement::firstOrCreate(
            ['reference' => 'SM-RESTOCK-002'],
            [
                'branch_id'     => $branchB->id,
                'material_id'   => $materials['matTarpaulin']->id,
                'user_id'       => $invUser->id ?? null,
                'quantity'      => 15.00,
                'movement_type' => 'stock_in',
                'movement_date' => now()->subDays(3),
                'reason'        => 'Restock for outdoor election campaign banners',
                'remarks'       => 'Verified roll length and thickness.',
            ]
        );

        StockMovement::firstOrCreate(
            ['reference' => 'SM-DEDUCT-001'],
            [
                'branch_id'     => $branchA->id,
                'material_id'   => $materials['matCardstock']->id,
                'user_id'       => $invUser->id ?? null,
                'quantity'      => 120.00,
                'movement_type' => 'stock_out',
                'movement_date' => now()->subDay(),
                'reason'        => 'Press Floor Allocation for ORD-SEED001',
                'remarks'       => 'Dispatched to Heidelberg XL 106 press operator.',
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // 8. Purchase Requisitions (Procurement Queue for Exec Approval)
        // ─────────────────────────────────────────────────────────────
        PurchaseRequest::updateOrCreate(
            ['notes' => 'Urgent replenishment for depleted Branch C toner stocks'],
            [
                'branch_id'    => $branchC->id,
                'requested_by' => $invUser->id ?? null,
                'material_id'  => $materials['matBlackToner']->id,
                'quantity'     => 15,
                'unit_cost'    => 2100.00,
                'total_amount' => 31500.00,
                'status'       => 'pending',
                'created_at'   => now()->subHours(8),
            ]
        );

        PurchaseRequest::updateOrCreate(
            ['notes' => 'Restocking thermal lamination rolls for Branch A high-volume production'],
            [
                'branch_id'    => $branchA->id,
                'requested_by' => $invUser->id ?? null,
                'material_id'  => $materials['matLamination']->id,
                'quantity'     => 20,
                'unit_cost'    => 1450.00,
                'total_amount' => 29000.00,
                'status'       => 'pending',
                'created_at'   => now()->subDays(1),
            ]
        );

        PurchaseRequest::updateOrCreate(
            ['notes' => 'Glossy substrate scheduled quarterly restock for Branch B'],
            [
                'branch_id'    => $branchB->id,
                'requested_by' => $invUser->id ?? null,
                'material_id'  => $materials['matPaper']->id,
                'quantity'     => 40,
                'unit_cost'    => 620.00,
                'total_amount' => 24800.00,
                'status'       => 'approved',
                'created_at'   => now()->subDays(4),
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // 9. Pricing Rules Matrix
        // ─────────────────────────────────────────────────────────────
        PricingRule::updateOrCreate(
            ['service' => 'Document Printing', 'size' => 'A4'],
            ['base_rate' => 3.50, 'material_rate' => 1.50, 'finishing_rate' => 0.50, 'is_active' => true]
        );
        PricingRule::updateOrCreate(
            ['service' => 'Flyers & Brochures', 'size' => 'A4'],
            ['base_rate' => 5.00, 'material_rate' => 2.50, 'finishing_rate' => 1.00, 'is_active' => true]
        );
        PricingRule::updateOrCreate(
            ['service' => 'Tarpaulin Banner', 'size' => '3x4 ft'],
            ['base_rate' => 25.00, 'material_rate' => 15.00, 'finishing_rate' => 5.00, 'is_active' => true]
        );
        PricingRule::updateOrCreate(
            ['service' => 'Calling Cards', 'size' => 'Standard Box'],
            ['base_rate' => 150.00, 'material_rate' => 50.00, 'finishing_rate' => 25.00, 'is_active' => true]
        );
        PricingRule::updateOrCreate(
            ['service' => 'Stickers & Labels', 'size' => 'A4 Sheet'],
            ['base_rate' => 45.00, 'material_rate' => 20.00, 'finishing_rate' => 10.00, 'is_active' => true]
        );

        // Note: Customer Print Requests, Quotations, Orders, Payments, and Production Jobs
        // are centrally seeded in JobOrderSeeder.php for realistic multi-stage demonstration.

        // ─────────────────────────────────────────────────────────────
        // 10. System Audit Log Entries (Admin Jessie Revalde)
        // ─────────────────────────────────────────────────────────────
        $auditSamples = [
            ['event' => 'user.login', 'module' => 'Authentication', 'description' => 'User Justine Bieber (management) logged in from internal network.'],
            ['event' => 'order.routed', 'module' => 'Capacity Routing', 'description' => 'Automated routing algorithm assigned Job #JOB-2026-121 to Branch A.'],
            ['event' => 'quotation.confirmed', 'module' => 'Sales', 'description' => 'Customer Aldren Reyes accepted and signed quotation QT-HIST-2026-134.'],
            ['event' => 'inventory.low_stock', 'module' => 'Inventory Buffer', 'description' => 'Stock warning triggered: CMYK Cyan Toner Cartridge below threshold at Branch B.'],
            ['event' => 'payment.verified', 'module' => 'Finance', 'description' => 'Payment ₱12,500 verified via GCash gateway reference PAY-2026-GH921.'],
        ];

        foreach ($auditSamples as $as) {
            AuditLog::firstOrCreate(
                ['description' => $as['description']],
                [
                    'user_id'    => $adminUser->id ?? 1,
                    'event'      => $as['event'],
                    'module'     => $as['module'],
                    'ip_address' => '127.0.0.1',
                    'created_at' => now()->subMinutes(rand(10, 300)),
                ]
            );
        }
    }
}
