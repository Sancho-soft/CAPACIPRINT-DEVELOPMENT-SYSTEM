<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\BranchInventory;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMaterials     = Material::where('is_active', true)->count();
        $availableCount     = BranchInventory::where('status', 'available')->count();
        $lowStockCount      = BranchInventory::where('status', 'low_stock')->count();
        $outOfStockCount    = BranchInventory::where('status', 'out_of_stock')->count();

        $recentMovements = StockMovement::with(['material', 'branch', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $lowStockItems = BranchInventory::with(['material', 'branch'])
            ->whereIn('status', ['low_stock', 'out_of_stock'])
            ->latest()
            ->take(10)
            ->get();

        return view('inventory.dashboard', compact(
            'totalMaterials', 'availableCount', 'lowStockCount',
            'outOfStockCount', 'recentMovements', 'lowStockItems'
        ));
    }
}
