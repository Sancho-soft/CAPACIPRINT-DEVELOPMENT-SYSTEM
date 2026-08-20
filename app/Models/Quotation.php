<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_number',
        'print_request_id',
        'user_id',
        'base_cost',
        'material_cost',
        'finishing_cost',
        'total_price',
        'valid_until',
        'status',
        'notes',
    ];

    protected $casts = [
        'valid_until'     => 'date',
        'base_cost'       => 'decimal:2',
        'material_cost'   => 'decimal:2',
        'finishing_cost'  => 'decimal:2',
        'total_price'     => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function printRequest()
    {
        return $this->belongsTo(PrintRequest::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Pending',
            'confirmed' => 'Confirmed',
            'declined'  => 'Declined',
            'expired'   => 'Expired',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'bg-amber-100 text-amber-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'declined'  => 'bg-red-100 text-red-800',
            'expired'   => 'bg-slate-100 text-slate-600',
            default     => 'bg-slate-100 text-slate-600',
        };
    }
}
