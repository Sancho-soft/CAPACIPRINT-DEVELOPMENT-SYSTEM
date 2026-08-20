<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'branch_id', 'name', 'type', 'model',
        'status', 'jobs_per_day_capacity', 'notes',
    ];

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
