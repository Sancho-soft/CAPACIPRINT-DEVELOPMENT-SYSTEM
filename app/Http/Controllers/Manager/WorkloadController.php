<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ProductionJob;
use Illuminate\Http\Request;

class WorkloadController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::where('status', 'active')
            ->with([
                'productionJobs' => fn($q) => $q->whereNotIn('status', ['completed']),
                'machines',
                'employees',
            ])
            ->withCount([
                'productionJobs as active_job_count' => fn($q) => $q->whereNotIn('status', ['completed']),
                'productionJobs as delayed_count'    => fn($q) => $q->where('status', 'delayed'),
                'productionJobs as rush_count'       => fn($q) => $q->whereIn('priority', ['rush', 'urgent'])->whereNotIn('status', ['completed']),
            ])->get();

        $branchFilter = $request->get('branch_id');
        $jobQuery = ProductionJob::with(['order.user', 'order.printRequest', 'branch'])
            ->whereNotIn('status', ['completed']);

        if ($branchFilter) {
            $jobQuery->where('branch_id', $branchFilter);
        }

        $jobs = $jobQuery->latest()->paginate(20);

        return view('manager.workload.index', compact('branches', 'jobs', 'branchFilter'));
    }
}
