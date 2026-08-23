<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'requested_by',
        'material_id',
        'quantity',
        'unit_cost',
        'total_amount',
        'status',
        'notes',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
