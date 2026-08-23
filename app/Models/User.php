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
        ];
    }

    // ── Role helpers ──────────────────────────────────────────
    public function isCustomer(): bool          { return $this->role === 'customer'; }
    public function isSuperAdmin(): bool        { return in_array($this->role, ['superadmin', 'admin']); }
    public function isOwner(): bool             { return in_array($this->role, ['owner', 'management']); }
    public function isSysAdmin(): bool          { return in_array($this->role, ['sysadmin', 'admin']); }
    public function isBranchManager(): bool     { return in_array($this->role, ['manager', 'branch_manager']); }
    public function isProductionOfficer(): bool { return in_array($this->role, ['production_officer', 'manager']); }
    public function isCustomerService(): bool   { return in_array($this->role, ['staff', 'cs']); }
    public function isLayoutDesigner(): bool    { return in_array($this->role, ['designer', 'layout_designer']); }
    public function isProductionOperator(): bool{ return in_array($this->role, ['production', 'operator']); }
    public function isAdmin(): bool             { return in_array($this->role, ['admin', 'superadmin', 'sysadmin']); }
    public function isStaff(): bool             { return in_array($this->role, ['staff', 'cs']); }
    public function isManager(): bool           { return in_array($this->role, ['manager', 'branch_manager', 'production_officer']); }
    public function isProduction(): bool        { return in_array($this->role, ['production', 'operator']); }
    public function isInventory(): bool         { return $this->role === 'inventory'; }
    public function isManagement(): bool        { return in_array($this->role, ['management', 'owner']); }

    public function isInternal(): bool
    {
        return $this->role !== 'customer';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'superadmin'         => 'Super Admin',
            'management', 'owner'=> 'Owner (Executive)',
            'admin', 'sysadmin'  => 'System Admin',
            'manager'            => 'Branch Manager',
            'production_officer' => 'Production Officer',
            'staff', 'cs'        => 'Customer Service (CS)',
            'designer'           => 'Layout Designer',
            'production'         => 'Production Operator',
            'inventory'          => 'Inventory Staff',
            'customer'           => 'Customer',
            default              => ucfirst($this->role),
        };
    }

    // ── Relationships ─────────────────────────────────────────
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
}
