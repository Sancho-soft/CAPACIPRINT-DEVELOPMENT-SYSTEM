<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'print_request_id',
        'designer_id',
        'version',
        'proof_file_path',
        'proof_file_name',
        'proof_file_size',
        'production_file_path',
        'production_file_name',
        'designer_notes',
        'customer_feedback',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function printRequest()
    {
        return $this->belongsTo(PrintRequest::class);
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'approved'           => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30',
            'revision_requested' => 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border border-amber-500/30',
            default              => 'bg-blue-500/15 text-blue-700 dark:text-blue-400 border border-blue-500/30',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'approved'           => 'Proof Approved',
            'revision_requested' => 'Revision Requested',
            default              => 'Pending Client Review',
        };
    }
}
