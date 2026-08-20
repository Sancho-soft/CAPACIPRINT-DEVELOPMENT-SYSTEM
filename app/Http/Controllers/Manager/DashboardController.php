<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\ProductionJob;

class DashboardController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount([
            'productionJobs as active_jobs' => fn($q) => $q->whereNotIn('status', ['completed']),
        ])->where('status', 'active')->get();

        $totalActiveJobs  = ProductionJob::whereNotIn('status', ['completed'])->count();
        $jobsDueToday     = ProductionJob::whereHas('order', fn($q) => $q->whereDate('estimated_completion', today()))->whereNotIn('status', ['completed'])->count();
        $jobsDueTomorrow  = ProductionJob::whereHas('order', fn($q) => $q->whereDate('estimated_completion', today()->addDay()))->whereNotIn('status', ['completed'])->count();
        $delayedJobs      = ProductionJob::where('status', 'delayed')->count();
        $rushJobs         = ProductionJob::whereIn('priority', ['rush', 'urgent'])->whereNotIn('status', ['completed'])->count();

        $recentJobs = ProductionJob::with(['order.user', 'order.printRequest', 'branch'])
            ->whereNotIn('status', ['completed'])
            ->latest()
            ->take(8)
            ->get();

        return view('manager.dashboard', compact(
            'branches', 'totalActiveJobs', 'jobsDueToday',
            'jobsDueTomorrow', 'delayedJobs', 'rushJobs', 'recentJobs'
        ));
    }
}
