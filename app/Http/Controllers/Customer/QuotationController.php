<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * List all quotations for the authenticated customer.
     */
    public function index(Request $request)
    {
        $quotations = $request->user()
            ->quotations()
            ->with('printRequest')
            ->latest()
            ->paginate(10);

        return view('customer.quotations.index', compact('quotations'));
    }

    /**
     * Show a single quotation (own only).
     */
    public function show(Request $request, Quotation $quotation)
    {
        if ($quotation->user_id !== $request->user()->id) {
            abort(403);
        }

        $quotation->load('printRequest');
        return view('customer.quotations.show', compact('quotation'));
    }

    /**
     * Confirm a quotation (customer accepts the price).
     */
    public function confirm(Request $request, Quotation $quotation)
    {
        if ($quotation->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($quotation->status !== 'pending') {
            return back()->with('error', 'This quotation can no longer be confirmed.');
        }

        $quotation->update(['status' => 'confirmed']);

        // Update the print request status to payment
        $quotation->printRequest->update(['status' => 'payment']);

        $order = \App\Models\Order::firstOrCreate(
            ['quotation_id' => $quotation->id],
            [
                'order_number'         => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'user_id'              => $quotation->user_id,
                'print_request_id'     => $quotation->print_request_id,
                'payment_status'       => 'pending',
                'status'               => 'payment',
                'assigned_branch'      => $quotation->printRequest->preferred_branch ?? 'Morning Star Printing Press',
                'estimated_completion' => now()->addDays(3),
            ]
        );

        \App\Models\Payment::firstOrCreate(
            ['order_id' => $order->id],
            [
                'user_id'        => $quotation->user_id,
                'amount'         => $quotation->total_price,
                'payment_method' => 'Cash on Pickup',
                'status'         => 'pending',
            ]
        );

        return redirect()->route('customer.payments.index')
            ->with('success', 'Quotation confirmed! Order and payment record created. Proceed to submit payment details.');
    }

    /**
     * Decline a quotation (customer rejects the price).
     */
    public function decline(Request $request, Quotation $quotation)
    {
        if ($quotation->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($quotation->status !== 'pending') {
            return back()->with('error', 'This quotation can no longer be declined.');
        }

        $quotation->update(['status' => 'declined']);

        return redirect()->route('customer.quotations.index')
            ->with('info', 'Quotation declined.');
    }

    /**
     * Download quotation as PDF.
     * (PDF generation can be added later with a package like DomPDF)
     */
    public function download(Request $request, Quotation $quotation)
    {
        if ($quotation->user_id !== $request->user()->id) {
            abort(403);
        }

        // Placeholder: return quotation view for printing
        $quotation->load('printRequest');
        return view('customer.quotations.download', compact('quotation'));
    }
}
