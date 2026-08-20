<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\BranchInventory;
use App\Models\Branch;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $materials = Material::when($request->search, fn($q, $s) => $q->where('name', 'like', "%$s%"))
            ->when($request->type, fn($q, $t) => $q->where('type', $t))
            ->withCount('branchInventory')
            ->latest()
            ->paginate(15);

        return view('inventory.materials.index', compact('materials'));
    }

    public function create()
    {
        return view('inventory.materials.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'type'        => ['required', 'in:paper,ink,lamination,binding,other'],
            'unit'        => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $material = Material::create($data);

        // Initialize branch_inventory record for all branches
        Branch::where('status', 'active')->each(function ($branch) use ($material) {
            BranchInventory::firstOrCreate(
                ['branch_id' => $branch->id, 'material_id' => $material->id],
                ['quantity' => 0, 'minimum_stock' => 100, 'status' => 'out_of_stock']
            );
        });

        return redirect()->route('inventory.materials.index')
            ->with('success', "Material '{$material->name}' added.");
    }

    public function show(Material $material)
    {
        $material->load('branchInventory.branch');
        $recentMovements = $material->stockMovements()->with(['branch', 'user'])->latest()->take(10)->get();
        return view('inventory.materials.show', compact('material', 'recentMovements'));
    }

    public function edit(Material $material)
    {
        return view('inventory.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'type'        => ['required', 'in:paper,ink,lamination,binding,other'],
            'unit'        => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['boolean'],
        ]);

        $material->update($data);
        return redirect()->route('inventory.materials.show', $material)->with('success', 'Material updated.');
    }
}
