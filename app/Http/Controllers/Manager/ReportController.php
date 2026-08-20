<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\ProductionJob;

class ReportController extends Controller
{
    public function index()
    {
        $totalOrders     = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $activeOrders    = Order::whereNotIn('status', ['completed', 'claimed'])->count();
        $delayedJobs     = ProductionJob::where('status', 'delayed')->count();

        return view('manager.reports.index', compact(
            'totalOrders', 'completedOrders', 'activeOrders', 'delayedJobs'
        ));
    }

    public function production()
    {
        $jobs = ProductionJob::with(['order.user', 'order.printRequest', 'branch'])
            ->latest()->paginate(20);

        return view('manager.reports.production', compact('jobs'));
    }

    public function capacity()
    {
        $branches = Branch::with(['productionJobs' => fn($q) => $q->whereNotIn('status', ['completed'])])
            ->withCount(['productionJobs', 'machines'])
            ->get();

        return view('manager.reports.capacity', compact('branches'));
    }
}
