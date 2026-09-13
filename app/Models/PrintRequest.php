<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class PrintRequest extends Model
{
    use Auditable;
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
        'proof_file_path',
        'proof_status',
        'proof_notes',
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

    public function branchRecommendation()
    {
        return $this->hasOne(BranchRecommendation::class);
    }

    public function capacityEvaluations()
    {
        return $this->hasMany(CapacityEvaluation::class);
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

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'submitted'          => 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-400',
            'quotation'          => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-500/15 dark:text-indigo-400',
            'payment'            => 'bg-orange-100 text-orange-800 dark:bg-orange-500/15 dark:text-orange-400',
            'branch_recommended' => 'bg-purple-100 text-purple-800 dark:bg-purple-500/15 dark:text-purple-400',
            'production'         => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/15 dark:text-cyan-400',
            'completed'          => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-400',
            'ready_for_pickup'   => 'bg-teal-100 text-teal-800 dark:bg-teal-500/15 dark:text-teal-400',
            'claimed'            => 'bg-slate-100 text-slate-800 dark:bg-slate-500/15 dark:text-slate-400',
            default              => 'bg-slate-100 text-slate-700 dark:bg-slate-500/15 dark:text-slate-400',
        };
    }
}
