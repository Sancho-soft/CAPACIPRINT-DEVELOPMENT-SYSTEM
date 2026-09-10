<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\Material;
use App\Models\StockMovement;
use App\Models\Branch;

class ReportController extends Controller
{
    public function index()
    {
        $totalMaterials  = Material::where('is_active', true)->count();
        $lowStockCount   = BranchInventory::where('status', 'low_stock')->count();
        $outOfStockCount = BranchInventory::where('status', 'out_of_stock')->count();
        $availableCount  = BranchInventory::where('status', 'available')->count();
        $totalTracked    = BranchInventory::count();
        $healthRate      = $totalTracked > 0 ? round(($availableCount / $totalTracked) * 100) : 100;
        $totalQuantity   = BranchInventory::sum('quantity');

        // Critical reorder items (low_stock or out_of_stock)
        $reorderItems = BranchInventory::with(['material', 'branch'])
            ->whereIn('status', ['low_stock', 'out_of_stock'])
            ->orderByRaw("CASE WHEN status = 'out_of_stock' THEN 1 ELSE 2 END")
            ->get();

        // Branch breakdown with their inventory
        $byBranch = Branch::with(['inventory.material'])
            ->where('status', 'active')
            ->get();

        // Recent movements
        $recentMovements = StockMovement::with(['material', 'branch', 'user'])
            ->latest()
            ->take(15)
            ->get();

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
            'healthRate', 'totalQuantity', 'reorderItems', 'byBranch',
            'recentMovements', 'categories'
        ));
    }
}
