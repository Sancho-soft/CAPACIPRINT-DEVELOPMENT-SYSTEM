<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\BranchRecommendation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchRecommendationController extends Controller
{
    public function index()
    {
        $recommendations = BranchRecommendation::with(['printRequest.user', 'recommendedBranch', 'createdBy'])
            ->latest()
            ->paginate(15);

        return view('manager.recommendations.index', compact('recommendations'));
    }

    public function show(BranchRecommendation $recommendation)
    {
        $recommendation->load([
            'printRequest.user',
            'recommendedBranch.machines',
            'createdBy',
            'printRequest.capacityEvaluations.branch',
        ]);

        return view('manager.recommendations.show', compact('recommendation'));
    }

    public function confirm(Request $request, BranchRecommendation $recommendation)
    {
        $data = $request->validate([
            'override_branch_id' => ['nullable', 'exists:branches,id'],
            'override_reason'    => ['nullable', 'string', 'max:500'],
        ]);

        $branchId     = $data['override_branch_id'] ?? $recommendation->recommended_branch_id;
        $isOverridden = isset($data['override_branch_id']) && $data['override_branch_id'] != $recommendation->recommended_branch_id;

        $recommendation->update([
            'recommended_branch_id' => $branchId,
            'status'                => $isOverridden ? 'overridden' : 'confirmed',
            'override_reason'       => $data['override_reason'] ?? null,
        ]);

        // Find linked order through print_request and update its assigned_branch
        $pr = $recommendation->printRequest;
        $order = Order::where('print_request_id', $pr->id)->first();

        if ($order) {
            $branch = \App\Models\Branch::find($branchId);
            $order->update([
                'assigned_branch' => $branch->name,
                'status'          => 'production',
            ]);

            $recommendation->update(['order_id' => $order->id]);

            // Notify customer
            \App\Models\CustomerNotification::create([
                'user_id'  => $pr->user_id,
                'order_id' => $order->id,
                'title'    => 'Branch Assigned',
                'body'     => "Your order #{$order->order_number} has been assigned to {$branch->name} for production.",
                'type'     => 'general',
            ]);
        }

        return redirect()->route('manager.recommendations.index')
            ->with('success', 'Branch recommendation confirmed.');
    }
}
