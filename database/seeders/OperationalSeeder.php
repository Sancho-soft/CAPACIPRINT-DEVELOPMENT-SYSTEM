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
use App\Models\ProductionJob;
use App\Models\CapacityEvaluation;
use App\Models\BranchRecommendation;
use App\Models\StockMovement;
use Illuminate\Support\Str;

class OperationalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Branches
        $mainBranch = Branch::updateOrCreate(
            ['name' => 'Main Printing Hub'],
            ['location' => 'Branch A', 'address' => 'Main Branch Address', 'phone' => '0917-111-2233', 'manager_name' => 'Branch Manager', 'status' => 'active', 'max_daily_jobs' => 25]
        );

        $northBranch = Branch::updateOrCreate(
            ['name' => 'North Press Branch'],
            ['location' => 'Branch B', 'address' => 'North Branch Address', 'phone' => '0917-222-3344', 'manager_name' => 'Branch Manager', 'status' => 'active', 'max_daily_jobs' => 15]
        );

        $southBranch = Branch::updateOrCreate(
            ['name' => 'South Digital Hub'],
            ['location' => 'Branch C', 'address' => 'South Branch Address', 'phone' => '0917-333-4455', 'manager_name' => 'Branch Manager', 'status' => 'active', 'max_daily_jobs' => 20]
        );

        // 2. Create Machines
        $m1 = Machine::firstOrCreate(
            ['name' => 'Heidelberg Speedmaster XL 106', 'branch_id' => $mainBranch->id],
            ['type' => 'Offset Press', 'model' => 'XL 106-6+L', 'status' => 'available', 'jobs_per_day_capacity' => 12]
        );

        $m2 = Machine::firstOrCreate(
            ['name' => 'HP Indigo 12000 Digital Press', 'branch_id' => $mainBranch->id],
            ['type' => 'Digital Press', 'model' => 'Indigo 12000', 'status' => 'available', 'jobs_per_day_capacity' => 15]
        );

        $m3 = Machine::firstOrCreate(
            ['name' => 'Roland Soljet Pro 4 XR-640', 'branch_id' => $northBranch->id],
            ['type' => 'Large Format', 'model' => 'XR-640', 'status' => 'available', 'jobs_per_day_capacity' => 8]
        );

        $m4 = Machine::firstOrCreate(
            ['name' => 'Canon ImagePRESS C10010VP', 'branch_id' => $southBranch->id],
            ['type' => 'Digital Press', 'model' => 'C10010VP', 'status' => 'available', 'jobs_per_day_capacity' => 10]
        );

        // 3. Create Employees
        $prodUser = User::where('role', 'production')->first();
        if ($prodUser) {
            Employee::firstOrCreate(
                ['user_id' => $prodUser->id],
                ['branch_id' => $mainBranch->id, 'name' => $prodUser->name, 'position' => 'Senior Press Operator', 'availability_status' => 'available']
            );
        }

        // 4. Create Materials
        $matPaper = Material::firstOrCreate(
            ['name' => 'Glossy Paper 150gsm (A4/A3)'],
            ['type' => 'paper', 'unit' => 'reams', 'description' => 'Premium high-gloss coated paper', 'is_active' => true]
        );

        $matMatte = Material::firstOrCreate(
            ['name' => 'Matte Cardstock 220gsm'],
            ['type' => 'paper', 'unit' => 'sheets', 'description' => 'Heavy cardstock for calling cards and book covers', 'is_active' => true]
        );

        $matInk = Material::firstOrCreate(
            ['name' => 'CMYK Cyan Toner Cartridge'],
            ['type' => 'ink', 'unit' => 'cartridges', 'description' => 'High capacity Cyan toner', 'is_active' => true]
        );

        $matTarpaulin = Material::firstOrCreate(
            ['name' => 'Flex Tarpaulin Banner Media 13oz'],
            ['type' => 'paper', 'unit' => 'rolls', 'description' => 'Weatherproof outdoor vinyl media', 'is_active' => true]
        );

        // 5. Initialize Branch Inventory
        foreach ([$mainBranch, $northBranch, $southBranch] as $b) {
            foreach ([$matPaper, $matMatte, $matInk, $matTarpaulin] as $m) {
                $qty = rand(40, 200);
                BranchInventory::firstOrCreate(
                    ['branch_id' => $b->id, 'material_id' => $m->id],
                    ['quantity' => $qty, 'minimum_stock' => 50, 'status' => $qty > 50 ? 'available' : 'low_stock', 'last_updated' => now()]
                );
            }
        }

        // 6. Create Pricing Rules
        PricingRule::firstOrCreate(
            ['service' => 'Document Printing', 'size' => 'A4'],
            ['base_rate' => 3.50, 'material_rate' => 1.50, 'finishing_rate' => 0.50, 'is_active' => true]
        );
        PricingRule::firstOrCreate(
            ['service' => 'Flyers & Brochures', 'size' => 'A4'],
            ['base_rate' => 5.00, 'material_rate' => 2.50, 'finishing_rate' => 1.00, 'is_active' => true]
        );
        PricingRule::firstOrCreate(
            ['service' => 'Tarpaulin Banner', 'size' => '3x4 ft'],
            ['base_rate' => 25.00, 'material_rate' => 15.00, 'finishing_rate' => 5.00, 'is_active' => true]
        );
        PricingRule::firstOrCreate(
            ['service' => 'Calling Cards', 'size' => 'Standard Box'],
            ['base_rate' => 150.00, 'material_rate' => 50.00, 'finishing_rate' => 25.00, 'is_active' => true]
        );

        // 7. Seed Sample Workflow (Customer Request -> Quote -> Order -> Job)
        $customer = User::where('role', 'customer')->first();
        if ($customer) {
            $pr1 = PrintRequest::firstOrCreate(
                ['user_id' => $customer->id, 'service' => 'Flyers & Brochures'],
                [
                    'quantity' => 1000,
                    'size' => 'A4',
                    'material' => 'Glossy Paper 150gsm',
                    'finishing' => 'Lamination',
                    'deadline' => now()->addDays(5),
                    'preferred_branch' => 'Main Printing Hub',
                    'status' => 'production',
                ]
            );

            $quote1 = Quotation::firstOrCreate(
                ['print_request_id' => $pr1->id],
                [
                    'quotation_number' => 'QT-SEED001',
                    'user_id' => $customer->id,
                    'base_cost' => 5000.00,
                    'material_cost' => 2500.00,
                    'finishing_cost' => 1000.00,
                    'total_price' => 8500.00,
                    'valid_until' => now()->addDays(14),
                    'status' => 'confirmed',
                ]
            );

            $order1 = Order::firstOrCreate(
                ['quotation_id' => $quote1->id],
                [
                    'order_number' => 'ORD-SEED001',
                    'user_id' => $customer->id,
                    'print_request_id' => $pr1->id,
                    'payment_status' => 'confirmed',
                    'status' => 'production',
                    'assigned_branch' => 'Main Printing Hub',
                    'estimated_completion' => now()->addDays(3),
                ]
            );

            ProductionJob::firstOrCreate(
                ['order_id' => $order1->id],
                [
                    'job_number' => 'JOB-SEED001',
                    'branch_id' => $mainBranch->id,
                    'machine_id' => $m1->id,
                    'assigned_to' => $prodUser->id ?? null,
                    'status' => 'in_production',
                    'priority' => 'rush',
                    'estimated_hours' => 6,
                    'started_at' => now()->subHour(),
                ]
            );

            // Second open request awaiting quote
            PrintRequest::firstOrCreate(
                ['user_id' => $customer->id, 'service' => 'Tarpaulin Banner'],
                [
                    'quantity' => 5,
                    'size' => '3x4 ft',
                    'material' => 'Flex Tarpaulin Banner Media 13oz',
                    'finishing' => 'Eyelets & Hemming',
                    'deadline' => now()->addDays(3),
                    'preferred_branch' => 'North Press Branch',
                    'status' => 'submitted',
                ]
            );
        }
    }
}
