<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'payment_method',
        'amount',
        'payment_reference',
        'status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount'  => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Pending',
            'submitted' => 'Submitted',
            'confirmed' => 'Confirmed',
            'rejected'  => 'Rejected',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'bg-amber-100 text-amber-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'rejected'  => 'bg-red-100 text-red-800',
            default     => 'bg-slate-100 text-slate-600',
        };
    }

    public function getDisplayReferenceAttribute(): string
    {
        $method = strtolower(trim($this->payment_method ?? ''));
        $ref = trim($this->payment_reference ?? '');

        // 1. GCash: must always show "Ref No. 987654321012345" (15 digits)
        if (str_contains($method, 'gcash')) {
            if ($ref !== '' && $ref !== '—') {
                if (preg_match('/^Ref\s*No\.?\s*(\d{10,18})$/i', $ref, $matches)) {
                    return 'Ref No. ' . $matches[1];
                }
                if (str_starts_with($ref, 'Ref No.')) {
                    return $ref;
                }
                if (str_starts_with($ref, 'Ref#') || str_starts_with($ref, 'REF:') || str_starts_with($ref, 'Ref:')) {
                    return 'Ref No. ' . trim(preg_replace('/^(Ref#|REF:|Ref:)\s*/i', '', $ref));
                }
                if (ctype_digit($ref)) {
                    return 'Ref No. ' . $ref;
                }
                if (!str_starts_with($ref, 'PAY-')) {
                    return 'Ref No. ' . $ref;
                }
            }

            // Fallback deterministic 15-digit GCash Reference Number: "Ref No. 987654321012345"
            $hash = abs(crc32('GCASH' . ($this->id ?? 1) . ($this->order_id ?? 1)));
            $digits = '987' . str_pad($hash, 9, '5', STR_PAD_RIGHT) . str_pad((($this->id ?? 1) * 37) % 1000, 3, '1', STR_PAD_LEFT);
            return 'Ref No. ' . substr($digits, 0, 15);
        }

        // 2. Maya: "Ref No. 887..."
        if (str_contains($method, 'maya')) {
            if ($ref !== '' && $ref !== '—') {
                if (preg_match('/^Ref\s*No\.?\s*(\d{10,18})$/i', $ref, $matches)) {
                    return 'Ref No. ' . $matches[1];
                }
                if (str_starts_with($ref, 'Ref No.')) {
                    return $ref;
                }
                if (str_starts_with($ref, 'Ref#') || str_starts_with($ref, 'REF:') || str_starts_with($ref, 'Ref:')) {
                    return 'Ref No. ' . trim(preg_replace('/^(Ref#|REF:|Ref:)\s*/i', '', $ref));
                }
                if (ctype_digit($ref)) {
                    return 'Ref No. ' . $ref;
                }
                if (!str_starts_with($ref, 'PAY-')) {
                    return 'Ref No. ' . $ref;
                }
            }

            $hash = abs(crc32('MAYA' . ($this->id ?? 1) . ($this->order_id ?? 1)));
            $digits = '887' . str_pad($hash, 9, '2', STR_PAD_RIGHT) . str_pad((($this->id ?? 1) * 41) % 1000, 3, '2', STR_PAD_LEFT);
            return 'Ref No. ' . substr($digits, 0, 15);
        }

        // 3. Cash / Over-the-Counter: No reference number required
        if (
            str_contains($method, 'cash') ||
            in_array($method, ['over-the-counter', 'otc', 'counter']) ||
            str_starts_with($ref, 'OTC-CASH')
        ) {
            return '—';
        }

        // 4. Bank Transfer template: PAY-[YEAR]-[6-DIGIT NUMBER] e.g. PAY-2026-847291
        if (str_contains($method, 'bank') || str_starts_with($ref, 'PAY-')) {
            $year = $this->paid_at?->format('Y') ?? $this->created_at?->format('Y') ?? date('Y');

            // If already formatted like PAY-2026-847291 (6 numeric digits)
            if (preg_match('/^PAY-(\d{4})-(\d{6})$/i', $ref, $m)) {
                return strtoupper($ref);
            }

            // If it has random letters like PAY-2026-WTFDIT, convert to deterministic 6 numeric digits
            if (preg_match('/^PAY-(\d{4})-([A-Za-z0-9]+)$/i', $ref, $m)) {
                $refYear = $m[1];
                $hashNum = str_pad((abs(crc32($ref . ($this->id ?? 1))) % 900000) + 100000, 6, '0', STR_PAD_LEFT);
                return "PAY-{$refYear}-{$hashNum}";
            }

            // If user inputted just numbers (e.g. 847291)
            if (ctype_digit($ref)) {
                return "PAY-{$year}-" . str_pad(substr($ref, 0, 6), 6, '0', STR_PAD_LEFT);
            }

            if ($ref !== '' && $ref !== '—') {
                return $ref;
            }

            // Fallback deterministic 6-digit Bank Transfer reference number
            $hashNum = str_pad((abs(crc32('BANK' . ($this->id ?? 1) . ($this->order_id ?? 1))) % 900000) + 100000, 6, '0', STR_PAD_LEFT);
            return "PAY-{$year}-{$hashNum}";
        }

        // 5. Other payment methods
        if ($ref === '' || $ref === '—') {
            return '—';
        }

        return $ref;
    }
}
