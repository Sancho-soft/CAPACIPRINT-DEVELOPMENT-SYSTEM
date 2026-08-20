<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    protected $fillable = [
        'service', 'size', 'base_rate', 'material_rate', 'finishing_rate', 'is_active',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'base_rate'      => 'decimal:2',
        'material_rate'  => 'decimal:2',
        'finishing_rate' => 'decimal:2',
    ];

    /**
     * Calculate total price for a given print request.
     */
    public function calculateTotal(int $quantity, string $finishing): float
    {
        $finishingRate = ($finishing && $finishing !== 'None') ? $this->finishing_rate : 0;
        return ($this->base_rate + $this->material_rate + $finishingRate) * $quantity;
    }

    /**
     * Find the best matching rule for a service+size combination.
     */
    public static function findBestMatch(string $service, string $size): ?self
    {
        return static::where('is_active', true)
            ->where('service', $service)
            ->where(function ($q) use ($size) {
                $q->where('size', $size)->orWhereNull('size');
            })
            ->orderByRaw("CASE WHEN size IS NOT NULL THEN 0 ELSE 1 END")
            ->first();
    }
}
