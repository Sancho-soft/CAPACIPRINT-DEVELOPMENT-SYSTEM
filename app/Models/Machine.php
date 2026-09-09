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
            'available'   => 'bg-green-100 text-green-800',
            'in_use'      => 'bg-blue-100 text-blue-800',
            'maintenance' => 'bg-amber-100 text-amber-800',
            'offline'     => 'bg-red-100 text-red-800',
            default       => 'bg-slate-100 text-slate-600',
        };
    }
}
