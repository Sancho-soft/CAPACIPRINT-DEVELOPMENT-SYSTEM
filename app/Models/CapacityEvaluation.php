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
            'qualified'     => 'bg-green-100 text-green-800',
            'near_capacity' => 'bg-amber-100 text-amber-800',
            'not_qualified' => 'bg-red-100 text-red-800',
            'over_capacity' => 'bg-red-200 text-red-900',
            'unavailable'   => 'bg-slate-200 text-slate-600',
            default         => 'bg-slate-100 text-slate-600',
        };
    }
}
