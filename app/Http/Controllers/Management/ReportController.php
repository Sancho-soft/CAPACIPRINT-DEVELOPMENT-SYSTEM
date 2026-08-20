<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductionJob;
use App\Models\Quotation;
use App\Models\BranchInventory;
use App\Models\Branch;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('management.reports.index');
    }

    public function orders(Request $request)
    {
        $query = Order::with(['user', 'printRequest'])->latest();
        if ($status = $request->get('status')) $query->where('status', $status);

        $orders   = $query->paginate(20);
        $statuses = Order::statusSteps();
        return view('management.reports.orders', compact('orders', 'statuses'));
    }

    public function production(Request $request)
    {
        $jobs = ProductionJob::with(['order.user', 'order.printRequest', 'branch'])
            ->when($request->branch_id, fn($q, $b) => $q->where('branch_id', $b))
            ->latest()->paginate(20);

        $branches = Branch::where('status', 'active')->get();
        return view('management.reports.production', compact('jobs', 'branches'));
    }

    public function inventory()
    {
        $inventory   = BranchInventory::with(['material', 'branch'])->orderBy('status')->get();
        $byBranch    = Branch::with(['inventory.material'])->where('status', 'active')->get();
        return view('management.reports.inventory', compact('inventory', 'byBranch'));
    }

    public function capacity()
    {
        $branches = Branch::with([
            'machines',
            'productionJobs' => fn($q) => $q->whereNotIn('status', ['completed'])
        ])->get();

        return view('management.reports.capacity', compact('branches'));
    }
}
