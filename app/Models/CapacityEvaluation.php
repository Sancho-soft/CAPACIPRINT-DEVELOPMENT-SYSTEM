<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapacityEvaluation extends Model
{
    protected $fillable = [
        'print_request_id', 'branch_id', 'evaluated_by',
        'machine_score', 'material_score', 'employee_score',
        'workload_score', 'deadline_score', 'total_score',
        'capacity_status', 'available_machines', 'current_workload_pct',
        'estimated_completion', 'deadline_feasible', 'evaluation_notes',
    ];

    protected $casts = [
        'estimated_completion' => 'date',
        'deadline_feasible'    => 'boolean',
        'current_workload_pct' => 'decimal:2',
    ];

    public function printRequest()
    {
        return $this->belongsTo(PrintRequest::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function evaluatedBy()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function getCapacityStatusLabelAttribute(): string
    {
        return match ($this->capacity_status) {
            'qualified'     => 'Qualified',
            'near_capacity' => 'Near Capacity',
            'not_qualified' => 'Not Qualified',
            'over_capacity' => 'Over Capacity',
            'unavailable'   => 'Unavailable',
            default         => ucfirst($this->capacity_status),
        };
    }

    public function getCapacityStatusBadgeClassAttribute(): string
    {
        return match ($this->capacity_status) {
            'qualified'     => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30',
            'near_capacity' => 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border border-amber-500/30',
            'not_qualified' => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30',
            'over_capacity' => 'bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-500/40',
            'unavailable'   => 'bg-cyber-sub text-cyber-muted border border-cyber',
            default         => 'bg-cyber-sub text-cyber-muted border border-cyber',
        };
    }
}
