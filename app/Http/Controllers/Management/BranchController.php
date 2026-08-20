<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount([
            'machines',
            'employees',
            'productionJobs as active_jobs' => fn($q) => $q->whereNotIn('status', ['completed']),
            'productionJobs as completed_jobs' => fn($q) => $q->where('status', 'completed'),
            'productionJobs as delayed_jobs'   => fn($q) => $q->where('status', 'delayed'),
        ])->get();

        return view('management.branches.index', compact('branches'));
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
