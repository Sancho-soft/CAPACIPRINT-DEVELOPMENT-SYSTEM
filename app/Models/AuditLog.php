<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event',
        'module',
        'ip_address',
        'description',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record($event, $module, $description = null, $oldValues = null, $newValues = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'module' => $module,
            'ip_address' => request()->ip(),
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
