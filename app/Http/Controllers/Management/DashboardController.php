<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductionJob;
use App\Models\BranchInventory;
use App\Models\Branch;
use App\Models\Quotation;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders      = Order::count();
        $activeOrders     = Order::whereNotIn('status', ['completed', 'claimed'])->count();
        $completedOrders  = Order::whereIn('status', ['completed', 'claimed'])->count();
        $delayedJobs      = ProductionJob::where('status', 'delayed')->count();
        $pendingQuotations= Quotation::where('status', 'pending')->count();
        $inProduction     = Order::where('status', 'production')->count();
        $readyForPickup   = Order::where('status', 'ready_for_pickup')->count();
        $lowStockCount    = BranchInventory::whereIn('status', ['low_stock', 'out_of_stock'])->count();

        $branches = Branch::withCount([
            'productionJobs as active_jobs' => fn($q) => $q->whereNotIn('status', ['completed']),
        ])->get();

        $recentOrders = Order::with(['user', 'printRequest'])
            ->latest()->take(8)->get();

        return view('management.dashboard', compact(
            'totalOrders', 'activeOrders', 'completedOrders',
            'delayedJobs', 'pendingQuotations', 'inProduction',
            'readyForPickup', 'lowStockCount', 'branches', 'recentOrders'
        ));
    }
}
