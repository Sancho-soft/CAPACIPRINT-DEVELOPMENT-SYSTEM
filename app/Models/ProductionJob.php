<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionJob extends Model
{
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
            'assigned'         => 'bg-blue-100 text-blue-800',
            'preparing'        => 'bg-indigo-100 text-indigo-800',
            'in_production'    => 'bg-cyan-100 text-cyan-800',
            'quality_checking' => 'bg-purple-100 text-purple-800',
            'completed'        => 'bg-green-100 text-green-800',
            'delayed'          => 'bg-red-100 text-red-800',
            default            => 'bg-slate-100 text-slate-600',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-red-100 text-red-800',
            'rush'   => 'bg-orange-100 text-orange-800',
            default  => 'bg-slate-100 text-slate-600',
        };
    }
}
