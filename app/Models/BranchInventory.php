<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchInventory extends Model
{
    protected $table = 'branch_inventory';

    protected $fillable = [
        'branch_id', 'material_id', 'quantity', 'minimum_stock', 'status', 'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
        'quantity'     => 'decimal:2',
        'minimum_stock'=> 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function stockMovements()
    {
        return $this->hasManyThrough(
            StockMovement::class,
            Material::class,
            'id',
            'material_id',
            'material_id',
            'id'
        );
    }

    /**
     * Recompute status based on quantity vs minimum_stock.
     */
    public function recalculateStatus(): void
    {
        if ($this->quantity <= 0) {
            $this->status = 'out_of_stock';
        } elseif ($this->quantity <= $this->minimum_stock) {
            $this->status = 'low_stock';
        } else {
            $this->status = 'available';
        }
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available'    => 'Available',
            'low_stock'    => 'Low Stock',
            'out_of_stock' => 'Out of Stock',
            default        => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'available'    => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30',
            'low_stock'    => 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border border-amber-500/30',
            'out_of_stock' => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30',
            default        => 'bg-cyber-sub text-cyber-muted border border-cyber',
        };
    }
}
