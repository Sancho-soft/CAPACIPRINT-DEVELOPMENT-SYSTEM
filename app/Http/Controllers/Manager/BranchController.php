<?php

namespace App\Http\Controllers\Manager;

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
        ])->get();

        return view('manager.branches.index', compact('branches'));
    }

    public function show(Branch $branch)
    {
        $branch->load([
            'machines',
            'employees',
            'productionJobs' => fn($q) => $q->with(['order.user', 'order.printRequest'])->whereNotIn('status', ['completed']),
        ]);

        $completedCount = $branch->productionJobs()->where('status', 'completed')->count();

        return view('manager.branches.show', compact('branch', 'completedCount'));
    }
}
