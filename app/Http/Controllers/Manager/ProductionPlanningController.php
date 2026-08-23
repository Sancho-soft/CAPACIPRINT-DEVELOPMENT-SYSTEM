<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ProductionJob;
use App\Models\User;
use App\Models\Branch;
use App\Models\Machine;
use App\Models\InternalNotification;
use Illuminate\Http\Request;

class ProductionPlanningController extends Controller
{
    /**
     * Display all production jobs across branches with scheduling & workload metrics.
     */
    public function index(Request $request)
    {
        $query = ProductionJob::with(['order.user', 'order.printRequest', 'branch', 'machine', 'assignedTo']);

        // Branch filter
        if ($branch = $request->get('branch_id')) {
            $query->where('branch_id', $branch);
        }

        // Priority filter
        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        // Status filter
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        } else {
            $query->whereNotIn('status', ['completed']);
        }

        // Search by Job # or Customer name
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('job_number', 'like', "%$search%")
                  ->orWhereHas('order.user', fn($u) => $u->where('name', 'like', "%$search%"))
                  ->orWhereHas('order.printRequest', fn($pr) => $pr->where('service', 'like', "%$search%"));
            });
        }

        $jobs = $query->orderByRaw("CASE priority WHEN 'urgent' THEN 0 WHEN 'rush' THEN 1 ELSE 2 END")
                      ->latest()
                      ->paginate(12)
                      ->withQueryString();

        $branches = Branch::where('status', 'active')->withCount(['machines', 'employees'])->get();
        
        // Summary metrics
        $totalActiveJobs = ProductionJob::whereNotIn('status', ['completed'])->count();
        $urgentJobsCount = ProductionJob::whereIn('priority', ['urgent', 'rush'])->whereNotIn('status', ['completed'])->count();
        $unassignedJobs  = ProductionJob::whereNull('assigned_to')->whereNotIn('status', ['completed'])->count();

        return view('manager.production-planning.index', compact('jobs', 'branches', 'totalActiveJobs', 'urgentJobsCount', 'unassignedJobs'));
    }

    /**
     * Show scheduling details and re-assignment workspace for a specific job.
     */
    public function show(ProductionJob $productionJob)
    {
        $productionJob->load(['order.user', 'order.printRequest', 'branch', 'machine', 'assignedTo']);
        
        $branches = Branch::where('status', 'active')->get();
        $staff    = User::whereIn('role', ['production', 'staff', 'manager'])->get();
        $machines = Machine::where('branch_id', $productionJob->branch_id)->get();

        return view('manager.production-planning.show', compact('productionJob', 'branches', 'staff', 'machines'));
    }

    /**
     * Update job scheduling: Branch Reassignment, Machine Routing, Staff Assignee, and Priority.
     */
    public function assign(Request $request, ProductionJob $productionJob)
    {
        $data = $request->validate([
            'branch_id'        => ['required', 'exists:branches,id'],
            'assigned_to'      => ['nullable', 'exists:users,id'],
            'machine_id'       => ['nullable', 'exists:machines,id'],
            'estimated_hours'  => ['nullable', 'integer', 'min:1'],
            'priority'         => ['required', 'in:normal,rush,urgent'],
            'status'           => ['nullable', 'in:assigned,preparing,in_production,quality_checking,delayed,completed'],
            'remarks'          => ['nullable', 'string', 'max:500'],
        ]);

        $newBranch = Branch::find($data['branch_id']);

        $productionJob->update([
            'branch_id'       => $data['branch_id'],
            'assigned_to'     => $data['assigned_to'] ?? null,
            'machine_id'      => $data['machine_id'] ?? null,
            'estimated_hours' => $data['estimated_hours'] ?? null,
            'priority'        => $data['priority'],
            'status'          => $data['status'] ?? $productionJob->status,
            'remarks'         => $data['remarks'] ?? $productionJob->remarks,
        ]);

        // Keep Order assigned_branch synchronized
        if ($productionJob->order) {
            $productionJob->order->update(['assigned_branch' => $newBranch->name]);
        }

        // Notify assigned production technician
        if ($data['assigned_to'] ?? null) {
            InternalNotification::create([
                'user_id'  => $data['assigned_to'],
                'order_id' => $productionJob->order_id,
                'title'    => 'Production Job Assigned / Rescheduled',
                'body'     => "You have been scheduled for Job #{$productionJob->job_number} ({$productionJob->priority} priority) at {$newBranch->name}.",
                'type'     => 'production',
            ]);
        }

        return redirect()->route('manager.production-planning.index')
            ->with('success', "Production Job #{$productionJob->job_number} schedule and routing updated successfully.");
    }
}
