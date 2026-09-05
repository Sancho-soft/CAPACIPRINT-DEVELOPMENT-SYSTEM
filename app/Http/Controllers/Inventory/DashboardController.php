<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\BranchInventory;
use App\Models\StockMovement;
use App\Models\ProductionJob;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMaterials     = Material::where('is_active', true)->count();
        $availableCount     = BranchInventory::where('status', 'available')->count();
        $lowStockCount      = BranchInventory::where('status', 'low_stock')->count();
        $outOfStockCount    = BranchInventory::where('status', 'out_of_stock')->count();

        // Recent stock movements
        $recentMovements = StockMovement::with(['material', 'branch', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // Critical low stock items
        $lowStockItems = BranchInventory::with(['material', 'branch'])
            ->whereIn('status', ['low_stock', 'out_of_stock'])
            ->latest()
            ->take(10)
            ->get();

        // Inventory & Material Consumption Pipeline
        $pipeline = [
            [
                'key'   => 'catalog',
                'label' => 'Active Media Items',
                'count' => $totalMaterials,
                'icon'  => 'fa-solid fa-boxes-stacked',
                'color' => 'cyan',
            ],
            [
                'key'   => 'optimal',
                'label' => 'Healthy Stock',
                'count' => $availableCount,
                'icon'  => 'fa-solid fa-circle-check',
                'color' => 'emerald',
            ],
            [
                'key'   => 'low',
                'label' => 'Below Reorder Point',
                'count' => $lowStockCount,
                'icon'  => 'fa-solid fa-triangle-exclamation',
                'color' => 'amber',
            ],
            [
                'key'   => 'critical',
                'label' => 'Depleted / Zero Stock',
                'count' => $outOfStockCount,
                'icon'  => 'fa-solid fa-circle-xmark',
                'color' => 'rose',
            ],
            [
                'key'   => 'movements',
                'label' => 'Recent Transactions',
                'count' => StockMovement::whereDate('created_at', today())->count(),
                'icon'  => 'fa-solid fa-arrow-right-arrow-left',
                'color' => 'indigo',
            ],
        ];

        // Level 1: Actionable Attention Center
        $attentionItems = [];
        foreach ($lowStockItems as $item) {
            $isDepleted = $item->status === 'out_of_stock';
            $attentionItems[] = [
                'title'        => ($isDepleted ? 'OUT OF STOCK: ' : 'Low Stock Warning: ') . ($item->material->name ?? 'Print Consumable'),
                'description'  => "Branch: {$item->branch->name} &middot; Current balance: {$item->quantity} {$item->material->unit} (Minimum threshold: {$item->minimum_stock})",
                'severity'     => $isDepleted ? 'critical' : 'warning',
                'icon'         => 'fa-solid fa-boxes-stacked',
                'badge'        => strtoupper(str_replace('_', ' ', $item->status)),
                'meta'         => "Threshold: {$item->minimum_stock} {$item->material->unit}",
                'action_url'   => route('inventory.stock-movements.create'),
                'action_label' => 'Record Stock In',
            ];
        }

        return view('inventory.dashboard', compact(
            'totalMaterials',
            'availableCount',
            'lowStockCount',
            'outOfStockCount',
            'recentMovements',
            'lowStockItems',
            'pipeline',
            'attentionItems'
        ));
    }
}
