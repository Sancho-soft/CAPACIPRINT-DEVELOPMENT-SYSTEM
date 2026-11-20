<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class ProductionJob extends Model
{
    use Auditable;
    protected $fillable = [
        'job_number', 'order_id', 'branch_id', 'machine_id', 'assigned_to',
        'status', 'priority', 'estimated_hours', 'delay_reason', 'remarks',
        'started_at', 'completed_at',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    const STATUSES = [
        'assigned', 'preparing', 'in_production',
        'quality_checking', 'completed', 'delayed',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'assigned'         => 'Assigned',
            'preparing'        => 'Preparing',
            'in_production'    => 'In Production',
            'quality_checking' => 'Quality Check',
            'completed'        => 'Completed',
            'delayed'          => 'Delayed',
            default            => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'assigned'         => 'bg-blue-500/15 text-blue-700 dark:text-blue-400 border border-blue-500/30',
            'preparing'        => 'bg-indigo-500/15 text-indigo-700 dark:text-indigo-400 border border-indigo-500/30',
            'in_production'    => 'bg-blue-500/15 text-blue-700 dark:text-blue-400 border border-blue-500/30',
            'quality_checking' => 'bg-purple-500/15 text-purple-700 dark:text-purple-400 border border-purple-500/30',
            'completed'        => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30',
            'delayed'          => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30',
            default            => 'bg-cyber-sub text-cyber-muted border border-cyber',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30',
            'rush'   => 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border border-amber-500/30',
            default  => 'bg-cyber-sub text-cyber-muted border border-cyber',
        };
    }
}
