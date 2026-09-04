<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * List all payments for the authenticated customer.
     */
    public function index(Request $request)
    {
        $payments = $request->user()
            ->payments()
            ->with('order.printRequest')
            ->latest()
            ->paginate(10);

        return view('customer.payments.index', compact('payments'));
    }

    /**
     * Show payment details (own only).
     */
    public function show(Request $request, Payment $payment)
    {
        if ($payment->user_id !== $request->user()->id) {
            abort(403);
        }

        $payment->load('order.printRequest');
        return view('customer.payments.show', compact('payment'));
    }

    /**
     * Customer submits payment reference (for offline or bank transfer).
     * The actual confirmation must be done by admin/staff.
     */
    public function submit(Request $request, Payment $payment)
    {
        if ($payment->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment has already been submitted or processed.');
        }

        $data = $request->validate([
            'payment_method'    => ['required', 'string', 'max:255'],
            'payment_reference' => ['required', 'string', 'max:255'],
            'notes'             => ['nullable', 'string', 'max:1000'],
        ]);

        $payment->update([
            'payment_method'    => $data['payment_method'],
            'payment_reference' => $data['payment_reference'],
            'notes'             => $data['notes'] ?? null,
            'status'            => 'submitted',
            'paid_at'           => now(),
        ]);

        // Update order payment status
        $payment->order->update(['payment_status' => 'submitted']);

        return redirect()->route('customer.payments.show', $payment)
            ->with('success', 'Payment reference submitted! Awaiting confirmation from our team.');
    }
}
