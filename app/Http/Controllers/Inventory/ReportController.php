<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\Material;
use App\Models\StockMovement;
use App\Models\Branch;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $branchId = $request->get('branch_id');
        $category = $request->get('category');
        $status   = $request->get('status');
        $search   = $request->get('search');
        $export   = $request->get('export');

        // Base query for branch inventory
        $stockQuery = BranchInventory::with(['material', 'branch']);

        if (!empty($branchId)) {
            $stockQuery->where('branch_id', $branchId);
        }

        if (!empty($category)) {
            $stockQuery->whereHas('material', function ($q) use ($category) {
                $q->where('type', $category);
            });
        }

        if (!empty($status)) {
            $stockQuery->where('status', $status);
        }

        if (!empty($search)) {
            $stockQuery->whereHas('material', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // CSV Export Handlers
        if ($export === 'stock_csv' || $export === 'csv') {
            return $this->exportStockCsv($stockQuery->orderBy('branch_id')->get());
        }

        if ($export === 'reorder_csv') {
            $reorderQuery = clone $stockQuery;
            $reorderItems = $reorderQuery->whereIn('status', ['low_stock', 'out_of_stock'])
                ->orderByRaw("CASE WHEN status = 'out_of_stock' THEN 1 ELSE 2 END")
                ->get();
            return $this->exportReorderCsv($reorderItems);
        }

        if ($export === 'movements_csv') {
            $movQuery = StockMovement::with(['material', 'branch', 'user'])->latest();
            if (!empty($branchId)) {
                $movQuery->where('branch_id', $branchId);
            }
            if (!empty($category)) {
                $movQuery->whereHas('material', fn($q) => $q->where('type', $category));
            }
            if (!empty($search)) {
                $movQuery->whereHas('material', fn($q) => $q->where('name', 'like', "%{$search}%"));
            }
            return $this->exportMovementsCsv($movQuery->get());
        }

        // KPI metrics (respecting active branch and category filters)
        $kpiQuery = BranchInventory::query();
        if (!empty($branchId)) {
            $kpiQuery->where('branch_id', $branchId);
        }
        if (!empty($category)) {
            $kpiQuery->whereHas('material', fn($q) => $q->where('type', $category));
        }

        $totalTracked    = (clone $kpiQuery)->count();
        $lowStockCount   = (clone $kpiQuery)->where('status', 'low_stock')->count();
        $outOfStockCount = (clone $kpiQuery)->where('status', 'out_of_stock')->count();
        $availableCount  = (clone $kpiQuery)->where('status', 'available')->count();
        $healthRate      = $totalTracked > 0 ? round(($availableCount / $totalTracked) * 100) : 100;
        $totalQuantity   = (clone $kpiQuery)->sum('quantity');

        // Total active materials
        $matQuery = Material::where('is_active', true);
        if (!empty($category)) {
            $matQuery->where('type', $category);
        }
        $totalMaterials = $matQuery->count();

        // Critical reorder items
        $reorderQuery = (clone $stockQuery)->whereIn('status', ['low_stock', 'out_of_stock'])
            ->orderByRaw("CASE WHEN status = 'out_of_stock' THEN 1 ELSE 2 END");
        $reorderItems = $reorderQuery->get();

        // Comprehensive inventory records paginated by 7 items per page
        $inventory = (clone $stockQuery)
            ->orderByRaw("CASE WHEN status = 'out_of_stock' THEN 1 WHEN status = 'low_stock' THEN 2 ELSE 3 END")
            ->paginate(7)
            ->withQueryString();

        // Branch breakdown with their inventory
        $byBranch = Branch::with(['inventory.material'])
            ->where('status', 'active')
            ->get();

        // Active branches for filter dropdown
        $branches = Branch::where('status', 'active')->orderBy('name')->get();

        // Recent movements
        $movementsQuery = StockMovement::with(['material', 'branch', 'user'])->latest();
        if (!empty($branchId)) {
            $movementsQuery->where('branch_id', $branchId);
        }
        $recentMovements = $movementsQuery->take(15)->get();

        // Category breakdown
        $categories = [
            'paper'      => ['label' => 'Paper / Media',       'icon' => 'fa-newspaper',   'count' => 0],
            'ink'        => ['label' => 'Inks & Toners',       'icon' => 'fa-fill-drip',   'count' => 0],
            'lamination' => ['label' => 'Lamination Films',    'icon' => 'fa-layer-group', 'count' => 0],
            'binding'    => ['label' => 'Binding Materials',   'icon' => 'fa-book-open',   'count' => 0],
            'other'      => ['label' => 'Other Consumables',   'icon' => 'fa-box-archive', 'count' => 0],
        ];
        $typeCounts = Material::where('is_active', true)
            ->selectRaw('type, count(*) as cnt')
            ->groupBy('type')
            ->pluck('cnt', 'type')
            ->toArray();
        foreach ($typeCounts as $type => $cnt) {
            if (isset($categories[$type])) {
                $categories[$type]['count'] = $cnt;
            } else {
                $categories['other']['count'] += $cnt;
            }
        }

        return view('inventory.reports.index', compact(
            'totalMaterials', 'lowStockCount', 'outOfStockCount', 'availableCount',
            'healthRate', 'totalQuantity', 'reorderItems', 'inventory', 'byBranch', 'branches',
            'recentMovements', 'categories', 'branchId', 'category', 'status', 'search'
        ));
    }

    /**
     * Export Full Stock Balances Report to CSV.
     */
    private function exportStockCsv($inventory): StreamedResponse
    {
        $fileName = 'CapaciPrint_Stock_Inventory_Report_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($inventory) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel
            fputcsv($handle, ['Branch Location', 'Material Specification', 'Category', 'Current Balance', 'Unit', 'Safety Minimum', 'Stock Status', 'Last Updated']);

            foreach ($inventory as $inv) {
                fputcsv($handle, [
                    $inv->branch->name ?? 'Unassigned',
                    $inv->material->name ?? 'Unknown Material',
                    $inv->material->type_label ?? ucfirst($inv->material->type ?? 'General'),
                    number_format($inv->quantity, 2),
                    $inv->material->unit ?? 'units',
                    number_format($inv->minimum_stock, 2),
                    $inv->status_label,
                    $inv->last_updated ? $inv->last_updated->format('Y-m-d H:i') : ($inv->updated_at ? $inv->updated_at->format('Y-m-d H:i') : 'N/A'),
                ]);
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Export Urgent Reorder Deficit List to CSV.
     */
    private function exportReorderCsv($reorderItems): StreamedResponse
    {
        $fileName = 'CapaciPrint_Urgent_Reorder_List_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($reorderItems) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel
            fputcsv($handle, ['Material Specification', 'Category', 'Branch Location', 'Current Balance', 'Safety Minimum', 'Replenishment Deficit', 'Unit', 'Stock Status', 'Reorder Priority']);

            foreach ($reorderItems as $item) {
                $deficit = max(0, $item->minimum_stock - $item->quantity);
                $priority = $item->status === 'out_of_stock' ? 'CRITICAL / IMMEDIATE' : 'HIGH';

                fputcsv($handle, [
                    $item->material->name ?? 'Unknown Material',
                    $item->material->type_label ?? ucfirst($item->material->type ?? 'General'),
                    $item->branch->name ?? 'Unassigned',
                    number_format($item->quantity, 2),
                    number_format($item->minimum_stock, 2),
                    number_format($deficit, 2),
                    $item->material->unit ?? 'units',
                    $item->status_label,
                    $priority,
                ]);
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Export Stock Movements Audit Trail to CSV.
     */
    private function exportMovementsCsv($movements): StreamedResponse
    {
        $fileName = 'CapaciPrint_Stock_Movements_Audit_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($movements) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel
            fputcsv($handle, ['Date & Time', 'Material Specification', 'Category', 'Branch Location', 'Transaction Type', 'Quantity', 'Unit', 'Reference / Job #', 'Logged By', 'Reason / Remarks']);

            foreach ($movements as $mov) {
                fputcsv($handle, [
                    $mov->movement_date?->format('Y-m-d') ?? $mov->created_at->format('Y-m-d H:i'),
                    $mov->material->name ?? 'Unknown Material',
                    $mov->material->type_label ?? ucfirst($mov->material->type ?? 'General'),
                    $mov->branch->name ?? 'Unassigned',
                    $mov->movement_type_label,
                    number_format($mov->quantity, 2),
                    $mov->material->unit ?? 'units',
                    $mov->reference ?? 'Manual',
                    $mov->user->name ?? 'Staff',
                    $mov->reason ?? $mov->remarks ?? 'N/A',
                ]);
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}

