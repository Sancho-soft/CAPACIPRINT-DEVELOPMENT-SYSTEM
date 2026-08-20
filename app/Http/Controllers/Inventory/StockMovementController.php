<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\BranchInventory;
use App\Models\Branch;
use App\Models\Material;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::with(['material', 'branch', 'user'])
            ->when($request->branch_id, fn($q, $b) => $q->where('branch_id', $b))
            ->when($request->type,      fn($q, $t) => $q->where('movement_type', $t))
            ->latest()
            ->paginate(20);

        $branches = Branch::where('status', 'active')->get();

        return view('inventory.stock-movements.index', compact('movements', 'branches'));
    }

    public function create()
    {
        $branches  = Branch::where('status', 'active')->get();
        $materials = Material::where('is_active', true)->get();
        return view('inventory.stock-movements.create', compact('branches', 'materials'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_id'     => ['required', 'exists:branches,id'],
            'material_id'   => ['required', 'exists:materials,id'],
            'movement_type' => ['required', 'in:stock_in,stock_out,adjustment'],
            'quantity'      => ['required', 'numeric', 'min:0.01'],
            'movement_date' => ['required', 'date'],
            'reference'     => ['nullable', 'string', 'max:100'],
            'reason'        => ['nullable', 'string', 'max:200'],
            'remarks'       => ['nullable', 'string', 'max:500'],
        ]);

        $data['user_id'] = auth()->id();

        StockMovement::create($data);

        // Update BranchInventory
        $inv = BranchInventory::firstOrCreate(
            ['branch_id' => $data['branch_id'], 'material_id' => $data['material_id']],
            ['quantity' => 0, 'minimum_stock' => 100]
        );

        match ($data['movement_type']) {
            'stock_in'   => $inv->quantity += $data['quantity'],
            'stock_out'  => $inv->quantity = max(0, $inv->quantity - $data['quantity']),
            'adjustment' => $inv->quantity = $data['quantity'],
        };

        $inv->recalculateStatus();
        $inv->last_updated = now();
        $inv->save();

        return redirect()->route('inventory.stock-movements.index')
            ->with('success', 'Stock movement recorded and inventory updated.');
    }
}
