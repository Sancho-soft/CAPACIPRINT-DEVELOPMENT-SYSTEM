<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'branch_id', 'material_id', 'user_id', 'quantity',
        'movement_type', 'movement_date', 'reference', 'reason', 'remarks',
    ];

    protected $casts = [
        'movement_date' => 'date',
        'quantity'      => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMovementTypeLabelAttribute(): string
    {
        return match ($this->movement_type) {
            'stock_in'   => 'Stock In',
            'stock_out'  => 'Stock Out',
            'adjustment' => 'Adjustment',
            default      => ucfirst($this->movement_type),
        };
    }

    public function getMovementTypeBadgeClassAttribute(): string
    {
        return match ($this->movement_type) {
            'stock_in'   => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30',
            'stock_out'  => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30',
            'adjustment' => 'bg-blue-500/15 text-blue-700 dark:text-blue-400 border border-blue-500/30',
            default      => 'bg-cyber-sub text-cyber-muted border border-cyber',
        };
    }
}
