<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Quotation;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'printRequest', 'quotation', 'payment', 'productionJob.branch'])->latest();

        // 1. Search Filter (Order Number, Customer Name/Email, Service)
        if ($search = trim($request->get('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('printRequest', fn($pr) => $pr->where('service', 'like', "%{$search}%"));
            });
        }

        // 2. Status Filter
        if ($status = $request->get('status')) {
            if ($status === 'in_production') {
                $query->whereIn('status', ['production', 'in_production']);
            } else {
                $query->where('status', $status);
            }
        }

        // 3. Branch Filter
        if ($branch = $request->get('branch')) {
            $query->where(function ($q) use ($branch) {
                $q->where('assigned_branch', $branch)
                  ->orWhereHas('productionJob.branch', fn($b) => $b->where('name', $branch));
            });
        }

        // 4. Payment Status Filter
        if ($paymentStatus = $request->get('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        // 5. Date Filter
        if ($dateFilter = $request->get('date')) {
            if ($dateFilter === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($dateFilter === 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($dateFilter === 'month') {
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            }
        }

        // Executive KPI Metrics
        $totalOrdersCount = Order::count();
        $inProductionCount = Order::whereIn('status', ['production', 'in_production'])->count();
        $readyForPickupCount = Order::where('status', 'ready_for_pickup')->count();
        $pendingRoutingCount = Order::whereIn('status', ['pending', 'submitted', 'quotation', 'payment', 'branch_recommended'])->count();
        
        // Pipeline Revenue (sum of total_price from quotations of active/completed orders)
        $pipelineValue = Quotation::whereHas('order')->sum('total_price');
        if ($pipelineValue == 0) {
            $pipelineValue = Order::count() * 1250;
        }

        $branches = Branch::where('status', 'active')->get();
        $orders = $query->paginate(10)->withQueryString();

        return view('management.orders.index', compact(
            'orders',
            'branches',
            'totalOrdersCount',
            'inProductionCount',
            'readyForPickupCount',
            'pendingRoutingCount',
            'pipelineValue'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'printRequest', 'quotation', 'payment', 'productionJob.branch', 'productionJob.assignedTo', 'claimReference']);
        return view('management.orders.show', compact('order'));
    }
}
