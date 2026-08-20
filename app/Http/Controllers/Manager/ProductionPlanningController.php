<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ProductionJob;
use App\Models\User;
use App\Models\Branch;
use App\Models\Machine;
use Illuminate\Http\Request;

class ProductionPlanningController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductionJob::with(['order.user', 'order.printRequest', 'branch', 'machine', 'assignedTo'])
            ->whereNotIn('status', ['completed']);

        if ($branch = $request->get('branch_id')) {
            $query->where('branch_id', $branch);
        }
        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        $jobs     = $query->latest()->paginate(15);
        $branches = Branch::where('status', 'active')->get();

        return view('manager.production-planning.index', compact('jobs', 'branches'));
    }

    public function show(ProductionJob $productionJob)
    {
        $productionJob->load(['order.user', 'order.printRequest', 'branch', 'machine', 'assignedTo']);
        $staff    = User::where('role', 'production')->get();
        $machines = Machine::where('branch_id', $productionJob->branch_id)->get();

        return view('manager.production-planning.show', compact('productionJob', 'staff', 'machines'));
    }

    public function assign(Request $request, ProductionJob $productionJob)
    {
        $data = $request->validate([
            'assigned_to'      => ['nullable', 'exists:users,id'],
            'machine_id'       => ['nullable', 'exists:machines,id'],
            'estimated_hours'  => ['nullable', 'integer', 'min:1'],
            'priority'         => ['required', 'in:normal,rush,urgent'],
        ]);

        $productionJob->update([
            'assigned_to'     => $data['assigned_to'] ?? null,
            'machine_id'      => $data['machine_id'] ?? null,
            'estimated_hours' => $data['estimated_hours'] ?? null,
            'priority'        => $data['priority'],
            'status'          => 'assigned',
        ]);

        // Notify assigned production staff
        if ($data['assigned_to'] ?? null) {
            \App\Models\InternalNotification::create([
                'user_id'  => $data['assigned_to'],
                'order_id' => $productionJob->order_id,
                'title'    => 'Job Assigned',
                'body'     => "You have been assigned to production job #{$productionJob->job_number}.",
                'type'     => 'production',
            ]);
        }

        return back()->with('success', 'Job assignment updated.');
    }
}
