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
}
