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
            'available' => 'bg-green-100 text-green-800',
            'on_leave'  => 'bg-amber-100 text-amber-800',
            'off_duty'  => 'bg-slate-100 text-slate-600',
            default     => 'bg-slate-100 text-slate-600',
        };
    }
}
