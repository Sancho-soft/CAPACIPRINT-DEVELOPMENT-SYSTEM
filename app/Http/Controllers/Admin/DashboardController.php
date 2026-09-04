<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\Order;
use App\Models\PrintRequest;
use App\Models\ProductionJob;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // System-wide User Stats
        $totalUsers    = User::count();
        $totalBranches = Branch::count();
        $totalOrders   = Order::count();
        $totalRequests = PrintRequest::count();

        // Role distribution
        $roleBreakdown = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        // Active production jobs
        $activeJobs = ProductionJob::where('status', 'in_production')->count();
        $delayedJobs = ProductionJob::where('status', 'delayed')->count();

        // Recent user registrations (6 per page)
        $recentUsers = User::latest()->paginate(6);

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Pending requests
        $pendingRequests = PrintRequest::whereIn('status', ['submitted', 'verified'])->count();
        $inProductionRequests = PrintRequest::where('status', 'production')->count();

        // Branch status
        $branches = Branch::withCount([
            'machines',
        ])->get();

        // Active task assignments
        $activeAssignments = ProductionJob::with(['assignedTo', 'branch'])
            ->latest()
            ->take(5)
            ->get();

        // Recent audit logs (if model exists)
        $recentAuditLogs = collect();
        if (class_exists(\App\Models\AuditLog::class)) {
            try {
                $recentAuditLogs = \App\Models\AuditLog::latest()->take(6)->get();
            } catch (\Exception $e) {
                $recentAuditLogs = collect();
            }
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBranches',
            'totalOrders',
            'totalRequests',
            'roleBreakdown',
            'activeJobs',
            'delayedJobs',
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
