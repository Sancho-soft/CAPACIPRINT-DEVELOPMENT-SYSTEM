<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintRequest extends Model
{
    protected $fillable = [
        'user_id',
        'service',
        'quantity',
        'size',
        'material',
        'finishing',
        'deadline',
        'preferred_branch',
        'additional_instructions',
        'design_file_path',
        'design_file_name',
        'design_file_size',
        'collection_mode',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // Status constants
    const STATUS_SUBMITTED          = 'submitted';
    const STATUS_QUOTATION          = 'quotation';
    const STATUS_PAYMENT            = 'payment';
    const STATUS_BRANCH_RECOMMENDED = 'branch_recommended';
    const STATUS_PRODUCTION         = 'production';
    const STATUS_COMPLETED          = 'completed';
    const STATUS_READY_FOR_PICKUP   = 'ready_for_pickup';
    const STATUS_CLAIMED            = 'claimed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function designProofs()
    {
        return $this->hasMany(DesignProof::class);
    }

    public function latestProof()
    {
        return $this->hasOne(DesignProof::class)->latestOfMany();
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

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'submitted'          => 'blue',
            'quotation'          => 'amber',
            'payment'            => 'orange',
            'branch_recommended' => 'purple',
            'production'         => 'cyan',
            'completed'          => 'green',
            'ready_for_pickup'   => 'teal',
            'claimed'            => 'slate',
            default              => 'gray',
        };
    }
}
