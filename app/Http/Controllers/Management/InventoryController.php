<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\Branch;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = BranchInventory::with(['material', 'branch'])
            ->orderBy('status')
            ->paginate(20);

        $lowStockCount   = BranchInventory::where('status', 'low_stock')->count();
        $outOfStockCount = BranchInventory::where('status', 'out_of_stock')->count();

        return view('management.inventory.index', compact('inventory', 'lowStockCount', 'outOfStockCount'));
    }
}
