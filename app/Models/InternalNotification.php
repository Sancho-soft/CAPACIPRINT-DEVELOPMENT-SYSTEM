<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalNotification extends Model
{
    protected $table = 'internal_notifications';

    protected $fillable = [
        'user_id', 'order_id', 'title', 'body', 'type', 'link', 'is_read',
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
            'new_request' => 'fa-file-circle-plus',
            'quotation'   => 'fa-file-invoice-dollar',
            'payment'     => 'fa-credit-card',
            'production'  => 'fa-industry',
            'capacity'    => 'fa-chart-bar',
            'inventory'   => 'fa-boxes-stacked',
            default       => 'fa-bell',
        };
    }
}
