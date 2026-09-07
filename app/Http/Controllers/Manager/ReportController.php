<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\ProductionJob;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $totalOrders     = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $activeOrders    = Order::whereNotIn('status', ['completed', 'claimed'])->count();
        $delayedJobs     = ProductionJob::where('status', 'delayed')->count();
        $branchesCount   = Branch::where('status', 'active')->count();
        $totalJobsCount  = ProductionJob::count();

        return view('manager.reports.index', compact(
            'totalOrders', 'completedOrders', 'activeOrders', 'delayedJobs', 'branchesCount', 'totalJobsCount'
        ));
    }

    public function production(Request $request)
    {
        $query = ProductionJob::with(['order.user', 'order.printRequest', 'branch', 'machine', 'assignedTo']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('job_number', 'like', "%{$search}%")
                  ->orWhereHas('order.user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('branch', fn($b) => $b->where('name', 'like', "%{$search}%"));
            });
        }

        $jobs = $query->latest()->paginate(15)->withQueryString();
        $selectedStatus = $request->get('status', '');
        $searchQuery = $request->get('search', '');

        return view('manager.reports.production', compact('jobs', 'selectedStatus', 'searchQuery'));
    }

    public function capacity()
    {
        $branches = Branch::where('status', 'active')
            ->with(['productionJobs' => fn($q) => $q->whereNotIn('status', ['completed'])])
            ->withCount(['productionJobs', 'machines'])
            ->get();

        return view('manager.reports.capacity', compact('branches'));
    }
}
