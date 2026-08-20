<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\Material;
use App\Models\StockMovement;
use App\Models\Branch;

class ReportController extends Controller
{
    public function index()
    {
        $totalMaterials  = Material::where('is_active', true)->count();
        $lowStockCount   = BranchInventory::where('status', 'low_stock')->count();
        $outOfStockCount = BranchInventory::where('status', 'out_of_stock')->count();

        $byBranch = Branch::with(['inventory.material'])
            ->where('status', 'active')
            ->get();

        $recentMovements = StockMovement::with(['material', 'branch', 'user'])
            ->latest()
            ->take(20)
            ->get();

        return view('inventory.reports.index', compact(
            'totalMaterials', 'lowStockCount', 'outOfStockCount',
            'byBranch', 'recentMovements'
        ));
    }
}
