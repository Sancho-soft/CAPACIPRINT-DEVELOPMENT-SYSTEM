<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductionJob;
use App\Models\Quotation;
use App\Models\BranchInventory;
use App\Models\Branch;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Reports Hub index.
     */
    public function index()
    {
        $totalOrdersCount     = Order::count();
        $completedOrdersCount = Order::whereIn('status', ['completed', 'claimed', 'ready_for_pickup'])->count();
        $totalRevenue         = Quotation::where('status', 'confirmed')->sum('total_price');
        $activeBranchesCount  = Branch::where('status', 'active')->count();

        return view('management.reports.index', compact('totalOrdersCount', 'completedOrdersCount', 'totalRevenue', 'activeBranchesCount'));
    }

    /**
     * Financial & Orders Audit Report with Date-Range filtering and CSV Export.
     */
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'printRequest', 'quotation', 'payment'])->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($branch = $request->get('branch')) {
            $query->where('assigned_branch', $branch);
        }

        if ($from = $request->get('from_date')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->get('to_date')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // CSV Export Trigger
        if ($request->get('export') === 'csv') {
            return $this->exportOrdersCsv($query->get());
        }

        $orders   = $query->paginate(20)->withQueryString();
        $statuses = Order::statusSteps();
        $branches = Branch::where('status', 'active')->get();

        $totalFilteredRevenue = (clone $query)->join('quotations', 'orders.id', '=', 'quotations.order_id')
                                             ->sum('quotations.total_price');

        return view('management.reports.orders', compact('orders', 'statuses', 'branches', 'totalFilteredRevenue'));
    }

    /**
     * Production Execution & Efficiency Report with CSV Export.
     */
    public function production(Request $request)
    {
        $query = ProductionJob::with(['order.user', 'order.printRequest', 'branch', 'machine', 'assignedTo'])->latest();

        if ($branch = $request->get('branch_id')) {
            $query->where('branch_id', $branch);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        if ($from = $request->get('from_date')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->get('to_date')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // CSV Export Trigger
        if ($request->get('export') === 'csv') {
            return $this->exportProductionCsv($query->get());
        }

        $jobs     = $query->paginate(20)->withQueryString();
        $branches = Branch::where('status', 'active')->get();

        return view('management.reports.production', compact('jobs', 'branches'));
    }

    /**
     * Inventory & Stock Valuation Report with CSV Export.
     */
    public function inventory(Request $request)
    {
        $query = BranchInventory::with(['material', 'branch'])->orderBy('status');

        if ($branch = $request->get('branch_id')) {
            $query->where('branch_id', $branch);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // CSV Export Trigger
        if ($request->get('export') === 'csv') {
            return $this->exportInventoryCsv($query->get());
        }

        $inventory = $query->get();
        $byBranch  = Branch::with(['inventory.material'])->where('status', 'active')->get();

        return view('management.reports.inventory', compact('inventory', 'byBranch'));
    }

    /**
     * Capacity Utilization & Workload Report.
     */
    public function capacity()
    {
        $branches = Branch::with([
            'machines',
            'employees',
            'productionJobs' => fn($q) => $q->whereNotIn('status', ['completed'])
        ])->get();

        return view('management.reports.capacity', compact('branches'));
    }

    // ── CSV Stream Exporters ───────────────────────────────────

    private function exportOrdersCsv($orders): StreamedResponse
    {
        $fileName = 'CapaciPrint_Orders_Report_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order #', 'Customer Name', 'Customer Email', 'Service', 'Quantity', 'Total Price (PHP)', 'Branch', 'Status', 'Date Placed']);

            foreach ($orders as $o) {
                fputcsv($handle, [
                    $o->order_number,
                    $o->user->name ?? 'N/A',
                    $o->user->email ?? 'N/A',
                    $o->printRequest->service ?? 'N/A',
                    $o->printRequest->quantity ?? 1,
                    $o->quotation->total_price ?? 0.00,
                    $o->assigned_branch ?? 'Unassigned',
                    $o->status_label,
                    $o->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    private function exportProductionCsv($jobs): StreamedResponse
    {
        $fileName = 'CapaciPrint_Production_Report_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($jobs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Job #', 'Order #', 'Customer', 'Service', 'Branch', 'Machine', 'Technician', 'Priority', 'Status', 'Started At', 'Completed At']);

            foreach ($jobs as $j) {
                fputcsv($handle, [
                    $j->job_number,
                    $j->order->order_number ?? 'N/A',
                    $j->order->user->name ?? 'N/A',
                    $j->order->printRequest->service ?? 'N/A',
                    $j->branch->name ?? 'N/A',
                    $j->machine->name ?? 'Unassigned',
                    $j->assignedTo->name ?? 'Unassigned',
                    strtoupper($j->priority),
                    $j->status_label,
                    $j->started_at ? $j->started_at->format('Y-m-d H:i') : 'Not Started',
                    $j->completed_at ? $j->completed_at->format('Y-m-d H:i') : 'Pending',
                ]);
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    private function exportInventoryCsv($inventory): StreamedResponse
    {
        $fileName = 'CapaciPrint_Inventory_Report_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($inventory) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Branch', 'Material Name', 'Type / Specification', 'Current Stock', 'Unit', 'Reorder Level', 'Stock Status']);

            foreach ($inventory as $inv) {
                fputcsv($handle, [
                    $inv->branch->name ?? 'N/A',
                    $inv->material->name ?? 'N/A',
                    $inv->material->specification ?? 'N/A',
                    $inv->current_stock,
                    $inv->material->unit ?? 'units',
                    $inv->reorder_level,
                    ucfirst(str_replace('_', ' ', $inv->status)),
                ]);
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }
}
