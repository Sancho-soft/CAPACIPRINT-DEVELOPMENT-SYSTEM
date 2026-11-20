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
            'pending'   => 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border border-amber-500/30',
            'confirmed' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30',
            'declined'  => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30',
            'expired'   => 'bg-cyber-sub text-cyber-muted border border-cyber',
            default     => 'bg-cyber-sub text-cyber-muted border border-cyber',
        };
    }

    public function getFormattedNumberAttribute(): string
    {
        if (preg_match('/(\d+)$/', (string)$this->quotation_number, $matches)) {
            return str_pad($matches[1], 5, '0', STR_PAD_LEFT);
        }
        return str_pad($this->print_request_id ?? $this->id, 5, '0', STR_PAD_LEFT);
    }
}
