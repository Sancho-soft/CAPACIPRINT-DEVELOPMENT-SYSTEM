<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNotification extends Model
{
    protected $table = 'customer_notifications';

    protected $fillable = [
        'user_id',
        'order_id',
        'title',
        'body',
        'type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'quotation'  => 'fa-file-invoice-dollar',
            'payment'    => 'fa-credit-card',
            'production' => 'fa-industry',
            'pickup'     => 'fa-truck-ramp-box',
            default      => 'fa-bell',
        };
    }
}
