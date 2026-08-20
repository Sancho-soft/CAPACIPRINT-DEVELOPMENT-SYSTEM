<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name', 'location', 'address', 'phone', 'manager_name',
        'status', 'max_daily_jobs',
    ];

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function inventory()
    {
        return $this->hasMany(BranchInventory::class);
    }

    public function productionJobs()
    {
        return $this->hasMany(ProductionJob::class);
    }

    public function capacityEvaluations()
    {
        return $this->hasMany(CapacityEvaluation::class);
    }

    public function getAvailableMachinesCountAttribute(): int
    {
        return $this->machines()->where('status', 'available')->count();
    }

    public function getActiveJobsCountAttribute(): int
    {
        return $this->productionJobs()
            ->whereNotIn('status', ['completed'])
            ->count();
    }

    public function getWorkloadPercentAttribute(): float
    {
        $capacity = max($this->max_daily_jobs, 1);
        return min(round(($this->active_jobs_count / $capacity) * 100, 1), 100);
    }
}
