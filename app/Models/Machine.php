<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use Auditable;
    protected $fillable = [
        'branch_id', 'name', 'type', 'model',
        'status', 'jobs_per_day_capacity', 'notes',
        'last_maintenance_date', 'next_maintenance_date',
    ];

    protected $casts = [
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
    ];

    /** Returns true if the machine is overdue for maintenance. */
    public function getMaintenanceOverdueAttribute(): bool
    {
        return $this->next_maintenance_date
            && $this->next_maintenance_date->isPast();
    }

    /** Returns true if scheduled maintenance is within the next 7 days. */
    public function getMaintenanceDueSoonAttribute(): bool
    {
        return $this->next_maintenance_date
            && !$this->next_maintenance_date->isPast()
            && $this->next_maintenance_date->diffInDays(now()) <= 7;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function productionJobs()
    {
        return $this->hasMany(ProductionJob::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available'   => 'Available',
            'in_use'      => 'In Use',
            'maintenance' => 'Maintenance',
            'offline'     => 'Offline',
            default       => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'available'   => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30',
            'in_use'      => 'bg-blue-500/15 text-blue-700 dark:text-blue-400 border border-blue-500/30',
            'maintenance' => 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border border-amber-500/30',
            'offline'     => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30',
            default       => 'bg-cyber-sub text-cyber-muted border border-cyber',
        };
    }
}
