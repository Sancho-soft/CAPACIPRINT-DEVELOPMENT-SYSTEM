<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\Material;
use App\Models\Branch;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function index()
    {
        $userBranchId = auth()->user()->branch_id ?? Branch::first()->id ?? 1;

        $requests = PurchaseRequest::where('branch_id', $userBranchId)
            ->with(['material', 'user', 'branch'])
            ->latest()
            ->paginate(15);

        $materials = Material::where('is_active', true)->get();

        return view('manager.purchasing.index', compact('requests', 'materials'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'material_id'  => ['required', 'exists:materials,id'],
            'quantity'     => ['required', 'integer', 'min:1'],
            'unit_cost'    => ['required', 'numeric', 'min:0'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ]);

        $material = Material::findOrFail($data['material_id']);
        $userBranchId = auth()->user()->branch_id ?? Branch::first()->id ?? 1;
        $totalAmount = $data['quantity'] * $data['unit_cost'];

        $pr = PurchaseRequest::create([
            'branch_id'    => $userBranchId,
            'requested_by' => auth()->id(),
            'material_id'  => $data['material_id'],
            'quantity'     => $data['quantity'],
            'unit_cost'    => $data['unit_cost'],
            'total_amount' => $totalAmount,
            'status'       => 'pending',
            'notes'        => $data['notes'] ?? null,
        ]);

        AuditLog::record(
            'Purchase Request Created',
            'Procurement',
            "Purchase Request #{$pr->id} created for {$material->name} ({$data['quantity']} units)",
            null,
            $pr->toArray()
        );

        return redirect()->back()->with('success', 'Purchase request submitted for management approval.');
    }

    public function markReceived(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved purchase requests can be received.');
        }

        $purchaseRequest->update(['status' => 'received']);

        // Auto-increment branch inventory stock
        $branchInv = \App\Models\BranchInventory::firstOrCreate(
            [
                'branch_id'   => $purchaseRequest->branch_id,
                'material_id' => $purchaseRequest->material_id,
            ],
            [
                'quantity'      => 0,
                'reorder_level' => 10,
                'status'        => 'optimal',
            ]
        );

        $branchInv->increment('quantity', $purchaseRequest->quantity);
        $branchInv->recalculateStatus();

        // Record stock movement
        \App\Models\StockMovement::create([
            'branch_id'     => $purchaseRequest->branch_id,
            'material_id'   => $purchaseRequest->material_id,
            'user_id'       => auth()->id(),
            'movement_type' => 'stock_in',
            'quantity'      => $purchaseRequest->quantity,
            'reference'     => "PO-{$purchaseRequest->id}",
            'remarks'       => "Received Purchase Order #{$purchaseRequest->id}",
            'movement_date' => now(),
        ]);

        AuditLog::record(
            'Materials Received',
            'Procurement',
            "Received {$purchaseRequest->quantity} units of material #{$purchaseRequest->material_id}",
            null,
            ['status' => 'received']
        );

        return redirect()->back()->with('success', 'Stock delivery received and inventory automatically updated!');
    }
}
