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
    public function isCustomer(): bool   { return $this->role === 'customer'; }
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isStaff(): bool      { return $this->role === 'staff'; }
    public function isManager(): bool    { return $this->role === 'manager'; }
    public function isProduction(): bool { return $this->role === 'production'; }
    public function isInventory(): bool  { return $this->role === 'inventory'; }
    public function isManagement(): bool { return $this->role === 'management'; }

    public function isInternal(): bool
    {
        return in_array($this->role, ['staff','manager','production','inventory','management','admin']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'customer'   => 'Customer',
            'staff'      => 'Sales / Customer Service',
            'manager'    => 'Branch Manager / Supervisor',
            'production' => 'Production Staff',
            'inventory'  => 'Inventory Staff',
            'management' => 'Owner / Management',
            'admin'      => 'System Administrator',
            default      => ucfirst($this->role),
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
