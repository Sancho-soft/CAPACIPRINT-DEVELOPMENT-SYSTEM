<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchRecommendation extends Model
{
    protected $fillable = [
        'print_request_id', 'order_id', 'recommended_branch_id',
        'created_by', 'recommendation_score', 'reason',
        'status', 'override_reason',
    ];

    public function printRequest()
    {
        return $this->belongsTo(PrintRequest::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function recommendedBranch()
    {
        return $this->belongsTo(Branch::class, 'recommended_branch_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'Pending',
            'confirmed'  => 'Confirmed',
            'overridden' => 'Overridden',
            default      => ucfirst($this->status),
        };
    }
}
