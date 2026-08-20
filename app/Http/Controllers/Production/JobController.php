<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\ProductionJob;
use App\Models\Order;
use App\Models\ClaimReference;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    /**
     * List only jobs assigned to this production staff member.
     */
    public function index(Request $request)
    {
        $query = ProductionJob::where('assigned_to', auth()->id())
            ->with(['order.user', 'order.printRequest', 'branch', 'machine']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $jobs = $query->orderByRaw("CASE priority WHEN 'urgent' THEN 0 WHEN 'rush' THEN 1 ELSE 2 END")
            ->latest()->paginate(15);

        return view('production.jobs.index', compact('jobs'));
    }

    /**
     * Show job details — only if assigned to this user.
     */
    public function show(ProductionJob $productionJob)
    {
        abort_if($productionJob->assigned_to !== auth()->id(), 403);
        $productionJob->load(['order.user', 'order.printRequest', 'branch', 'machine']);
        return view('production.jobs.show', compact('productionJob'));
    }

    public function statusForm(ProductionJob $productionJob)
    {
        abort_if($productionJob->assigned_to !== auth()->id(), 403);
        return view('production.jobs.status', compact('productionJob'));
    }

    /**
     * Update job status — only allowed for own jobs.
     */
    public function updateStatus(Request $request, ProductionJob $productionJob)
    {
        abort_if($productionJob->assigned_to !== auth()->id(), 403);

        $data = $request->validate([
            'status'       => ['required', 'in:' . implode(',', ProductionJob::STATUSES)],
            'delay_reason' => ['required_if:status,delayed', 'nullable', 'string', 'max:500'],
            'remarks'      => ['nullable', 'string', 'max:1000'],
        ]);

        $updates = [
            'status'       => $data['status'],
            'delay_reason' => $data['delay_reason'] ?? null,
            'remarks'      => $data['remarks'] ?? null,
        ];

        if ($data['status'] === 'in_production' && !$productionJob->started_at) {
            $updates['started_at'] = now();

            // Automated Material Stock Deduction
            if ($productionJob->branch_id) {
                $inventoryItems = \App\Models\BranchInventory::where('branch_id', $productionJob->branch_id)->get();
                if ($inventoryItems->isNotEmpty()) {
                    // Deduct 1 unit/ream from first available paper stock as job consumption
                    $firstStock = $inventoryItems->first();
                    $deductQty = min($firstStock->quantity, 1);
                    if ($deductQty > 0) {
                        $firstStock->decrement('quantity', $deductQty);
                        $firstStock->recalculateStatus();

                        \App\Models\StockMovement::create([
                            'branch_id'     => $productionJob->branch_id,
                            'material_id'   => $firstStock->material_id,
                            'user_id'       => auth()->id(),
                            'movement_type' => 'stock_out',
                            'quantity'      => $deductQty,
                            'reference'     => "JOB-{$productionJob->job_number}",
                            'remarks'       => "Consumed for Production Job #{$productionJob->job_number}",
                            'movement_date' => now(),
                        ]);
                    }
                }
            }
        }

        if ($data['status'] === 'delayed') {
            // Notify Branch Manager of production delay
            \App\Models\InternalNotification::create([
                'user_id' => null, // Broadcast to managers
                'role'    => 'manager',
                'title'   => 'Production Delay Alert',
                'body'    => "Job #{$productionJob->job_number} at {$productionJob->branch->name} is DELAYED. Reason: {$data['delay_reason']}",
                'type'    => 'capacity_alert',
                'link'    => route('manager.production-planning.show', $productionJob),
            ]);
        }

        if ($data['status'] === 'completed') {
            $updates['completed_at'] = now();

            // Update order status
            $order = $productionJob->order;
            $order->update(['status' => 'ready_for_pickup']);

            // Generate claim reference
            ClaimReference::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id'         => $order->user_id,
                    'claim_code'      => 'CLM-' . strtoupper(Str::random(8)),
                    'pickup_branch'   => $productionJob->branch->name ?? null,
                    'completion_date' => today(),
                ]
            );

            // Notify customer
            \App\Models\CustomerNotification::create([
                'user_id'  => $order->user_id,
                'order_id' => $order->id,
                'title'    => 'Your Order is Ready for Pickup!',
                'body'     => "Order #{$order->order_number} has been completed and is ready for collection at {$productionJob->branch->name}.",
                'type'     => 'pickup',
            ]);
        }

        $productionJob->update($updates);

        return redirect()->route('production.jobs.show', $productionJob)
            ->with('success', 'Job status updated to: ' . $productionJob->fresh()->status_label);
    }
}
