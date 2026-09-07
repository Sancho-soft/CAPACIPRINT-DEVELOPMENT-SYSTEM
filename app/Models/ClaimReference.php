<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimReference extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'claim_code',
        'pickup_branch',
        'completion_date',
        'is_claimed',
        'claimed_at',
    ];

    protected $casts = [
        'completion_date' => 'date',
        'claimed_at'      => 'datetime',
        'is_claimed'      => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusAttribute(): string
    {
        return $this->is_claimed ? 'claimed' : 'ready_for_pickup';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_claimed ? 'Claimed' : 'Ready for Pickup';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->is_claimed 
            ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border-emerald-500/30'
            : 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border-amber-500/30';
    }
}
