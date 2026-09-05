<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Machine;
use App\Models\Employee;

class UpdateBranchMetricsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Morning Star Printing Press
        $b1 = Branch::where('name', 'like', '%Morning Star Printing Press%')
            ->orWhere('location', 'Branch A')
            ->first();
        if ($b1) {
            $b1->update([
                'max_daily_jobs' => 85,
            ]);

            // Sync employees count to 7
            $currentEmp = Employee::where('branch_id', $b1->id)->count();
            for ($i = $currentEmp + 1; $i <= 7; $i++) {
                Employee::create([
                    'branch_id' => $b1->id,
                    'name' => "Staff Member A{$i}",
                    'position' => 'Press Operator',
                    'availability_status' => 'available',
                ]);
            }

            // Sync machines count to 5
            $currentMac = Machine::where('branch_id', $b1->id)->count();
            for ($i = $currentMac + 1; $i <= 5; $i++) {
                Machine::create([
                    'branch_id' => $b1->id,
                    'name' => "Press Machine A{$i}",
                    'type' => 'Offset Press',
                    'model' => 'Speedmaster-XL',
                    'status' => 'available',
                    'jobs_per_day_capacity' => 15,
                ]);
            }
        }

        // 2. Morning Star Printing Network
        $b2 = Branch::where('name', 'like', '%Morning Star Printing Network%')
            ->orWhere('location', 'Branch B')
            ->first();
        if ($b2) {
            $b2->update([
                'max_daily_jobs' => 60,
            ]);

            // Sync employees count to 5
            $currentEmp = Employee::where('branch_id', $b2->id)->count();
            for ($i = $currentEmp + 1; $i <= 5; $i++) {
                Employee::create([
                    'branch_id' => $b2->id,
                    'name' => "Staff Member B{$i}",
                    'position' => 'Digital Operator',
                    'availability_status' => 'available',
                ]);
            }

            // Sync machines count to 4
            $currentMac = Machine::where('branch_id', $b2->id)->count();
            for ($i = $currentMac + 1; $i <= 4; $i++) {
                Machine::create([
                    'branch_id' => $b2->id,
                    'name' => "Network Machine B{$i}",
                    'type' => 'Digital Press',
                    'model' => 'Pro-XR',
                    'status' => 'available',
                    'jobs_per_day_capacity' => 12,
                ]);
            }
        }

        // 3. Green Heart Printing Hub
        $b3 = Branch::where('name', 'like', '%Green Heart Printing Hub%')
            ->orWhere('location', 'Branch C')
            ->first();
        if ($b3) {
            $b3->update([
                'max_daily_jobs' => 50,
            ]);
            // Sync employees count to 7
            $currentEmp = Employee::where('branch_id', $b3->id)->count();
            for ($i = $currentEmp + 1; $i <= 7; $i++) {
                Employee::create([
                    'branch_id' => $b3->id,
                    'name' => "Staff Member C{$i}",
                    'position' => 'Finishing Specialist',
                    'availability_status' => 'available',
                ]);
            }

            // Sync machines count to 8
            $currentMac = Machine::where('branch_id', $b3->id)->count();
            for ($i = $currentMac + 1; $i <= 8; $i++) {
                Machine::create([
                    'branch_id' => $b3->id,
                    'name' => "Hub Machine C{$i}",
                    'type' => 'Large Format Press',
                    'model' => 'Canon-C100',
                    'status' => 'available',
                    'jobs_per_day_capacity' => 10,
                ]);
            }
        }
    }
}
