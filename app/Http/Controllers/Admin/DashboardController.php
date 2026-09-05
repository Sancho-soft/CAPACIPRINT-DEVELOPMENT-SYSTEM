<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\Machine;
use App\Models\Order;
use App\Models\PrintRequest;
use App\Models\Quotation;
use App\Models\ProductionJob;
use App\Models\BranchInventory;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // System-wide User & Infrastructure Stats
        $totalUsers    = User::count();
        $totalBranches = Branch::count();
        $totalMachines = Machine::count();
        $availableMachines = Machine::where('status', 'available')->count();
        $totalOrders   = Order::count();
        $totalRequests = PrintRequest::count();

        // Role distribution across all 9 roles
        $roleBreakdown = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        // Commercial Printing Pipeline Stages
        $pipeline = [
            [
                'key'   => 'intake',
                'label' => 'Intake & Specs',
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
                'label' => 'Payment Review',
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
                'label' => 'Ready / Claiming',
                'count' => Order::whereIn('status', ['ready_for_pickup', 'completed'])->count(),
                'icon'  => 'fa-solid fa-box-open',
                'color' => 'emerald',
            ],
        ];

        // Active production jobs
        $activeJobs = ProductionJob::whereIn('status', ['assigned', 'preparing', 'in_production', 'quality_checking'])->count();
        $delayedJobs = ProductionJob::where('status', 'delayed')->count();
        $rushJobs = ProductionJob::whereIn('priority', ['rush', 'urgent'])->whereNotIn('status', ['completed'])->count();

        // Level 1: Actionable Attention Items
        $attentionItems = [];

        // 1. Delayed Production Jobs
        $delayedJobList = ProductionJob::where('status', 'delayed')->with(['order.user', 'branch', 'machine'])->take(3)->get();
        foreach ($delayedJobList as $dJob) {
            $attentionItems[] = [
                'title'        => "Delayed Press Run: {$dJob->job_number}",
                'description'  => "Assigned to {$dJob->branch->name} (" . ($dJob->machine->name ?? 'Press Unit') . "). Reason: " . ($dJob->delay_reason ?: 'Operational hold reported on press floor.'),
                'severity'     => 'critical',
                'icon'         => 'fa-solid fa-triangle-exclamation',
                'badge'        => 'DELAYED PRESS',
                'meta'         => "Started " . ($dJob->started_at ? $dJob->started_at->diffForHumans() : 'Recently'),
                'action_url'   => Route('admin.dashboard'),
                'action_label' => 'Review Job',
            ];
        }

        // 2. Urgent / Rush Jobs Near Deadlines
        $urgentJobList = ProductionJob::whereIn('priority', ['rush', 'urgent'])
            ->whereNotIn('status', ['completed'])
            ->with(['order.user', 'branch'])
            ->take(3)
            ->get();
        foreach ($urgentJobList as $uJob) {
            $attentionItems[] = [
                'title'        => "Priority Order: {$uJob->job_number} (" . strtoupper($uJob->priority) . ")",
                'description'  => "Customer: " . ($uJob->order->user->name ?? 'Client') . " &middot; Branch: " . ($uJob->branch->name ?? 'Main'),
                'severity'     => 'warning',
                'icon'         => 'fa-solid fa-bolt',
                'badge'        => strtoupper($uJob->priority),
                'meta'         => "Status: " . ucfirst($uJob->status),
                'action_url'   => Route('admin.dashboard'),
                'action_label' => 'Track Priority',
            ];
        }

        // 3. Raw Material Shortages
        $lowStockMaterials = BranchInventory::whereIn('status', ['low_stock', 'out_of_stock'])->with(['material', 'branch'])->take(3)->get();
        foreach ($lowStockMaterials as $mat) {
            $attentionItems[] = [
                'title'        => "Low Material Stock: " . ($mat->material->name ?? 'Print Consumable'),
                'description'  => "Branch: {$mat->branch->name} &middot; Current On-Hand: {$mat->quantity} {$mat->material->unit} (Min Threshold: {$mat->minimum_stock})",
                'severity'     => $mat->status === 'out_of_stock' ? 'critical' : 'warning',
                'icon'         => 'fa-solid fa-boxes-stacked',
                'badge'        => strtoupper(str_replace('_', ' ', $mat->status)),
                'meta'         => "Stock Warning",
                'action_url'   => route('management.branches.index'),
                'action_label' => 'Inspect Branch',
            ];
        }

        // Live Production Queue (Latest 8 jobs)
        $recentJobs = ProductionJob::with(['order.user', 'order.printRequest', 'branch', 'machine'])
            ->whereNotIn('status', ['completed'])
            ->latest()
            ->take(8)
            ->get();

        // Recent user registrations
        $recentUsers = User::latest()->paginate(6);

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Pending requests
        $pendingRequests = PrintRequest::whereIn('status', ['submitted', 'verified'])->count();
        $inProductionRequests = PrintRequest::where('status', 'production')->count();

        // Multi-Branch Status & Workload
        $branches = Branch::with(['machines'])
            ->withCount([
                'machines',
                'productionJobs as active_jobs' => fn($q) => $q->whereNotIn('status', ['completed']),
            ])->get();

        // Active task assignments
        $activeAssignments = ProductionJob::with(['assignedTo', 'branch'])
            ->latest()
            ->take(5)
            ->get();

        // Recent audit logs
        $recentAuditLogs = collect();
        if (class_exists(AuditLog::class)) {
            try {
                $recentAuditLogs = AuditLog::latest()->take(6)->get();
            } catch (\Exception $e) {
                $recentAuditLogs = collect();
            }
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBranches',
            'totalMachines',
            'availableMachines',
            'totalOrders',
            'totalRequests',
            'roleBreakdown',
            'pipeline',
            'activeJobs',
            'delayedJobs',
            'rushJobs',
            'attentionItems',
            'recentJobs',
            'recentUsers',
            'ordersByStatus',
            'pendingRequests',
            'inProductionRequests',
            'branches',
            'recentAuditLogs',
            'activeAssignments'
        ));
    }
}
