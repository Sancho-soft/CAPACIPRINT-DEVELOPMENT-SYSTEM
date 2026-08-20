<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'print_request_id',
        'quotation_id',
        'assigned_branch',
        'estimated_completion',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'estimated_completion' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function printRequest()
    {
        return $this->belongsTo(PrintRequest::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function claimReference()
    {
        return $this->hasOne(ClaimReference::class);
    }

    public function notifications()
    {
        return $this->hasMany(CustomerNotification::class);
    }

    // Ordered status steps for tracking timeline
    public static function statusSteps(): array
    {
        return [
            'submitted',
            'quotation',
            'payment',
            'branch_recommended',
            'production',
            'completed',
            'ready_for_pickup',
            'claimed',
        ];
    }

    public function getStatusIndexAttribute(): int
    {
        return array_search($this->status, self::statusSteps()) ?: 0;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'submitted'          => 'Submitted',
            'quotation'          => 'Quotation Ready',
            'payment'            => 'Awaiting Payment',
            'branch_recommended' => 'Branch Assigned',
            'production'         => 'In Production',
            'completed'          => 'Completed',
            'ready_for_pickup'   => 'Ready for Pickup',
            'claimed'            => 'Claimed',
            default              => ucfirst($this->status),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending'   => 'Pending',
            'submitted' => 'Submitted',
            'confirmed' => 'Confirmed',
            'rejected'  => 'Rejected',
            default     => ucfirst($this->payment_status),
        };
    }
}
