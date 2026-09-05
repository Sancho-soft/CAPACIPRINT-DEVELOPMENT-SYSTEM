<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductionJob;
use App\Models\BranchInventory;
use App\Models\Branch;
use App\Models\Quotation;
use App\Models\PrintRequest;
use App\Models\Payment;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\DB;

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

        // Revenue metrics from real confirmed payments
        $totalRevenue = Payment::whereIn('status', ['verified', 'confirmed'])->sum('amount');
        $pendingPayments = Payment::where('status', 'submitted')->sum('amount');

        // Multi-Branch with machines & active jobs
        $branches = Branch::with(['machines'])
            ->withCount([
                'machines',
                'productionJobs as active_jobs' => fn($q) => $q->whereNotIn('status', ['completed']),
            ])->get();

        // Print Services Breakdown for Chart.js
        $serviceBreakdown = PrintRequest::select('service', DB::raw('count(*) as count'))
            ->groupBy('service')
            ->pluck('count', 'service')
            ->toArray();

        // Commercial Printing Pipeline
        $pipeline = [
            [
                'key'   => 'intake',
                'label' => 'Technical Specs',
                'count' => PrintRequest::whereIn('status', ['submitted', 'verified'])->count(),
                'icon'  => 'fa-solid fa-file-arrow-up',
                'color' => 'cyan',
            ],
            [
                'key'   => 'quotation',
                'label' => 'Quotation Matrix',
                'count' => Quotation::where('status', 'pending')->count(),
                'icon'  => 'fa-solid fa-file-invoice-dollar',
                'color' => 'indigo',
            ],
            [
                'key'   => 'payment',
                'label' => 'Payment Cleared',
                'count' => Order::where('status', 'payment')->orWhere('payment_status', 'submitted')->count(),
                'icon'  => 'fa-solid fa-credit-card',
                'color' => 'amber',
            ],
            [
                'key'   => 'routing',
                'label' => 'Capacity Routing',
                'count' => ProductionJob::where('status', 'assigned')->count(),
                'icon'  => 'fa-solid fa-network-wired',
                'color' => 'blue',
            ],
            [
                'key'    => 'production',
                'label'  => 'On Press Floor',
                'count'  => ProductionJob::whereIn('status', ['preparing', 'in_production'])->count(),
                'icon'   => 'fa-solid fa-industry',
                'color'  => 'teal',
                'active' => true,
            ],
            [
                'key'   => 'qc',
                'label' => 'Quality Check',
                'count' => ProductionJob::where('status', 'quality_checking')->count(),
                'icon'  => 'fa-solid fa-microscope',
                'color' => 'purple',
            ],
            [
                'key'   => 'ready',
                'label' => 'Ready / Claimed',
                'count' => Order::whereIn('status', ['ready_for_pickup', 'completed', 'claimed'])->count(),
                'icon'  => 'fa-solid fa-box-open',
                'color' => 'emerald',
            ],
        ];

        // Level 1: Actionable Attention Items
        $attentionItems = [];

        // 1. Pending purchase requests awaiting executive approval
        if (class_exists(PurchaseRequest::class)) {
            $pendingPurchases = PurchaseRequest::where('status', 'pending')->with(['branch', 'material'])->take(3)->get();
            foreach ($pendingPurchases as $pr) {
                $attentionItems[] = [
                    'title'        => "Purchase Requisition: " . ($pr->material->name ?? 'Consumables'),
                    'description'  => "Branch: " . ($pr->branch->name ?? 'Hub') . " requested {$pr->quantity} units (Total Est: ₱" . number_format($pr->total_amount ?? 0, 2) . ")",
                    'severity'     => 'warning',
                    'icon'         => 'fa-solid fa-file-invoice-dollar',
                    'badge'        => 'APPROVAL REQUIRED',
                    'meta'         => "Submitted " . ($pr->created_at ? $pr->created_at->diffForHumans() : 'Recently'),
                    'action_url'   => route('management.purchasing.index'),
                    'action_label' => 'Review Request',
                ];
            }
        }

        // 2. Delayed production jobs
        $delayedJobsList = ProductionJob::where('status', 'delayed')->with(['branch', 'machine'])->take(3)->get();
        foreach ($delayedJobsList as $dj) {
            $attentionItems[] = [
                'title'        => "Production Stoppage: {$dj->job_number}",
                'description'  => "Assigned to {$dj->branch->name}. Reason: " . ($dj->delay_reason ?: 'Press floor delay reported.'),
                'severity'     => 'critical',
                'icon'         => 'fa-solid fa-triangle-exclamation',
                'badge'        => 'DELAYED PRESS',
                'meta'         => "Status: " . ucfirst($dj->status),
                'action_url'   => route('management.production.index'),
                'action_label' => 'View Production',
            ];
        }

        // 3. Low stock inventory warnings
        $lowMaterials = BranchInventory::whereIn('status', ['low_stock', 'out_of_stock'])->with(['material', 'branch'])->take(2)->get();
        foreach ($lowMaterials as $mat) {
            $attentionItems[] = [
                'title'        => "Low Raw Material: " . ($mat->material->name ?? 'Consumable'),
                'description'  => "Stock at {$mat->branch->name} is {$mat->quantity} {$mat->material->unit} (Min: {$mat->minimum_stock})",
                'severity'     => 'warning',
                'icon'         => 'fa-solid fa-boxes-stacked',
                'badge'        => 'LOW STOCK',
                'meta'         => "Inventory Warning",
                'action_url'   => route('management.inventory.index'),
                'action_label' => 'Inspect Inventory',
            ];
        }

        // Recent orders
        $recentOrders = Order::with(['user', 'printRequest', 'quotation'])
            ->latest()
            ->take(8)
            ->get();

        return view('management.dashboard', compact(
            'totalOrders',
            'activeOrders',
            'completedOrders',
            'delayedJobs',
            'pendingQuotations',
            'inProduction',
            'readyForPickup',
            'lowStockCount',
            'totalRevenue',
            'pendingPayments',
            'branches',
            'serviceBreakdown',
            'pipeline',
            'attentionItems',
            'recentOrders'
        ));
    }
}
