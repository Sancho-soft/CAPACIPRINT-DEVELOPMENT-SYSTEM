<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\ProductionJob;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $assignedCount   = ProductionJob::where('assigned_to', $userId)->whereNotIn('status', ['completed'])->count();
        $inProductionCount = ProductionJob::where('assigned_to', $userId)->where('status', 'in_production')->count();
        $dueTodayCount   = ProductionJob::where('assigned_to', $userId)
            ->whereNotIn('status', ['completed'])
            ->whereHas('order', fn($q) => $q->whereDate('estimated_completion', today()))
            ->count();
        $completedCount  = ProductionJob::where('assigned_to', $userId)->where('status', 'completed')->count();
        $delayedCount    = ProductionJob::where('assigned_to', $userId)->where('status', 'delayed')->count();

        $myJobs = ProductionJob::where('assigned_to', $userId)
            ->whereNotIn('status', ['completed'])
            ->with(['order.printRequest', 'branch', 'machine'])
            ->orderByRaw("CASE priority WHEN 'urgent' THEN 0 WHEN 'rush' THEN 1 ELSE 2 END")
            ->latest()
            ->take(10)
            ->get();

        return view('production.dashboard', compact(
            'assignedCount', 'inProductionCount', 'dueTodayCount',
            'completedCount', 'delayedCount', 'myJobs'
        ));
    }
}
