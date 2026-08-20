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
            'stock_in'   => 'bg-green-100 text-green-800',
            'stock_out'  => 'bg-red-100 text-red-800',
            'adjustment' => 'bg-blue-100 text-blue-800',
            default      => 'bg-slate-100 text-slate-600',
        };
    }
}
