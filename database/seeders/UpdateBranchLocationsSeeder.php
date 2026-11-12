<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class UpdateBranchLocationsSeeder extends Seeder
{
    public function run(): void
    {
        // Branch A
        $bA = Branch::where('name', 'like', '%Morning Star Printing Press%')
            ->orWhere('location', 'Branch A')
            ->first();
        if ($bA) {
            $bA->update([
                'address' => '132 Bolton Street, Poblacion District, Davao City, 8000',
            ]);
            $this->command->info("Updated Branch A: {$bA->name} -> {$bA->address}");
        }

        // Branch B
        $bB = Branch::where('name', 'like', '%Morning Star Printing Network%')
            ->orWhere('location', 'Branch B')
            ->first();
        if ($bB) {
            $bB->update([
                'address' => 'Ground Floor, The Paper Tree Building, Bolton Street, Barangay 35-D, Poblacion District, Davao City, 8000',
            ]);
            $this->command->info("Updated Branch B: {$bB->name} -> {$bB->address}");
        }

        // Branch C
        $bC = Branch::where('name', 'like', '%Green Heart Printing Hub%')
            ->orWhere('location', 'Branch C')
            ->first();
        if ($bC) {
            $bC->update([
                'address' => 'A. Pichon Street corner Magallanes Street, Poblacion District, Davao City, 8000',
            ]);
            $this->command->info("Updated Branch C: {$bC->name} -> {$bC->address}");
        }
    }
}

