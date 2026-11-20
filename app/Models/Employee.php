<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id', 'branch_id', 'name', 'position', 'availability_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getAvailabilityLabelAttribute(): string
    {
        return match ($this->availability_status) {
            'available' => 'Available',
            'on_leave'  => 'On Leave',
            'off_duty'  => 'Off Duty',
            default     => ucfirst($this->availability_status),
        };
    }

    public function getAvailabilityBadgeClassAttribute(): string
    {
        return match ($this->availability_status) {
            'available' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30',
            'on_leave'  => 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border border-amber-500/30',
            'off_duty'  => 'bg-cyber-sub text-cyber-muted border border-cyber',
            default     => 'bg-cyber-sub text-cyber-muted border border-cyber',
        };
    }
}
