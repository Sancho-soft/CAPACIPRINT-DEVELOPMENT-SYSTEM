<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\ProductionJob;
use App\Models\PrintRequest;
use App\Models\Quotation;
use App\Models\Machine;
use App\Models\BranchInventory;
use App\Models\CapacityEvaluation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $branchId = $user->branch_id;

        // Branches with active jobs and machines
        $branches = Branch::with(['machines'])
            ->withCount([
                'productionJobs as active_jobs' => fn($q) => $q->whereNotIn('status', ['completed']),
            ])
            ->where('status', 'active')
            ->get();

        // Core Production Metrics
        $totalActiveJobs = ProductionJob::whereNotIn('status', ['completed'])->count();
        $jobsDueToday    = ProductionJob::whereHas('order', fn($q) => $q->whereDate('estimated_completion', today()))
            ->whereNotIn('status', ['completed'])
            ->count();
        $jobsDueTomorrow = ProductionJob::whereHas('order', fn($q) => $q->whereDate('estimated_completion', today()->addDay()))
            ->whereNotIn('status', ['completed'])
            ->count();
        $delayedJobs     = ProductionJob::where('status', 'delayed')->count();
        $rushJobs        = ProductionJob::whereIn('priority', ['rush', 'urgent'])
            ->whereNotIn('status', ['completed'])
            ->count();

        // Machine Fleet Metrics
        $totalMachines = Machine::count();
        $availableMachines = Machine::where('status', 'available')->count();
        $inUseMachines = Machine::where('status', 'in_use')->count();
        $maintenanceMachines = Machine::whereIn('status', ['maintenance', 'offline'])->count();

        // Commercial Printing Pipeline
        $pipeline = [
            [
                'key'   => 'intake',
                'label' => 'Technical Specs',
                'count' => PrintRequest::whereIn('status', ['submitted', 'verified'])->count(),
                'icon'  => 'fa-solid fa-file-arrow-up',
                'color' => 'cyan',
            ],
            [
                'key'   => 'quotation',
                'label' => 'Quotation Matrix',
                'count' => Quotation::where('status', 'pending')->count(),
                'icon'  => 'fa-solid fa-file-invoice-dollar',
                'color' => 'indigo',
            ],
            [
                'key'   => 'payment',
                'label' => 'Payment Verified',
                'count' => Order::where('status', 'payment')->orWhere('payment_status', 'submitted')->count(),
                'icon'  => 'fa-solid fa-credit-card',
                'color' => 'amber',
            ],
            [
                'key'   => 'routing',
                'label' => 'Capacity Routing',
                'count' => ProductionJob::where('status', 'assigned')->count(),
                'icon'  => 'fa-solid fa-network-wired',
                'color' => 'blue',
            ],
            [
                'key'    => 'production',
                'label'  => 'On Press Floor',
                'count'  => ProductionJob::whereIn('status', ['preparing', 'in_production'])->count(),
                'icon'   => 'fa-solid fa-industry',
                'color'  => 'teal',
                'active' => true,
            ],
            [
                'key'   => 'qc',
                'label' => 'Quality Check',
                'count' => ProductionJob::where('status', 'quality_checking')->count(),
                'icon'  => 'fa-solid fa-microscope',
                'color' => 'purple',
            ],
            [
                'key'   => 'ready',
                'label' => 'Ready for Pickup',
                'count' => Order::whereIn('status', ['ready_for_pickup', 'completed'])->count(),
                'icon'  => 'fa-solid fa-box-open',
                'color' => 'emerald',
            ],
        ];

        // Level 1: Actionable Attention Center
        $attentionItems = [];

        // 1. Delayed production runs
        $delayedList = ProductionJob::where('status', 'delayed')
            ->with(['order.user', 'branch', 'machine'])
            ->take(3)
            ->get();
        foreach ($delayedList as $d) {
            $attentionItems[] = [
                'title'        => "Delayed Press Run: {$d->job_number}",
                'description'  => "Press: " . ($d->machine->name ?? 'Press Line') . " at {$d->branch->name}. Reason: " . ($d->delay_reason ?: 'Production stoppage logged.'),
                'severity'     => 'critical',
                'icon'         => 'fa-solid fa-triangle-exclamation',
                'badge'        => 'DELAYED',
                'meta'         => "Reported " . ($d->updated_at ? $d->updated_at->diffForHumans() : 'Recently'),
                'action_url'   => route('manager.production-planning.show', $d->id),
                'action_label' => 'Resolve Delay',
            ];
        }

        // 2. Urgent priority jobs
        $rushList = ProductionJob::whereIn('priority', ['rush', 'urgent'])
            ->whereNotIn('status', ['completed'])
            ->with(['order.user', 'branch'])
            ->take(3)
            ->get();
        foreach ($rushList as $r) {
            $attentionItems[] = [
                'title'        => "Urgent Turnaround: {$r->job_number} (" . strtoupper($r->priority) . ")",
                'description'  => "Client: " . ($r->order->user->name ?? 'Customer') . " &middot; Assigned to " . ($r->branch->name ?? 'Branch'),
                'severity'     => 'warning',
                'icon'         => 'fa-solid fa-bolt',
                'badge'        => strtoupper($r->priority),
                'meta'         => "Status: " . ucfirst($r->status),
                'action_url'   => route('manager.production-planning.show', $r->id),
                'action_label' => 'View Job',
            ];
        }

        // 3. Raw materials requiring replenishment
        $lowMaterials = BranchInventory::whereIn('status', ['low_stock', 'out_of_stock'])
            ->with(['material', 'branch'])
            ->take(3)
            ->get();
        foreach ($lowMaterials as $lm) {
            $attentionItems[] = [
                'title'        => "Stock Alert: " . ($lm->material->name ?? 'Material'),
                'description'  => "Current balance: {$lm->quantity} {$lm->material->unit} at {$lm->branch->name} (Threshold: {$lm->minimum_stock})",
                'severity'     => $lm->status === 'out_of_stock' ? 'critical' : 'warning',
                'icon'         => 'fa-solid fa-boxes-stacked',
                'badge'        => strtoupper(str_replace('_', ' ', $lm->status)),
                'meta'         => "Material Shortage",
                'action_url'   => route('manager.purchasing.index'),
                'action_label' => 'Reorder Supplies',
            ];
        }

        // Active production jobs list
        $recentJobs = ProductionJob::with(['order.user', 'order.printRequest', 'branch', 'machine', 'assignedTo'])
            ->whereNotIn('status', ['completed'])
            ->latest()
            ->take(8)
            ->get();

        return view('manager.dashboard', compact(
            'branches',
            'totalActiveJobs',
            'jobsDueToday',
            'jobsDueTomorrow',
            'delayedJobs',
            'rushJobs',
            'totalMachines',
            'availableMachines',
            'inUseMachines',
            'maintenanceMachines',
            'pipeline',
            'attentionItems',
            'recentJobs'
        ));
    }
}
