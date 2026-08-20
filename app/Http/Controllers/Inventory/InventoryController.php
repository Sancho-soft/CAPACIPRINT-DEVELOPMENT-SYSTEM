<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\Branch;
use App\Models\Material;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = BranchInventory::with(['branch', 'material'])
            ->when($request->branch_id, fn($q, $b) => $q->where('branch_id', $b))
            ->when($request->status,    fn($q, $s) => $q->where('status', $s));

        $inventory = $query->orderBy('status')->paginate(20);
        $branches  = Branch::where('status', 'active')->get();

        return view('inventory.stock.index', compact('inventory', 'branches'));
    }

    public function update(Request $request, BranchInventory $branchInventory)
    {
        $data = $request->validate([
            'quantity'      => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
        ]);

        $branchInventory->fill($data);
        $branchInventory->recalculateStatus();
        $branchInventory->last_updated = now();
        $branchInventory->save();

        return back()->with('success', 'Inventory record updated.');
    }

    public function availability(Request $request)
    {
        $branchId = $request->get('branch_id');

        $inventory = BranchInventory::with(['material', 'branch'])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->get()
            ->groupBy('branch.name');

        $branches = Branch::where('status', 'active')->get();

        return view('inventory.availability', compact('inventory', 'branches', 'branchId'));
    }
}
