<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'branch_id',
        'is_archived',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_archived' => 'boolean',
        ];
    }

    public function isOwner(): bool             { return in_array($this->role, ['owner', 'management']); }
    public function isAdmin(): bool             { return in_array($this->role, ['admin', 'system_admin']); }
    public function isManager(): bool           { return $this->role === 'manager'; }
    public function isProductionOfficer(): bool { return in_array($this->role, ['production_officer', 'planner']); }
    public function isStaff(): bool             { return $this->role === 'staff'; }
    public function isDesigner(): bool          { return $this->role === 'designer'; }
    public function isProduction(): bool        { return $this->role === 'production'; }
    public function isInventory(): bool         { return $this->role === 'inventory'; }
    public function isCustomer(): bool          { return $this->role === 'customer'; }
    public function isManagement(): bool        { return $this->isOwner(); }

    public function isInternal(): bool
    {
        return in_array($this->role, ['system_admin', 'owner', 'admin', 'manager', 'production_officer', 'planner', 'staff', 'designer', 'production', 'inventory', 'management']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'system_admin'       => 'System Admin',
            'admin'              => 'System Admin',
            'owner'              => 'Owner (Executive)',
            'manager'            => 'Branch Manager',
            'production_officer' => 'Production Officer',
            'staff'              => 'Customer Service (CS)',
            'designer'           => 'Layout Designer',
            'production'         => 'Production Operator',
            'inventory'          => 'Inventory Staff',
            'customer'           => 'Customer',
            'management'         => 'Owner (Executive)',
            default              => ucfirst($this->role),
        };
    }

    // ── Relationships ─────────────────────────────────────────
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function printRequests()
    {
        return $this->hasMany(PrintRequest::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(CustomerNotification::class);
    }

    public function claimReferences()
    {
        return $this->hasMany(ClaimReference::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
