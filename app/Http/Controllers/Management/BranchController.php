<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with(['machines'])
            ->withCount([
                'machines',
                'employees',
                'productionJobs as active_jobs' => fn($q) => $q->whereNotIn('status', ['completed']),
                'productionJobs as completed_jobs' => fn($q) => $q->where('status', 'completed'),
                'productionJobs as delayed_jobs'   => fn($q) => $q->where('status', 'delayed'),
            ])->get();

        $totalActiveJobs    = $branches->sum('active_jobs');
        $totalCompletedJobs = $branches->sum('completed_jobs');
        $totalDelayedJobs   = $branches->sum('delayed_jobs');
        $totalMachines      = $branches->sum('machines_count');
        $totalDailyCapacity = $branches->sum('max_daily_jobs');

        return view('management.branches.index', compact(
            'branches',
            'totalActiveJobs',
            'totalCompletedJobs',
            'totalDelayedJobs',
            'totalMachines',
            'totalDailyCapacity'
        ));
    }

    public function show(Branch $branch)
    {
        $branch->load(['machines', 'employees', 'inventory.material']);
        $branch->loadCount([
            'productionJobs as active_jobs'   => fn($q) => $q->whereNotIn('status', ['completed']),
            'productionJobs as completed_jobs' => fn($q) => $q->where('status', 'completed'),
        ]);

        return view('management.branches.show', compact('branch'));
    }
}
