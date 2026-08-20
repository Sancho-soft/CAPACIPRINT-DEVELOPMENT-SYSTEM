<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name', 'type', 'unit', 'description', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function branchInventory()
    {
        return $this->hasMany(BranchInventory::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'paper'      => 'Paper / Media',
            'ink'        => 'Ink / Toner',
            'lamination' => 'Lamination',
            'binding'    => 'Binding',
            'other'      => 'Other',
            default      => ucfirst($this->type),
        };
    }
}
