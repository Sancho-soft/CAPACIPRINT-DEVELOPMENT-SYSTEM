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
        $user = auth()->user();
        $query = PurchaseRequest::with(['material', 'user', 'branch']);

        if (!in_array($user->role, ['system_admin', 'admin', 'owner', 'management']) && $user->branch_id) {
            $query->where('branch_id', $user->branch_id);
        }

        $requests = $query->latest()->paginate(10);

        $statsQuery = PurchaseRequest::query();
        if (!in_array($user->role, ['system_admin', 'admin', 'owner', 'management']) && $user->branch_id) {
            $statsQuery->where('branch_id', $user->branch_id);
        }

        $pendingCount = (clone $statsQuery)->where('status', 'pending')->count();
        $approvedCount = (clone $statsQuery)->where('status', 'approved')->count();
        $totalSpent = (clone $statsQuery)->whereIn('status', ['approved', 'received'])->sum('total_amount');

        $materials = Material::where('is_active', true)->get();

        return view('manager.purchasing.index', compact('requests', 'materials', 'pendingCount', 'approvedCount', 'totalSpent'));
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

    public function approve(PurchaseRequest $purchaseRequest)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['system_admin', 'admin', 'owner', 'management'])) {
            return redirect()->back()->with('error', 'Unauthorized. Only executive management or administrators can approve purchase requests.');
        }

        if ($purchaseRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending purchase requests can be approved.');
        }

        $purchaseRequest->update(['status' => 'approved']);

        AuditLog::record(
            'Purchase Request Approved',
            'Procurement',
            "Purchase Request #{$purchaseRequest->id} approved by {$user->name} for ₱" . number_format($purchaseRequest->total_amount, 2),
            ['status' => 'pending'],
            ['status' => 'approved']
        );

        return redirect()->back()->with('success', "Purchase Request #{$purchaseRequest->id} approved successfully.");
    }

    public function reject(Request $request, PurchaseRequest $purchaseRequest)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['system_admin', 'admin', 'owner', 'management'])) {
            return redirect()->back()->with('error', 'Unauthorized. Only executive management or administrators can reject purchase requests.');
        }

        if ($purchaseRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending purchase requests can be rejected.');
        }

        $reason = $request->input('reason');
        $updatedNotes = $purchaseRequest->notes;
        if (!empty($reason)) {
            $updatedNotes = ($updatedNotes ? $updatedNotes . "\n" : '') . "[Rejection Reason]: " . $reason;
        }

        $purchaseRequest->update([
            'status' => 'rejected',
            'notes'  => $updatedNotes,
        ]);

        AuditLog::record(
            'Purchase Request Rejected',
            'Procurement',
            "Purchase Request #{$purchaseRequest->id} rejected by {$user->name}",
            ['status' => 'pending'],
            ['status' => 'rejected']
        );

        return redirect()->back()->with('success', "Purchase Request #{$purchaseRequest->id} rejected.");
    }

    public function cancel(PurchaseRequest $purchaseRequest)
    {
        $user = auth()->user();
        $canCancel = ($purchaseRequest->requested_by === $user->id) || in_array($user->role, ['system_admin', 'admin', 'owner', 'management']);

        if (!$canCancel) {
            return redirect()->back()->with('error', 'Unauthorized. You can only cancel requests you submitted.');
        }

        if ($purchaseRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending purchase requests can be cancelled.');
        }

        $id = $purchaseRequest->id;
        $purchaseRequest->delete();

        AuditLog::record(
            'Purchase Request Cancelled',
            'Procurement',
            "Purchase Request #{$id} was withdrawn/cancelled by {$user->name}",
            ['status' => 'pending'],
            null
        );

        return redirect()->back()->with('success', "Purchase Request #{$id} has been withdrawn and cancelled.");
    }
}
