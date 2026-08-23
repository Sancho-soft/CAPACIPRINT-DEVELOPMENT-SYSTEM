<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function index()
    {
        $requests = PurchaseRequest::with(['material', 'user', 'branch'])
            ->latest()
            ->paginate(20);

        $pendingCount = PurchaseRequest::where('status', 'pending')->count();
        $approvedCount = PurchaseRequest::where('status', 'approved')->count();
        $totalSpent = PurchaseRequest::whereIn('status', ['approved', 'received'])->sum('total_amount');

        return view('management.purchasing.index', compact('requests', 'pendingCount', 'approvedCount', 'totalSpent'));
    }

    public function approve(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->update(['status' => 'approved']);

        AuditLog::record(
            'Purchase Request Approved',
            'Procurement',
            "Executive approved Purchase Request #{$purchaseRequest->id} for ₱" . number_format($purchaseRequest->total_amount, 2),
            ['status' => 'pending'],
            ['status' => 'approved']
        );

        return redirect()->back()->with('success', "Purchase Request #{$purchaseRequest->id} approved.");
    }

    public function reject(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->update(['status' => 'rejected']);

        AuditLog::record(
            'Purchase Request Rejected',
            'Procurement',
            "Executive rejected Purchase Request #{$purchaseRequest->id}",
            ['status' => 'pending'],
            ['status' => 'rejected']
        );

        return redirect()->back()->with('success', "Purchase Request #{$purchaseRequest->id} rejected.");
    }
}
