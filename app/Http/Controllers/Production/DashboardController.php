<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\ProductionJob;
use App\Models\Machine;
use App\Models\Order;
use App\Models\PrintRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;
        $branchId = $user->branch_id;

        // Assigned operator metrics
        $assignedCount     = ProductionJob::where(function($q) use ($userId, $branchId) {
            $q->where('assigned_to', $userId)
              ->orWhere(fn($sq) => $sq->whereNull('assigned_to')->when($branchId, fn($bq) => $bq->where('branch_id', $branchId)));
        })->whereNotIn('status', ['completed'])->count();

        $inProductionCount = ProductionJob::where(function($q) use ($userId, $branchId) {
            $q->where('assigned_to', $userId)
              ->orWhere(fn($sq) => $sq->whereNull('assigned_to')->when($branchId, fn($bq) => $bq->where('branch_id', $branchId)));
        })->where('status', 'in_production')->count();

        $dueTodayCount     = ProductionJob::where(function($q) use ($userId, $branchId) {
            $q->where('assigned_to', $userId)
              ->orWhere(fn($sq) => $sq->whereNull('assigned_to')->when($branchId, fn($bq) => $bq->where('branch_id', $branchId)));
        })->whereNotIn('status', ['completed'])
          ->whereHas('order', fn($q) => $q->whereDate('estimated_completion', today()))
          ->count();

        $completedCount    = ProductionJob::where('assigned_to', $userId)->where('status', 'completed')->count();
        $delayedCount      = ProductionJob::where(function($q) use ($userId, $branchId) {
            $q->where('assigned_to', $userId)
              ->orWhere(fn($sq) => $sq->whereNull('assigned_to')->when($branchId, fn($bq) => $bq->where('branch_id', $branchId)));
        })->where('status', 'delayed')->count();

        // Shop-floor production jobs prioritized by urgency (Urgent -> Rush -> Normal)
        $myJobs = ProductionJob::where(function($q) use ($userId, $branchId) {
            $q->where('assigned_to', $userId)
              ->orWhere(fn($sq) => $sq->whereNull('assigned_to')->when($branchId, fn($bq) => $bq->where('branch_id', $branchId)));
        })
        ->whereNotIn('status', ['completed'])
        ->with(['order.user', 'order.printRequest', 'branch', 'machine'])
        ->orderByRaw("CASE priority WHEN 'urgent' THEN 0 WHEN 'rush' THEN 1 ELSE 2 END")
        ->latest()
        ->take(10)
        ->get();

        // Shop Floor Press Machines
        $pressMachines = Machine::when($branchId, fn($q) => $q->where('branch_id', $branchId))->get();

        // Commercial Printing Pipeline for Shop Floor
        $pipeline = [
            [
                'key'   => 'assigned',
                'label' => 'Queued on Line',
                'count' => ProductionJob::where('status', 'assigned')->count(),
                'icon'  => 'fa-solid fa-list-check',
                'color' => 'blue',
            ],
            [
                'key'   => 'preparing',
                'label' => 'Pre-Press / Plates',
                'count' => ProductionJob::where('status', 'preparing')->count(),
                'icon'  => 'fa-solid fa-sliders',
                'color' => 'indigo',
            ],
            [
                'key'    => 'in_production',
                'label'  => 'Actively on Press',
                'count'  => ProductionJob::where('status', 'in_production')->count(),
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
                'key'   => 'completed',
                'label' => 'Shift Completed',
                'count' => $completedCount,
                'icon'  => 'fa-solid fa-circle-check',
                'color' => 'emerald',
            ],
        ];

        // Level 1: Actionable Attention Items for Operator
        $attentionItems = [];

        // 1. Delayed runs
        $delayedJobsList = ProductionJob::where('status', 'delayed')
            ->with(['order.user', 'machine'])
            ->take(3)
            ->get();
        foreach ($delayedJobsList as $dj) {
            $attentionItems[] = [
                'title'        => "Delayed Press Run: {$dj->job_number}",
                'description'  => "Press: " . ($dj->machine->name ?? 'Press Unit') . ". Reason: " . ($dj->delay_reason ?: 'Stoppage reported on line.'),
                'severity'     => 'critical',
                'icon'         => 'fa-solid fa-triangle-exclamation',
                'badge'        => 'DELAY REPORTED',
                'meta'         => "Reported " . ($dj->updated_at ? $dj->updated_at->diffForHumans() : 'Recently'),
                'action_url'   => route('production.jobs.status-form', $dj->id),
                'action_label' => 'Update Status',
            ];
        }

        // 2. Urgent priority runs
        $urgentJobs = ProductionJob::whereIn('priority', ['urgent', 'rush'])
            ->whereNotIn('status', ['completed'])
            ->with(['order.user', 'machine'])
            ->take(3)
            ->get();
        foreach ($urgentJobs as $uj) {
            $attentionItems[] = [
                'title'        => "Priority Turnaround: {$uj->job_number} (" . strtoupper($uj->priority) . ")",
                'description'  => "Customer: " . ($uj->order->user->name ?? 'Client') . " &middot; Machine: " . ($uj->machine->name ?? 'Any compatible'),
                'severity'     => 'warning',
                'icon'         => 'fa-solid fa-bolt',
                'badge'        => strtoupper($uj->priority),
                'meta'         => "Current Status: " . ucfirst($uj->status),
                'action_url'   => route('production.jobs.status-form', $uj->id),
                'action_label' => 'Set Status',
            ];
        }

        return view('production.dashboard', compact(
            'assignedCount',
            'inProductionCount',
            'dueTodayCount',
            'completedCount',
            'delayedCount',
            'myJobs',
            'pressMachines',
            'pipeline',
            'attentionItems'
        ));
    }
}
