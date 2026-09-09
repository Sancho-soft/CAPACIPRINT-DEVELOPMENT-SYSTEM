<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\ProductionJob;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        if ($from = $request->get('from_date')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->get('to_date')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // CSV Export
        if ($request->get('export') === 'csv') {
            return $this->exportProductionCsv($query->get());
        }

        $jobs = $query->latest()->paginate(15)->withQueryString();
        $selectedStatus = $request->get('status', '');
        $searchQuery    = $request->get('search', '');

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

    private function exportProductionCsv($jobs): StreamedResponse
    {
        $fileName = 'CapaciPrint_Production_Report_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($jobs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Job #', 'Order #', 'Customer', 'Service', 'Branch', 'Machine', 'Technician', 'Priority', 'Status', 'Started At', 'Completed At', 'Delay Reason']);

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
                    $j->started_at   ? $j->started_at->format('Y-m-d H:i')   : 'Not Started',
                    $j->completed_at ? $j->completed_at->format('Y-m-d H:i') : 'Pending',
                    $j->delay_reason ?? '',
                ]);
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }
}

