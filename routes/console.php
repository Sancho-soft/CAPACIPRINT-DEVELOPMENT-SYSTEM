<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('payments:fix-references', function () {
    $payments = \App\Models\Payment::all();
    $count = 0;
    foreach ($payments as $payment) {
        $method = strtolower(trim($payment->payment_method ?? ''));
        if (str_contains($method, 'cash') || in_array($method, ['over-the-counter', 'otc'])) {
            if ($payment->payment_reference !== null) {
                $payment->payment_reference = null;
                $payment->save();
                $count++;
            }
        } elseif (str_contains($method, 'gcash')) {
            $ref = trim($payment->payment_reference ?? '');
            if ($ref === '' || str_starts_with($ref, 'PAY-') || !preg_match('/^Ref No\.\s*\d{15}$/i', $ref)) {
                $hash = abs(crc32('GCASH' . $payment->id . ($payment->order_id ?? 1)));
                $digits = '987' . str_pad($hash, 9, '5', STR_PAD_RIGHT) . str_pad(($payment->id * 37) % 1000, 3, '1', STR_PAD_LEFT);
                $payment->payment_reference = 'Ref No. ' . substr($digits, 0, 15);
                $payment->save();
                $count++;
            }
        } elseif (str_contains($method, 'maya')) {
            $ref = trim($payment->payment_reference ?? '');
            if ($ref === '' || str_starts_with($ref, 'PAY-') || !preg_match('/^Ref No\.\s*\d{15}$/i', $ref)) {
                $hash = abs(crc32('MAYA' . $payment->id . ($payment->order_id ?? 1)));
                $digits = '887' . str_pad($hash, 9, '2', STR_PAD_RIGHT) . str_pad(($payment->id * 41) % 1000, 3, '2', STR_PAD_LEFT);
                $payment->payment_reference = 'Ref No. ' . substr($digits, 0, 15);
                $payment->save();
                $count++;
            }
        } elseif (str_contains($method, 'bank') || str_starts_with($payment->payment_reference ?? '', 'PAY-')) {
            $ref = trim($payment->payment_reference ?? '');
            $year = $payment->paid_at?->format('Y') ?? $payment->created_at?->format('Y') ?? '2026';
            if ($ref === '' || !preg_match('/^PAY-\d{4}-\d{6}$/i', $ref)) {
                $hashNum = str_pad((abs(crc32('BANK' . $payment->id . ($payment->order_id ?? 1))) % 900000) + 100000, 6, '0', STR_PAD_LEFT);
                $payment->payment_reference = "PAY-{$year}-{$hashNum}";
                $payment->save();
                $count++;
            }
        }
    }
    $this->info("Updated {$count} payments.");
});
